<?php

namespace Modules\Branch\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shop\Models\Shop;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'name',
        'location',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'shop_id' => 'integer',
        'is_active' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isManager()) {
            return $query->where('id', $user->branch_id);
        }

        return $query->whereHas('shop', function (Builder $shopQuery) use ($user) {
            $shopQuery->where('user_id', $user->id);
        });
    }
}
