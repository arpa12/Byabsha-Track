<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Models\Category;
use Modules\Shop\Models\Shop;
use Modules\Sale\Models\Sale;
use Modules\Restock\Models\Restock;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'name',
        'model_name',
        'category_id',
        'category',
        'brand',
        'purchase_price',
        'sale_price',
        'stock_quantity',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function restocks()
    {
        return $this->hasMany(Restock::class);
    }

    public function dynamicValues()
    {
        return $this->hasMany(ProductDynamicValue::class);
    }
}
