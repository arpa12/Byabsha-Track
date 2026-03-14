<?php

namespace Modules\Capital\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shop\Models\Shop;

class Capital extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop_capitals';

    protected $fillable = [
        'shop_id',
        'total_capital',
    ];

    protected $casts = [
        'total_capital' => 'decimal:2',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
