@extends('layouts.app')

@section('title', __('product.show_title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">{{ __('product.show_title') }}</h1>
        <p class="page-subtitle">{{ __('product.show_subtitle') }}</p>
    </div>
    <div>
        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> {{ __('app.edit') }}
        </a>
        <a href="{{ route('product.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('product.back_to_list') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    {{ __('product.product_information') }}
                </h5>
            </div>
            <div class="p-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="fw-semibold" style="width: 200px;">{{ __('product.product_name') }}:</td>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.shop') }}:</td>
                            <td><span class="badge bg-primary">{{ $product->shop->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.category') }}:</td>
                            <td>{{ $product->category ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.brand') }}:</td>
                            <td>{{ $product->brand ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.purchase_price') }}:</td>
                            <td>{{ number_format($product->purchase_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.sale_price') }}:</td>
                            <td>{{ number_format($product->sale_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.profit_per_unit') }}:</td>
                            <td>
                                @php
                                    $profit = $product->sale_price - $product->purchase_price;
                                @endphp
                                <span class="badge {{ $profit > 0 ? 'bg-success' : ($profit < 0 ? 'bg-danger' : 'bg-secondary') }}">
                                    {{ number_format($profit, 2) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('product.current_stock') }}:</td>
                            <td>
                                @if($product->stock_quantity <= 5)
                                    <span class="badge bg-danger fs-6">{{ $product->stock_quantity }} {{ __('app.units') }}</span>
                                    <small class="text-danger d-block mt-1"> {{ __('product.low_stock_alert') }}</small>
                                @elseif($product->stock_quantity <= 20)
                                    <span class="badge bg-warning fs-6">{{ $product->stock_quantity }} {{ __('app.units') }}</span>
                                @else
                                    <span class="badge bg-success fs-6">{{ $product->stock_quantity }} {{ __('app.units') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('app.created_at') }}:</td>
                            <td>{{ $product->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">{{ __('app.updated_at') }}:</td>
                            <td>{{ $product->updated_at->format('d M Y, h:i A') }}</td>
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
                    {{ __('product.quick_stats') }}
                </h5>
            </div>
            <div class="p-4">
                @php
                    $profit = $product->sale_price - $product->purchase_price;
                    $profitMargin = $product->purchase_price > 0 ? (($profit / $product->purchase_price) * 100) : 0;
                    $inventoryValue = $product->stock_quantity * $product->purchase_price;
                    $potentialRevenue = $product->stock_quantity * $product->sale_price;
                @endphp

                <div class="mb-3">
                    <label class="form-label text-muted small">{{ __('product.profit_margin') }}</label>
                    <h4 class="mb-0 {{ $profitMargin > 0 ? 'text-success' : ($profitMargin < 0 ? 'text-danger' : 'text-secondary') }}">
                        {{ number_format($profitMargin, 2) }}%
                    </h4>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">{{ __('product.inventory_value') }}</label>
                    <h4 class="mb-0">{{ number_format($inventoryValue, 2) }}</h4>
                    <small class="text-muted">{{ $product->stock_quantity }} units  {{ number_format($product->purchase_price, 2) }}</small>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">{{ __('product.potential_revenue') }}</label>
                    <h4 class="mb-0 text-primary">{{ number_format($potentialRevenue, 2) }}</h4>
                    <small class="text-muted">{{ __('product.if_all_sold') }}</small>
                </div>

                <div>
                    <label class="form-label text-muted small">{{ __('product.potential_profit') }}</label>
                    <h4 class="mb-0 text-success">{{ number_format($potentialRevenue - $inventoryValue, 2) }}</h4>
                    <small class="text-muted">{{ __('product.total_profit_potential') }}</small>
                </div>
            </div>
        </div>

        <div class="content-card mt-3">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-gear"></i>
                    {{ __('product.actions') }}
                </h5>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> {{ __('product.edit_product') }}
                    </a>
                    <form action="{{ route('product.destroy', $product->id) }}"
                          method="POST"
                          onsubmit="return confirm('{{ __("product.confirm_delete") }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> {{ __('product.delete_product') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
