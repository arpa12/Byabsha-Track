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

    public function updateAllShopsCapital(array $shopIds = [])
    {
        $query = Shop::query();
        if (!empty($shopIds)) {
            $query->whereIn('id', $shopIds);
        }
        $shops = $query->get();
        $results = [];
        foreach ($shops as $shop) {
            $results[$shop->id] = $this->updateShopCapital($shop->id);
        }
        return $results;
    }

    public function getAllShopCapitals(array $shopIds = [])
    {
        $query = Capital::with(['shop', 'shop.products'])->whereHas('shop');
        if (!empty($shopIds)) {
            $query->whereIn('shop_id', $shopIds);
        }
        return $query->get();
    }

    public function getShopCapital($shopId)
    {
        return Capital::where('shop_id', $shopId)->first();
    }
}
