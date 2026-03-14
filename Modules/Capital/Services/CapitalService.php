<?php
namespace Modules\Capital\Services;

use Modules\Capital\Models\Capital;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;

class CapitalService
{
    public function calculateShopCapital($shopId)
    {
        return Product::where('shop_id', $shopId)
            ->get()
            ->sum(function($product) {
                return $product->stock_quantity * $product->purchase_price;
            });
    }

    public function updateShopCapital($shopId)
    {
        $totalCapital = $this->calculateShopCapital($shopId);
        Capital::updateOrCreate(
            ['shop_id' => $shopId],
            ['total_capital' => $totalCapital]
        );
        return $totalCapital;
    }

    public function updateAllShopsCapital()
    {
        $shops = Shop::all();
        $results = [];
        foreach ($shops as $shop) {
            $results[$shop->id] = $this->updateShopCapital($shop->id);
        }
        return $results;
    }

    public function getAllShopCapitals()
    {
        return Capital::with(['shop', 'shop.products'])
            ->whereHas('shop')
            ->get();
    }

    public function getShopCapital($shopId)
    {
        return Capital::where('shop_id', $shopId)->first();
    }
}
