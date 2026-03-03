<?php

namespace Modules\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shop\Models\Shop;
use Modules\Product\Models\Product;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'product_id',
        'quantity',
        'sale_price',
        'total_amount',
        'profit',
        'sale_date',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'quantity' => 'integer',
        'sale_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
