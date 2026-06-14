<?php

namespace Modules\Reconciliation\Models;

use App\Models\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shop\Models\Shop;

class CashRegister extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'user_id',
        'opening_balance',
        'expected_balance',
        'actual_balance',
        'cash_in_hand',
        'discrepancy',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'expected_balance' => 'decimal:2',
        'actual_balance' => 'decimal:2',
        'cash_in_hand' => 'decimal:2',
        'discrepancy' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function transactions()
    {
        return $this->hasMany(LedgerTransaction::class, 'register_id');
    }

    public function receivables()
    {
        return $this->hasMany(AccountsReceivable::class, 'register_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }
}
