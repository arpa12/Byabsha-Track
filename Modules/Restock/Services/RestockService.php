<?php

namespace Modules\Restock\Services;

use Modules\Restock\Models\Restock;
use Modules\Product\Models\Product;
use Modules\Capital\Services\CapitalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestockService
{
    protected CapitalService $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }

    public function getRestocks(array $filters = [], int $perPage = 15)
    {
        $query = Restock::with([
            'product' => fn ($query) => $query->withTrashed(),
            'shop' => fn ($query) => $query->withTrashed(),
        ])->latest('restock_date');

        // Always scope to allowed shop IDs when provided
        if (!empty($filters['shop_ids'])) {
            $query->whereIn('shop_id', $filters['shop_ids']);
        }

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
                'remaining_quantity' => $data['quantity'],
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

    public function getRestock(int $id): Restock
    {
        return Restock::with([
            'product' => fn ($query) => $query->withTrashed(),
            'shop' => fn ($query) => $query->withTrashed(),
        ])->findOrFail($id);
    }

    public function updateRestock(int $id, array $data): Restock
    {
        return DB::transaction(function () use ($id, $data) {
            $restock = Restock::findOrFail($id);
            $product = Product::withTrashed()->findOrFail($data['product_id']);

            // Units already consumed (sold) from this batch must remain consumed.
            $consumed = $restock->quantity - $restock->remaining_quantity;
            $newQuantity = (int) $data['quantity'];

            if ($newQuantity < $consumed) {
                throw new \RuntimeException(
                    "Cannot reduce batch quantity below already-sold units ({$consumed})."
                );
            }

            // Reverse the old stock increment (use withTrashed for soft-deleted products)
            $oldProduct = Product::withTrashed()->findOrFail($restock->product_id);
            $oldProduct->decrement('stock_quantity', $restock->remaining_quantity);

            // Calculate new total cost
            $totalCost = $newQuantity * $data['purchase_price_per_unit'];

            // Update restock
            $restock->update([
                'product_id' => $data['product_id'],
                'shop_id' => $data['shop_id'],
                'quantity' => $newQuantity,
                'remaining_quantity' => $newQuantity - $consumed,
                'purchase_price_per_unit' => $data['purchase_price_per_unit'],
                'total_cost' => $totalCost,
                'restock_date' => $data['restock_date'],
                'note' => $data['note'] ?? null,
            ]);

            // Apply new stock increment (only remaining units affect live stock)
            $product->increment('stock_quantity', $newQuantity - $consumed);

            // Recalculate capital for both shops (if different)
            $this->capitalService->updateShopCapital($restock->shop_id);
            if ($restock->shop_id !== $data['shop_id']) {
                $this->capitalService->updateShopCapital($data['shop_id']);
            }

            return $restock;
        });
    }

    public function deleteRestock(int $id): void
    {
        DB::transaction(function () use ($id) {
            Log::debug('Finding restock with ID: ' . $id);
            $restock = Restock::findOrFail($id);
            Log::debug('Found restock, deleting...');

            // Block deletion if units from this batch have already been sold
            $consumed = $restock->quantity - $restock->remaining_quantity;
            if ($consumed > 0) {
                throw new \RuntimeException(
                    "Cannot delete a restock batch that has already been partially sold ({$consumed} units sold)."
                );
            }

            // Reverse stock increment for unsold units only (use withTrashed for soft-deleted products)
            $product = Product::withTrashed()->findOrFail($restock->product_id);
            $product->decrement('stock_quantity', $restock->remaining_quantity);

            // Delete restock (soft delete)
            $restock->delete();

            // Recalculate capital
            $this->capitalService->updateShopCapital($restock->shop_id);
            Log::debug('Restock deletion transaction completed');
        });
    }
}
