@extends('layouts.app')

@section('title', __('shop.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --shop-ink-900: #0f172a;
        --shop-ink-700: #334155;
        --shop-ink-500: #64748b;
        --shop-brand: #0f766e;
        --shop-brand-deep: #155e75;
        --shop-line: #d8e4ee;
    }

    .shop-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--shop-ink-900);
    }

    .shop-shell::before {
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

    .shop-header {
        gap: 0.9rem;
    }

    .shop-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--shop-brand);
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
        color: var(--shop-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--shop-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-add-shop {
        background: linear-gradient(140deg, var(--shop-brand), var(--shop-brand-deep));
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

    .btn-add-shop:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--shop-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .table.table-custom {
        margin-bottom: 0;
    }

    .table.table-custom thead th {
        background: #f7fbff;
        border-bottom: 1px solid #dce8f3;
        color: #4b637b;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0.9rem 0.95rem;
        white-space: nowrap;
    }

    .table.table-custom tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .table.table-custom tbody tr:hover {
        background: #fbfdff;
    }

    .shop-name {
        color: #1e293b;
        font-weight: 700;
    }

    .shop-date {
        color: #64748b;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .shop-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.38rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .shop-badge-products {
        background: rgba(15, 118, 110, 0.14);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .shop-badge-sales {
        background: rgba(245, 158, 11, 0.14);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.24);
    }

    .actions-cell {
        white-space: nowrap;
    }

    .btn-group .btn {
        border-radius: 10px !important;
        margin-right: 0.25rem;
        padding: 0.34rem 0.52rem;
        border-width: 1px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .btn-outline-info {
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.35);
    }

    .btn-outline-info:hover {
        background: #0f766e;
        border-color: #0f766e;
        color: #fff;
    }

    .btn-outline-warning {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.45);
    }

    .btn-outline-warning:hover {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
    }

    .btn-outline-danger {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.35);
    }

    .empty-state {
        padding: 2.5rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 2.2rem;
        color: #8aa0b6;
        display: block;
        margin-bottom: 0.65rem;
    }

    .btn-create-first {
        background: linear-gradient(140deg, var(--shop-brand), var(--shop-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.48rem 0.88rem;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .btn-create-first:hover {
        color: #fff;
    }

    @media (max-width: 767.98px) {
        .shop-header {
            align-items: stretch !important;
        }

        .btn-add-shop {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="shop-shell">
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap shop-header">
    <div>
        <span class="shop-kicker"><i class="bi bi-shop-window"></i>{{ __('shop.title') }}</span>
        <h1 class="page-title display-font">{{ __('shop.title') }}</h1>
        <p class="page-subtitle">{{ __('shop.subtitle') }}</p>
    </div>
    <a href="{{ route('shop.create') }}" class="btn-add-shop">
        <i class="bi bi-plus-circle"></i> {{ __('shop.add_new') }}
    </a>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>{{ __('shop.col_name') }}</th>
                    <th>{{ __('shop.col_products') }}</th>
                    <th>{{ __('shop.col_sales') }}</th>
                    <th>{{ __('shop.col_created') }}</th>
                    <th>{{ __('shop.col_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shops as $shop)
                <tr>
                    <td>
                        <strong class="shop-name">{{ $shop->name }}</strong>
                    </td>
                    <td>
                        <span class="shop-badge shop-badge-products">{{ $shop->products_count }} {{ __('shop.products_badge') }}</span>
                    </td>
                    <td>
                        <span class="shop-badge shop-badge-sales">{{ $shop->sales_count }} {{ __('shop.sales_badge') }}</span>
                    </td>
                    <td class="shop-date">
                        {{ $shop->created_at->format('M d, Y') }}
                    </td>
                    <td class="actions-cell">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('shop.show', $shop->id) }}"
                               class="btn btn-outline-info"
                               title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('shop.edit', $shop->id) }}"
                               class="btn btn-outline-warning"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('shop.destroy', $shop->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('{{ __("shop.confirm_delete") }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-outline-danger"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-shop"></i>
                            <p class="mb-2">{{ __('shop.no_shops') }}</p>
                            <a href="{{ route('shop.create') }}" class="btn btn-sm btn-create-first">
                                <i class="bi bi-plus-circle"></i> {{ __('shop.create_first') }}
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
