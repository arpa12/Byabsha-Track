@extends('layouts.app')

@section('title', __('stock::stock.title'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="bi bi-boxes"></i> {{ __('stock::stock.title') }}</h1>
        <p class="page-subtitle">{{ __('stock::stock.subtitle') }}</p>
    </div>
    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-box-seam"></i> {{ __('stock::stock.manage_products') }}
    </a>
</div>

<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-funnel"></i>
            {{ __('stock::stock.filter_title') }}
        </h5>
    </div>
    <div class="p-4">
        <form action="{{ route('stock.index') }}" method="GET">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label for="shop_id" class="form-label fw-semibold">{{ __('stock::stock.shop') }}</label>
                    <select id="shop_id" name="shop_id" class="form-select">
                        <option value="">{{ __('stock::stock.all_shops') }}</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ (string) $selectedShopId === (string) $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> {{ __('stock::stock.apply_filter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    @foreach($shops as $shop)
        @php
            $totalProducts = $shop->products->count();
            $totalUnits = $shop->products->sum('stock_quantity');
            $stockValue = $shop->products->sum(function ($product) {
                return $product->stock_quantity * $product->purchase_price;
            });
            $lowStockCount = $shop->products->filter(function ($product) {
                return $product->stock_quantity > 0 && $product->stock_quantity <= 5;
            })->count();
            $outOfStockCount = $shop->products->filter(function ($product) {
                return $product->stock_quantity <= 0;
            })->count();
        @endphp
        <div class="col-md-4 mb-3">
            <div class="content-card h-100">
                <div class="p-3 border-bottom">
                    <h6 class="mb-0 fw-semibold">{{ $shop->name }}</h6>
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">{{ __('stock::stock.total_products') }}</span>
                        <strong>{{ $totalProducts }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">{{ __('stock::stock.total_units') }}</span>
                        <strong>{{ number_format($totalUnits) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">{{ __('stock::stock.stock_value') }}</span>
                        <strong>{{ number_format($stockValue, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">{{ __('stock::stock.low_stock') }}</span>
                        <span class="badge {{ $lowStockCount > 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">{{ $lowStockCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">{{ __('stock::stock.out_of_stock') }}</span>
                        <span class="badge {{ $outOfStockCount > 0 ? 'bg-danger' : 'bg-secondary' }}">{{ $outOfStockCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="content-card">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="bi bi-table"></i>
            {{ __('stock::stock.stock_table_title') }}
        </h5>
    </div>

    @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('stock::stock.shop') }}</th>
                        <th>{{ __('stock::stock.product') }}</th>
                        <th>{{ __('stock::stock.category') }}</th>
                        <th>{{ __('stock::stock.brand') }}</th>
                        <th class="text-end">{{ __('stock::stock.purchase_price') }}</th>
                        <th class="text-end">{{ __('stock::stock.sale_price') }}</th>
                        <th class="text-center">{{ __('stock::stock.current_stock') }}</th>
                        <th class="text-end">{{ __('stock::stock.stock_value') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        @php($stockValue = $product->stock_quantity * $product->purchase_price)
                        <tr>
                            <td><span class="badge bg-primary">{{ $product->shop?->name ?? __('stock::stock.deleted_shop') }}</span></td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td class="text-muted">{{ $product->category ?? '-' }}</td>
                            <td class="text-muted">{{ $product->brand ?? '-' }}</td>
                            <td class="text-end">{{ number_format($product->purchase_price, 2) }}</td>
                            <td class="text-end">{{ number_format($product->sale_price, 2) }}</td>
                            <td class="text-center">
                                @if($product->stock_quantity <= 0)
                                    <span class="badge bg-danger">{{ __('stock::stock.out') }}</span>
                                @elseif($product->stock_quantity <= 5)
                                    <span class="badge bg-warning text-dark">{{ $product->stock_quantity }}</span>
                                @else
                                    <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ number_format($stockValue, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $products->links() }}
        </div>
    @endif

    @if($products->count() === 0)
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <h3>{{ __('stock::stock.no_products') }}</h3>
            <p>{{ __('stock::stock.no_products_subtitle') }}</p>
        </div>
    @endif
</div>
@endsection
