<?php

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Models\Product;

class Brand extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'name',
	];

	public function products()
	{
		return $this->hasMany(Product::class, 'brand', 'name');
	}
}
