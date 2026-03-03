<?php

namespace Modules\Restock\Services;

use Modules\Restock\Models\Restock;
use Modules\Product\Models\Product;
use Modules\Capital\Services\CapitalService;
use Illuminate\Support\Facades\DB;

class RestockService
{
    protected CapitalService $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }

    public function getRestocks(array $filters = [], int $perPage = 15)
    {
        $query = Restock::with(['product', 'shop'])->latest('restock_date');

        if (!empty($filters['shop_id'])) {
            $query->where('shop_id', $filters['shop_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('restock_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('restock_date', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage)->appends($filters);
    }

    public function storeRestock(array $data): Restock
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);

            $totalCost = $data['quantity'] * $data['purchase_price_per_unit'];

            $restock = Restock::create([
                'product_id' => $data['product_id'],
                'shop_id' => $data['shop_id'],
                'quantity' => $data['quantity'],
                'purchase_price_per_unit' => $data['purchase_price_per_unit'],
                'total_cost' => $totalCost,
                'restock_date' => $data['restock_date'],
                'note' => $data['note'] ?? null,
            ]);

            $product->increment('stock_quantity', $data['quantity']);

            $this->capitalService->updateShopCapital($data['shop_id']);

            return $restock;
        });
    }
}
