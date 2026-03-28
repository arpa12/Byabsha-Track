<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\Models\Product;
use Modules\Shop\Models\Shop;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedShopIds = $user->accessibleShopIds();

        $selectedShopId = $request->input('shop_id');

        // Prevent accessing a shop the user doesn't own
        if ($selectedShopId && !in_array((int) $selectedShopId, $allowedShopIds)) {
            abort(403, 'You do not have access to this shop.');
        }

        $shops = Shop::forUser($user)->with(['products' => function ($query) {
            $query->select('id', 'shop_id', 'stock_quantity', 'purchase_price', 'sale_price');
        }])->get();

        $productsQuery = Product::with('shop')
            ->whereIn('shop_id', $allowedShopIds)
            ->orderBy('shop_id')
            ->orderBy('name');

        if (!empty($selectedShopId)) {
            $productsQuery->where('shop_id', $selectedShopId);
        }

        $products = $productsQuery->paginate(20)->withQueryString();

        return view('stock::index', [
            'shops' => $shops,
            'products' => $products,
            'selectedShopId' => $selectedShopId,
        ]);
    }
}
