<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sale\Models\Sale;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Modules\Capital\Services\CapitalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }
    public function index()
    {
        $sales = Sale::with(['shop', 'product'])->latest()->paginate(15);
        return view('sale::index', compact('sales'));
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
