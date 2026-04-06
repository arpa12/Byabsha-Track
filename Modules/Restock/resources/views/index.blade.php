@extends('layouts.app')

@section('title', __('restock.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --restock-ink-900: #0f172a;
        --restock-ink-700: #334155;
        --restock-ink-500: #64748b;
        --restock-brand: #0f766e;
        --restock-brand-deep: #155e75;
        --restock-line: #d8e4ee;
    }

    .restock-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--restock-ink-900);
    }

    .restock-shell::before {
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

    .restock-header {
        gap: 0.9rem;
    }

    .restock-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--restock-brand);
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
        color: var(--restock-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--restock-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-new-restock {
        background: linear-gradient(140deg, var(--restock-brand), var(--restock-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.22rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-new-restock:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--restock-line);
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

    .form-control,
    .form-select {
        border-radius: 11px;
        border: 1px solid #d6e2ee;
        background: #fbfdff;
        color: var(--restock-ink-900);
        font-size: 0.94rem;
        padding-top: 0.62rem;
        padding-bottom: 0.62rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #53a89f;
        box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.14);
        background: #ffffff;
    }

    .btn-apply-filter {
        background: linear-gradient(140deg, var(--restock-brand), var(--restock-brand-deep));
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

    .restock-table {
        margin-bottom: 0;
    }

    .restock-table thead th {
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

    .restock-table tbody td,
    .restock-table tfoot td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .restock-table tbody tr:hover {
        background: #fbfdff;
    }

    .shop-pill,
    .qty-pill,
    .stock-pill {
        border-radius: 999px;
        padding: 0.35rem 0.68rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .shop-pill {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .qty-pill {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .stock-pill-ok {
        background: rgba(14, 165, 233, 0.14);
        color: #0369a1;
        border: 1px solid rgba(14, 165, 233, 0.24);
    }

    .stock-pill-out {
        background: rgba(220, 38, 38, 0.14);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.24);
    }

    .btn-row-action {
        border-radius: 10px;
        padding: 0.34rem 0.52rem;
    }

    .btn-row-edit {
        color: #475569;
        border-color: #cdd9e6;
        background: #fff;
    }

    .btn-row-edit:hover {
        background: #64748b;
        border-color: #64748b;
        color: #fff;
    }

    .btn-row-delete {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.35);
        background: #fff;
    }

    .btn-row-delete:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: #fff;
    }

    .restock-table tfoot {
        background: #f9fbff;
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
        color: var(--restock-ink-500);
        margin-bottom: 0.95rem;
    }

    .btn-empty-restock {
        background: linear-gradient(140deg, var(--restock-brand), var(--restock-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.5rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .btn-empty-restock:hover {
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .restock-header {
            align-items: stretch !important;
        }

        .btn-new-restock,
        .btn-apply-filter {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="restock-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap restock-header">
    <div>
        <span class="restock-kicker"><i class="bi bi-arrow-repeat"></i>{{ __('restock.title') }}</span>
        <h1 class="page-title display-font">{{ __('restock.title') }}</h1>
        <p class="page-subtitle">{{ __('restock.subtitle') }}</p>
    </div>
    <a href="{{ route('restock.create') }}" class="btn-new-restock">
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
                    <button type="submit" class="btn btn-apply-filter w-100">
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
            <table class="table table-hover align-middle mb-0 restock-table">
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
                        <th class="text-end">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($restocks as $restock)
                    <tr>
                        <td>{{ $restock->restock_date->format('d M Y') }}</td>
                        <td>
                            <span class="shop-pill">{{ $restock->shop?->name ?? 'Deleted shop' }}</span>
                        </td>
                        <td>{{ $restock->product?->name ?? 'Deleted product' }}</td>
                        <td class="text-center">
                            <span class="qty-pill">+{{ number_format($restock->quantity) }}</span>
                        </td>
                        <td class="text-end">{{ number_format($restock->purchase_price_per_unit, 2) }}</td>
                        <td class="text-end"><strong>{{ number_format($restock->total_cost, 2) }}</strong></td>
                        <td class="text-center">
                            <span class="stock-pill {{ (($restock->product?->stock_quantity ?? 0) > 0) ? 'stock-pill-ok' : 'stock-pill-out' }}">
                                {{ $restock->product?->stock_quantity !== null ? number_format($restock->product->stock_quantity) : 'N/A' }}
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
                        <td class="text-end">
                            <a href="{{ route('product.batches', $restock->product_id) }}"
                               class="btn btn-sm btn-row-action"
                               style="color:#0f766e;border-color:rgba(15,118,110,.35);background:#fff;"
                               title="View Batch Tracker">
                                <i class="bi bi-layers"></i>
                            </a>
                            <a href="{{ route('restock.edit', $restock->id) }}" class="btn btn-sm btn-row-action btn-row-edit" title="{{ __('app.edit') }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('restock.destroy', $restock->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('restock.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-row-action btn-row-delete" title="{{ __('app.delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
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
                            <span class="qty-pill">+{{ number_format($pageTotalQty) }}</span>
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
    @endif

    @if($restocks->count() === 0)
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <h3>{{ __('restock.no_restocks') }}</h3>
            <p>{{ __('restock.no_restocks_sub') }}</p>
            <a href="{{ route('restock.create') }}" class="btn btn-empty-restock">
                <i class="bi bi-plus-circle"></i> {{ __('restock.create_title') }}
            </a>
        </div>
    @endif
</div>
</div>
@endsection
