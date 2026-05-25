<?php $__env->startSection('title', __('branch::branch.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --branch-brand: #0f766e;
        --branch-brand-deep: #155e75;
        --branch-line: #d8e4ee;
        --branch-ink-900: #0f172a;
        --branch-ink-700: #334155;
        --branch-ink-500: #64748b;

    .btn-branch-theme {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.66rem 1.2rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 14px 28px rgba(15, 118, 110, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
    }

    .btn-branch-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(15, 118, 110, 0.32);
    }

    .btn-branch-theme:hover,
    .btn-branch-theme:focus,
    .btn-branch-theme:active {
        text-decoration: none;
        outline: none;
    }

    .branch-shell {
        position: relative;
    }

    .branch-header {
        gap: 0.9rem;
    }

    .branch-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--branch-ink-900);
        margin-bottom: 0.45rem;
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .branch-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: rgba(15, 118, 110, 0.12);
        color: var(--branch-brand);
        border: 1px solid rgba(15, 118, 110, 0.22);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px rgba(15, 118, 110, 0.13);
    }

    .branch-page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--branch-ink-900);
        margin-bottom: 0.45rem;
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .btn-add-branch {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
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

    .btn-add-branch:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 30px rgba(15, 118, 110, 0.34);
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--branch-line);
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .content-card-header {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid #e7edf4;
        background: #f7fbff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .content-card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--branch-ink-700);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .content-card-title i { color: var(--branch-brand); }

    /* Table */
    .table-custom { margin-bottom: 0; }

    .table-custom thead th {
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

    .table-custom tbody td {
        border-color: #e7edf4;
        padding: 0.92rem 0.95rem;
        vertical-align: middle;
    }

    .table-custom tbody tr:hover { background: #fbfdff; }

    .branch-name-cell {
        color: var(--branch-ink-900);
        font-weight: 700;
    }

    .branch-date-cell {
        color: var(--branch-ink-500);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.14);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.26);
    }

    .status-inactive {
        background: rgba(100, 116, 139, 0.14);
        color: #475569;
        border: 1px solid rgba(100, 116, 139, 0.26);
    }

    .action-btn {
        border-radius: 10px !important;
        padding: 0.32rem 0.5rem;
        border-width: 1px;
        font-size: 0.8rem;
    }

    .btn-outline-info {
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.35);
    }
    .btn-outline-info:hover { background: #0f766e; border-color: #0f766e; color: #fff; }

    .btn-outline-warning {
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.45);
    }
    .btn-outline-warning:hover { background: #f59e0b; border-color: #f59e0b; color: #fff; }

    .btn-outline-danger {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.35);
    }
    .btn-outline-danger:hover { background: #dc2626; border-color: #dc2626; color: #fff; }

    /* Mobile card view */
    .branch-mobile-cards { display: none; }

    .branch-mobile-card {
        background: #fff;
        border: 1px solid var(--branch-line);
        border-radius: 14px;
        padding: 1rem 1.1rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05);
    }

    .branch-mobile-card:last-child { margin-bottom: 0; }

    .branch-mobile-card .bmc-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.65rem;
    }

    .branch-mobile-card .bmc-meta {
        font-size: 0.8rem;
        color: var(--branch-ink-500);
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .branch-mobile-card .bmc-actions {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.8rem;
        padding-top: 0.75rem;
        border-top: 1px solid #edf3f8;
    }

    .empty-state { padding: 3rem 1rem; text-align: center; }
    .empty-state i { font-size: 2.5rem; color: #8aa0b6; display: block; margin-bottom: 0.65rem; }

    .btn-create-first {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.48rem 1rem;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
    }
    .btn-create-first:hover { color: #fff; }

    @media (max-width: 767.98px) {
        .branch-header { flex-direction: column; align-items: stretch !important; }
        .btn-branch-theme { width: 100%; justify-content: center; }
        .btn-add-branch { width: 100%; justify-content: center; }
        .table-desktop { display: none; }
        .branch-mobile-cards { display: block; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="branch-shell">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap branch-header">
        <div>
            <span class="branch-kicker"><i class="bi bi-diagram-3"></i><?php echo e(__('branch::branch.title')); ?></span>
            <h1 class="branch-title fw-bold"><?php echo e(__('branch::branch.title')); ?></h1>
            <p class="text-muted mb-0"><?php echo e(__('branch::branch.subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('branch.create', ['shop_id' => $selectedShopId])); ?>" class="btn-branch-theme">
            <i class="bi bi-plus-circle me-1"></i><?php echo e(__('branch::branch.add_new')); ?>

        </a>
    </div>
</div>


<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title"><i class="bi bi-funnel"></i><?php echo e(__('branch::branch.shop_filter')); ?></h5>
    </div>
    <div class="p-3 p-md-4">
        <form action="<?php echo e(route('branch.index')); ?>" method="GET" class="row g-3 align-items-end">
            <div class="col-sm-8 col-md-9">
                <select id="shop_id" name="shop_id" class="form-select">
                    <option value=""><?php echo e(__('branch::branch.all_shops')); ?></option>
                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($shop->id); ?>" <?php echo e((string) $selectedShopId === (string) $shop->id ? 'selected' : ''); ?>>
                            <?php echo e($shop->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-sm-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary fw-semibold flex-fill">
                    <i class="bi bi-search me-1 d-none d-sm-inline"></i><?php echo e(__('app.apply_filters')); ?>

                </button>
                <?php if($selectedShopId): ?>
                    <a href="<?php echo e(route('branch.index')); ?>" class="btn btn-outline-secondary" title="<?php echo e(__('app.back_to_list')); ?>">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>


<div class="content-card table-desktop">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th><?php echo e(__('branch::branch.name')); ?></th>
                    <th><?php echo e(__('branch::branch.shop')); ?></th>
                    <th><?php echo e(__('branch::branch.location')); ?></th>
                    <th><?php echo e(__('branch::branch.phone')); ?></th>
                    <th><?php echo e(__('branch::branch.status')); ?></th>
                    <th><?php echo e(__('app.created_at')); ?></th>
                    <th><?php echo e(__('app.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong class="branch-name-cell"><?php echo e($branch->name); ?></strong></td>
                        <td class="text-muted"><?php echo e($branch->shop?->name ?? '-'); ?></td>
                        <td class="text-muted"><?php echo e($branch->location ?: '-'); ?></td>
                        <td class="text-muted"><?php echo e($branch->phone ?: '-'); ?></td>
                        <td>
                            <?php if($branch->is_active): ?>
                                <span class="status-badge status-active"><i class="bi bi-check-circle-fill"></i><?php echo e(__('branch::branch.active')); ?></span>
                            <?php else: ?>
                                <span class="status-badge status-inactive"><i class="bi bi-dash-circle"></i><?php echo e(__('branch::branch.inactive')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="branch-date-cell"><?php echo e($branch->created_at?->format('M d, Y')); ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('branch.show', $branch->id)); ?>" class="btn btn-outline-info action-btn" title="<?php echo e(__('app.view')); ?>">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('branch.edit', $branch->id)); ?>" class="btn btn-outline-warning action-btn" title="<?php echo e(__('app.edit')); ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?php echo e(route('branch.destroy', $branch->id)); ?>" method="POST" class="d-inline"
                                    onsubmit="return confirm('<?php echo e(__('branch::branch.confirm_delete')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger action-btn" title="<?php echo e(__('app.delete')); ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-diagram-3"></i>
                                <strong class="d-block mb-1"><?php echo e(__('branch::branch.no_branches')); ?></strong>
                                <p class="text-muted mb-3 small"><?php echo e(__('branch::branch.no_branches_sub')); ?></p>
                                <a href="<?php echo e(route('branch.create', ['shop_id' => $selectedShopId])); ?>" class="btn-create-first">
                                    <i class="bi bi-plus-circle"></i><?php echo e(__('branch::branch.add_new')); ?>

                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($branches->hasPages()): ?>
        <div class="p-3 border-top"><?php echo e($branches->links()); ?></div>
    <?php endif; ?>
</div>


<div class="branch-mobile-cards">
    <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="branch-mobile-card">
            <div class="bmc-header">
                <div>
                    <strong class="branch-name-cell d-block"><?php echo e($branch->name); ?></strong>
                    <span class="text-muted small"><?php echo e($branch->shop?->name ?? '-'); ?></span>
                </div>
                <?php if($branch->is_active): ?>
                    <span class="status-badge status-active"><i class="bi bi-check-circle-fill"></i><?php echo e(__('branch::branch.active')); ?></span>
                <?php else: ?>
                    <span class="status-badge status-inactive"><i class="bi bi-dash-circle"></i><?php echo e(__('branch::branch.inactive')); ?></span>
                <?php endif; ?>
            </div>
            <?php if($branch->location): ?>
                <div class="bmc-meta"><i class="bi bi-geo-alt"></i><?php echo e($branch->location); ?></div>
            <?php endif; ?>
            <?php if($branch->phone): ?>
                <div class="bmc-meta"><i class="bi bi-telephone"></i><?php echo e($branch->phone); ?></div>
            <?php endif; ?>
            <div class="bmc-meta"><i class="bi bi-calendar3"></i><?php echo e($branch->created_at?->format('M d, Y')); ?></div>
            <div class="bmc-actions">
                <a href="<?php echo e(route('branch.show', $branch->id)); ?>" class="btn btn-sm btn-outline-info flex-fill text-center">
                    <i class="bi bi-eye me-1"></i><?php echo e(__('app.view')); ?>

                </a>
                <a href="<?php echo e(route('branch.edit', $branch->id)); ?>" class="btn btn-sm btn-outline-warning flex-fill text-center">
                    <i class="bi bi-pencil me-1"></i><?php echo e(__('app.edit')); ?>

                </a>
                <form action="<?php echo e(route('branch.destroy', $branch->id)); ?>" method="POST"
                    onsubmit="return confirm('<?php echo e(__('branch::branch.confirm_delete')); ?>')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="content-card p-4">
            <div class="empty-state">
                <i class="bi bi-diagram-3"></i>
                <strong class="d-block mb-1"><?php echo e(__('branch::branch.no_branches')); ?></strong>
                <p class="text-muted mb-3 small"><?php echo e(__('branch::branch.no_branches_sub')); ?></p>
                <a href="<?php echo e(route('branch.create', ['shop_id' => $selectedShopId])); ?>" class="btn-create-first">
                    <i class="bi bi-plus-circle"></i><?php echo e(__('branch::branch.add_new')); ?>

                </a>
            </div>
        </div>
    <?php endif; ?>
    <?php if($branches->hasPages()): ?>
        <div class="mt-3"><?php echo e($branches->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Arpa\self_project\byabshaTrack\Modules/Branch\resources/views/index.blade.php ENDPATH**/ ?>