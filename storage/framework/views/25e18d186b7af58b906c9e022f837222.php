<?php $__env->startSection('title', __('damage.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1"><?php echo e(__('damage.title')); ?></h1>
            <p class="text-muted mb-0"><?php echo e(__('damage.subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('damage.create')); ?>" class="btn btn-danger">
            <i class="bi bi-exclamation-triangle me-1"></i><?php echo e(__('damage.record_damage')); ?>

        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong><?php echo e(__('damage.filters')); ?></strong>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('damage.index')); ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label"><?php echo e(__('damage.shop')); ?></label>
                    <select name="shop_id" class="form-select">
                        <option value=""><?php echo e(__('damage.all_shops')); ?></option>
                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($shop->id); ?>" <?php echo e((string)($filters['shop_id'] ?? '') === (string)$shop->id ? 'selected' : ''); ?>>
                                <?php echo e($shop->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo e(__('damage.date_from')); ?></label>
                    <input type="date" name="date_from" class="form-control" value="<?php echo e($filters['date_from'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo e(__('damage.date_to')); ?></label>
                    <input type="date" name="date_to" class="form-control" value="<?php echo e($filters['date_to'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" type="submit"><?php echo e(__('damage.apply_filters')); ?></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th><?php echo e(__('damage.reference_no')); ?></th>
                    <th><?php echo e(__('damage.damage_date')); ?></th>
                    <th><?php echo e(__('damage.shop')); ?></th>
                    <th class="text-end"><?php echo e(__('damage.total_quantity')); ?></th>
                    <th class="text-end"><?php echo e(__('damage.total_loss')); ?></th>
                    <th><?php echo e(__('app.actions')); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $damages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $damage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($damage->reference_no); ?></td>
                        <td><?php echo e(optional($damage->damage_date)->format('d M Y')); ?></td>
                        <td><?php echo e($damage->shop?->name ?? '-'); ?></td>
                        <td class="text-end"><?php echo e(number_format((int)$damage->total_quantity)); ?></td>
                        <td class="text-end"><?php echo e(number_format((float)$damage->total_loss, 2)); ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('damage.show', $damage->id)); ?>" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="<?php echo e(route('damage.destroy', $damage->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('damage.confirm_delete')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted"><?php echo e(__('damage.no_records')); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($damages->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($damages->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Arpa\self_project\byabshaTrack\Modules/Damage\resources/views/index.blade.php ENDPATH**/ ?>