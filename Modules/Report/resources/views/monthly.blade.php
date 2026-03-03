@extends('layouts.app')

@section('title', __('report.monthly_pnl'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="bi bi-calendar-range"></i> {{ __('report.monthly_pnl') }}</h1>
        <p class="page-subtitle">{{ __('report.monthly_pnl_subtitle') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('report.export.monthly-pdf', request()->query()) }}" class="btn btn-danger">
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
        <form action="{{ route('report.monthly') }}" method="GET">
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
                    <label for="year" class="form-label fw-semibold">{{ __('report.year') }}</label>
                    <select class="form-select" id="year" name="year">
                        @for($y = now()->format('Y'); $y >= now()->format('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $filters['year'] == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
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
        <strong>{{ __('report.profit_margin') }}</strong> = (Profit &divide; Revenue) &times; 100
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
                        <h4 class="mb-0">{{ $monthlyData['totals']->total_sales_count }}</h4>
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
                        <h4 class="mb-0">{{ number_format($monthlyData['totals']->total_revenue, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    @php $yearProfit = $monthlyData['totals']->total_profit; @endphp
                    <div class="stat-icon {{ $yearProfit >= 0 ? 'bg-success' : 'bg-danger' }}">
                        <i class="bi bi-graph-{{ $yearProfit >= 0 ? 'up' : 'down' }}-arrow"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_profit') }}</p>
                        <h4 class="mb-0 {{ $yearProfit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($yearProfit, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.profit_margin') }}</p>
                        <h4 class="mb-0">{{ $monthlyData['totals']->profit_margin }}%</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $monthNames = [
        1 => __('report.month_jan'), 2 => __('report.month_feb'), 3 => __('report.month_mar'),
        4 => __('report.month_apr'), 5 => __('report.month_may'), 6 => __('report.month_jun'),
        7 => __('report.month_jul'), 8 => __('report.month_aug'), 9 => __('report.month_sep'),
        10 => __('report.month_oct'), 11 => __('report.month_nov'), 12 => __('report.month_dec'),
    ];
    $rowsByMonth = $monthlyData['rows']->keyBy('month_number');
@endphp

<!-- Monthly Profit Trend Chart -->
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-bar-chart-fill"></i>
            {{ __('report.profit_trend') }}
            <small class="text-muted ms-2">({{ $monthlyData['year'] }})</small>
        </h5>
    </div>
    <div class="p-4">
        <canvas id="profitTrendChart" height="80"></canvas>
    </div>
</div>

<!-- Monthly P&L Table -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-table"></i>
            {{ __('report.monthly_pnl_table') }}
            <small class="text-muted ms-2">({{ $monthlyData['year'] }})</small>
        </h5>
    </div>
    <div class="table-responsive">
    @if($monthlyData['rows']->count() > 0)
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('report.month') }}</th>
                    <th class="text-center">{{ __('report.total_sales_count') }}</th>
                    <th class="text-end">{{ __('report.total_revenue') }}</th>
                    <th class="text-end">{{ __('report.total_cost') }}</th>
                    <th class="text-end">{{ __('report.total_profit') }}</th>
                    <th class="text-center">{{ __('report.profit_margin') }}</th>
                    <th class="text-center">{{ __('report.pnl_status') }}</th>
                </tr>
            </thead>
            <tbody>
                @for($m = 1; $m <= 12; $m++)
                    @php $row = $rowsByMonth->get($m); @endphp
                    @if($row)
                    <tr>
                        <td class="fw-semibold">{{ $monthNames[$m] }}</td>
                        <td class="text-center">{{ $row->total_sales_count }}</td>
                        <td class="text-end">{{ number_format($row->total_revenue, 2) }}</td>
                        <td class="text-end">{{ number_format($row->total_cost, 2) }}</td>
                        <td class="text-end {{ $row->total_profit >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                            {{ number_format($row->total_profit, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $row->profit_margin >= 20 ? 'bg-success' : ($row->profit_margin >= 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $row->profit_margin }}%
                            </span>
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

                    {{-- Per-shop comparison rows --}}
                    @if(empty($filters['shop_id']) && $monthlyData['shopBreakdown']->count() > 0)
                    <tr>
                        <td colspan="7" class="p-0 border-0">
                            <div class="px-3 py-1">
                                <a class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" href="#month-{{ $m }}" role="button" aria-expanded="false">
                                    <i class="bi bi-chevron-expand"></i> {{ __('report.per_shop_comparison') }}
                                </a>
                            </div>
                            <div class="collapse" id="month-{{ $m }}">
                                <table class="table table-sm mb-0 ms-4" style="background:#f8fafc;">
                                    <thead>
                                        <tr class="text-muted small">
                                            <th>{{ __('report.shop_name') }}</th>
                                            <th class="text-center">{{ __('report.total_sales_count') }}</th>
                                            <th class="text-end">{{ __('report.total_revenue') }}</th>
                                            <th class="text-end">{{ __('report.total_cost') }}</th>
                                            <th class="text-end">{{ __('report.total_profit') }}</th>
                                            <th class="text-center">{{ __('report.profit_margin') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyData['shopBreakdown'] as $breakdown)
                                            @php $shopRow = $breakdown->rows->firstWhere('month_number', $m); @endphp
                                            @if($shopRow)
                                            <tr>
                                                <td><span class="badge bg-primary">{{ $breakdown->shop->name }}</span></td>
                                                <td class="text-center">{{ $shopRow->total_sales_count }}</td>
                                                <td class="text-end">{{ number_format($shopRow->total_revenue, 2) }}</td>
                                                <td class="text-end">{{ number_format($shopRow->total_cost, 2) }}</td>
                                                <td class="text-end {{ $shopRow->total_profit >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                                    {{ number_format($shopRow->total_profit, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $shopRow->profit_margin >= 20 ? 'bg-success' : ($shopRow->profit_margin >= 0 ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ $shopRow->profit_margin }}%
                                                    </span>
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

                    @else
                    <tr class="text-muted">
                        <td>{{ $monthNames[$m] }}</td>
                        <td class="text-center">0</td>
                        <td class="text-end">0.00</td>
                        <td class="text-end">0.00</td>
                        <td class="text-end">0.00</td>
                        <td class="text-center"><span class="badge bg-light text-muted">0%</span></td>
                        <td class="text-center"><span class="badge bg-light text-muted">&mdash;</span></td>
                    </tr>
                    @endif
                @endfor
            </tbody>
            <tfoot class="table-light">
                <tr class="fw-bold">
                    <td>{{ __('report.yearly_total') }}</td>
                    <td class="text-center">{{ $monthlyData['totals']->total_sales_count }}</td>
                    <td class="text-end">{{ number_format($monthlyData['totals']->total_revenue, 2) }}</td>
                    <td class="text-end">{{ number_format($monthlyData['totals']->total_cost, 2) }}</td>
                    <td class="text-end {{ $monthlyData['totals']->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($monthlyData['totals']->total_profit, 2) }}
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $monthlyData['totals']->profit_margin >= 20 ? 'bg-success' : ($monthlyData['totals']->profit_margin >= 0 ? 'bg-warning' : 'bg-danger') }}">
                            {{ $monthlyData['totals']->profit_margin }}%
                        </span>
                    </td>
                    <td class="text-center">
                        @if($monthlyData['totals']->total_profit > 0)
                            <span class="badge bg-success"><i class="bi bi-arrow-up-short"></i> {{ __('report.profit_label') }}</span>
                        @elseif($monthlyData['totals']->total_profit < 0)
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
            <p class="mb-0">{{ __('report.no_monthly_data') }}</p>
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
        .content-card.mb-4:has(#profitTrendChart),
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('profitTrendChart');
    if (!ctx) return;

    const monthLabels = @json(array_values($monthNames));
    const profitData = [];
    const revenueData = [];
    const costData = [];
    const bgColors = [];

    @for($m = 1; $m <= 12; $m++)
        @php $chartRow = $rowsByMonth->get($m); @endphp
        profitData.push({{ $chartRow ? $chartRow->total_profit : 0 }});
        revenueData.push({{ $chartRow ? $chartRow->total_revenue : 0 }});
        costData.push({{ $chartRow ? $chartRow->total_cost : 0 }});
        bgColors.push({{ $chartRow && $chartRow->total_profit >= 0 ? "'rgba(25,135,84,0.7)'" : "'rgba(220,53,69,0.7)'" }});
    @endfor

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: '{{ __("report.total_profit") }}',
                    data: profitData,
                    backgroundColor: bgColors,
                    borderRadius: 4,
                    order: 1
                },
                {
                    label: '{{ __("report.total_revenue") }}',
                    data: revenueData,
                    type: 'line',
                    borderColor: 'rgba(37,99,235,0.8)',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.3,
                    fill: false,
                    order: 0
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
