<?php

namespace Modules\Reconciliation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Shop\Models\Shop;
use Modules\Reconciliation\Models\CashRegister;
use Modules\Reconciliation\Models\LedgerTransaction;
use Modules\Reconciliation\Models\AccountsReceivable;
use Modules\Sale\Models\Sale;

class ReconciliationController extends Controller
{
    /**
     * Display the reconciliation dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        
        $selectedShopId = $request->integer('shop_id', (int) optional($shops->first())->id);
        if ($selectedShopId && !$user->ownsShop($selectedShopId)) {
            $selectedShopId = (int) optional($shops->first())->id;
        }

        if (!$selectedShopId) {
            return view('reconciliation::index', [
                'shops' => $shops,
                'selectedShopId' => null,
                'activeRegister' => null,
            ]);
        }

        // Get active open register
        $activeRegister = CashRegister::where('shop_id', $selectedShopId)
            ->where('status', 'open')
            ->first();

        $rolloverBalance = 0.00;
        $expectedBalance = 0.00;
        $posSalesSum = 0.00;
        $posSalesProfit = 0.00;
        $manualIncomeSum = 0.00;
        $totalExpenses = 0.00;
        $receivablesSum = 0.00;
        $recentTransactions = collect();
        $recentReceivables = collect();

        if ($activeRegister) {
            // Get POS sales since register was opened
            $posSalesSum = (float) Sale::where('shop_id', $selectedShopId)
                ->where('created_at', '>=', $activeRegister->opened_at)
                ->sum('total_amount');

            // Get POS sales profit since register was opened
            $posSalesProfit = (float) Sale::where('shop_id', $selectedShopId)
                ->where('created_at', '>=', $activeRegister->opened_at)
                ->sum('profit');

            // Get manual ledger income (exclude auto-logged POS sales to avoid double counting)
            $manualIncomeSum = (float) $activeRegister->transactions()
                ->where('type', 'income')
                ->where('category', '!=', 'POS Sale')
                ->sum('amount');

            // Get manual ledger expenses
            $totalExpenses = (float) $activeRegister->transactions()
                ->where('type', 'expense')
                ->sum('amount');

            // Expected Balance = Opening + POS Sales + Manual Income - Expenses
            $expectedBalance = (float) $activeRegister->opening_balance + $posSalesSum + $manualIncomeSum - $totalExpenses;

            // Accounts Receivable (Customer due credits logged in this register)
            $receivablesSum = (float) $activeRegister->receivables()
                ->where('status', 'pending')
                ->sum('amount');

            $recentTransactions = $activeRegister->transactions()->with('sale')->latest()->limit(10)->get();
            $recentReceivables = $activeRegister->receivables()->latest()->limit(10)->get();
        } else {
            // Get last closed register to roll over cash in hand
            $lastClosedRegister = CashRegister::where('shop_id', $selectedShopId)
                ->where('status', 'closed')
                ->latest('closed_at')
                ->first();
            $rolloverBalance = $lastClosedRegister ? (float) $lastClosedRegister->cash_in_hand : 0.00;
        }

        return view('reconciliation::index', compact(
            'shops',
            'selectedShopId',
            'activeRegister',
            'rolloverBalance',
            'expectedBalance',
            'posSalesSum',
            'posSalesProfit',
            'manualIncomeSum',
            'totalExpenses',
            'receivablesSum',
            'recentTransactions',
            'recentReceivables'
        ));
    }

    /**
     * Open a new cash register session.
     */
    public function open(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'opening_balance' => 'required|numeric|min:0',
        ]);

        $shopId = (int) $validated['shop_id'];
        abort_unless(auth()->user()->ownsShop($shopId), 403, 'You do not have access to this shop.');

        // Check if there is an active open register
        $existing = CashRegister::where('shop_id', $shopId)->where('status', 'open')->first();
        if ($existing) {
            return back()->with('error', __('reconciliation.error_already_open'));
        }

        CashRegister::create([
            'shop_id' => $shopId,
            'user_id' => Auth::id(),
            'opening_balance' => $validated['opening_balance'],
            'status' => 'open',
            'opened_at' => now(),
        ]);

        return back()->with('success', __('reconciliation.success_opened'));
    }

    /**
     * Close and Reconcile the cash register session.
     */
    public function close(Request $request, $id)
    {
        $validated = $request->validate([
            'cash_in_hand' => 'required|numeric|min:0',
        ]);

        $register = CashRegister::findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $register->shop_id), 403, 'You do not have access to this shop.');

        if ($register->status !== 'open') {
            return back()->with('error', __('reconciliation.error_not_open'));
        }

        $cashInHand = (float) $validated['cash_in_hand'];

        DB::transaction(function () use ($register, $cashInHand) {
            // 1. Calculate values
            $posSalesSum = (float) Sale::where('shop_id', $register->shop_id)
                ->where('created_at', '>=', $register->opened_at)
                ->sum('total_amount');

            $manualIncomeSum = (float) $register->transactions()
                ->where('type', 'income')
                ->where('category', '!=', 'POS Sale')
                ->sum('amount');

            $totalExpenses = (float) $register->transactions()
                ->where('type', 'expense')
                ->sum('amount');

            $receivablesSum = (float) $register->receivables()
                ->where('status', 'pending')
                ->sum('amount');

            // 2. Expected Balance = Opening + POS Sales + Manual Income - Expenses
            $expectedBalance = (float) $register->opening_balance + $posSalesSum + $manualIncomeSum - $totalExpenses;

            // 3. Actual Balance = Cash in Hand + Accounts Receivable
            $actualBalance = $cashInHand + $receivablesSum;

            // 4. Discrepancy = Expected - Actual
            $discrepancy = $expectedBalance - $actualBalance;

            // 5. Update session fields
            $register->update([
                'expected_balance' => $expectedBalance,
                'actual_balance'   => $actualBalance,
                'cash_in_hand'     => $cashInHand,
                'discrepancy'      => $discrepancy,
                'status'           => 'closed',
                'closed_at'        => now(),
            ]);
        });

        return redirect()->route('reconciliation.index', ['shop_id' => $register->shop_id])
            ->with('success', __('reconciliation.success_closed'));
    }

    /**
     * Display a listing of closed register sessions.
     */
    public function history(Request $request)
    {
        $user = auth()->user();
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        
        $selectedShopId = $request->integer('shop_id', (int) optional($shops->first())->id);
        if ($selectedShopId && !$user->ownsShop($selectedShopId)) {
            $selectedShopId = (int) optional($shops->first())->id;
        }

        $registersQuery = CashRegister::with(['user', 'shop'])
            ->where('status', 'closed');

        if ($selectedShopId) {
            $registersQuery->where('shop_id', $selectedShopId);
        } else {
            $registersQuery->whereIn('shop_id', $user->accessibleShopIds());
        }

        $registers = $registersQuery->latest('closed_at')->paginate(20);

        return view('reconciliation::history', compact('shops', 'selectedShopId', 'registers'));
    }

    /**
     * Show the detailed audit sheet for a closed session.
     */
    public function show($id)
    {
        $register = CashRegister::with(['user', 'shop', 'transactions', 'receivables'])->findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $register->shop_id), 403, 'You do not have access to this shop.');

        // Get POS sales during this register session's active period
        $posSales = Sale::with('product')
            ->where('shop_id', $register->shop_id)
            ->where('created_at', '>=', $register->opened_at)
            ->where('created_at', '<=', $register->closed_at)
            ->get();

        return view('reconciliation::show', compact('register', 'posSales'));
    }
}
