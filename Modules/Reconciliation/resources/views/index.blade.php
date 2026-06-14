@extends('layouts.app')

@section('title', __('reconciliation.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    .reconciliation-shell {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: #0f172a;
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .page-title {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 0.45rem;
        font-weight: 800;
    }

    .report-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: color-mix(in srgb, var(--brand, #0f766e) 12%, transparent);
        color: var(--brand, #0f766e);
        border: 1px solid color-mix(in srgb, var(--brand, #0f766e) 22%, transparent);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px color-mix(in srgb, var(--brand, #0f766e) 13%, transparent);
    }

    .panel {
        background: #fff;
        border: 1px solid #dce6ef;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .panel-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e7eef5;
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fafbfe;
    }

    .panel-body {
        padding: 1.25rem;
    }

    .btn-brand-primary {
        background: linear-gradient(135deg, var(--brand, #0f766e) 0%, var(--brand-deep, #0d5969) 100%);
        border-color: var(--brand, #0f766e);
        color: #fff;
        font-weight: 700;
        border-radius: 10px;
        padding: 0.6rem 1.25rem;
        box-shadow: 0 8px 18px color-mix(in srgb, var(--brand, #0f766e) 18%, transparent);
        transition: all 0.2s ease;
    }

    .btn-brand-primary:hover,
    .btn-brand-primary:focus {
        background: linear-gradient(135deg, var(--brand-deep, #0d5969) 0%, var(--brand, #0f766e) 100%);
        border-color: var(--brand-deep, #0d5969);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-brand-outline {
        border: 2px solid var(--brand, #0f766e);
        color: var(--brand, #0f766e);
        background: transparent;
        font-weight: 700;
        border-radius: 10px;
        padding: 0.55rem 1.25rem;
        transition: all 0.2s ease;
    }

    .btn-brand-outline:hover {
        background: var(--brand, #0f766e);
        color: #fff;
    }

    .btn-secondary-custom {
        border-radius: 10px;
        padding: 0.6rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 700;
        border: 1px solid #9eb8cb;
        color: #1f3f58;
        background: #f7fbff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-secondary-custom:hover {
        color: #0f172a;
        border-color: #6f93b0;
        background: #ffffff;
    }

    .ledger-column {
        height: 100%;
        min-height: 400px;
        display: flex;
        flex-direction: column;
    }

    .ledger-list {
        flex-grow: 1;
        overflow-y: auto;
        max-height: 480px;
    }

    .ledger-item {
        border: 1px solid #eef2f6;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
        background: #fff;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .ledger-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .ledger-amount-positive {
        color: #10b981;
        font-weight: 700;
    }

    .ledger-amount-negative {
        color: #ef4444;
        font-weight: 700;
    }

    .stat-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
    }

    .stat-badge-pending {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .stat-badge-repaid {
        background-color: #d1fae5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .math-card {
        background: radial-gradient(circle at top right, #fafbfe 0%, #edf4fa 100%);
        border: 1px solid #cbdde9;
        border-radius: 16px;
    }

    .reconciliation-math-row {
        font-size: 1.1rem;
        font-weight: 600;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #cfdfec;
    }

    .reconciliation-math-row:last-child {
        border-bottom: none;
        font-size: 1.35rem;
        font-weight: 800;
    }

    .discrepancy-badge {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 800;
        display: inline-block;
    }

    .discrepancy-zero {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .discrepancy-active {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .custom-modal {
        border-radius: 16px;
        overflow: hidden;
        border: none;
    }

    .custom-modal .modal-header {
        background: #f8fbff;
        border-bottom: 1px solid #e8eff5;
    }

    .custom-modal .modal-footer {
        background: #f8fbff;
        border-top: 1px solid #e8eff5;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 0.6rem 0.85rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--brand, #0f766e);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--brand, #0f766e) 25%, transparent);
    }
</style>
@endpush

@section('content')
<div class="reconciliation-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-4 mb-4">
        <div>
            <span class="report-kicker"><i class="bi bi-calculator"></i> {{ __('reconciliation.title') }}</span>
            <h1 class="page-title display-font text-3xl font-black text-slate-900 leading-none mb-1">{{ __('reconciliation.title') }}</h1>
            <p class="page-subtitle text-slate-500 text-sm mt-1.5">{{ __('reconciliation.subtitle') }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('reconciliation.history', ['shop_id' => $selectedShopId]) }}" class="btn-secondary-custom">
                <i class="bi bi-clock-history"></i> {{ __('reconciliation.history') }}
            </a>
        </div>
    </div>

    <!-- Shop Selector -->
    <div class="panel mb-4">
        <div class="panel-body">
            <form action="{{ route('reconciliation.index') }}" method="GET" id="shopSelectForm">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-4">
                        <label for="shop_id" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.select_shop') }}</label>
                        <select class="form-select" id="shop_id" name="shop_id" onchange="document.getElementById('shopSelectForm').submit()">
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ $selectedShopId == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(!$selectedShopId)
        <div class="alert alert-info">
            {{ __('reconciliation.select_shop') }}
        </div>
    @elseif(!$activeRegister)
        <!-- No Active Session - Open Session UI -->
        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-head">
                        <span><i class="bi bi-door-open me-2 text-primary"></i>{{ __('reconciliation.open_session') }}</span>
                        <span class="badge bg-danger">{{ __('reconciliation.no_active_session') }}</span>
                    </div>
                    <div class="panel-body">
                        <p class="text-slate-500 small mb-4">
                            {{ __('reconciliation.rollover_info') }}
                        </p>

                        @if($rolloverBalance > 0)
                            <div class="alert alert-success d-flex align-items-center mb-4 rounded-3 p-3">
                                <i class="bi bi-wallet2 fs-4 me-3 text-success"></i>
                                <div>
                                    <div class="small text-slate-500">Rollover Cash Available from Last Session:</div>
                                    <div class="fw-bold fs-5">৳{{ number_format($rolloverBalance, 2) }}</div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('reconciliation.open') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $selectedShopId }}">

                            <div class="mb-4">
                                <label for="opening_balance" class="form-label fw-bold small text-slate-600">{{ __('reconciliation.enter_opening_balance') }} (৳)</label>
                                <input type="number" step="0.01" class="form-control form-control-lg display-font font-bold" id="opening_balance" name="opening_balance" value="{{ $rolloverBalance }}" required min="0">
                            </div>

                            <button type="submit" class="btn btn-brand-primary w-100 py-3">
                                <i class="bi bi-play-circle-fill me-2"></i>{{ __('reconciliation.open_session') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Active Open Register Session -->
        <div class="row g-4 mb-4">
            <!-- Left Side Ledger (Sales / Income) -->
            <div class="col-lg-6">
                <div class="panel ledger-column">
                    <div class="panel-head">
                        <span class="text-success"><i class="bi bi-graph-up text-success me-2"></i>{{ __('reconciliation.left_side') }}</span>
                        <button class="btn btn-sm btn-brand-primary" data-bs-toggle="modal" data-bs-target="#logIncomeModal">
                            <i class="bi bi-plus-circle me-1"></i>{{ __('reconciliation.income') }}
                        </button>
                    </div>
                    <div class="panel-body d-flex flex-column flex-grow-1">
                        <!-- POS Cash Sales Card -->
                        <div class="alert alert-light border border-slate-200 d-flex justify-content-between align-items-center p-3 mb-3 rounded-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-pc-display-horizontal fs-4 me-3 text-slate-600"></i>
                                <div>
                                    <div class="fw-bold small text-slate-600">{{ __('reconciliation.pos_sales') }}</div>
                                    <div class="text-slate-400 small">Since: {{ $activeRegister->opened_at->format('d M, h:i A') }}</div>
                                    <div class="text-success small fw-semibold mt-1">
                                        <i class="bi bi-arrow-up-right-circle me-1"></i>Profit: ৳{{ number_format($posSalesProfit, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="fs-5 fw-bold display-font text-slate-800">৳{{ number_format($posSalesSum, 2) }}</div>
                        </div>

                        <!-- Manual Income List -->
                        <div class="ledger-list pe-1">
                            @php
                                $manualIncomes = $recentTransactions->where('type', 'income');
                            @endphp

                            <h6 class="text-slate-500 fw-bold small mb-2">{{ __('reconciliation.manual_income') }}</h6>
                            @forelse($manualIncomes as $item)
                                <div class="ledger-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold small text-slate-800">{{ $item->category }}</div>
                                        @if($item->notes)
                                            <div class="text-slate-500 small" style="font-size: 0.75rem;">{{ $item->notes }}</div>
                                        @endif
                                        @if($item->category === 'POS Sale' && $item->sale)
                                            <div class="text-success small fw-semibold mt-0.5" style="font-size: 0.75rem;">
                                                <i class="bi bi-arrow-up-right-circle me-1"></i>Profit: ৳{{ number_format($item->sale->profit, 2) }}
                                            </div>
                                        @endif
                                        <div class="text-slate-400 small" style="font-size: 0.7rem;">{{ $item->created_at->format('h:i A') }}</div>
                                    </div>
                                    <div class="ledger-amount-positive display-font">+৳{{ number_format($item->amount, 2) }}</div>
                                </div>
                            @empty
                                <div class="text-center text-slate-400 py-4 small">
                                    {{ __('reconciliation.no_transactions') }}
                                </div>
                            @endforelse
                        </div>

                        <!-- Total Left Side Sum -->
                        @php
                            $totalLeftSide = $posSalesSum + $manualIncomeSum;
                        @endphp
                        <div class="border-top border-slate-200 pt-3 mt-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-slate-700">Total Sales & Income:</span>
                            <span class="fs-4 fw-extrabold display-font text-success">৳{{ number_format($totalLeftSide, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Ledger (Expenses / Due Credits) -->
            <div class="col-lg-6">
                <div class="panel ledger-column">
                    <div class="panel-head">
                        <span class="text-danger"><i class="bi bi-graph-down text-danger me-2"></i>{{ __('reconciliation.right_side') }}</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logExpenseModal">
                                <i class="bi bi-plus-circle me-1"></i>{{ __('reconciliation.expense') }}
                            </button>
                            <button class="btn btn-sm btn-brand-outline" data-bs-toggle="modal" data-bs-target="#logReceivableModal">
                                <i class="bi bi-plus-circle me-1"></i>Due/Credit
                            </button>
                        </div>
                    </div>
                    <div class="panel-body d-flex flex-column flex-grow-1">
                        <!-- Manual Expenses List -->
                        <div class="mb-4">
                            <h6 class="text-slate-500 fw-bold small mb-2">{{ __('reconciliation.total_expenses') }}</h6>
                            <div class="ledger-list pe-1" style="max-height: 200px;">
                                @php
                                    $manualExpenses = $recentTransactions->where('type', 'expense');
                                @endphp
                                @forelse($manualExpenses as $item)
                                    <div class="ledger-item d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <div class="fw-bold small text-slate-800">{{ $item->category }}</div>
                                            @if($item->notes)
                                                <div class="text-slate-500 small" style="font-size: 0.75rem;">{{ $item->notes }}</div>
                                            @endif
                                            <div class="text-slate-400 small" style="font-size: 0.7rem;">{{ $item->created_at->format('h:i A') }}</div>
                                        </div>
                                        <div class="ledger-amount-negative display-font">-৳{{ number_format($item->amount, 2) }}</div>
                                    </div>
                                @empty
                                    <div class="text-center text-slate-400 py-3 small">
                                        {{ __('reconciliation.no_transactions') }}
                                    </div>
                                @endforelse
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                <span class="small fw-bold text-slate-500">Expenses Sum:</span>
                                <span class="fw-bold text-danger display-font">৳{{ number_format($totalExpenses, 2) }}</span>
                            </div>
                        </div>

                        <!-- Customer Receivables / Due Credits List -->
                        <div class="flex-grow-1 d-flex flex-column">
                            <h6 class="text-slate-500 fw-bold small mb-2">{{ __('reconciliation.recent_receivables') }}</h6>
                            <div class="ledger-list pe-1" style="max-height: 200px;">
                                @forelse($recentReceivables as $item)
                                    <div class="ledger-item d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <div class="fw-bold small text-slate-800">{{ $item->customer_name }}</div>
                                            @if($item->customer_phone)
                                                <div class="text-slate-500 small" style="font-size: 0.75rem;"><i class="bi bi-telephone me-1"></i>{{ $item->customer_phone }}</div>
                                            @endif
                                            <div class="text-slate-400 small" style="font-size: 0.7rem;">
                                                Logged: {{ $item->created_at->format('h:i A') }}
                                                @if($item->due_date) | Due: {{ \Carbon\Carbon::parse($item->due_date)->format('d M') }} @endif
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="stat-badge stat-badge-{{ $item->status }}">
                                                {{ $item->status === 'pending' ? __('reconciliation.pending') : __('reconciliation.repaid') }}
                                            </span>
                                            <div class="display-font fw-bold text-slate-800 me-2">৳{{ number_format($item->amount, 2) }}</div>
                                            @if($item->isPending())
                                                <form action="{{ route('ledger.repay', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-success py-1 px-2 text-white small" style="font-size: 0.75rem; border-radius: 6px;" title="{{ __('reconciliation.repay_btn') }}">
                                                        <i class="bi bi-check2"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-slate-400 py-3 small">
                                        {{ __('reconciliation.no_receivables') }}
                                    </div>
                                @endforelse
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                <span class="small fw-bold text-slate-500">Active Receivables Sum:</span>
                                <span class="fw-bold text-slate-800 display-font">৳{{ number_format($receivablesSum, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reconciliation Math & Closure Section -->
        <div class="row g-4">
            <!-- Dynamic Math Calculation Card -->
            <div class="col-lg-6">
                <div class="panel math-card h-100">
                    <div class="panel-head" style="background: transparent;">
                        <span><i class="bi bi-calculator-fill me-2 text-slate-600"></i>{{ __('reconciliation.reconcile_drawer') }}</span>
                    </div>
                    <div class="panel-body">
                        <div class="mb-3">
                            <div class="reconciliation-math-row d-flex justify-content-between align-items-center">
                                <span class="text-slate-500">{{ __('reconciliation.opening_balance') }}:</span>
                                <span class="display-font">৳{{ number_format($activeRegister->opening_balance, 2) }}</span>
                            </div>
                            <div class="reconciliation-math-row d-flex justify-content-between align-items-center">
                                <span class="text-slate-500">{{ __('reconciliation.pos_sales') }} (+):</span>
                                <span class="display-font">৳{{ number_format($posSalesSum, 2) }}</span>
                            </div>
                            <div class="reconciliation-math-row d-flex justify-content-between align-items-center">
                                <span class="text-slate-500">{{ __('reconciliation.manual_income') }} (+):</span>
                                <span class="display-font">৳{{ number_format($manualIncomeSum, 2) }}</span>
                            </div>
                            <div class="reconciliation-math-row d-flex justify-content-between align-items-center">
                                <span class="text-slate-500">{{ __('reconciliation.total_expenses') }} (-):</span>
                                <span class="display-font text-danger">-৳{{ number_format($totalExpenses, 2) }}</span>
                            </div>
                            <div class="reconciliation-math-row d-flex justify-content-between align-items-center bg-white border border-slate-200 rounded-3 p-3 my-2">
                                <span class="fw-bold text-slate-800">{{ __('reconciliation.expected_balance') }}:</span>
                                <span class="display-font text-primary fw-extrabold" id="lblExpected" data-val="{{ $expectedBalance }}">৳{{ number_format($expectedBalance, 2) }}</span>
                            </div>
                            <div class="reconciliation-math-row d-flex justify-content-between align-items-center">
                                <span class="text-slate-500">{{ __('reconciliation.accounts_receivable') }} (+):</span>
                                <span class="display-font" id="lblReceivable" data-val="{{ $receivablesSum }}">৳{{ number_format($receivablesSum, 2) }}</span>
                            </div>
                        </div>

                        <div class="alert alert-light border border-slate-200 small text-slate-500 mb-0">
                            <strong>Formula Details:</strong>
                            <ul class="mb-0 ps-3">
                                <li>{{ __('reconciliation.reconciliation_formula') }}</li>
                                <li>{{ __('reconciliation.actual_formula') }}</li>
                                <li>{{ __('reconciliation.discrepancy_formula') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Close & Reconcile Form -->
            <div class="col-lg-6">
                <div class="panel h-100">
                    <div class="panel-head">
                        <span><i class="bi bi-lock me-2 text-danger"></i>{{ __('reconciliation.close_session') }}</span>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('reconciliation.close', $activeRegister->id) }}" method="POST" onsubmit="return confirm('{{ __('reconciliation.close_confirmation') }}')">
                            @csrf

                            <div class="mb-4">
                                <label for="cash_in_hand" class="form-label fw-bold text-slate-700">{{ __('reconciliation.enter_cash_in_hand') }} (৳)</label>
                                <input type="number" step="0.01" class="form-control form-control-lg display-font font-bold" id="cash_in_hand" name="cash_in_hand" placeholder="0.00" required min="0">
                            </div>

                            <!-- Real-time Discrepancy UI -->
                            <div class="card border border-slate-200 rounded-3 p-3 mb-4 bg-slate-50">
                                <div class="row text-center">
                                    <div class="col-6 border-end border-slate-200">
                                        <div class="small text-slate-500 mb-1">{{ __('reconciliation.actual_balance') }}</div>
                                        <div class="fw-bold fs-5 display-font text-slate-800" id="lblActual">৳{{ number_format($receivablesSum, 2) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-slate-500 mb-1">{{ __('reconciliation.discrepancy') }}</div>
                                        <div class="d-inline-block" id="discrepancyBadgeWrap">
                                            <span class="discrepancy-badge discrepancy-active display-font" id="lblDiscrepancy">৳{{ number_format($expectedBalance - $receivablesSum, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold rounded-3">
                                <i class="bi bi-check2-all me-2"></i>{{ __('reconciliation.close_session') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Log Income Modal -->
<div class="modal fade" id="logIncomeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal">
            <div class="modal-header">
                <h5 class="modal-title display-font fw-bold"><i class="bi bi-journal-plus text-success me-2"></i>{{ __('reconciliation.log_transaction') }} ({{ __('reconciliation.income') }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if($activeRegister)
            <form action="{{ route('ledger.transaction') }}" method="POST">
                @csrf
                <input type="hidden" name="register_id" value="{{ $activeRegister->id }}">
                <input type="hidden" name="type" value="income">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="income_category" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.category') }}</label>
                        <select class="form-select" id="income_category" name="category" required>
                            <option value="Manual Sale">Manual Sale</option>
                            <option value="Repayment">Repayment</option>
                            <option value="Capital Input">Capital Input</option>
                            <option value="Other Income">Other Income</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="income_amount" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.amount') }} (৳)</label>
                        <input type="number" step="0.01" class="form-control display-font" id="income_amount" name="amount" required min="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="income_notes" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.notes') }}</label>
                        <textarea class="form-control" id="income_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-primary">Save Income</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Log Expense Modal -->
<div class="modal fade" id="logExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal">
            <div class="modal-header">
                <h5 class="modal-title display-font fw-bold"><i class="bi bi-journal-minus text-danger me-2"></i>{{ __('reconciliation.log_transaction') }} ({{ __('reconciliation.expense') }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if($activeRegister)
            <form action="{{ route('ledger.transaction') }}" method="POST">
                @csrf
                <input type="hidden" name="register_id" value="{{ $activeRegister->id }}">
                <input type="hidden" name="type" value="expense">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="expense_category" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.category') }}</label>
                        <select class="form-select" id="expense_category" name="category" required>
                            <option value="Supplier Payment">Supplier Payment</option>
                            <option value="Shop Rent">Shop Rent</option>
                            <option value="Utility Bill">Utility Bill</option>
                            <option value="Staff Salary">Staff Salary</option>
                            <option value="Tea/Snacks">Tea/Snacks</option>
                            <option value="Conveyance">Conveyance</option>
                            <option value="Other Expense">Other Expense</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="expense_amount" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.amount') }} (৳)</label>
                        <input type="number" step="0.01" class="form-control display-font" id="expense_amount" name="amount" required min="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="expense_notes" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.notes') }}</label>
                        <textarea class="form-control" id="expense_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Save Expense</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Log Accounts Receivable Modal -->
<div class="modal fade" id="logReceivableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal">
            <div class="modal-header">
                <h5 class="modal-title display-font fw-bold"><i class="bi bi-person-plus text-primary me-2"></i>{{ __('reconciliation.log_receivable') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if($activeRegister)
            <form action="{{ route('ledger.receivable') }}" method="POST">
                @csrf
                <input type="hidden" name="register_id" value="{{ $activeRegister->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.customer_name') }}</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.customer_phone') }}</label>
                        <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                    </div>
                    <div class="mb-3">
                        <label for="receivable_amount" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.amount') }} (৳)</label>
                        <input type="number" step="0.01" class="form-control display-font" id="receivable_amount" name="amount" required min="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label small fw-bold text-slate-600">{{ __('reconciliation.due_date') }}</label>
                        <input type="date" class="form-control" id="due_date" name="due_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-primary">Save Credit Due</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($activeRegister)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cashInHandInput = document.getElementById('cash_in_hand');
        const lblActual = document.getElementById('lblActual');
        const lblDiscrepancy = document.getElementById('lblDiscrepancy');
        const discrepancyBadgeWrap = document.getElementById('discrepancyBadgeWrap');
        
        const expectedVal = parseFloat(document.getElementById('lblExpected').getAttribute('data-val')) || 0;
        const receivableVal = parseFloat(document.getElementById('lblReceivable').getAttribute('data-val')) || 0;

        function updateReconciliationMath() {
            const cashVal = parseFloat(cashInHandInput.value) || 0;
            const actualVal = cashVal + receivableVal;
            const discrepancyVal = expectedVal - actualVal;

            lblActual.textContent = '৳' + actualVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            lblDiscrepancy.textContent = '৳' + discrepancyVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            if (Math.abs(discrepancyVal) < 0.01) {
                lblDiscrepancy.className = 'discrepancy-badge discrepancy-zero display-font';
            } else {
                lblDiscrepancy.className = 'discrepancy-badge discrepancy-active display-font';
            }
        }

        if (cashInHandInput) {
            cashInHandInput.addEventListener('input', updateReconciliationMath);
            updateReconciliationMath(); // Run once on load
        }
    });
</script>
@endif
@endpush
