<?php $__env->startSection('title', 'Subscription Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-stars me-2"></i>Subscription Plans</h4>
    </div>

    <div class="row g-4">
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo e($plan['name']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo e($plan['price']); ?></h6>
                        <p class="card-text"><?php echo e($plan['description']); ?></p>

                        <ul class="list-unstyled mt-3 mb-4">
                            <?php $__currentLoopData = $plan['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $included): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <?php if($included): ?>
                                        <span class="text-success">✓</span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                    <span class="ms-2"><?php echo e($label); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <div class="mt-auto">
                            <?php if($plan['key'] === 'premium'): ?>
                                <a href="#" class="btn btn-primary w-100">Choose Premium</a>
                            <?php elseif($plan['key'] === 'standard'): ?>
                                <a href="#" class="btn btn-outline-primary w-100">Choose Standard</a>
                            <?php else: ?>
                                <a href="#" class="btn btn-outline-secondary w-100">Choose Basic</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Arpa\self_project\byabshaTrack\resources\views/admin/subscriptions.blade.php ENDPATH**/ ?>