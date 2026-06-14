<?php

namespace Modules\Reconciliation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Reconciliation\Models\CashRegister;
use Modules\Reconciliation\Models\LedgerTransaction;
use Modules\Reconciliation\Models\AccountsReceivable;

class LedgerController extends Controller
{
    /**
     * Store a manual ledger transaction (Income/Expense).
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'register_id' => 'required|exists:cash_registers,id',
            'type'        => 'required|in:income,expense',
            'category'    => 'required|string|max:100',
            'amount'      => 'required|numeric|min:0.01',
            'notes'       => 'nullable|string|max:500',
        ]);

        $register = CashRegister::findOrFail($validated['register_id']);
        abort_unless(auth()->user()->ownsShop((int) $register->shop_id), 403, 'You do not have access to this shop.');

        if (!$register->isOpen()) {
            return back()->with('error', __('reconciliation.error_not_open'));
        }

        LedgerTransaction::create([
            'register_id' => $register->id,
            'type'        => $validated['type'],
            'category'    => $validated['category'],
            'amount'      => $validated['amount'],
            'notes'       => $validated['notes'] ?? null,
        ]);

        $msg = $validated['type'] === 'income' ? __('reconciliation.success_income_logged') : __('reconciliation.success_expense_logged');
        return back()->with('success', $msg);
    }

    /**
     * Store a customer credit/receivable transaction.
     */
    public function storeReceivable(Request $request)
    {
        $validated = $request->validate([
            'register_id'   => 'required|exists:cash_registers,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone'=> 'nullable|string|max:30',
            'amount'        => 'required|numeric|min:0.01',
            'due_date'      => 'nullable|date',
        ]);

        $register = CashRegister::findOrFail($validated['register_id']);
        abort_unless(auth()->user()->ownsShop((int) $register->shop_id), 403, 'You do not have access to this shop.');

        if (!$register->isOpen()) {
            return back()->with('error', __('reconciliation.error_not_open'));
        }

        AccountsReceivable::create([
            'register_id'   => $register->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone'=> $validated['customer_phone'] ?? null,
            'amount'        => $validated['amount'],
            'status'        => 'pending',
            'due_date'      => $validated['due_date'] ?? null,
        ]);

        return back()->with('success', __('reconciliation.success_receivable_logged'));
    }

    /**
     * Mark customer due balance as repaid.
     */
    public function repayReceivable(Request $request, $id)
    {
        $receivable = AccountsReceivable::findOrFail($id);
        $register = $receivable->register;
        
        abort_unless(auth()->user()->ownsShop((int) $register->shop_id), 403, 'You do not have access to this shop.');

        if (!$receivable->isPending()) {
            return back()->with('error', __('reconciliation.error_already_repaid'));
        }

        // Find current active open register session for this shop
        $activeRegister = CashRegister::where('shop_id', $register->shop_id)
            ->where('status', 'open')
            ->first();

        if (!$activeRegister) {
            return back()->with('error', __('reconciliation.error_repay_needs_open_register'));
        }

        DB::transaction(function () use ($receivable, $activeRegister) {
            // 1. Mark as repaid
            $receivable->update(['status' => 'repaid']);

            // 2. Add an income transaction to the active register
            LedgerTransaction::create([
                'register_id' => $activeRegister->id,
                'type'        => 'income',
                'category'    => 'Receivable Repayment',
                'amount'      => $receivable->amount,
                'notes'       => 'Repayment from customer: ' . $receivable->customer_name,
            ]);
        });

        return back()->with('success', __('reconciliation.success_receivable_repaid'));
    }
}
