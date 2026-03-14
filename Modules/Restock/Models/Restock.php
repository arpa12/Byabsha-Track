<?php

namespace Modules\Restock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Models\Product;
use Modules\Shop\Models\Shop;

class Restock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'shop_id',
        'quantity',
        'purchase_price_per_unit',
        'total_cost',
        'restock_date',
        'note',
    ];

    protected $casts = [
        'restock_date' => 'date',
        'quantity' => 'integer',
        'purchase_price_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
