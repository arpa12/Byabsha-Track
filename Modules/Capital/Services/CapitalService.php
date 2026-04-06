<?php
namespace Modules\Capital\Services;

use Modules\Capital\Models\Capital;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;
use Modules\Restock\Models\Restock;

class CapitalService
{
    /**
     * Calculate total inventory value for a shop using batch-level pricing (FIFO model).
     *
     * For each product we sum (remaining_quantity * purchase_price_per_unit) across all
     * active restock batches. Products that have no restock records fall back to the
     * static product.purchase_price so legacy data remains consistent.
     */
    public function calculateShopCapital($shopId)
    {
        // Sum of remaining batch value
        $batchValue = Restock::where('shop_id', $shopId)
            ->whereNull('deleted_at')
            ->where('remaining_quantity', '>', 0)
            ->selectRaw('SUM(remaining_quantity * purchase_price_per_unit) as total')
            ->value('total') ?? 0;

        // Products that have zero total remaining_quantity in batches but still show
        // stock (e.g. stock added before batch tracking existed) — use purchase_price fallback.
        $productsWithoutBatchStock = Product::where('shop_id', $shopId)
            ->whereDoesntHave('restocks', function ($q) {
                $q->whereNull('deleted_at')->where('remaining_quantity', '>', 0);
            })
            ->where('stock_quantity', '>', 0)
            ->get();

        $fallbackValue = $productsWithoutBatchStock->sum(function ($product) {
            return $product->stock_quantity * (float) $product->purchase_price;
        });

        return round((float) $batchValue + $fallbackValue, 2);
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
