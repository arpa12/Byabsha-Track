@extends('layouts.app')

@section('title', __('capital.title'))

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    .capital-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        border: none;
        margin-bottom: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .capital-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .capital-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .shop-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .shop-name i {
        font-size: 1.75rem;
        color: #667eea;
    }

    .capital-amount {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .capital-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .capital-info {
        flex-grow: 1;
    }

    .capital-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .last-updated {
        color: #94a3b8;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .btn-update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: transform 0.2s, box-shadow 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-update-all {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-update-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-state h5 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
    }

    .alert {
        border-radius: 12px;
        border: none;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ __('capital.title') }}</h1>
        <p class="page-subtitle">{{ __('capital.subtitle') }}</p>
    </div>
    <form action="{{ route('capital.update-all') }}" method="POST">
        @csrf
        <button type="submit" class="btn-update-all">
            <i class="bi bi-arrow-clockwise"></i>
            {{ __('capital.update_all') }}
        </button>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Formula Explanation Banner --}}
<div class="alert alert-light border mb-4 p-3" style="font-size:0.85rem;">
    <div class="fw-semibold mb-1"><i class="bi bi-calculator me-1 text-primary"></i>{{ __('capital.how_calculated') }}</div>
    <div class="text-muted">
        {{ __('capital.formula_desc') }}
    </div>
    <div class="text-muted mt-1">
        {{ __('capital.click_breakdown') }}
    </div>
</div>

@forelse($capitals as $capital)
    @php $products = $capital->shop->products; @endphp
    <div class="capital-card">
        <div class="capital-header">
            <h3 class="shop-name">
                <i class="bi bi-shop-window"></i>
                {{ $capital->shop->name }}
            </h3>
            <span class="badge bg-secondary">{{ $products->count() }} product(s)</span>
        </div>
        <div class="capital-body">
            <div class="capital-info">
                <div class="capital-label">{{ __('capital.current_capital') }}</div>
                <div class="capital-amount">{{ number_format($capital->total_capital, 2) }}</div>
                <div class="d-flex align-items-center gap-2 mt-1" style="font-size:0.82rem; color:#94a3b8;">
                    <span><i class="bi bi-clock"></i> {{ __('capital.last_updated') }}: {{ $capital->updated_at->diffForHumans() }}</span>
                    <span class="text-muted">&nbsp;|&nbsp; Formula: &Sigma;(Stock &times; Purchase Price)</span>
                </div>
            </div>
            <form action="{{ route('capital.update-shop', $capital->shop_id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-update">
                    <i class="bi bi-arrow-clockwise"></i>
                    {{ __('capital.update_capital') }}
                </button>
            </form>
        </div>

        {{-- Product-level Breakdown --}}
        <div class="mt-3">
            <button class="btn btn-sm btn-outline-secondary"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#breakdown-{{ $capital->id }}"
                    aria-expanded="false">
                <i class="bi bi-table me-1"></i>{{ __('capital.show_breakdown') }}
            </button>
            <div class="collapse mt-2" id="breakdown-{{ $capital->id }}">
                @if($products->count() > 0)
                    <div class="table-responsive" style="font-size:0.85rem;">
                        <table class="table table-sm table-bordered mb-1">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('capital.col_product') }}</th>
                                    <th class="text-center">{{ __('capital.col_stock') }}</th>
                                    <th class="text-end">{{ __('capital.col_purchase_price') }}</th>
                                    <th class="text-center text-muted">{{ __('capital.col_formula') }}</th>
                                    <th class="text-end">{{ __('capital.col_stock_value') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $p)
                                    @php $stockValue = $p->stock_quantity * $p->purchase_price; @endphp
                                    <tr>
                                        <td>{{ $p->name }}</td>
                                        <td class="text-center">{{ $p->stock_quantity }}</td>
                                        <td class="text-end">{{ number_format($p->purchase_price, 2) }}</td>
                                        <td class="text-center text-muted" style="font-size:0.78rem;">
                                            {{ $p->stock_quantity }} &times; {{ number_format($p->purchase_price, 2) }}
                                        </td>
                                        <td class="text-end fw-semibold">{{ number_format($stockValue, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end">{{ __('capital.total_capital_row') }} &nbsp;<span class="text-muted fw-normal" style="font-size:0.78rem;">{{ __('capital.sum_note') }}</span></td>
                                    <td class="text-end">{{ number_format($capital->total_capital, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted small">{{ __('capital.no_products') }}</p>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="empty-state">
        <i class="bi bi-piggy-bank"></i>
        <h5>{{ __('capital.no_capitals') }}</h5>
        <p>{{ __('capital.no_capitals_sub') }}</p>
    </div>
@endforelse
@endsection
