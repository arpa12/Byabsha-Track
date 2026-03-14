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
        $selectedShopId = $request->input('shop_id');

        $shops = Shop::with(['products' => function ($query) {
            $query->select('id', 'shop_id', 'stock_quantity', 'purchase_price', 'sale_price');
        }])->get();

        $productsQuery = Product::with('shop')->orderBy('shop_id')->orderBy('name');

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
