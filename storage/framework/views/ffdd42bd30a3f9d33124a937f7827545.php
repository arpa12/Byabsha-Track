<?php $__env->startSection('title', __('reconciliation.history')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    .reconciliation-shell {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: #0f172a;
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .page-title {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 0.45rem;
        font-weight: 800;
    }

    .report-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: color-mix(in srgb, var(--brand, #0f766e) 12%, transparent);
        color: var(--brand, #0f766e);
        border: 1px solid color-mix(in srgb, var(--brand, #0f766e) 22%, transparent);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px color-mix(in srgb, var(--brand, #0f766e) 13%, transparent);
    }

    .panel {
        background: #fff;
        border: 1px solid #dce6ef;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .panel-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e7eef5;
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        background: #fafbfe;
    }

    .btn-secondary-custom {
        border-radius: 10px;
        padding: 0.6rem 1.25rem;
        font-size: 0.85rem;
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

    .compact-table th {
        background: #f8fafc;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        font-weight: 700;
        padding: 1rem 0.75rem;
    }

    .compact-table td {
        padding: 1rem 0.75rem;
        font-size: 0.875rem;
        border-color: #e2e8f0;
    }

    .discrepancy-text-zero {
        color: #059669;
        font-weight: 700;
    }

    .discrepancy-text-active {
        color: #ef4444;
        font-weight: 700;
    }

    .form-select {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 0.6rem 0.85rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="reconciliation-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-4 mb-4">
        <div>
            <span class="report-kicker"><i class="bi bi-clock-history"></i> <?php echo e(__('reconciliation.history')); ?></span>
            <h1 class="page-title display-font text-3xl font-black text-slate-900 leading-none mb-1"><?php echo e(__('reconciliation.history')); ?></h1>
            <p class="page-subtitle text-slate-500 text-sm mt-1.5"><?php echo e(__('reconciliation.subtitle')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('reconciliation.index', ['shop_id' => $selectedShopId])); ?>" class="btn-secondary-custom">
                <i class="bi bi-arrow-left"></i> <?php echo e(__('reconciliation.back_to_dashboard')); ?>

            </a>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="panel mb-4">
        <div class="panel-head">
            <i class="bi bi-funnel me-1"></i> Filter
        </div>
        <div class="panel-body">
            <form action="<?php echo e(route('reconciliation.history')); ?>" method="GET" id="historyFilterForm">
                <div class="row align-items-end g-3">
                    <div class="col-md-6 col-lg-4">
                        <label for="shop_id" class="form-label small fw-bold text-slate-600"><?php echo e(__('reconciliation.select_shop')); ?></label>
                        <select class="form-select" id="shop_id" name="shop_id" onchange="document.getElementById('historyFilterForm').submit()">
                            <option value=""><?php echo e(__('reconciliation.all_shops')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e($selectedShopId == $shop->id ? 'selected' : ''); ?>>
                                    <?php echo e($shop->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- History Table -->
    <div class="panel">
        <div class="table-responsive">
            <table class="table compact-table align-middle mb-0">
                <thead>
                    <tr>
                        <th><?php echo e(__('reconciliation.shop')); ?></th>
                        <th><?php echo e(__('reconciliation.opened_at')); ?></th>
                        <th><?php echo e(__('reconciliation.closed_at')); ?></th>
                        <th><?php echo e(__('reconciliation.opened_by')); ?></th>
                        <th class="text-end"><?php echo e(__('reconciliation.opening_balance')); ?></th>
                        <th class="text-end"><?php echo e(__('reconciliation.expected_balance')); ?></th>
                        <th class="text-end"><?php echo e(__('reconciliation.actual_balance')); ?></th>
                        <th class="text-end"><?php echo e(__('reconciliation.discrepancy')); ?></th>
                        <th class="text-center"><?php echo e(__('reconciliation.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $registers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $register): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold"><?php echo e($register->shop->name ?? '-'); ?></td>
                            <td>
                                <div class="small text-slate-800"><?php echo e($register->opened_at->format('d M, Y')); ?></div>
                                <div class="text-slate-400 small" style="font-size: 0.75rem;"><?php echo e($register->opened_at->format('h:i A')); ?></div>
                            </td>
                            <td>
                                <div class="small text-slate-800"><?php echo e($register->closed_at ? $register->closed_at->format('d M, Y') : '-'); ?></div>
                                <div class="text-slate-400 small" style="font-size: 0.75rem;"><?php echo e($register->closed_at ? $register->closed_at->format('h:i A') : '-'); ?></div>
                            </td>
                            <td class="small text-slate-600"><?php echo e($register->user->name ?? '-'); ?></td>
                            <td class="text-end display-font">৳<?php echo e(number_format($register->opening_balance, 2)); ?></td>
                            <td class="text-end display-font">৳<?php echo e(number_format($register->expected_balance, 2)); ?></td>
                            <td class="text-end display-font">৳<?php echo e(number_format($register->actual_balance, 2)); ?></td>
                            <td class="text-end display-font">
                                <span class="<?php echo e(abs($register->discrepancy) < 0.01 ? 'discrepancy-text-zero' : 'discrepancy-text-active'); ?>">
                                    ৳<?php echo e(number_format($register->discrepancy, 2)); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo e(route('reconciliation.show', $register->id)); ?>" class="btn btn-sm btn-outline-primary px-3 rounded-pill" title="<?php echo e(__('reconciliation.audit_sheet')); ?>">
                                    <i class="bi bi-eye-fill me-1"></i><?php echo e(__('reconciliation.details')); ?>

                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-30"></i>
                                No historical reconciliation records found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($registers->hasPages()): ?>
            <div class="panel-body border-top border-slate-200">
                <?php echo e($registers->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\personal\Byabsha-Track\Modules/Reconciliation\resources/views/history.blade.php ENDPATH**/ ?>