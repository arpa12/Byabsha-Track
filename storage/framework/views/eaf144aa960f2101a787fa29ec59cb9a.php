<?php $__env->startSection('title', __('dashboard.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --landing-bg: #f4f8fb;
        --landing-ink-900: #0f172a;
        --landing-ink-700: #334155;
        --landing-ink-500: #64748b;
        --landing-brand: #0f766e;
        --landing-brand-deep: #155e75;
        --landing-line: #d8e4ee;
    }

    .dashboard-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--landing-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .dashboard-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        background:
            radial-gradient(900px 500px at 85% -5%, rgba(15, 118, 110, 0.23), transparent 60%),
            radial-gradient(650px 420px at -5% 8%, rgba(245, 158, 11, 0.2), transparent 55%),
            linear-gradient(180deg, #f7fafc 0%, #f1f6f9 60%, #edf3f8 100%);
        pointer-events: none;
        z-index: -1;
    }

    .dashboard-header {
        margin-bottom: 1.4rem;
        background: transparent;
        color: var(--landing-ink-900);
        border-radius: 0;
        padding: 0.35rem 0;
        border: 0;
        box-shadow: none;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::after {
        display: none;
    }

    .dashboard-title {
        font-size: clamp(1.45rem, 3vw, 2.1rem);
        font-weight: 700;
        color: var(--landing-ink-900);
        margin-bottom: 0.35rem;
        position: relative;
        z-index: 1;
        text-wrap: balance;
    }

    .dashboard-subtitle {
        color: var(--landing-ink-700);
        font-size: 0.95rem;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
        max-width: 720px;
    }

    .overall-stats {
        margin-bottom: 2.1rem;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.15rem;
        box-shadow: 0 11px 20px rgba(15, 23, 42, 0.04);
        border: 1px solid var(--landing-line);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 28px rgba(15, 23, 42, 0.1);
        border-color: #97b0c8;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.24rem;
        margin-bottom: 0.85rem;
    }

    .stat-icon.blue {
        background: linear-gradient(145deg, var(--landing-brand), var(--landing-brand-deep));
        color: white;
    }

    .stat-icon.green {
        background: linear-gradient(145deg, #0f766e, #0d5969);
        color: white;
    }

    .stat-icon.yellow {
        background: linear-gradient(145deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .stat-label {
        color: var(--landing-ink-500);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 0.35rem;
    }

    .stat-value {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        font-size: clamp(1.2rem, 2.1vw, 1.6rem);
        font-weight: 700;
        color: var(--landing-ink-900);
        line-height: 1.2;
    }

    .stat-card .text-muted,
    .summary-stat .text-muted {
        margin-top: 0.25rem;
        color: #7f8fa3 !important;
    }

    .btn-calc-guide {
        border-color: #c7d8e6;
        color: #3f556c;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.77rem;
        padding: 0.38rem 0.82rem;
        background: rgba(255, 255, 255, 0.62);
    }

    .btn-calc-guide:hover,
    .btn-calc-guide:focus {
        border-color: #97b0c8;
        color: #1e293b;
        background: #ffffff;
    }

    .calc-guide-panel {
        background: rgba(255, 255, 255, 0.8) !important;
        border: 1px solid var(--landing-line) !important;
        border-radius: 16px !important;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
    }

    .shop-section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--landing-ink-900);
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .shop-section-title i {
        font-size: 1.15rem;
        color: #0f766e;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(15, 118, 110, 0.12);
        border: 1px solid rgba(15, 118, 110, 0.22);
    }

    .shop-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(265px, 1fr));
        gap: 1.1rem;
        margin-bottom: 2rem;
    }

    .shop-summary-card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 11px 20px rgba(15, 23, 42, 0.04);
        border: 1px solid var(--landing-line);
        overflow: hidden;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    .shop-summary-card:hover {
        box-shadow: 0 18px 28px rgba(15, 23, 42, 0.1);
        transform: translateY(-4px);
    }

    .shop-summary-header {
        background: linear-gradient(135deg, #0c2f44 0%, #0f766e 60%, #0d5969 100%);
        color: white;
        padding: 1.05rem 1.2rem;
    }

    .shop-summary-header h3 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .shop-summary-body {
        padding: 1.08rem 1.15rem;
        flex-grow: 1;
    }

    .shop-summary-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.65rem;
    }

    .summary-stat {
        background: #f8fbff;
        border: 1px solid #dce7f2;
        border-radius: 11px;
        padding: 0.7rem;
        text-align: center;
    }

    .summary-stat-label {
        display: block;
        font-size: 0.67rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .summary-stat-value {
        display: block;
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        font-size: 1.02rem;
        font-weight: 700;
        color: var(--landing-ink-900);
    }

    .shop-summary-footer {
        padding: 0 1.15rem 1rem;
    }

    .btn-show-details {
        display: block;
        width: 100%;
        background: linear-gradient(140deg, var(--landing-brand), var(--landing-brand-deep));
        color: white;
        border: none;
        border-radius: 999px;
        padding: 0.62rem 0.95rem;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-align: center;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.24);
    }

    .btn-show-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.3);
    }

    #calcGuide .border {
        border: 1px solid var(--landing-line) !important;
    }

    #calcGuide code {
        background: #edf3f8;
        border-radius: 6px;
        padding: 0.3rem 0.45rem;
        font-size: 0.75rem;
    }

    .modal-content {
        border: 1px solid rgba(255, 255, 255, 0.11);
        border-radius: 20px;
        box-shadow: 0 20px 38px rgba(15, 23, 42, 0.22);
    }

    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(125deg, #f8fbff 0%, #eef6fb 100%);
    }

    @media (max-width: 991px) {
        .dashboard-header {
            padding: 0.2rem 0;
        }
    }

    @media (max-width: 768px) {
        .shop-card-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-title {
            font-size: 1.38rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-shell">
<div class="dashboard-header">
    <h1 class="dashboard-title display-font"><?php echo e(__('dashboard.title')); ?></h1>
    <p class="dashboard-subtitle"><?php echo e(__('dashboard.subtitle')); ?></p>
</div>

<!-- Overall Statistics -->
<div class="overall-stats">
    <div class="row g-3">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.total_shops')); ?></div>
                <div class="stat-value"><?php echo e($overallMetrics['total_shops']); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.total_products')); ?></div>
                <div class="stat-value"><?php echo e($overallMetrics['total_products']); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.sales_today')); ?></div>
                <div class="stat-value"><?php echo e($overallMetrics['total_sales_today']); ?></div>
                <div class="text-muted" style="font-size:0.72rem;"><?php echo e(__('dashboard.sales_count_today')); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.revenue_today')); ?></div>
                <div class="stat-value"><?php echo e(number_format($overallMetrics['total_revenue_today'], 0)); ?></div>
                <div class="text-muted" style="font-size:0.72rem;"><?php echo e(__('dashboard.revenue_formula')); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.profit_today')); ?></div>
                <div class="stat-value"><?php echo e(number_format($overallMetrics['total_profit_today'], 0)); ?></div>
                <div class="text-muted" style="font-size:0.72rem;"><?php echo e(__('dashboard.profit_formula')); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-label"><?php echo e(__('dashboard.low_stock')); ?></div>
                <div class="stat-value"><?php echo e($overallMetrics['low_stock_count']); ?></div>
                <div class="text-muted" style="font-size:0.72rem;"><?php echo e(__('dashboard.low_stock_desc')); ?></div>
            </div>
        </div>
    </div>
</div>


<div class="mb-4">
    <button class="btn btn-sm btn-outline-secondary btn-calc-guide"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#calcGuide"
            aria-expanded="false">
        <i class="bi bi-lightbulb me-1"></i> <?php echo e(__('dashboard.how_calculated')); ?>

    </button>
    <div class="collapse mt-2" id="calcGuide">
        <div class="p-3 rounded border calc-guide-panel" style="font-size:0.85rem;">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-semibold mb-1 text-primary"><i class="bi bi-cash-stack me-1"></i><?php echo e(__('dashboard.revenue_title')); ?></div>
                    <code class="d-block text-muted"><?php echo e(__('dashboard.revenue_formula_full')); ?></code>
                    <div class="text-muted mt-1"><?php echo e(__('dashboard.revenue_desc')); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold mb-1 text-success"><i class="bi bi-graph-up me-1"></i><?php echo e(__('dashboard.profit_title')); ?></div>
                    <code class="d-block text-muted"><?php echo e(__('dashboard.profit_formula_full')); ?></code>
                    <div class="text-muted mt-1"><?php echo e(__('dashboard.profit_desc')); ?></div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold mb-1" style="color:#7c3aed;"><i class="bi bi-bank me-1"></i><?php echo e(__('dashboard.capital_title')); ?></div>
                    <code class="d-block text-muted"><?php echo e(__('dashboard.capital_formula_full')); ?></code>
                    <div class="text-muted mt-1"><?php echo e(__('dashboard.capital_desc')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shop-wise Metrics -->
<div class="shop-metrics-section">
    <h2 class="shop-section-title">
        <i class="bi bi-shop"></i>
        <?php echo e(__('dashboard.shop_performance')); ?>

    </h2>

    <div class="shop-card-grid">
    <?php $__empty_1 = true; $__currentLoopData = $shopMetrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="shop-summary-card">
            <div class="shop-summary-header">
                <h3>
                    <i class="bi bi-shop-window"></i>
                    <?php echo e($metric['shop']->name); ?>

                </h3>
            </div>
            <div class="shop-summary-body">
                <div class="shop-summary-stats">
                    <div class="summary-stat">
                        <span class="summary-stat-label"><?php echo e(__('dashboard.todays_sales')); ?></span>
                        <span class="summary-stat-value"><?php echo e(number_format($metric['today_sales'], 2)); ?></span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;"><?php echo e(__('dashboard.total_amount_val')); ?></span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label"><?php echo e(__('dashboard.todays_profit')); ?></span>
                        <span class="summary-stat-value"><?php echo e(number_format($metric['today_profit'], 2)); ?></span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;"><?php echo e(__('dashboard.profit_val')); ?></span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label"><?php echo e(__('dashboard.monthly_profit')); ?></span>
                        <span class="summary-stat-value"><?php echo e(number_format($metric['monthly_profit'], 2)); ?></span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;"><?php echo e(__('dashboard.monthly_val')); ?></span>
                    </div>
                    <div class="summary-stat">
                        <span class="summary-stat-label"><?php echo e(__('dashboard.capital')); ?></span>
                        <span class="summary-stat-value"><?php echo e(number_format($metric['total_capital'], 2)); ?></span>
                        <span class="d-block mt-1" style="font-size:0.68rem;color:#94a3b8;"><?php echo e(__('dashboard.capital_val')); ?></span>
                    </div>
                </div>
            </div>
            <div class="shop-summary-footer">
                <button class="btn-show-details" data-shop-id="<?php echo e($metric['shop']->id); ?>">
                    <i class="bi bi-info-circle me-1"></i> <?php echo e(__('dashboard.show_details')); ?>

                </button>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="shop-summary-card">
            <div class="empty-state" style="padding: 3rem 2rem;">
                <i class="bi bi-shop" style="font-size:3rem; color:#94a3b8; display:block; margin-bottom:1rem;"></i>
                <h5 style="color:#64748b;"><?php echo e(__('dashboard.no_shops')); ?></h5>
                <p style="color:#94a3b8;"><?php echo e(__('dashboard.create_first_shop')); ?></p>
            </div>
        </div>
    <?php endif; ?>
    </div>

<!-- Shop Details Modal -->
<div class="modal fade" id="shopDetailsModal" tabindex="-1" aria-labelledby="shopDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shopDetailsModalLabel"><?php echo e(__('dashboard.shop_details')); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="shopDetailsModalBody">
        <!-- Shop details will be loaded here -->
      </div>
    </div>
  </div>
</div>

</div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Arpa\self_project\byabshaTrack\Modules/Dashboard\resources/views/index.blade.php ENDPATH**/ ?>