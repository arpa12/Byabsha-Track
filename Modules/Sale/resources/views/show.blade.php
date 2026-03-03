@extends('layouts.app')

@section('title', __('sale.show_title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">{{ __('sale.show_title') }}</h1>
        <p class="page-subtitle">{{ __('sale.show_subtitle') }}</p>
    </div>
    <a href="{{ route('sale.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> {{ __('sale.back_to_list') }}
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-receipt"></i>
                    {{ __('sale.sale_information') }}
                </h5>
            </div>
            <div class="p-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="fw-semibold" style="width: 200px;">{{ __('sale.sale_date') }}:</td>
                            <td>{{ $sale->sale_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.shop') }}:</td>
                            <td><span class="badge bg-primary">{{ $sale->shop->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.product') }}:</td>
                            <td>{{ $sale->product->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.category') }}:</td>
                            <td>{{ $sale->product->category ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.brand') }}:</td>
                            <td>{{ $sale->product->brand ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.quantity_sold') }}:</td>
                            <td><span class="badge bg-info fs-6">{{ $sale->quantity }} units</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.sale_price_unit') }}:</td>
                            <td>{{ number_format($sale->sale_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.total_amount') }}:</td>
                            <td><h4 class="mb-0">{{ number_format($sale->total_amount, 2) }}</h4></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('sale.profit') }}:</td>
                            <td>
                                <span class="badge {{ $sale->profit > 0 ? 'bg-success' : ($sale->profit < 0 ? 'bg-danger' : 'bg-secondary') }} fs-6">
                                    {{ number_format($sale->profit, 2) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('app.created_at') }}:</td>
                            <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('app.updated_at') }}:</td>
                            <td>{{ $sale->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-calculator"></i>
                    {{ __('sale.breakdown') }}
                </h5>
            </div>
            <div class="p-4">
                @php
                    $purchaseTotal = $sale->product->purchase_price * $sale->quantity;
                    $profitMargin  = $sale->total_amount > 0 ? (($sale->profit / $sale->total_amount) * 100) : 0;
                    $perUnitProfit = $sale->sale_price - $sale->product->purchase_price;
                @endphp

                {{-- Total Amount --}}
                <div class="mb-3 p-3 rounded" style="background:#f0f9ff; border-left:3px solid #0ea5e9;">
                    <div class="text-muted small fw-semibold mb-1">{{ __('sale.total_amount') }}</div>
                    <code class="d-block small text-muted">= Quantity &times; Sale Price</code>
                    <code class="d-block small text-muted">= {{ $sale->quantity }} &times; {{ number_format($sale->sale_price, 2) }}</code>
                    <h4 class="mb-0 text-primary mt-1">{{ number_format($sale->total_amount, 2) }}</h4>
                </div>

                {{-- Purchase Cost --}}
                <div class="mb-3 p-3 rounded" style="background:#fff7ed; border-left:3px solid #f97316;">
                    <div class="text-muted small fw-semibold mb-1">{{ __('sale.purchase_cost') }} <span class="text-muted fw-normal">({{ __('sale.your_expense') }})</span></div>
                    <code class="d-block small text-muted">= Quantity &times; Purchase Price</code>
                    <code class="d-block small text-muted">= {{ $sale->quantity }} &times; {{ number_format($sale->product->purchase_price, 2) }}</code>
                    <h4 class="mb-0 text-warning mt-1">{{ number_format($purchaseTotal, 2) }}</h4>
                </div>

                {{-- Profit --}}
                <div class="mb-3 p-3 rounded" style="background:#f0fdf4; border-left:3px solid #22c55e;">
                    <div class="text-muted small fw-semibold mb-1">{{ __('sale.profit') }}</div>
                    <code class="d-block small text-muted">= (Sale Price &minus; Purchase Price) &times; Quantity</code>
                    <code class="d-block small text-muted">= ({{ number_format($sale->sale_price, 2) }} &minus; {{ number_format($sale->product->purchase_price, 2) }}) &times; {{ $sale->quantity }}</code>
                    <code class="d-block small text-muted">= {{ number_format($perUnitProfit, 2) }} &times; {{ $sale->quantity }}</code>
                    <h4 class="mb-0 mt-1 {{ $sale->profit > 0 ? 'text-success' : ($sale->profit < 0 ? 'text-danger' : 'text-secondary') }}">
                        {{ number_format($sale->profit, 2) }}
                    </h4>
                </div>

                {{-- Profit Margin --}}
                <div class="p-3 rounded" style="background:#faf5ff; border-left:3px solid #a855f7;">
                    <div class="text-muted small fw-semibold mb-1">{{ __('sale.profit_margin') }}</div>
                    <code class="d-block small text-muted">= (Profit &divide; Total Amount) &times; 100</code>
                    <code class="d-block small text-muted">= ({{ number_format($sale->profit, 2) }} &divide; {{ number_format($sale->total_amount, 2) }}) &times; 100</code>
                    <h4 class="mb-0 mt-1 {{ $profitMargin > 0 ? 'text-success' : ($profitMargin < 0 ? 'text-danger' : 'text-secondary') }}">
                        {{ number_format($profitMargin, 2) }}%
                    </h4>
                </div>
            </div>
        </div>

        <div class="content-card mt-3">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-gear"></i>
                    {{ __('sale.actions') }}
                </h5>
            </div>
            <div class="p-3">
                <form action="{{ route('sale.destroy', $sale->id) }}"
                      method="POST"
                      onsubmit="return confirm('{{ __("sale.confirm_delete") }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> {{ __('sale.delete_sale') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
