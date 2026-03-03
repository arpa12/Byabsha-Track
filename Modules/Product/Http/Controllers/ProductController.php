<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Product\Models\Product;
use Modules\Shop\Models\Shop;
use Modules\Capital\Services\CapitalService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }
    public function index()
    {
        $products = Product::with('shop')->latest()->paginate(15);
        return view('product::index', compact('products'));
    }

    public function create()
    {
        $shops = Shop::all();
        return view('product::create', compact('shops'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        // Recalculate shop capital
        $this->capitalService->updateShopCapital($product->shop_id);

        return redirect()->route('product.index')
            ->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        $product = Product::with('shop')->findOrFail($id);
        return view('product::show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $shops = Shop::all();
        return view('product::edit', compact('product', 'shops'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        // Ensure stock cannot go below zero
        if ($validated['stock_quantity'] < 0) {
            return back()->withErrors(['stock_quantity' => 'Stock quantity cannot be negative.'])->withInput();
        }

        $oldShopId = $product->shop_id;
        $product->update($validated);

        // Recalculate capital for affected shop(s)
        $this->capitalService->updateShopCapital($product->shop_id);
        if ($oldShopId != $product->shop_id) {
            $this->capitalService->updateShopCapital($oldShopId);
        }

        return redirect()->route('product.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $shopId = $product->shop_id;
        $product->delete();

        // Recalculate shop capital
        $this->capitalService->updateShopCapital($shopId);

        return redirect()->route('product.index')
            ->with('success', 'Product deleted successfully!');
    }
}
