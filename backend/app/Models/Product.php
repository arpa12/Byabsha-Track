<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'category_id',
        'description',
        'unit',
        'purchase_price',
        'selling_price',



























































}    }        return $stock ? $stock->quantity : 0;        $stock = $this->branchStocks()->where('branch_id', $branchId)->first();    {    public function getStockForBranch(int $branchId): float     */     * Get stock for a specific branch.    /**    }        return $this->hasMany(SaleItem::class);    {    public function saleItems(): HasMany     */     * Get the sale items for the product.    /**    }        return $this->hasMany(PurchaseItem::class);    {    public function purchaseItems(): HasMany     */     * Get the purchase items for the product.    /**    }        return $this->hasMany(BranchStock::class);    {    public function branchStocks(): HasMany     */     * Get the branch stocks for the product.    /**    }        return $this->belongsTo(Category::class);    {    public function category(): BelongsTo     */     * Get the category that owns the product.    /**    ];        'is_active' => 'boolean',        'minimum_stock' => 'decimal:2',        'selling_price' => 'decimal:2',        'purchase_price' => 'decimal:2',    protected $casts = [     */     * @var array<string, string>     *     * The attributes that should be cast.    /**    ];        'is_active',        'image',        'minimum_stock',
