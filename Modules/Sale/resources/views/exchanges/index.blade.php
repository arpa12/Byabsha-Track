@extends('layouts.app')

@section('title', __('sale.exchange_title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1">{{ __('sale.exchange_title') }}</h2>
        <p class="text-muted mb-0">{{ __('sale.exchange_subtitle') }}</p>
    </div>
    <a href="{{ route('sale.exchanges.create') }}" class="btn btn-primary">
        <i class="bi bi-arrow-repeat"></i> {{ __('sale.exchange_create_btn') }}
    </a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="shop_id" class="form-select">
            <option value="">{{ __('sale.all_shops') }}</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}" {{ (string)($filters['shop_id'] ?? '') === (string)$shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <select name="type" class="form-select">
            <option value="">{{ __('sale.all_types') }}</option>
            <option value="replacement" {{ ($filters['type'] ?? '') === 'replacement' ? 'selected' : '' }}>{{ __('sale.exchange_type_replacement') }}</option>
            <option value="return_only" {{ ($filters['type'] ?? '') === 'return_only' ? 'selected' : '' }}>{{ __('sale.exchange_type_return_only') }}</option>
        </select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-outline-secondary" type="submit">{{ __('sale.apply_filters') }}</button>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                <tr>
                    <th>{{ __('sale.sale_reference') }}</th>
                    <th>{{ __('sale.shop') }}</th>
                    <th>{{ __('sale.product') }}</th>
                    <th>{{ __('sale.exchange_date') }}</th>
                    <th>{{ __('sale.exchange_type') }}</th>
                    <th>{{ __('sale.quantity') }}</th>
                    <th>{{ __('sale.exchange_cost_difference') }}</th>
                    <th>{{ __('sale.reason') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($exchanges as $exchange)
                    <tr>
                        <td>#{{ $exchange->sale_id }}</td>
                        <td>{{ $exchange->shop?->name ?? '-' }}</td>
                        <td>{{ $exchange->sale?->product?->name ?? '-' }}</td>
                        <td>{{ $exchange->exchange_date->format('d M Y') }}</td>
                        <td>{{ __('sale.exchange_type_' . $exchange->exchange_type) }}</td>
                        <td>{{ $exchange->quantity }}</td>
                        <td>{{ number_format((float)$exchange->cost_difference, 2) }}</td>
                        <td>{{ $exchange->reason }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">{{ __('sale.no_records') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $exchanges->links() }}
</div>
@endsection
