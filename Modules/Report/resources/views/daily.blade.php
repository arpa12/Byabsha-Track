@extends('layouts.app')

@section('title', __('report.daily_pnl'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="bi bi-calendar-check"></i> {{ __('report.daily_pnl') }}</h1>
        <p class="page-subtitle">{{ __('report.daily_pnl_subtitle') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('report.export.daily-pdf', request()->query()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> {{ __('report.download_pdf') }}
        </a>
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="bi bi-printer"></i> {{ __('report.print') }}
        </button>
        <a href="{{ route('report.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('report.back_to_reports') }}
        </a>
    </div>
</div>

<!-- Filters -->
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-funnel"></i>
            {{ __('report.filters') }}
        </h5>
    </div>
    <div class="p-4">
        <form action="{{ route('report.daily') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label for="shop_id" class="form-label fw-semibold">{{ __('report.shop') }}</label>
                    <select class="form-select" id="shop_id" name="shop_id">
                        <option value="">{{ __('report.all_shops') }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ ($filters['shop_id'] ?? '') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="month" class="form-label fw-semibold">{{ __('report.month') }}</label>
                    <input type="month" class="form-control" id="month" name="month" value="{{ $filters['month'] }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> {{ __('report.apply_filters') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Formula hint -->
<div class="alert alert-light border mb-3 py-2 px-3" style="font-size:0.8rem;">
    <span class="fw-semibold text-muted"><i class="bi bi-calculator me-1"></i>{{ __('report.formulas') }} &nbsp;</span>
    <span class="text-muted">
        <strong>{{ __('report.total_revenue') }}</strong> = &Sigma;(Qty &times; Sale Price) &nbsp;|&nbsp;
        <strong>{{ __('report.total_cost') }}</strong> = &Sigma;(Qty &times; Purchase Price) &nbsp;|&nbsp;
        <strong>{{ __('report.total_profit') }}</strong> = Revenue &minus; Cost
    </span>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_sales_count') }}</p>
                        <h4 class="mb-0">{{ $dailyData['totals']->total_sales_count }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_revenue') }}</p>
                        <h4 class="mb-0">৳{{ number_format($dailyData['totals']->total_revenue, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_cost') }}</p>
                        <h4 class="mb-0">৳{{ number_format($dailyData['totals']->total_cost, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    @php $monthProfit = $dailyData['totals']->total_profit; @endphp
                    <div class="stat-icon {{ $monthProfit >= 0 ? 'bg-success' : 'bg-danger' }}">
                        <i class="bi bi-graph-{{ $monthProfit >= 0 ? 'up' : 'down' }}-arrow"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_profit') }}</p>
                        <h4 class="mb-0 {{ $monthProfit >= 0 ? 'text-success' : 'text-danger' }}">৳{{ number_format($monthProfit, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daily P&L Table -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-table"></i>
            {{ __('report.daily_pnl_table') }}
            <small class="text-muted ms-2">({{ \Carbon\Carbon::parse($dailyData['start_date'])->format('M Y') }})</small>
        </h5>
    </div>
    <div class="table-responsive">
    @if($dailyData['rows']->count() > 0)
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('report.date') }}</th>
                    <th>{{ __('report.shop_col') }}</th>
                    <th class="text-center">{{ __('report.total_sales_count') }}</th>
                    <th class="text-end">{{ __('report.total_revenue') }}</th>
                    <th class="text-end">{{ __('report.total_cost') }}</th>
                    <th class="text-end">{{ __('report.total_profit') }}</th>
                    <th class="text-center">{{ __('report.pnl_status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyData['rows'] as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->date)->format('D, M d') }}</td>
                    <td>
                        @if($filters['shop_id'])
                            {{ $shops->firstWhere('id', $filters['shop_id'])->name ?? '-' }}
                        @else
                            {{ __('report.all_shops') }}
                        @endif
                    </td>
                    <td class="text-center">{{ $row->total_sales_count }}</td>
                    <td class="text-end">৳{{ number_format($row->total_revenue, 2) }}</td>
                    <td class="text-end">৳{{ number_format($row->total_cost, 2) }}</td>
                    <td class="text-end {{ $row->total_profit >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                        ৳{{ number_format($row->total_profit, 2) }}
                    </td>
                    <td class="text-center">
                        @if($row->total_profit > 0)
                            <span class="badge bg-success"><i class="bi bi-arrow-up-short"></i> {{ __('report.profit_label') }}</span>
                        @elseif($row->total_profit < 0)
                            <span class="badge bg-danger"><i class="bi bi-arrow-down-short"></i> {{ __('report.loss_label') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('report.breakeven_label') }}</span>
                        @endif
                    </td>
                </tr>

                {{-- Per-shop collapsible rows --}}
                @if(empty($filters['shop_id']) && $dailyData['shopBreakdown']->count() > 0)
                <tr>
                    <td colspan="7" class="p-0 border-0">
                        <div class="px-3 py-1">
                            <a class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" href="#day-{{ $row->date }}" role="button" aria-expanded="false">
                                <i class="bi bi-chevron-expand"></i> {{ __('report.per_shop_breakdown') }}
                            </a>
                        </div>
                        <div class="collapse" id="day-{{ $row->date }}">
                            <table class="table table-sm mb-0 ms-4" style="background:#f8fafc;">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>{{ __('report.shop_name') }}</th>
                                        <th class="text-center">{{ __('report.total_sales_count') }}</th>
                                        <th class="text-end">{{ __('report.total_revenue') }}</th>
                                        <th class="text-end">{{ __('report.total_cost') }}</th>
                                        <th class="text-end">{{ __('report.total_profit') }}</th>
                                        <th class="text-center">{{ __('report.pnl_status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dailyData['shopBreakdown'] as $breakdown)
                                        @php
                                            $shopRow = $breakdown->rows->firstWhere('date', $row->date);
                                        @endphp
                                        @if($shopRow)
                                        <tr>
                                            <td><span class="badge bg-primary">{{ $breakdown->shop->name }}</span></td>
                                            <td class="text-center">{{ $shopRow->total_sales_count }}</td>
                                            <td class="text-end">৳{{ number_format($shopRow->total_revenue, 2) }}</td>
                                            <td class="text-end">৳{{ number_format($shopRow->total_cost, 2) }}</td>
                                            <td class="text-end {{ $shopRow->total_profit >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                                ৳{{ number_format($shopRow->total_profit, 2) }}
                                            </td>
                                            <td class="text-center">
                                                @if($shopRow->total_profit > 0)
                                                    <span class="badge bg-success"><i class="bi bi-arrow-up-short"></i></span>
                                                @elseif($shopRow->total_profit < 0)
                                                    <span class="badge bg-danger"><i class="bi bi-arrow-down-short"></i></span>
                                                @else
                                                    <span class="badge bg-secondary">&mdash;</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr class="fw-bold">
                    <td>{{ __('report.monthly_total') }}</td>
                    <td></td>
                    <td class="text-center">{{ $dailyData['totals']->total_sales_count }}</td>
                    <td class="text-end">৳{{ number_format($dailyData['totals']->total_revenue, 2) }}</td>
                    <td class="text-end">৳{{ number_format($dailyData['totals']->total_cost, 2) }}</td>
                    <td class="text-end {{ $dailyData['totals']->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                        ৳{{ number_format($dailyData['totals']->total_profit, 2) }}
                    </td>
                    <td class="text-center">
                        @if($dailyData['totals']->total_profit > 0)
                            <span class="badge bg-success"><i class="bi bi-arrow-up-short"></i> {{ __('report.profit_label') }}</span>
                        @elseif($dailyData['totals']->total_profit < 0)
                            <span class="badge bg-danger"><i class="bi bi-arrow-down-short"></i> {{ __('report.loss_label') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('report.breakeven_label') }}</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    @else
        <div class="p-5 text-center text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            <p class="mb-0">{{ __('report.no_daily_data') }}</p>
        </div>
    @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .top-header,
        .sidebar,
        .sidebar-toggle,
        .content-card.mb-4:has(.bi-funnel),
        .alert,
        .btn,
        a.btn {
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
        .content-card {
            border: none !important;
            box-shadow: none !important;
        }
        .table { font-size: 10px; }
    }
</style>
@endpush
