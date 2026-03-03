<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shop\Models\Shop;
use Modules\Sale\Models\Sale;
use Modules\Restock\Models\Restock;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'category',
        'brand',
        'purchase_price',
        'sale_price',
        'stock_quantity',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function restocks()
    {
        return $this->hasMany(Restock::class);
    }
}
