<?php

namespace Modules\Restock\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Restock\Services\RestockService;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Illuminate\Http\Request;

class RestockController extends Controller
{
    protected RestockService $restockService;

    public function __construct(RestockService $restockService)
    {
        $this->restockService = $restockService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['shop_id', 'date_from', 'date_to']);
        $restocks = $this->restockService->getRestocks($filters);
        $shops = Shop::all();

        return view('restock::index', compact('restocks', 'shops', 'filters'));
    }

    public function create()
    {
        $shops = Shop::all();
        $products = Product::with('shop')->get();

        return view('restock::create', compact('shops', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price_per_unit' => 'required|numeric|min:0.01',
            'restock_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        // Ensure product belongs to the selected shop
        $product = Product::where('id', $validated['product_id'])
            ->where('shop_id', $validated['shop_id'])
            ->first();

        if (!$product) {
            return back()->withInput()
                ->withErrors(['product_id' => __('restock.product_shop_mismatch')]);
        }

        $this->restockService->storeRestock($validated);

        return redirect()->route('restock.index')
            ->with('success', __('restock.created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $restock = $this->restockService->getRestock($id);
        $shops = Shop::all();
        $products = Product::with('shop')->get();

        return view('restock::edit', compact('restock', 'shops', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price_per_unit' => 'required|numeric|min:0.01',
            'restock_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        // Ensure product belongs to the selected shop
        $product = Product::where('id', $validated['product_id'])
            ->where('shop_id', $validated['shop_id'])
            ->first();

        if (!$product) {
            return back()->withInput()
                ->withErrors(['product_id' => __('restock.product_shop_mismatch')]);
        }

        $this->restockService->updateRestock($id, $validated);

        return redirect()->route('restock.index')
            ->with('success', __('restock.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->restockService->deleteRestock($id);

        return redirect()->route('restock.index')
            ->with('success', __('restock.deleted'));
    }

    /**
     * API endpoint: return products for a given shop (JSON).
     */
    public function productsByShop(Request $request)
    {
        $products = Product::where('shop_id', $request->shop_id)
            ->select('id', 'name', 'purchase_price', 'stock_quantity')
            ->orderBy('name')
            ->get();

        return response()->json($products);
    }
}
