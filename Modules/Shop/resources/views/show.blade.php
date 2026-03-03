@extends('layouts.app')

@section('title', __('shop.show_title'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ $shop->name }}</h1>
        <p class="page-subtitle">{{ __('shop.show_subtitle') }}</p>
    </div>
    <div>
        <a href="{{ route('shop.edit', $shop->id) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> {{ __('app.edit') }}
        </a>
        <a href="{{ route('shop.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Shop Info Card -->
    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-info-circle"></i>
                    {{ __('shop.shop_information') }}
                </h5>
            </div>
            <div class="p-4">
                <div class="mb-3">
                    <label class="text-muted small">{{ __('shop.shop_name') }}</label>
                    <p class="mb-0 fw-semibold">{{ $shop->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">{{ __('shop.created_date') }}</label>
                    <p class="mb-0">{{ $shop->created_at->format('F d, Y') }}</p>
                </div>
                <div class="mb-0">
                    <label class="text-muted small">{{ __('shop.last_updated') }}</label>
                    <p class="mb-0">{{ $shop->updated_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="content-card mt-4">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-bar-chart"></i>
                    {{ __('shop.statistics') }}
                </h5>
            </div>
            <div class="p-4">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <h3 class="mb-0" style="color: #2563eb;">{{ $shop->products_count }}</h3>
                            <small class="text-muted">{{ __('shop.col_products') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <h3 class="mb-0" style="color: #10b981;">{{ $shop->sales_count }}</h3>
                            <small class="text-muted">{{ __('shop.col_sales') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="col-md-8">
        <div class="content-card">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    {{ __('shop.recent_products') }}
                </h5>
                <a href="{{ route('product.create') }}?shop_id={{ $shop->id }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> {{ __('shop.add_product') }}
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('product.category') }}</th>
                            <th>{{ __('product.brand') }}</th>
                            <th>{{ __('product.purchase_price') }}</th>
                            <th>{{ __('product.sale_price') }}</th>
                            <th>{{ __('product.stock') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shop->products as $product)
                        <tr>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category }}</td>
                            <td>{{ $product->brand }}</td>
                            <td>৳{{ number_format($product->purchase_price, 2) }}</td>
                            <td>৳{{ number_format($product->sale_price, 2) }}</td>
                            <td>
                                <span class="badge {{ $product->stock_quantity < 10 ? 'bg-danger' : 'bg-info' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-box"></i>
                                    <p class="mb-0">{{ __('shop.no_products') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
