@extends('layouts.app')

@section('title', __('report.title'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ __('report.title') }}</h1>
        <p class="page-subtitle">{{ __('report.subtitle') }}</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="bi bi-printer"></i> {{ __('report.print') }}
        </button>
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
        <form action="{{ route('report.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <label for="shop_id" class="form-label fw-semibold">{{ __('report.shop') }}</label>
                    <select class="form-select" id="shop_id" name="shop_id">
                        <option value="">{{ __('report.all_shops') }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ $filters['shop_id'] == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label fw-semibold">{{ __('report.start_date') }}</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $filters['start_date'] }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label fw-semibold">{{ __('report.end_date') }}</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $filters['end_date'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> {{ __('report.apply_filters') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sales Summary Cards -->
<div class="alert alert-light border mb-3 py-2 px-3" style="font-size:0.8rem;">
    <span class="fw-semibold text-muted"><i class="bi bi-calculator me-1"></i>{{ __('report.formulas') }} &nbsp;</span>
    <span class="text-muted">
        <strong>Revenue</strong> = &Sigma;(Qty &times; Sale Price) &nbsp;|&nbsp;
        <strong>Profit</strong> = &Sigma;((Sale Price &minus; Purchase Price) &times; Qty) &nbsp;|&nbsp;
        <strong>Avg Sale</strong> = Total Revenue &divide; Total Transactions
    </span>
</div>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="content-card">
            <div class="p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_sales') }}</p>
                        <h4 class="mb-0">{{ $salesSummary->total_transactions ?? 0 }}</h4>
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
                        <h4 class="mb-0">{{ number_format($salesSummary->total_revenue ?? 0, 2) }}</h4>
                        <small class="text-muted" style="font-size:0.72rem;">&Sigma;(Qty &times; Sale Price)</small>
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
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.total_profit') }}</p>
                        <h4 class="mb-0 text-success">{{ number_format($salesSummary->total_profit ?? 0, 2) }}</h4>
                        <small class="text-muted" style="font-size:0.72rem;">&Sigma;(Sale&minus;Purchase)&times;Qty</small>
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
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1">{{ __('report.items_sold') }}</p>
                        <h4 class="mb-0">{{ $salesSummary->total_quantity_sold ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales by Shop -->
@if($salesByShop->count() > 0)
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-shop"></i>
            {{ __('report.sales_by_shop') }}
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('report.shop_name') }}</th>
                    <th class="text-center">{{ __('report.total_transactions') }}</th>
                    <th class="text-center">{{ __('report.qty_sold') }}</th>
                    <th class="text-end">{{ __('report.revenue') }}</th>
                    <th class="text-end">{{ __('report.profit') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesByShop as $shop)
                <tr>
                    <td><span class="badge bg-primary">{{ $shop->name }}</span></td>
                    <td class="text-center">{{ $shop->total_sales }}</td>
                    <td class="text-center">{{ $shop->total_quantity }}</td>
                    <td class="text-end">{{ number_format($shop->total_revenue, 2) }}</td>
                    <td class="text-end text-success">{{ number_format($shop->total_profit, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Top Selling Products -->
@if($topProducts->count() > 0)
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-trophy"></i>
            {{ __('report.top_selling') }}
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('report.rank') }}</th>
                    <th>{{ __('report.product_name') }}</th>
                    <th>{{ __('report.shop') }}</th>
                    <th class="text-center">{{ __('report.units_sold') }}</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $index => $product)
                <tr>
                    <td>
                        @if($index == 0)
                            <span class="badge bg-warning"> 1</span>
                        @elseif($index == 1)
                            <span class="badge bg-secondary"> 2</span>
                        @elseif($index == 2)
                            <span class="badge bg-danger"> 3</span>
                        @else
                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                        @endif
                    </td>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td><span class="badge bg-primary">{{ $product->shop_name }}</span></td>
                    <td class="text-center">{{ $product->total_sold }}</td>
                    <td class="text-end">{{ number_format($product->total_revenue, 2) }}</td>
                    <td class="text-end text-success">{{ number_format($product->total_profit, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Stock Summary -->
<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-boxes"></i>
            {{ __('report.stock_summary') }}
        </h5>
    </div>
    <div class="p-4">
        <div class="row">
            <div class="col-md-3">
                <div class="border-start border-primary border-4 ps-3 mb-3">
                    <p class="text-muted small mb-1">{{ __('report.total_products') }}</p>
                    <h4 class="mb-0">{{ $stockSummary['total_products'] }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border-start border-success border-4 ps-3 mb-3">
                    <p class="text-muted small mb-1">{{ __('report.stock_value') }}</p>
                    <h4 class="mb-0">{{ number_format($stockSummary['total_stock_value'], 2) }}</h4>
                    <small class="text-muted" style="font-size:0.72rem;">&Sigma;(Stock &times; Purchase Price)</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border-start border-info border-4 ps-3 mb-3">
                    <p class="text-muted small mb-1">{{ __('report.potential_revenue') }}</p>
                    <h4 class="mb-0">{{ number_format($stockSummary['total_potential_revenue'], 2) }}</h4>
                    <small class="text-muted" style="font-size:0.72rem;">&Sigma;(Stock &times; Sale Price)</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border-start border-warning border-4 ps-3 mb-3">
                    <p class="text-muted small mb-1">{{ __('report.potential_profit') }}</p>
                    <h4 class="mb-0 text-success">{{ number_format($stockSummary['total_potential_profit'], 2) }}</h4>
                    <small class="text-muted" style="font-size:0.72rem;">Potential Revenue &minus; Stock Value</small>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>{{ __('report.low_stock_alert') }}</strong> {{ $stockSummary['low_stock_count'] }} {{ __('report.low_stock_products') }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i>
                    <div>
                        <strong>{{ __('report.out_of_stock') }}</strong> {{ $stockSummary['out_of_stock_count'] }} {{ __('report.products') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
