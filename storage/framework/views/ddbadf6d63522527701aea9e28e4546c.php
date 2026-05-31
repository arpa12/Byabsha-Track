<?php $__env->startSection('title', __('sale.warranty_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1"><?php echo e(__('sale.warranty_title')); ?></h2>
        <p class="text-muted mb-0"><?php echo e(__('sale.warranty_subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('sale.warranties.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> <?php echo e(__('sale.warranty_create_btn')); ?>

    </a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="shop_id" class="form-select">
            <option value=""><?php echo e(__('sale.all_shops')); ?></option>
            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($shop->id); ?>" <?php echo e((string)($filters['shop_id'] ?? '') === (string)$shop->id ? 'selected' : ''); ?>><?php echo e($shop->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-4">
        <select name="status" class="form-select">
            <option value=""><?php echo e(__('sale.all_statuses')); ?></option>
            <?php $__currentLoopData = ['active', 'claimed', 'expired', 'void']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php echo e(($filters['status'] ?? '') === $status ? 'selected' : ''); ?>><?php echo e(__('sale.status_' . $status)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-outline-secondary" type="submit"><?php echo e(__('sale.apply_filters')); ?></button>
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                <tr>
                    <th><?php echo e(__('sale.warranty_code')); ?></th>
                    <th><?php echo e(__('sale.shop')); ?></th>
                    <th><?php echo e(__('sale.product')); ?></th>
                    <th><?php echo e(__('sale.customer_name')); ?></th>
                    <th><?php echo e(__('sale.warranty_period')); ?></th>
                    <th><?php echo e(__('sale.warranty_status')); ?></th>
                    <th class="text-end"><?php echo e(__('sale.col_actions')); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $effectiveStatus = ($warranty->status === 'active' && $warranty->end_date->isPast()) ? 'expired' : $warranty->status;
                    ?>
                    <tr>
                        <td><strong><?php echo e($warranty->warranty_code); ?></strong></td>
                        <td><?php echo e($warranty->shop?->name ?? '-'); ?></td>
                        <td><?php echo e($warranty->sale?->product?->name ?? '-'); ?></td>
                        <td><?php echo e($warranty->sale?->customer_name ?? '-'); ?></td>
                        <td><?php echo e($warranty->start_date->format('d M Y')); ?> - <?php echo e($warranty->end_date->format('d M Y')); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($effectiveStatus === 'active' ? 'success' : ($effectiveStatus === 'claimed' ? 'info' : ($effectiveStatus === 'expired' ? 'warning text-dark' : 'secondary'))); ?>">
                                <?php echo e(__('sale.status_' . $effectiveStatus)); ?>

                            </span>
                        </td>
                        <td class="text-end">
                            <?php if($warranty->status === 'active'): ?>
                                <form method="POST" action="<?php echo e(route('sale.warranties.claim', $warranty->id)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-success"><?php echo e(__('sale.warranty_mark_claimed')); ?></button>
                                </form>
                                <form method="POST" action="<?php echo e(route('sale.warranties.void', $warranty->id)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary"><?php echo e(__('sale.warranty_void')); ?></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted"><?php echo e(__('sale.no_records')); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <?php echo e($warranties->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Sale\resources/views/warranties/index.blade.php ENDPATH**/ ?>