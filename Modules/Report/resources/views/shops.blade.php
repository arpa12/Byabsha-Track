@extends('layouts.app')

@section('title', __('report.shops_report'))

@section('content')
<div class="report-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-5 mb-8 report-header">
        <div>
            <span class="report-kicker"><i class="bi bi-shop-window"></i> {{ __('app.analytics') }}</span>
            <h1 class="dashboard-title display-font text-3xl font-black text-slate-900 leading-none mb-1">{{ __('report.shops_report') }}</h1>
            <p class="dashboard-subtitle text-slate-500 text-sm mt-1.5">{{ __('report.shops_report_subtitle') }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('report.export.shops-pdf', request()->query()) }}" class="btn-brand-custom" style="background: linear-gradient(140deg, #ef4444, #b91c1c); box-shadow: 0 14px 28px rgba(239, 68, 68, 0.28);">
                <i class="bi bi-file-earmark-pdf"></i> {{ __('report.download_pdf') }}
            </a>
            <button onclick="window.print()" class="btn-secondary-custom">
                <i class="bi bi-printer"></i> {{ __('report.print') }}
            </button>
            <a href="{{ route('report.index') }}" class="btn-secondary-custom">
                <i class="bi bi-arrow-left"></i> {{ __('report.back_to_reports') }}
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-funnel"></i>
                {{ __('report.filters') }}
            </h5>
        </div>
        <div class="p-4">
            <form action="{{ route('report.shops') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider">{{ __('report.start_date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $filters['start_date'] }}" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider">{{ __('report.end_date') }}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $filters['end_date'] }}" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="background-color: #0f766e; border-color: #0f766e; border-radius: 10px; height: 38px;">
                            <i class="bi bi-search"></i> {{ __('report.apply_filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Shops Comparison Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-bar-chart"></i>
                {{ __('report.shop_comparison') }}
            </h5>
        </div>
        <div class="table-responsive">
        @if($shopData->count() > 0)
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('report.shop_name') }}</th>
                        <th class="text-center">{{ __('report.total_products') }}</th>
                        <th class="text-end">{{ __('report.stock_value_capital') }}</th>
                        <th class="text-center">{{ __('report.total_sales_count') }}</th>
                        <th class="text-end">{{ __('report.total_revenue') }}</th>
                        <th class="text-end">{{ __('report.total_profit') }}</th>
                        <th class="text-end">{{ __('report.profit_margin') }}</th>
                        <th class="text-center">{{ __('report.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shopData as $shop)
                    <tr>
                        <td><span class="badge bg-light text-dark border"><i class="bi bi-shop"></i> {{ $shop->name }}</span></td>
                        <td class="text-center fw-bold">{{ $shop->total_products }}</td>
                        <td class="text-end font-monospace">{{ number_format($shop->stock_value, 2) }}</td>
                        <td class="text-center font-monospace">{{ $shop->total_sales }}</td>
                        <td class="text-end font-monospace fw-bold">{{ number_format($shop->total_revenue, 2) }}</td>
                        <td class="text-end text-success font-monospace fw-bold">{{ number_format($shop->total_profit, 2) }}</td>
                        <td class="text-end">
                            <span class="badge rounded-pill px-2.5 {{ $shop->profit_margin >= 20 ? 'bg-success-subtle text-success border border-success-subtle' : ($shop->profit_margin >= 10 ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle') }}">
                                {{ number_format($shop->profit_margin, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $shopDataSerialized = [
                                    'id' => $shop->id,
                                    'name' => $shop->name,
                                    'total_products' => $shop->total_products,
                                    'stock_value' => (float)$shop->stock_value,
                                    'total_sales' => $shop->total_sales,
                                    'total_revenue' => (float)$shop->total_revenue,
                                    'total_profit' => (float)$shop->total_profit,
                                    'profit_margin' => (float)$shop->profit_margin,
                                ];
                            @endphp
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#shopDetailsModal"
                                data-shop='@json($shopDataSerialized, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG)'
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td>{{ __('report.grand_total') }}</td>
                        <td class="text-center">{{ $shopData->sum('total_products') }}</td>
                        <td class="text-end font-monospace">{{ number_format($shopData->sum('stock_value'), 2) }}</td>
                        <td class="text-center font-monospace">{{ $shopData->sum('total_sales') }}</td>
                        <td class="text-end font-monospace">{{ number_format($shopData->sum('total_revenue'), 2) }}</td>
                        <td class="text-end text-success font-monospace">{{ number_format($shopData->sum('total_profit'), 2) }}</td>
                        <td class="text-end">
                            @php
                                $totalRev = $shopData->sum('total_revenue');
                                $totalProf = $shopData->sum('total_profit');
                                $overallMargin = $totalRev > 0 ? ($totalProf / $totalRev) * 100 : 0;
                            @endphp
                            <span class="badge rounded-pill px-2.5 {{ $overallMargin >= 20 ? 'bg-success-subtle text-success border border-success-subtle' : ($overallMargin >= 10 ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle') }}">
                                {{ number_format($overallMargin, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">-</td>
                    </tr>
                </tfoot>
            </table>
        @else
        <div class="p-5 text-center text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
            <p class="mb-0">{{ __('report.no_shops_found') }}</p>
        </div>
        @endif
        </div>
    </div>
</div>

<!-- Shop Details Modal -->
<div class="modal fade" id="shopDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content report-modal">
            <div class="modal-header">
                <div>
                    <p class="modal-kicker">Shop comparison details</p>
                    <h5 class="modal-title mb-0" id="shopDetailsModalTitle">Shop Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 bg-light rounded-3 mb-4">
                    <div class="card-body text-center py-4">
                        <div class="brand-chip mx-auto mb-3" style="width: 50px; height: 50px; border-radius: 14px; font-size: 1.5rem; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(145deg, var(--report-brand), var(--report-brand-deep)); color: #fff;">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h4 class="fw-black display-font text-slate-900 mb-1" id="modalShopName">-</h4>
                        <p class="text-xs text-muted mb-0">Detailed performance overview</p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded bg-white">
                            <span class="text-xs text-muted d-block mb-1">Products</span>
                            <strong class="fs-5 text-dark" id="modalShopProducts">0</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded bg-white">
                            <span class="text-xs text-muted d-block mb-1">Stock Value</span>
                            <strong class="fs-5 text-dark" id="modalShopStockValue">0.00</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded bg-white">
                            <span class="text-xs text-muted d-block mb-1">Sales Count</span>
                            <strong class="fs-5 text-dark" id="modalShopSales">0</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded bg-white">
                            <span class="text-xs text-muted d-block mb-1">Revenue</span>
                            <strong class="fs-5 text-primary" id="modalShopRevenue">0.00</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded bg-white">
                            <span class="text-xs text-muted d-block mb-1">Total Profit</span>
                            <strong class="fs-5 text-success" id="modalShopProfit">0.00</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded bg-white">
                            <span class="text-xs text-muted d-block mb-1">Profit Margin</span>
                            <strong class="fs-5 text-warning" id="modalShopMargin">0.0%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    @vite(['resources/css/app.css'])
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --report-ink-900: #0f172a;
        --report-ink-700: #334155;
        --report-ink-500: #64748b;
        --report-brand: var(--brand, #0f766e);
        --report-brand-deep: var(--brand-deep, #155e75);
        --report-line: #d8e4ee;
    }

    .report-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--report-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .report-header {
        gap: 0.9rem;
    }

    .report-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: color-mix(in srgb, var(--report-brand) 12%, transparent);
        color: var(--report-brand);
        border: 1px solid color-mix(in srgb, var(--report-brand) 22%, transparent);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px color-mix(in srgb, var(--report-brand) 13%, transparent);
    }

    .page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--report-ink-900);
        margin-bottom: 0.45rem;
        font-weight: 800;
    }

    .page-subtitle {
        color: var(--report-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--report-line);
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .content-card-header {
        border-bottom: 1px solid var(--report-line);
        background: #f8fafc;
        padding: 0.85rem 1.2rem;
    }

    .content-card-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--report-ink-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .content-card-title i {
        color: var(--report-brand);
    }

    /* button design matching plans */
    .btn-brand-custom {
        background: linear-gradient(140deg, var(--report-brand), var(--report-brand-deep));
        color: white;
        border: none;
        border-radius: 999px;
        padding: 0.52rem 1.1rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 10px 20px color-mix(in srgb, var(--report-brand) 22%, transparent);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-brand-custom:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 14px 24px color-mix(in srgb, var(--report-brand) 30%, transparent);
    }

    .btn-secondary-custom {
        border-radius: 999px;
        padding: 0.52rem 1.1rem;
        font-size: 0.84rem;
        font-weight: 700;
        border: 1px solid #9eb8cb;
        color: #1f3f58;
        background: #f7fbff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-secondary-custom:hover {
        color: #0f172a;
        border-color: #6f93b0;
        background: #ffffff;
    }

    /* table styling */
    .table thead th {
        background: #f8fafc !important;
        border-bottom: 1px solid var(--report-line);
        color: var(--report-ink-700);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.8rem 1rem;
        white-space: nowrap;
    }

    .table tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 0.65rem 1rem !important;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Report Modal details style */
    .report-modal {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
    }

    .report-modal .modal-header {
        background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
        border-bottom: 1px solid #dbe7f3;
    }

    .report-modal .modal-header .modal-kicker {
        margin-bottom: 0;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--report-brand);
        font-weight: 700;
    }

    @media print {
        .top-header,
        .sidebar,
        .sidebar-toggle,
        .content-card.mb-4:has(.bi-funnel),
        .alert,
        .btn,
        a.btn {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        body {
            background: white !important;
            font-size: 11px;
        }
        .content-card {
            border: none !important;
            box-shadow: none !important;
        }
        .table { font-size: 10px; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('shopDetailsModal');
        if (!modalEl) return;

        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const shop = JSON.parse(button.getAttribute('data-shop') || '{}');
            
            document.getElementById('modalShopName').textContent = shop.name || '-';
            document.getElementById('modalShopProducts').textContent = shop.total_products || '0';
            document.getElementById('modalShopSales').textContent = shop.total_sales || '0';
            
            const currencySymbol = @json(currency_symbol());
            document.getElementById('modalShopStockValue').textContent = currencySymbol + Number(shop.stock_value || 0).toFixed(2);
            document.getElementById('modalShopRevenue').textContent = currencySymbol + Number(shop.total_revenue || 0).toFixed(2);
            document.getElementById('modalShopProfit').textContent = currencySymbol + Number(shop.total_profit || 0).toFixed(2);
            document.getElementById('modalShopMargin').textContent = Number(shop.profit_margin || 0).toFixed(1) + '%';
        });
    });
</script>
@endpush
@endsection