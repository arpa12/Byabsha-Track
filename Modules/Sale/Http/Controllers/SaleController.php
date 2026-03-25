<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sale\Models\Sale;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Modules\Capital\Services\CapitalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }
    public function index(Request $request)
    {
        $shops = Shop::orderBy('name')->get(['id', 'name']);
        $selectedShopId = $request->integer('shop_id', (int) optional($shops->first())->id);

        return view('sale::index', compact('shops', 'selectedShopId'));
    }

    public function productsTable(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
        ]);

        $shopId = (int) $validated['shop_id'];

        $products = Product::query()
            ->where('shop_id', $shopId)
            ->with('productCategory:id,name')
            ->select([
                'products.id',
                'products.shop_id',
                'products.name',
                'products.category',
                'products.category_id',
                'products.purchase_price',
                'products.stock_quantity',
            ])
            ->addSelect([
                'latest_sale_id' => Sale::query()
                    ->select('id')
                    ->whereColumn('sales.product_id', 'products.id')
                    ->where('sales.shop_id', $shopId)
                    ->latest('sales.id')
                    ->limit(1),
                'latest_profit' => Sale::query()
                    ->selectRaw('COALESCE(SUM(CASE WHEN profit > 0 THEN profit ELSE 0 END), 0)')
                    ->whereColumn('sales.product_id', 'products.id')
                    ->where('sales.shop_id', $shopId)
                    ->limit(1),
                'latest_loss' => Sale::query()
                    ->selectRaw('COALESCE(SUM(CASE WHEN profit < 0 THEN ABS(profit) ELSE 0 END), 0)')
                    ->whereColumn('sales.product_id', 'products.id')
                    ->where('sales.shop_id', $shopId)
                    ->limit(1),
            ]);

        return DataTables::eloquent($products)
            ->addColumn('category_name', function (Product $product) {
                return $product->productCategory?->name ?? $product->category ?? '-';
            })
            ->editColumn('purchase_price', function (Product $product) {
                return number_format((float) $product->purchase_price, 2);
            })
            ->editColumn('stock_quantity', function (Product $product) {
                return (int) $product->stock_quantity;
            })
            ->editColumn('latest_profit', function (Product $product) {
                return number_format((float) ($product->latest_profit ?? 0), 2);
            })
            ->editColumn('latest_loss', function (Product $product) {
                return number_format((float) ($product->latest_loss ?? 0), 2);
            })
            ->addColumn('actions', function (Product $product) {
                $createUrl = route('product.create', ['shop_id' => $product->shop_id]);
                $editUrl = route('product.edit', $product->id);
                $deleteUrl = route('product.destroy', $product->id);
                $viewSalesUrl = route('sale.product-sales', [
                    'product' => (int) $product->id,
                    'shop_id' => (int) $product->shop_id,
                ]);
                $canSell = $product->stock_quantity > 0;

                $saleButton = '<button type="button" class="btn btn-sm btn-outline-success js-sale-btn" '
                    . 'data-product-id="' . e((string) $product->id) . '" '
                    . 'data-shop-id="' . e((string) $product->shop_id) . '" '
                    . 'data-product-name="' . e($product->name) . '" '
                    . 'data-stock="' . e((string) $product->stock_quantity) . '" '
                    . 'data-purchase-price="' . e((string) $product->purchase_price) . '" '
                    . ($canSell ? '' : 'disabled ')
                    . 'title="Sale">'
                    . '<i class="bi bi-cart-plus"></i> ' . e(__('sale.sale_button'))
                    . '</button>';

                $deleteForm = '<form action="' . e($deleteUrl) . '" method="POST" class="d-inline" '
                    . 'onsubmit="return confirm(\'' . e(__('product.confirm_delete')) . '\')">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">'
                    . '<i class="bi bi-trash"></i>'
                    . '</button>'
                    . '</form>';

                return '<div class="d-flex gap-1 justify-content-center">'
                    . '<a href="' . e($viewSalesUrl) . '" class="btn btn-sm btn-outline-info" title="' . e(__('sale.view_all_sales')) . '">'
                        . '<i class="bi bi-eye"></i>'
                    . '</a>'
                    . '<a href="' . e($createUrl) . '" class="btn btn-sm btn-outline-primary" title="Create">'
                    . '<i class="bi bi-plus-circle"></i>'
                    . '</a>'
                    . '<a href="' . e($editUrl) . '" class="btn btn-sm btn-outline-secondary" title="Edit">'
                    . '<i class="bi bi-pencil"></i>'
                    . '</a>'
                    . $deleteForm
                    . $saleButton
                    . '</div>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function quickSale(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'sale_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'nullable|date',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'customer_address' => 'nullable|string|max:500',
        ]);

        $product = Product::query()
            ->where('id', $validated['product_id'])
            ->where('shop_id', $validated['shop_id'])
            ->first();

        if (!$product) {
            return response()->json([
                'message' => 'Selected product does not belong to the selected shop.',
            ], 422);
        }

        if ($product->stock_quantity < $validated['quantity']) {
            return response()->json([
                'message' => 'Insufficient stock. Available: ' . $product->stock_quantity,
            ], 422);
        }

        DB::transaction(function () use ($validated, $product): void {
            $quantity = (int) $validated['quantity'];
            $salePrice = (float) $validated['sale_price'];
            $discount = (float) ($validated['discount'] ?? 0);
            $discountedSalePrice = max($salePrice - $discount, 0);
            $totalAmount = $quantity * $discountedSalePrice;
            $profit = ($discountedSalePrice - (float) $product->purchase_price) * $quantity;

            Sale::create([
                'shop_id' => $validated['shop_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $quantity,
                'sale_price' => $salePrice,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'profit' => $profit,
                'sale_date' => $validated['sale_date'] ?? now()->toDateString(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_address' => $validated['customer_address'] ?? null,
            ]);

            $product->decrement('stock_quantity', $quantity);
        });

        $product->refresh();
        $this->capitalService->updateShopCapital((int) $validated['shop_id']);

        return response()->json([
            'message' => 'Sale recorded successfully.',
            'stock_quantity' => $product->stock_quantity,
        ]);
    }

    public function create()
    {
        $shops = Shop::all();
        $products = Product::with('shop')->get();
        return view('sale::create', compact('shops', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);

        // Get the product
        $product = Product::findOrFail($validated['product_id']);

        // Ensure selected product belongs to selected shop
        if ((int) $product->shop_id !== (int) $validated['shop_id']) {
            return back()
                ->withInput()
                ->withErrors(['product_id' => 'Selected product does not belong to the selected shop.']);
        }

        // Check if stock is sufficient
        if ($product->stock_quantity < $validated['quantity']) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Insufficient stock. Available: ' . $product->stock_quantity]);
        }

        DB::transaction(function () use ($validated, $product) {
            // Calculate amounts
            $salePrice = $product->sale_price;
            $totalAmount = $validated['quantity'] * $salePrice;
            $profit = ($salePrice - $product->purchase_price) * $validated['quantity'];

            // Create sale
            Sale::create([
                'shop_id' => $validated['shop_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'sale_price' => $salePrice,
                'total_amount' => $totalAmount,
                'profit' => $profit,
                'sale_date' => $validated['sale_date'],
            ]);

            // Deduct stock
            $product->decrement('stock_quantity', $validated['quantity']);
        });

        // Recalculate shop capital after stock deduction
        $this->capitalService->updateShopCapital($validated['shop_id']);

        return redirect()->route('sale.index')
            ->with('success', 'Sale created successfully!');
    }

    public function show($id)
    {
        $sale = Sale::with(['shop', 'product'])->findOrFail($id);
        return view('sale::show', compact('sale'));
    }

    public function productSales(Request $request, $productId)
    {
        $product = Product::with('shop')->findOrFail($productId);
        $shopId = $request->integer('shop_id');

        $salesQuery = Sale::query()
            ->with(['shop', 'product'])
            ->where('product_id', $product->id)
            ->when($shopId, function ($query) use ($shopId) {
                $query->where('shop_id', $shopId);
            });

        $sales = (clone $salesQuery)
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $totals = (clone $salesQuery)
            ->selectRaw('COALESCE(SUM(quantity), 0) as total_quantity')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as total_amount')
            ->selectRaw('COALESCE(SUM(profit), 0) as total_profit')
            ->first();

        return view('sale::product-sales', compact('product', 'sales', 'totals'));
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $shops = Shop::all();
        $products = Product::with('shop')->get();
        return view('sale::edit', compact('sale', 'shops', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);

        $sale = Sale::findOrFail($id);
        $product = Product::findOrFail($validated['product_id']);

        // Ensure selected product belongs to selected shop
        if ((int) $product->shop_id !== (int) $validated['shop_id']) {
            return back()
                ->withInput()
                ->withErrors(['product_id' => 'Selected product does not belong to the selected shop.']);
        }

        // Calculate available stock (add back the old quantity if same product)
        $availableStock = $product->stock_quantity;
        if ($sale->product_id == $validated['product_id']) {
            $availableStock += $sale->quantity;
        }

        // Check if stock is sufficient
        if ($availableStock < $validated['quantity']) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Insufficient stock. Available: ' . $availableStock]);
        }

        DB::transaction(function () use ($validated, $sale, $product) {
            // Restore old stock
            $oldProduct = Product::findOrFail($sale->product_id);
            $oldProduct->increment('stock_quantity', $sale->quantity);

            // Calculate amounts
            $salePrice = $product->sale_price;
            $totalAmount = $validated['quantity'] * $salePrice;
            $profit = ($salePrice - $product->purchase_price) * $validated['quantity'];

            // Update sale
            $sale->update([
                'shop_id' => $validated['shop_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'sale_price' => $salePrice,
                'total_amount' => $totalAmount,
                'profit' => $profit,
                'sale_date' => $validated['sale_date'],
            ]);

            // Deduct new stock
            $product->decrement('stock_quantity', $validated['quantity']);
        });

        // Recalculate capital for affected shop(s)
        $this->capitalService->updateShopCapital($validated['shop_id']);

        return redirect()->route('sale.index')
            ->with('success', 'Sale updated successfully!');
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $shopId = $sale->shop_id;

        DB::transaction(function () use ($sale) {
            // Restore stock
            $product = Product::withTrashed()->find($sale->product_id);
            if ($product) {
                $product->increment('stock_quantity', $sale->quantity);
            }

            // Delete sale
            $sale->delete();
        });

        // Recalculate shop capital after stock restoration
        $this->capitalService->updateShopCapital($shopId);

        return redirect()->route('sale.index')
            ->with('success', 'Sale deleted successfully!');
    }

    /**
     * API endpoint: return products for a given shop (JSON).
     */
    public function productsByShop(Request $request)
    {
        $products = Product::where('shop_id', $request->shop_id)
            ->select('id', 'shop_id', 'name', 'purchase_price', 'sale_price', 'stock_quantity')
            ->orderBy('name')
            ->get();

        return response()->json($products);
    }
}
