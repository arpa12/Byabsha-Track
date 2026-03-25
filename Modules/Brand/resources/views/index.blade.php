@extends('layouts.app')

@section('title', __('brand::brand.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --brand-ink-900: #0f172a;
        --brand-ink-700: #334155;
        --brand-ink-500: #64748b;
        --brand-accent: #0f766e;
        --brand-accent-deep: #155e75;
        --brand-line: #d8e4ee;
    }

    .brand-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--brand-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .brand-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--brand-accent);
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
        color: var(--brand-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--brand-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-add-brand {
        background: linear-gradient(140deg, var(--brand-accent), var(--brand-accent-deep));
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
        text-decoration: none;
    }

    .btn-add-brand:hover {
        color: #fff;
        filter: brightness(1.03);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--brand-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .table.table-brand {
        margin-bottom: 0;
    }

    .table.table-brand thead th {
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

    .table.table-brand tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .brand-name {
        color: #1e293b;
        font-weight: 700;
    }

    .product-count-pill {
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

    .btn-empty-brand {
        background: linear-gradient(140deg, var(--brand-accent), var(--brand-accent-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.5rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-empty-brand:hover {
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="brand-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <span class="brand-kicker"><i class="bi bi-bookmark-star"></i>{{ __('brand::brand.title') }}</span>
        <h1 class="page-title display-font">{{ __('brand::brand.title') }}</h1>
        <p class="page-subtitle">{{ __('brand::brand.subtitle') }}</p>
    </div>
    <a href="{{ route('brand.create') }}" class="btn-add-brand">
        <i class="bi bi-plus-circle"></i> {{ __('brand::brand.add_new') }}
    </a>
</div>

<div class="content-card">
    <div class="card-body p-0">
        @if($brands->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-brand">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('brand::brand.name') }}</th>
                            <th class="text-center">{{ __('brand::brand.product_count') }}</th>
                            <th class="text-end">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $brand)
                            <tr>
                                <td><span class="brand-name">{{ $brand->name }}</span></td>
                                <td class="text-center"><span class="product-count-pill">{{ $brand->products_count }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('brand.show', $brand->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('brand.edit', $brand->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('brand.destroy', $brand->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('brand::brand.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $brands->links() }}</div>
        @else
            <div class="empty-state">
                <i class="bi bi-bookmark-star"></i>
                <h5 class="mb-2">{{ __('brand::brand.no_brands') }}</h5>
                <p class="text-muted mb-3">{{ __('brand::brand.no_brands_sub') }}</p>
                <a href="{{ route('brand.create') }}" class="btn-empty-brand">{{ __('brand::brand.add_new') }}</a>
            </div>
        @endif
    </div>
</div>
</div>
@endsection
