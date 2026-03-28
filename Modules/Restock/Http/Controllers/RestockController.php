<?php

namespace Modules\Restock\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
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
        $user = auth()->user();
        $allowedShopIds = $user->accessibleShopIds();

        $filters = $request->only(['shop_id', 'date_from', 'date_to']);

        // Prevent filtering by a shop the user doesn't own
        if (!empty($filters['shop_id']) && !in_array((int) $filters['shop_id'], $allowedShopIds)) {
            abort(403, 'You do not have access to this shop.');
        }

        // Inject allowed shop IDs so the service always scopes correctly
        $filters['shop_ids'] = $allowedShopIds;

        $restocks = $this->restockService->getRestocks($filters);
        $shops = Shop::forUser($user)->get();

        return view('restock::index', compact('restocks', 'shops', 'filters'));
    }

    public function create()
    {
        $user = auth()->user();
        $shops = Shop::forUser($user)->get();
        $products = Product::with('shop')->whereIn('shop_id', $user->accessibleShopIds())->get();

        return view('restock::create', compact('shops', 'products'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price_per_unit' => 'required|numeric|min:0.01',
            'restock_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

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
        $user = auth()->user();
        $restock = $this->restockService->getRestock($id);
        abort_unless($user->ownsShop((int) $restock->shop_id), 403, 'You do not have access to this shop.');
        $shops = Shop::forUser($user)->get();
        $products = Product::with('shop')->whereIn('shop_id', $user->accessibleShopIds())->get();

        return view('restock::edit', compact('restock', 'shops', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price_per_unit' => 'required|numeric|min:0.01',
            'restock_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

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
        $user = auth()->user();
        $restock = $this->restockService->getRestock($id);
        abort_unless($user->ownsShop((int) $restock->shop_id), 403, 'You do not have access to this shop.');

        Log::info('Destroy method called with ID: ' . $id);
        try {
            $this->restockService->deleteRestock($id);
            Log::info('Restock deleted successfully');
            return redirect()->route('restock.index')
                ->with('success', __('restock.deleted'));
        } catch (\Exception $e) {
            Log::error('Delete failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * API endpoint: return products for a given shop (JSON).
     */
    public function productsByShop(Request $request)
    {
        $user = auth()->user();
        $shopId = (int) $request->shop_id;
        abort_unless($user->ownsShop($shopId), 403, 'You do not have access to this shop.');

        $products = Product::where('shop_id', $shopId)
            ->select('id', 'name', 'purchase_price', 'stock_quantity')
            ->orderBy('name')
            ->get();

        return response()->json($products);
    }
}
