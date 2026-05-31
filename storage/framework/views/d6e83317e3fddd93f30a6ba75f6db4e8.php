<?php $__env->startSection('title', __('sale.exchange_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1"><?php echo e(__('sale.exchange_title')); ?></h2>
        <p class="text-muted mb-0"><?php echo e(__('sale.exchange_subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('sale.exchanges.create')); ?>" class="btn btn-primary">
        <i class="bi bi-arrow-repeat"></i> <?php echo e(__('sale.exchange_create_btn')); ?>

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
        <select name="type" class="form-select">
            <option value=""><?php echo e(__('sale.all_types')); ?></option>
            <option value="replacement" <?php echo e(($filters['type'] ?? '') === 'replacement' ? 'selected' : ''); ?>><?php echo e(__('sale.exchange_type_replacement')); ?></option>
            <option value="return_only" <?php echo e(($filters['type'] ?? '') === 'return_only' ? 'selected' : ''); ?>><?php echo e(__('sale.exchange_type_return_only')); ?></option>
        </select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-outline-secondary" type="submit"><?php echo e(__('sale.apply_filters')); ?></button>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                <tr>
                    <th><?php echo e(__('sale.sale_reference')); ?></th>
                    <th><?php echo e(__('sale.shop')); ?></th>
                    <th><?php echo e(__('sale.product')); ?></th>
                    <th><?php echo e(__('sale.exchange_date')); ?></th>
                    <th><?php echo e(__('sale.exchange_type')); ?></th>
                    <th><?php echo e(__('sale.quantity')); ?></th>
                    <th><?php echo e(__('sale.exchange_cost_difference')); ?></th>
                    <th><?php echo e(__('sale.reason')); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $exchanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exchange): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>#<?php echo e($exchange->sale_id); ?></td>
                        <td><?php echo e($exchange->shop?->name ?? '-'); ?></td>
                        <td><?php echo e($exchange->sale?->product?->name ?? '-'); ?></td>
                        <td><?php echo e($exchange->exchange_date->format('d M Y')); ?></td>
                        <td><?php echo e(__('sale.exchange_type_' . $exchange->exchange_type)); ?></td>
                        <td><?php echo e($exchange->quantity); ?></td>
                        <td><?php echo e(number_format((float)$exchange->cost_difference, 2)); ?></td>
                        <td><?php echo e($exchange->reason); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted"><?php echo e(__('sale.no_records')); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <?php echo e($exchanges->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\byabshaTrack\Modules/Sale\resources/views/exchanges/index.blade.php ENDPATH**/ ?>