@extends('layouts.app')

@section('title', __('stock::stock.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --stock-ink-900: #0f172a;
        --stock-ink-700: #334155;
        --stock-ink-500: #64748b;
        --stock-brand: #0f766e;
        --stock-brand-deep: #155e75;
        --stock-line: #d8e4ee;
    }

    .stock-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--stock-ink-900);
    }

    .stock-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(15, 118, 110, 0.19), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.16), transparent 55%),
            linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .stock-header {
        gap: 0.9rem;
    }

    .stock-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--stock-brand);
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--stock-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-title i {
        color: var(--stock-brand);
        font-size: 0.9em;
    }

    .page-subtitle {
        color: var(--stock-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-manage-products {
        border-radius: 999px;
        border: 1px solid #cedce9;
        background: rgba(255, 255, 255, 0.82);
        color: #3f556c;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.58rem 1rem;
        white-space: nowrap;
    }

    .btn-manage-products:hover {
        background: #ffffff;
        color: #1e293b;
        border-color: #97b0c8;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--stock-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .content-card-header {
        background: #f7fbff;
        border-bottom: 1px solid #dce8f3;
        padding: 0.9rem 1.2rem;
    }

    .content-card-title {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
        color: #36506b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .form-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        margin-bottom: 0.48rem;
    }

    .form-select,
    .form-control {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: var(--stock-ink-900);
        font-size: 0.94rem;
        padding-top: 0.62rem;
        padding-bottom: 0.62rem;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #53a89f;
        box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.14);
        background: #ffffff;
    }

    .btn-apply-filter {
        background: linear-gradient(140deg, var(--stock-brand), var(--stock-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.62rem 0.95rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
    }

    .btn-apply-filter:hover {
        color: #fff;
    }

    .shop-stat-card {
        border: 1px solid #dbe7f2;
        border-radius: 16px;
        background: #fbfdff;
    }

    .shop-stat-head {
        border-bottom: 1px solid #e4edf6;
    }

    .shop-stat-head h6 {
        font-size: 0.95rem;
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.84rem;
        margin-bottom: 0.6rem;
    }

    .stat-row:last-child {
        margin-bottom: 0;
    }

    .stat-label {
        color: var(--stock-ink-500);
    }

    .stat-value {
        color: var(--stock-ink-900);
        font-weight: 700;
    }

    .stat-badge {
        border-radius: 999px;
        padding: 0.34rem 0.62rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .badge-low {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.24);
    }

    .badge-out {
        background: rgba(220, 38, 38, 0.14);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.24);
    }

    .badge-neutral {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #dbe6f1;
    }

    .stock-table {
        margin-bottom: 0;
    }

    .stock-table thead th {
        background: #f7fbff !important;
        border-bottom: 1px solid #dce8f3;
        color: #4b637b;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.9rem 0.95rem;
        white-space: nowrap;
    }

    .stock-table tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .stock-table tbody tr:hover {
        background: #fbfdff;
    }

    .shop-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .stock-pill {
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .stock-pill-low {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.24);
    }

    .stock-pill-out {
        background: rgba(220, 38, 38, 0.14);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.24);
    }

    .stock-pill-ok {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .empty-state {
        padding: 2.8rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2.3rem;
        color: #8aa0b6;
        display: block;
        margin-bottom: 0.65rem;
    }

    .empty-state h3 {
        font-size: 1.15rem;
        margin-bottom: 0.35rem;
    }

    .empty-state p {
        color: var(--stock-ink-500);
        margin-bottom: 0;
    }

    @media (max-width: 767.98px) {
        .stock-header {
            align-items: stretch !important;
        }

        .btn-manage-products,
        .btn-apply-filter {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="stock-shell">
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap stock-header">
    <div>
        <span class="stock-kicker"><i class="bi bi-boxes"></i>{{ __('stock::stock.title') }}</span>
        <h1 class="page-title display-font"><i class="bi bi-boxes"></i> {{ __('stock::stock.title') }}</h1>
        <p class="page-subtitle">{{ __('stock::stock.subtitle') }}</p>
    </div>
    <a href="{{ route('product.index') }}" class="btn btn-manage-products">
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
                    <button type="submit" class="btn btn-apply-filter w-100">
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
            <div class="content-card shop-stat-card h-100">
                <div class="p-3 shop-stat-head">
                    <h6 class="mb-0 fw-semibold">{{ $shop->name }}</h6>
                </div>
                <div class="p-3">
                    <div class="stat-row">
                        <span class="stat-label">{{ __('stock::stock.total_products') }}</span>
                        <strong class="stat-value">{{ $totalProducts }}</strong>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">{{ __('stock::stock.total_units') }}</span>
                        <strong class="stat-value">{{ number_format($totalUnits) }}</strong>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">{{ __('stock::stock.stock_value') }}</span>
                        <strong class="stat-value">{{ number_format($stockValue, 2) }}</strong>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">{{ __('stock::stock.low_stock') }}</span>
                        <span class="stat-badge {{ $lowStockCount > 0 ? 'badge-low' : 'badge-neutral' }}">{{ $lowStockCount }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">{{ __('stock::stock.out_of_stock') }}</span>
                        <span class="stat-badge {{ $outOfStockCount > 0 ? 'badge-out' : 'badge-neutral' }}">{{ $outOfStockCount }}</span>
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
            <table class="table table-hover align-middle mb-0 stock-table">
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
                            <td><span class="shop-pill">{{ $product->shop?->name ?? __('stock::stock.deleted_shop') }}</span></td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td class="text-muted">{{ $product->category ?? '-' }}</td>
                            <td class="text-muted">{{ $product->brand ?? '-' }}</td>
                            <td class="text-end">{{ number_format($product->purchase_price, 2) }}</td>
                            <td class="text-end">{{ number_format($product->sale_price, 2) }}</td>
                            <td class="text-center">
                                @if($product->stock_quantity <= 0)
                                    <span class="stock-pill stock-pill-out">{{ __('stock::stock.out') }}</span>
                                @elseif($product->stock_quantity <= 5)
                                    <span class="stock-pill stock-pill-low">{{ $product->stock_quantity }}</span>
                                @else
                                    <span class="stock-pill stock-pill-ok">{{ $product->stock_quantity }}</span>
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
</div>
@endsection
