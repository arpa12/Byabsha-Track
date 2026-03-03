@extends('layouts.app')

@section('title', __('dashboard.title'))

@push('styles')
<style>
    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .dashboard-subtitle {
        color: #64748b;
        font-size: 1rem;
    }

    .overall-stats {
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
        color: white;
    }

    .stat-icon.yellow {
        background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
        color: white;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
    }

    .shop-section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .shop-section-title i {
        font-size: 1.75rem;
        color: #667eea;
    }

    .shop-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .shop-summary-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
        display: flex;
        flex-direction: column;
    }

    .shop-summary-card:hover {
        box-shadow: 0 8px 24px rgba(102,126,234,0.15);
        transform: translateY(-3px);
    }

    .shop-summary-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .shop-summary-header h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .shop-summary-body {
        padding: 1.25rem 1.5rem;
        flex-grow: 1;
    }

    .shop-summary-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .summary-stat {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        text-align: center;
    }

    .summary-stat-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.3rem;
    }

    .summary-stat-value {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .shop-summary-footer {
        padding: 0 1.5rem 1.25rem;
    }

    .btn-show-details {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s;
        text-align: center;
    }

    .btn-show-details:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">{{ __('dashboard.title') }}</h1>
    <p class="dashboard-subtitle">{{ __('dashboard.subtitle') }}</p>
</div>

<!-- Overall Statistics -->
<div class="overall-stats">
    <div class="row g-3">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="stat-label">{{ __('dashboard.total_shops') }}</div>
                <div class="stat-value">{{ $overallMetrics['total_shops'] }}</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-label">{{ __('dashboard.total_products') }}</div>
                <div class="stat-value">{{ $overallMetrics['total_products'] }}</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stat-label">{{ __('dashboard.sales_today') }}</div>
                <div class="stat-value">{{ $overallMetrics['total_sales_today'] }}</div>
                <div class="text-muted" style="font-size:0.72rem;">{{ __('dashboard.sales_count_today') }}</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-label">{{ __('dashboard.revenue_today') }}</div>
                <div class="stat-value">{{ number_format($overallMetrics['total_revenue_today'], 0) }}</div>
                <div class="text-muted" style="font-size:0.72rem;">{{ __('dashboard.revenue_formula') }}</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label">{{ __('dashboard.profit_today') }}</div>
                <div class="stat-value">{{ number_format($overallMetrics['total_profit_today'], 0) }}</div>
                <div class="text-muted" style="font-size:0.72rem;">{{ __('dashboard.profit_formula') }}</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-label">{{ __('dashboard.low_stock') }}</div>
                <div class="stat-value">{{ $overallMetrics['low_stock_count'] }}</div>
                <div class="text-muted" style="font-size:0.72rem;">{{ __('dashboard.low_stock_desc') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Calculation Guide (collapsible) --}}
<div class="mb-4">
    <button class="btn btn-sm btn-outline-secondary"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#calcGuide"
            aria-expanded="false">
        <i class="bi bi-lightbulb me-1"></i> {{ __('dashboard.how_calculated') }}
    </button>
    <div class="collapse mt-2" id="calcGuide">
        <div class="p-3 rounded border" style="background:#f8fafc; font-size:0.85rem;">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-semibold mb-1 text-primary"><i class="bi bi-cash-stack me-1"></i>{{ __('dashboard.revenue_title') }}</div>
                    <code class="d-block text-muted">{{ __('dashboard.revenue_formula_full') }}</code>
                    <div class="text-muted mt-1">{{ __('dashboard.revenue_desc') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold mb-1 text-success"><i class="bi bi-graph-up me-1"></i>{{ __('dashboard.profit_title') }}</div>
                    <code class="d-block text-muted">{{ __('dashboard.profit_formula_full') }}</code>
                    <div class="text-muted mt-1">{{ __('dashboard.profit_desc') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold mb-1" style="color:#7c3aed;"><i class="bi bi-bank me-1"></i>{{ __('dashboard.capital_title') }}</div>
                    <code class="d-block text-muted">{{ __('dashboard.capital_formula_full') }}</code>
                    <div class="text-muted mt-1">{{ __('dashboard.capital_desc') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shop-wise Metrics -->
<div class="shop-metrics-section">
    <h2 class="shop-section-title">
        <i class="bi bi-shop"></i>
        {{ __('dashboard.shop_performance') }}
    </h2>

    <div class="shop-card-grid">
    @forelse($shopMetrics as $metric)
        <div class="shop-summary-card">
            <div class="shop-summary-header">
                <h3>
                    <i class="bi bi-shop-window"></i>
                    {{ $metric['shop']->name }}
                </h3>
            </div>
            <div class="shop-summary-body">
                <div class="shop-summary-stats">
                    <div class="summary-stat">
                        <span class="summary-stat-label">{{ __('dashboard.todays_sales') }}</span>
                        <span class="summary-stat-value">{{ number_format($metric['today_sales'], 2) }}</span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;">{{ __('dashboard.total_amount_val') }}</span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label">{{ __('dashboard.todays_profit') }}</span>
                        <span class="summary-stat-value">{{ number_format($metric['today_profit'], 2) }}</span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;">{{ __('dashboard.profit_val') }}</span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label">{{ __('dashboard.monthly_profit') }}</span>
                        <span class="summary-stat-value">{{ number_format($metric['monthly_profit'], 2) }}</span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;">{{ __('dashboard.monthly_val') }}</span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label">{{ __('dashboard.capital') }}</span>
                        <span class="summary-stat-value">{{ number_format($metric['total_capital'], 2) }}</span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;">{{ __('dashboard.capital_val') }}</span>
                    </div>
                </div>
            </div>
            <div class="shop-summary-footer">
                <button class="btn-show-details" data-shop-id="{{ $metric['shop']->id }}">
                    <i class="bi bi-info-circle me-1"></i> {{ __('dashboard.show_details') }}
                </button>
            </div>
        </div>
    @empty
        <div class="shop-summary-card">
            <div class="empty-state" style="padding: 3rem 2rem;">
                <i class="bi bi-shop" style="font-size:3rem; color:#94a3b8; display:block; margin-bottom:1rem;"></i>
                <h5 style="color:#64748b;">{{ __('dashboard.no_shops') }}</h5>
                <p style="color:#94a3b8;">{{ __('dashboard.create_first_shop') }}</p>
            </div>
        </div>
    @endforelse
    </div>

<!-- Shop Details Modal -->
<div class="modal fade" id="shopDetailsModal" tabindex="-1" aria-labelledby="shopDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shopDetailsModalLabel">{{ __('dashboard.shop_details') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="shopDetailsModalBody">
        <!-- Shop details will be loaded here -->
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-show-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var shopId = this.getAttribute('data-shop-id');
            var modalBody = document.getElementById('shopDetailsModalBody');
            modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            var modal = new bootstrap.Modal(document.getElementById('shopDetailsModal'));
            modal.show();
            fetch('/dashboard/shop-details/' + shopId)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(function() {
                    modalBody.innerHTML = '<p class="text-danger">Failed to load shop details.</p>';
                });
        });
    });
});
</script>
@endpush
</div>
@endsection
