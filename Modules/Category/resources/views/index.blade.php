@extends('layouts.app')

@section('title', __('category::category.title'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --category-ink-900: #0f172a;
        --category-ink-700: #334155;
        --category-ink-500: #64748b;
        --category-brand: #0f766e;
        --category-brand-deep: #155e75;
        --category-line: #d8e4ee;
    }

    .category-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--category-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .category-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--category-brand);
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
        color: var(--category-ink-900);
        margin-bottom: 0.45rem;
    }

    .page-subtitle {
        color: var(--category-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .btn-add-category {
        background: linear-gradient(140deg, var(--category-brand), var(--category-brand-deep));
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

    .btn-add-category:hover {
        color: #fff;
        filter: brightness(1.03);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--category-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .table.table-category {
        margin-bottom: 0;
    }

    .table.table-category thead th {
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

    .table.table-category tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .category-name {
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

    .btn-empty-category {
        background: linear-gradient(140deg, var(--category-brand), var(--category-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.5rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-empty-category:hover {
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="category-shell">
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <span class="category-kicker"><i class="bi bi-tags"></i>{{ __('category::category.title') }}</span>
        <h1 class="page-title display-font">{{ __('category::category.title') }}</h1>
        <p class="page-subtitle">{{ __('category::category.subtitle') }}</p>
    </div>
    <a href="{{ route('category.create') }}" class="btn-add-category">
        <i class="bi bi-plus-circle"></i> {{ __('category::category.add_new') }}
    </a>
</div>

<div class="content-card">
    <div class="card-body p-0">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-category">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('category::category.name') }}</th>
                            <th class="text-center">{{ __('category::category.product_count') }}</th>
                            <th class="text-end">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td><span class="category-name">{{ $category->name }}</span></td>
                                <td class="text-center"><span class="product-count-pill">{{ $category->products_count }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('category.show', $category->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('category.edit', $category->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('category::category.confirm_delete') }}')">
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
            <div class="p-3">{{ $categories->links() }}</div>
        @else
            <div class="empty-state">
                <i class="bi bi-tags"></i>
                <h5 class="mb-2">{{ __('category::category.no_categories') }}</h5>
                <p class="text-muted mb-3">{{ __('category::category.no_categories_sub') }}</p>
                <a href="{{ route('category.create') }}" class="btn-empty-category">{{ __('category::category.add_new') }}</a>
            </div>
        @endif
    </div>
</div>
</div>
@endsection
