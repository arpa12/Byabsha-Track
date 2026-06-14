<?php

namespace Modules\Reconciliation\Models;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountsReceivable extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'accounts_receivable';

    protected $fillable = [
        'register_id',
        'customer_name',
        'customer_phone',
        'amount',
        'status', // pending, repaid
        'due_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function register()
    {
        return $this->belongsTo(CashRegister::class, 'register_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRepaid(): bool
    {
        return $this->status === 'repaid';
    }
}
