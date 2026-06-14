<?php

namespace Modules\Reconciliation\Models;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LedgerTransaction extends TenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'register_id',
        'type', // income, expense
        'category',
        'amount',
        'notes',
        'sale_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function register()
    {
        return $this->belongsTo(CashRegister::class, 'register_id');
    }

    public function sale()
    {
        return $this->belongsTo(\Modules\Sale\Models\Sale::class, 'sale_id');
    }

    public function isIncome(): bool
    {
        return $this->type === 'income';
    }

    public function isExpense(): bool
    {
        return $this->type === 'expense';
    }
}
