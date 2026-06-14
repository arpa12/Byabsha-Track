<?php $__env->startSection('title', __('report.warranties_report')); ?>

<?php $__env->startSection('content'); ?>
<div class="report-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-5 mb-8 report-header">
        <div>
            <span class="report-kicker"><i class="bi bi-shield-check"></i> <?php echo e(__('app.analytics')); ?></span>
            <h1 class="dashboard-title display-font text-3xl font-black text-slate-900 leading-none mb-1"><?php echo e(__('report.warranties_report')); ?></h1>
            <p class="dashboard-subtitle text-slate-500 text-sm mt-1.5"><?php echo e(__('report.warranties_report_subtitle')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('report.export.warranties-pdf', request()->query())); ?>" class="btn-brand-custom" style="background: linear-gradient(140deg, #ef4444, #b91c1c); box-shadow: 0 14px 28px rgba(239, 68, 68, 0.28);">
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
            <form action="<?php echo e(route('report.warranties')); ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="shop_id" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.shop')); ?></label>
                        <select class="form-select" id="shop_id" name="shop_id" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                            <option value=""><?php echo e(__('report.all_shops')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e(($filters['shop_id'] ?? '') == $shop->id ? 'selected' : ''); ?>>
                                    <?php echo e($shop->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('sale.warranty_status')); ?></label>
                        <select class="form-select" id="status" name="status" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                            <option value=""><?php echo e(__('sale.all_statuses')); ?></option>
                            <?php $__currentLoopData = ['active', 'claimed', 'expired', 'void']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($statusOption); ?>" <?php echo e(($filters['status'] ?? '') === $statusOption ? 'selected' : ''); ?>>
                                    <?php echo e(__('sale.status_' . $statusOption)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.start_date')); ?></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e($filters['start_date'] ?? ''); ?>" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.end_date')); ?></label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e($filters['end_date'] ?? ''); ?>" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
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
        <div class="col-md-2-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #64748b, #475569);">
                    <i class="bi bi-shield"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.total_warranties')); ?></div>
                <div class="stat-value"><?php echo e($warrantySummary->total ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-2-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #10b981, #047857);">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="stat-label"><?php echo e(__('sale.status_active')); ?></div>
                <div class="stat-value text-success"><?php echo e($warrantySummary->active ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-2-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #f59e0b, #d97706);">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <div class="stat-label"><?php echo e(__('sale.status_expired')); ?></div>
                <div class="stat-value text-warning"><?php echo e($warrantySummary->expired ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-2-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #0ea5e9, #0284c7);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-label"><?php echo e(__('sale.status_claimed')); ?></div>
                <div class="stat-value text-info"><?php echo e($warrantySummary->claimed ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-2-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #ef4444, #b91c1c);">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-label"><?php echo e(__('sale.status_void')); ?></div>
                <div class="stat-value text-danger"><?php echo e($warrantySummary->voided ?? 0); ?></div>
            </div>
        </div>
    </div>

    <!-- Warranties Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-shield-check"></i>
                <?php echo e(__('sale.warranty_title')); ?>

            </h5>
        </div>
        <?php if($warranties->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?php echo e(__('report.warranty_code')); ?></th>
                        <th><?php echo e(__('report.shop')); ?></th>
                        <th><?php echo e(__('report.product_name')); ?></th>
                        <th><?php echo e(__('report.customer_name')); ?></th>
                        <th><?php echo e(__('report.customer_phone')); ?></th>
                        <th><?php echo e(__('report.warranty_period')); ?></th>
                        <th><?php echo e(__('sale.warranty_status')); ?></th>
                        <th><?php echo e(__('report.terms_notes')); ?></th>
                        <th class="text-center"><?php echo e(__('report.action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $effectiveStatus = ($warranty->status === 'active' && $warranty->end_date->isPast()) ? 'expired' : $warranty->status;
                        $pillClass = $effectiveStatus === 'active' ? 'success' : ($effectiveStatus === 'claimed' ? 'info' : ($effectiveStatus === 'expired' ? 'warning' : 'danger'));
                        
                        $warrantyData = [
                            'id' => $warranty->id,
                            'warranty_code' => $warranty->warranty_code,
                            'shop_name' => $warranty->shop->name ?? '-',
                            'product_name' => $warranty->sale->product->name ?? '-',
                            'customer_name' => $warranty->sale->customer_name ?: '-',
                            'customer_phone' => $warranty->sale->customer_phone ?: '-',
                            'customer_address' => $warranty->sale->customer_address ?: '-',
                            'period' => $warranty->start_date->format('M d, Y') . ' - ' . $warranty->end_date->format('M d, Y'),
                            'duration' => $warranty->duration_snapshot_value . ' ' . $warranty->duration_snapshot_unit,
                            'coverage_qty' => $warranty->coverage_quantity,
                            'status' => $effectiveStatus,
                            'terms' => $warranty->terms ?: '-',
                            'claim_note' => $warranty->claim_note ?: '-',
                            'claimed_at' => $warranty->claimed_at ? $warranty->claimed_at->format('M d, Y g:i A') : '-',
                            'created_by' => $warranty->creator->name ?? '-',
                        ];
                    ?>
                    <tr>
                        <td><strong class="text-teal"><i class="bi bi-shield-check"></i> <?php echo e($warranty->warranty_code); ?></strong></td>
                        <td><span class="badge bg-light text-dark border"><?php echo e($warranty->shop->name); ?></span></td>
                        <td><strong><?php echo e($warranty->sale->product->name ?? '-'); ?></strong></td>
                        <td><?php echo e($warranty->sale->customer_name ?: '-'); ?></td>
                        <td><?php echo e($warranty->sale->customer_phone ?: '-'); ?></td>
                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-calendar-event"></i>
                                <?php echo e($warranty->start_date->format('M d, Y')); ?> - <?php echo e($warranty->end_date->format('M d, Y')); ?>

                            </span>
                        </td>
                        <td>
                            <span class="status-pill status-pill-<?php echo e($pillClass); ?>">
                                <span class="status-indicator"></span>
                                <?php echo e(__('sale.status_' . $effectiveStatus)); ?>

                            </span>
                        </td>
                        <td>
                            <span class="text-muted small text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo e($warranty->terms ?: $warranty->claim_note); ?>">
                                <?php echo e($warranty->terms ?: ($warranty->claim_note ?: '-')); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#warrantyDetailsModal"
                                data-warranty='<?php echo json_encode($warrantyData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG, 512) ?>'
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="p-3">
            <?php echo e($warranties->links()); ?>

        </div>
        <?php else: ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-shield-slash fs-1 d-block mb-2 text-secondary"></i>
            <p class="mb-0"><?php echo e(__('report.no_warranties_found')); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Warranty Details Modal -->
<div class="modal fade" id="warrantyDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content report-modal">
            <div class="modal-header">
                <div>
                    <p class="modal-kicker">Warranty details</p>
                    <h5 class="modal-title mb-0" id="warrantyDetailsModalTitle">Warranty Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Left: Customer Info & Status -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Customer & Status</h6>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Customer Name</div>
                                    <div class="fw-bold fs-6" id="modalWarrantyCustomerName">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Phone</div>
                                    <div class="fw-bold" id="modalWarrantyCustomerPhone">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Address</div>
                                    <div class="fw-bold text-wrap" id="modalWarrantyCustomerAddress">-</div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-xs text-muted mb-0.5">Warranty Status</div>
                                    <div class="fw-bold mt-1" id="modalWarrantyStatus">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Warranty Terms & Code -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Warranty Parameters</h6>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Warranty Code</div>
                                    <div class="fw-bold text-primary fs-6" id="modalWarrantyCode">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Product Name</div>
                                    <div class="fw-bold" id="modalWarrantyProductName">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Warranty Period</div>
                                    <div class="fw-bold" id="modalWarrantyPeriod">-</div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-xs text-muted mb-0.5">Duration Snapshot</div>
                                    <div class="fw-bold" id="modalWarrantyDuration">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width: Terms, Notes, Claims -->
                    <div class="col-12">
                        <div class="card border-0 border-top border-slate-200 rounded-0 mt-2 pt-3">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-2 tracking-wider">Terms & Claims Info</h6>
                            <div class="p-3 bg-light rounded border mb-3">
                                <div class="text-xs text-muted mb-1">Warranty Terms</div>
                                <div class="text-dark small text-wrap" id="modalWarrantyTerms">-</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border">
                                        <div class="text-xs text-muted mb-1">Claim Note</div>
                                        <div class="text-dark small text-wrap" id="modalWarrantyClaimNote">-</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border">
                                        <div class="text-xs text-muted mb-1">Claimed Timestamp</div>
                                        <div class="text-dark small fw-bold" id="modalWarrantyClaimedAt">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meta details: Creator & Shop -->
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-light border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-xs text-muted">Created By:</span>
                                <strong class="text-dark ms-1" id="modalWarrantyCreator">-</strong>
                            </div>
                            <div class="text-end">
                                <span class="text-xs text-muted">Shop Name:</span>
                                <strong class="text-dark ms-1" id="modalWarrantyShop">-</strong>
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

    .col-md-2-4 {
        flex: 0 0 20%;
        max-width: 20%;
    }
    @media (max-width: 992px) {
        .col-md-2-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    @media (max-width: 576px) {
        .col-md-2-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    /* Status indicator badges */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        padding: 0.15rem 0.6rem;
        font-size: 0.72rem;
        font-weight: 700;
        border-width: 1px;
        border-style: solid;
        white-space: nowrap;
    }

    .status-indicator {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-pill-success {
        background: rgba(16, 185, 129, 0.08);
        color: #047857;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .status-pill-success .status-indicator {
        background-color: #10b981;
    }

    .status-pill-warning {
        background: rgba(245, 158, 11, 0.08);
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .status-pill-warning .status-indicator {
        background-color: #f59e0b;
    }

    .status-pill-info {
        background: rgba(14, 165, 233, 0.08);
        color: #0369a1;
        border-color: rgba(14, 165, 233, 0.2);
    }

    .status-pill-info .status-indicator {
        background-color: #0ea5e9;
    }

    .status-pill-danger {
        background: rgba(239, 68, 68, 0.08);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .status-pill-danger .status-indicator {
        background-color: #ef4444;
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
        const modalEl = document.getElementById('warrantyDetailsModal');
        if (!modalEl) return;

        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const warranty = JSON.parse(button.getAttribute('data-warranty') || '{}');
            
            document.getElementById('modalWarrantyCustomerName').textContent = warranty.customer_name || '-';
            document.getElementById('modalWarrantyCustomerPhone').textContent = warranty.customer_phone || '-';
            document.getElementById('modalWarrantyCustomerAddress').textContent = warranty.customer_address || '-';
            document.getElementById('modalWarrantyCode').textContent = warranty.warranty_code || '-';
            document.getElementById('modalWarrantyProductName').textContent = warranty.product_name || '-';
            document.getElementById('modalWarrantyPeriod').textContent = warranty.period || '-';
            document.getElementById('modalWarrantyDuration').textContent = warranty.duration || '-';
            document.getElementById('modalWarrantyTerms').textContent = warranty.terms || '-';
            document.getElementById('modalWarrantyClaimNote').textContent = warranty.claim_note || '-';
            document.getElementById('modalWarrantyClaimedAt').textContent = warranty.claimed_at || '-';
            document.getElementById('modalWarrantyCreator').textContent = warranty.created_by || '-';
            document.getElementById('modalWarrantyShop').textContent = warranty.shop_name || '-';

            const statusEl = document.getElementById('modalWarrantyStatus');
            const statusLabel = warranty.status ? warranty.status.toUpperCase() : '-';
            let pillClass = 'bg-secondary text-white';
            if (warranty.status === 'active') pillClass = 'bg-success text-white px-2.5 py-1 rounded-pill small';
            else if (warranty.status === 'claimed') pillClass = 'bg-info text-white px-2.5 py-1 rounded-pill small';
            else if (warranty.status === 'expired') pillClass = 'bg-warning text-dark px-2.5 py-1 rounded-pill small';
            else if (warranty.status === 'void') pillClass = 'bg-danger text-white px-2.5 py-1 rounded-pill small';
            
            statusEl.innerHTML = `<span class="${pillClass}">${statusLabel}</span>`;
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\personal\Byabsha-Track\Modules/Report\resources/views/warranties.blade.php ENDPATH**/ ?>