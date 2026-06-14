<?php $__env->startSection('title', __('report.products_report')); ?>

<?php $__env->startSection('content'); ?>
<div class="report-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-5 mb-8 report-header">
        <div>
            <span class="report-kicker"><i class="bi bi-box-seam"></i> <?php echo e(__('app.analytics')); ?></span>
            <h1 class="dashboard-title display-font text-3xl font-black text-slate-900 leading-none mb-1"><?php echo e(__('report.products_report')); ?></h1>
            <p class="dashboard-subtitle text-slate-500 text-sm mt-1.5"><?php echo e(__('report.products_report_subtitle')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('report.export.products-pdf', request()->query())); ?>" class="btn-brand-custom" style="background: linear-gradient(140deg, #ef4444, #b91c1c); box-shadow: 0 14px 28px rgba(239, 68, 68, 0.28);">
                <i class="bi bi-file-earmark-pdf"></i> <?php echo e(__('report.download_pdf')); ?>

            </a>
            <button onclick="window.print()" class="btn-secondary-custom">
                <i class="bi bi-printer"></i> <?php echo e(__('report.print')); ?>

            </button>
            <a href="<?php echo e(route('report.index')); ?>" class="btn-secondary-custom">
                <i class="bi bi-arrow-left"></i> <?php echo e(__('report.back_to_reports')); ?>

            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-funnel"></i>
                <?php echo e(__('report.filters')); ?>

            </h5>
        </div>
        <div class="p-4">
            <form action="<?php echo e(route('report.products')); ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="shop_id" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.shop')); ?></label>
                        <select class="form-select" id="shop_id" name="shop_id" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                            <option value=""><?php echo e(__('report.all_shops')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e($filters['shop_id'] == $shop->id ? 'selected' : ''); ?>>
                                    <?php echo e($shop->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="background-color: #0f766e; border-color: #0f766e; border-radius: 10px; height: 38px;">
                            <i class="bi bi-search"></i> <?php echo e(__('report.apply_filters')); ?>

                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4 g-3">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #64748b, #475569);">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.total_products')); ?></div>
                <div class="stat-value"><?php echo e($stockSummary['total_products']); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #0f766e, #0d5969);">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.stock_value')); ?></div>
                <div class="stat-value text-teal" style="color: #0f766e !important;"><?php echo e(number_format($stockSummary['total_stock_value'], 2)); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #0ea5e9, #0284c7);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.potential_revenue')); ?></div>
                <div class="stat-value"><?php echo e(number_format($stockSummary['total_potential_revenue'], 2)); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #10b981, #047857);">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.potential_profit')); ?></div>
                <div class="stat-value text-success"><?php echo e(number_format($stockSummary['total_potential_profit'], 2)); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #f59e0b, #d97706);">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.low_stock_label')); ?></div>
                <div class="stat-value text-warning"><?php echo e($stockSummary['low_stock_count']); ?></div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #ef4444, #b91c1c);">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.out_of_stock')); ?></div>
                <div class="stat-value text-danger"><?php echo e($stockSummary['out_of_stock_count']); ?></div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-table"></i>
                <?php echo e(__('report.product_details')); ?>

            </h5>
        </div>
        <div class="table-responsive">
        <?php if($products->count() > 0): ?>
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?php echo e(__('report.product_name')); ?></th>
                        <th><?php echo e(__('report.category')); ?></th>
                        <th><?php echo e(__('report.brand')); ?></th>
                        <th><?php echo e(__('report.shop')); ?></th>
                        <th class="text-end"><?php echo e(__('report.purchase_price')); ?></th>
                        <th class="text-end"><?php echo e(__('report.sale_price')); ?></th>
                        <th class="text-center"><?php echo e(__('report.stock_qty')); ?></th>
                        <th class="text-end"><?php echo e(__('report.inventory_value')); ?></th>
                        <th class="text-center"><?php echo e(__('report.units_sold')); ?></th>
                        <th class="text-end"><?php echo e(__('report.total_revenue_col')); ?></th>
                        <th class="text-center"><?php echo e(__('report.action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($product->name); ?></strong></td>
                        <td class="text-muted"><?php echo e($product->category ?? '-'); ?></td>
                        <td class="text-muted"><?php echo e($product->brand ?? '-'); ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo e($product->shop->name); ?></span></td>
                        <td class="text-end fw-semibold"><?php echo e(number_format($product->purchase_price, 2)); ?></td>
                        <td class="text-end fw-semibold"><?php echo e(number_format($product->sale_price, 2)); ?></td>
                        <td class="text-center fw-bold">
                            <?php if($product->stock_quantity == 0): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5"><?php echo e(__('report.out_of_stock_badge')); ?></span>
                            <?php elseif($product->stock_quantity <= (int) \Modules\Settings\Models\Setting::get('low_stock_alert', 5)): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5"><?php echo e($product->stock_quantity); ?></span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5"><?php echo e($product->stock_quantity); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end font-monospace fw-bold"><?php echo e(number_format($product->stock_quantity * $product->purchase_price, 2)); ?></td>
                        <td class="text-center font-monospace"><?php echo e($product->total_units_sold); ?></td>
                        <td class="text-end text-success font-monospace fw-bold"><?php echo e(number_format($product->total_revenue, 2)); ?></td>
                        <td class="text-center">
                            <?php
                                $fsDuration = $product->has_free_service
                                    ? ($product->free_service_duration_value . ' ' . $product->free_service_duration_unit)
                                    : '-';
                                $productData = [
                                    'id' => $product->id,
                                    'name' => $product->name,
                                    'model_name' => $product->model_name ?: '-',
                                    'category' => $product->category ?: '-',
                                    'brand' => $product->brand ?: '-',
                                    'shop_name' => $product->shop->name ?? '-',
                                    'purchase_price' => (float)$product->purchase_price,
                                    'sale_price' => (float)$product->sale_price,
                                    'stock_quantity' => $product->stock_quantity,
                                    'inventory_value' => (float)($product->stock_quantity * $product->purchase_price),
                                    'total_units_sold' => (int)$product->total_units_sold,
                                    'total_revenue' => (float)$product->total_revenue,
                                    'has_free_service' => (bool)$product->has_free_service,
                                    'free_service_duration' => $fsDuration,
                                    'free_service_terms' => $product->free_service_terms ?: '-',
                                ];
                            ?>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#productDetailsModal"
                                data-product='<?php echo json_encode($productData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG, 512) ?>'
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
            <p class="mb-0"><?php echo e(__('report.no_products_found')); ?></p>
        </div>
        <?php endif; ?>
        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content report-modal">
            <div class="modal-header">
                <div>
                    <p class="modal-kicker">Product details</p>
                    <h5 class="modal-title mb-0" id="productDetailsModalTitle">Product Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Left: Basic Info -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Specifications</h6>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Product Name</div>
                                    <div class="fw-bold fs-6 text-primary" id="modalProductName">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Model Name</div>
                                    <div class="fw-bold" id="modalProductModel">-</div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-muted mb-0.5">Category</div>
                                        <div class="fw-bold" id="modalProductCategory">-</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-muted mb-0.5">Brand</div>
                                        <div class="fw-bold" id="modalProductBrand">-</div>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-xs text-muted mb-0.5">Shop</div>
                                    <div class="fw-bold" id="modalProductShop">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Free Service details -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Warranty & Service</h6>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Free Service Status</div>
                                    <div class="fw-bold" id="modalProductServiceStatus">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Service Duration</div>
                                    <div class="fw-bold" id="modalProductServiceDuration">-</div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-xs text-muted mb-0.5">Service Terms</div>
                                    <div class="fw-bold text-wrap" id="modalProductServiceTerms">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial breakdown -->
                    <div class="col-12">
                        <div class="card border-0 border-top border-slate-200 rounded-0 mt-2 pt-3">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Stock & Sales Performance</h6>
                            <div class="row text-center g-3">
                                <div class="col-6 col-md-3">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="text-xs text-muted">Stock Quantity</div>
                                        <div class="fs-5 fw-bold" id="modalProductStockQty">0</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="text-xs text-muted">Purchase Price</div>
                                        <div class="fs-5 fw-bold" id="modalProductPurchasePrice">0.00</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="text-xs text-muted">Sale Price</div>
                                        <div class="fs-5 fw-bold" id="modalProductSalePrice">0.00</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="text-xs text-muted">Inventory Value</div>
                                        <div class="fs-5 fw-bold text-primary" id="modalProductInvValue">0.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Summary -->
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-light border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-xs text-muted">Total Units Sold:</span>
                                <strong class="text-dark ms-1" id="modalProductUnitsSold">0</strong>
                            </div>
                            <div class="text-end">
                                <span class="text-xs text-muted">Total Revenue Realized:</span>
                                <strong class="text-success fs-5 ms-1" id="modalProductRevenue">0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
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

    /* stat card style */
    .stat-card {
        background: #ffffff;
        border: 1px solid var(--report-line);
        border-radius: 18px;
        padding: 1.15rem;
        box-shadow: 0 11px 20px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 28px rgba(15, 23, 42, 0.1);
        border-color: #97b0c8;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 0.75rem;
    }

    .stat-label {
        color: var(--report-ink-500);
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--report-ink-900);
        line-height: 1.2;
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('productDetailsModal');
        if (!modalEl) return;

        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const product = JSON.parse(button.getAttribute('data-product') || '{}');
            
            document.getElementById('modalProductName').textContent = product.name || '-';
            document.getElementById('modalProductModel').textContent = product.model_name || '-';
            document.getElementById('modalProductCategory').textContent = product.category || '-';
            document.getElementById('modalProductBrand').textContent = product.brand || '-';
            document.getElementById('modalProductShop').textContent = product.shop_name || '-';
            
            const serviceStatusEl = document.getElementById('modalProductServiceStatus');
            if (product.has_free_service) {
                serviceStatusEl.innerHTML = '<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">Active</span>';
            } else {
                serviceStatusEl.innerHTML = '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill">No Free Service</span>';
            }
            document.getElementById('modalProductServiceDuration').textContent = product.free_service_duration || '-';
            document.getElementById('modalProductServiceTerms').textContent = product.free_service_terms || '-';
            
            document.getElementById('modalProductStockQty').textContent = product.stock_quantity || '0';
            
            const currencySymbol = <?php echo json_encode(currency_symbol(), 15, 512) ?>;
            document.getElementById('modalProductPurchasePrice').textContent = currencySymbol + Number(product.purchase_price || 0).toFixed(2);
            document.getElementById('modalProductSalePrice').textContent = currencySymbol + Number(product.sale_price || 0).toFixed(2);
            document.getElementById('modalProductInvValue').textContent = currencySymbol + Number(product.inventory_value || 0).toFixed(2);
            
            document.getElementById('modalProductUnitsSold').textContent = product.total_units_sold || '0';
            document.getElementById('modalProductRevenue').textContent = currencySymbol + Number(product.total_revenue || 0).toFixed(2);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\personal\Byabsha-Track\Modules/Report\resources/views/products.blade.php ENDPATH**/ ?>