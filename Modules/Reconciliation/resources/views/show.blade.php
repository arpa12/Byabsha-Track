@extends('layouts.app')

@section('title', __('reconciliation.audit_sheet'))

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
        background: #fafbfe;
    }

    .panel-body {
        padding: 1.25rem;
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

    .compact-table th {
        background: #f8fafc;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        font-weight: 700;
        padding: 0.75rem 0.5rem;
    }

    .compact-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
        border-color: #e2e8f0;
    }

    .math-card {
        background: radial-gradient(circle at top right, #fafbfe 0%, #edf4fa 100%);
        border: 1px solid #cbdde9;
        border-radius: 16px;
    }

    .reconciliation-math-row {
        font-size: 1.05rem;
        font-weight: 600;
        padding: 0.45rem 0;
        border-bottom: 1px dashed #cfdfec;
    }

    .reconciliation-math-row:last-child {
        border-bottom: none;
        font-size: 1.3rem;
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

    @media print {
        .top-header,
        .sidebar,
        .sidebar-toggle,
        .btn,
        a.btn,
        .no-print {
            display: none !important;
        }

        .main-content {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }

        body {
            background: white !important;
            font-size: 11px;
        }

        .panel {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            page-break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="reconciliation-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-4 mb-4 no-print">
        <div>
            <span class="report-kicker"><i class="bi bi-file-earmark-bar-graph"></i> {{ __('reconciliation.audit_sheet') }}</span>
            <h1 class="page-title display-font text-3xl font-black text-slate-900 leading-none mb-1">{{ __('reconciliation.audit_sheet') }}</h1>
            <p class="page-subtitle text-slate-500 text-sm mt-1.5">{{ __('reconciliation.session_details') }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="window.print()" class="btn-secondary-custom">
                <i class="bi bi-printer"></i> Print Sheet
            </button>
            <a href="{{ route('reconciliation.history', ['shop_id' => $register->shop_id]) }}" class="btn-secondary-custom">
                <i class="bi bi-arrow-left"></i> {{ __('reconciliation.back_to_history') }}
            </a>
        </div>
    </div>

    <!-- Metadata Panel -->
    <div class="panel mb-4">
        <div class="panel-head">
            <span><i class="bi bi-info-circle me-1"></i>{{ __('reconciliation.session_details') }}</span>
            <span class="badge bg-secondary">Reference #REG-{{ $register->id }}</span>
        </div>
        <div class="panel-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="small text-slate-400">Shop</div>
                    <div class="fw-bold">{{ $register->shop->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-slate-400">Opened At</div>
                    <div class="fw-bold">{{ $register->opened_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-slate-400">Closed At</div>
                    <div class="fw-bold">{{ $register->closed_at ? $register->closed_at->format('d M Y, h:i A') : '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-slate-400">Session Manager</div>
                    <div class="fw-bold">{{ $register->user->name ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reconciliation Math Card -->
    <div class="panel math-card mb-4">
        <div class="panel-head" style="background: transparent;">
            <span><i class="bi bi-calculator me-1"></i>{{ __('reconciliation.reconciliation_summary') }}</span>
        </div>
        <div class="panel-body">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 border-end border-slate-200 pe-md-4">
                    <div class="reconciliation-math-row d-flex justify-content-between">
                        <span class="text-slate-500">{{ __('reconciliation.opening_balance') }}:</span>
                        <span class="display-font">৳{{ number_format($register->opening_balance, 2) }}</span>
                    </div>
                    <div class="reconciliation-math-row d-flex justify-content-between">
                        @php
                            $posSalesSum = (float) $posSales->sum('total_amount');
                            $posSalesProfitSum = (float) $posSales->sum('profit');
                            $manualIncomeSum = (float) $register->transactions->where('type', 'income')->where('category', '!=', 'POS Sale')->sum('amount');
                            $totalExpenses = (float) $register->transactions->where('type', 'expense')->sum('amount');
                            $receivablesSum = (float) $register->receivables->where('status', 'pending')->sum('amount');
                        @endphp
                        <span class="text-slate-500">{{ __('reconciliation.pos_sales') }} (+):</span>
                        <div>
                            <span class="display-font text-slate-800 me-2">৳{{ number_format($posSalesSum, 2) }}</span>
                            <span class="text-success small fw-semibold">(Profit: ৳{{ number_format($posSalesProfitSum, 2) }})</span>
                        </div>
                    </div>
                    <div class="reconciliation-math-row d-flex justify-content-between">
                        <span class="text-slate-500">{{ __('reconciliation.manual_income') }} (+):</span>
                        <span class="display-font">৳{{ number_format($manualIncomeSum, 2) }}</span>
                    </div>
                    <div class="reconciliation-math-row d-flex justify-content-between">
                        <span class="text-slate-500">{{ __('reconciliation.total_expenses') }} (-):</span>
                        <span class="display-font text-danger">-৳{{ number_format($totalExpenses, 2) }}</span>
                    </div>
                    <div class="reconciliation-math-row d-flex justify-content-between fw-bold bg-white border border-slate-200 rounded-3 p-3 my-2">
                        <span class="text-slate-800">{{ __('reconciliation.expected_balance') }}:</span>
                        <span class="display-font text-primary">৳{{ number_format($register->expected_balance, 2) }}</span>
                    </div>
                </div>
                <div class="col-md-6 ps-md-4">
                    <div class="reconciliation-math-row d-flex justify-content-between">
                        <span class="text-slate-500">{{ __('reconciliation.cash_in_hand') }} (v):</span>
                        <span class="display-font">৳{{ number_format($register->cash_in_hand, 2) }}</span>
                    </div>
                    <div class="reconciliation-math-row d-flex justify-content-between">
                        <span class="text-slate-500">Accounts Receivable (+):</span>
                        <span class="display-font">৳{{ number_format($register->actual_balance - $register->cash_in_hand, 2) }}</span>
                    </div>
                    <div class="reconciliation-math-row d-flex justify-content-between fw-bold bg-white border border-slate-200 rounded-3 p-3 my-2">
                        <span class="text-slate-800">{{ __('reconciliation.actual_balance') }}:</span>
                        <span class="display-font text-slate-800">৳{{ number_format($register->actual_balance, 2) }}</span>
                    </div>
                    <div class="reconciliation-math-row d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-slate-800">{{ __('reconciliation.discrepancy') }}:</span>
                        <span class="discrepancy-badge {{ abs($register->discrepancy) < 0.01 ? 'discrepancy-zero' : 'discrepancy-active' }} display-font">
                            ৳{{ number_format($register->discrepancy, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Ledger Records -->
    <div class="row g-4">
        <!-- Left Side: POS Sales and Manual Income -->
        <div class="col-lg-6">
            <div class="panel h-100">
                <div class="panel-head">
                    <span class="text-success"><i class="bi bi-graph-up me-2"></i>{{ __('reconciliation.left_side') }} Details</span>
                </div>
                <div class="panel-body">
                    <!-- POS Sales List -->
                    <h6 class="text-slate-500 fw-bold small mb-2">POS Sales</h6>
                    <div class="table-responsive mb-4" style="max-height: 300px; overflow-y: auto;">
                        <table class="table compact-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Profit</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posSales as $sale)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-slate-800">{{ $sale->product->name ?? '-' }}</span>
                                            <div class="text-slate-400 small" style="font-size: 0.7rem;">Time: {{ $sale->created_at->format('h:i A') }}</div>
                                        </td>
                                        <td class="text-center">{{ $sale->quantity }}</td>
                                        <td class="text-end text-success display-font">৳{{ number_format($sale->profit, 2) }}</td>
                                        <td class="text-end display-font">৳{{ number_format($sale->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No POS sales in this session.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Manual Incomes -->
                    <h6 class="text-slate-500 fw-bold small mb-2">{{ __('reconciliation.manual_income') }}</h6>
                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                        <table class="table compact-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Notes</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($register->transactions->where('type', 'income')->where('category', '!=', 'POS Sale') as $item)
                                    <tr>
                                        <td class="fw-semibold text-slate-800">{{ $item->category }}</td>
                                        <td class="text-slate-500 small">{{ $item->notes ?? '-' }}</td>
                                        <td class="text-end display-font text-success font-bold">+৳{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No manual income transactions logged.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Manual Expenses and Accounts Receivable -->
        <div class="col-lg-6">
            <div class="panel h-100">
                <div class="panel-head">
                    <span class="text-danger"><i class="bi bi-graph-down me-2"></i>{{ __('reconciliation.right_side') }} Details</span>
                </div>
                <div class="panel-body">
                    <!-- Manual Expenses -->
                    <h6 class="text-slate-500 fw-bold small mb-2">{{ __('reconciliation.total_expenses') }}</h6>
                    <div class="table-responsive mb-4" style="max-height: 300px; overflow-y: auto;">
                        <table class="table compact-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Notes</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($register->transactions->where('type', 'expense') as $item)
                                    <tr>
                                        <td class="fw-semibold text-slate-800">{{ $item->category }}</td>
                                        <td class="text-slate-500 small">{{ $item->notes ?? '-' }}</td>
                                        <td class="text-end display-font text-danger font-bold">-৳{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No manual expense transactions logged.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Accounts Receivable -->
                    <h6 class="text-slate-500 fw-bold small mb-2">Customer Due Credits (Receivables)</h6>
                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                        <table class="table compact-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($register->receivables as $item)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-slate-800">{{ $item->customer_name }}</span>
                                            @if($item->customer_phone)
                                                <div class="text-slate-400 small" style="font-size: 0.7rem;"><i class="bi bi-telephone me-1"></i>{{ $item->customer_phone }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end display-font font-bold">৳{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No due credits logged in this session.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
