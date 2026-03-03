@extends('layouts.app')

@section('title', __('restock.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">{{ __('restock.title') }}</h1>
        <p class="page-subtitle">{{ __('restock.subtitle') }}</p>
    </div>
    <a href="{{ route('restock.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> {{ __('restock.create_title') }}
    </a>
</div>

<!-- Filters -->
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-funnel"></i>
            {{ __('restock.filters') }}
        </h5>
    </div>
    <div class="p-4">
        <form action="{{ route('restock.index') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <label for="shop_id" class="form-label fw-semibold">{{ __('restock.shop') }}</label>
                    <select class="form-select" id="filter_shop_id" name="shop_id">
                        <option value="">{{ __('restock.all_shops') }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ ($filters['shop_id'] ?? '') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label fw-semibold">{{ __('restock.date_from') }}</label>
                    <input type="date" class="form-control" id="date_from" name="date_from"
                           value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label fw-semibold">{{ __('restock.date_to') }}</label>
                    <input type="date" class="form-control" id="date_to" name="date_to"
                           value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> {{ __('restock.apply_filters') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Restocks Table -->
<div class="content-card">
    @if($restocks->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('restock.col_date') }}</th>
                        <th>{{ __('restock.col_shop') }}</th>
                        <th>{{ __('restock.col_product') }}</th>
                        <th class="text-center">{{ __('restock.col_quantity') }}</th>
                        <th class="text-end">{{ __('restock.col_price_per_unit') }}</th>
                        <th class="text-end">{{ __('restock.col_total_cost') }}</th>
                        <th class="text-center">{{ __('restock.col_current_stock') }}</th>
                        <th>{{ __('restock.col_note') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($restocks as $restock)
                    <tr>
                        <td>{{ $restock->restock_date->format('d M Y') }}</td>
                        <td><span class="badge bg-primary">{{ $restock->shop->name }}</span></td>
                        <td>{{ $restock->product->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-success">+{{ number_format($restock->quantity) }}</span>
                        </td>
                        <td class="text-end">{{ number_format($restock->purchase_price_per_unit, 2) }}</td>
                        <td class="text-end"><strong>{{ number_format($restock->total_cost, 2) }}</strong></td>
                        <td class="text-center">
                            <span class="badge {{ $restock->product->stock_quantity > 0 ? 'bg-info' : 'bg-danger' }}">
                                {{ number_format($restock->product->stock_quantity) }}
                            </span>
                        </td>
                        <td>
                            @if($restock->note)
                                <span class="text-muted small" title="{{ $restock->note }}">
                                    {{ Str::limit($restock->note, 30) }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-semibold">
                    @php
                        $pageTotalQty  = $restocks->sum('quantity');
                        $pageTotalCost = $restocks->sum('total_cost');
                    @endphp
                    <tr>
                        <td colspan="3" class="text-muted small">
                            {{ __('restock.page_totals') }} ({{ $restocks->count() }} {{ __('restock.records') }})
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">+{{ number_format($pageTotalQty) }}</span>
                        </td>
                        <td></td>
                        <td class="text-end">{{ number_format($pageTotalCost, 2) }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="p-3">
            {{ $restocks->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <h3>{{ __('restock.no_restocks') }}</h3>
            <p>{{ __('restock.no_restocks_sub') }}</p>
            <a href="{{ route('restock.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> {{ __('restock.create_title') }}
            </a>
        </div>
    @endif
</div>
@endsection
