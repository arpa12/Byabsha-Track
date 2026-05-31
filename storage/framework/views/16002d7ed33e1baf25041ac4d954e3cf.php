<?php $__env->startSection('title', __('branch::branch.edit_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --branch-brand: #0f766e;
        --branch-brand-deep: #155e75;
        --branch-line: #d8e4ee;
    }

    .btn-branch-theme {
        background: linear-gradient(140deg, var(--branch-brand), var(--branch-brand-deep));
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
        white-space: nowrap;
    }

    .btn-branch-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 18px 30px rgba(15, 118, 110, 0.32);
    }

    .content-card {
        background: #fff;
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
        color: #334155;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .content-card-title i { color: var(--branch-brand); }

    .info-row {
        display: flex;
        gap: 0.65rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #edf3f8;
        font-size: 0.85rem;
        color: #475569;
    }

    .info-row:last-child { border-bottom: none; padding-bottom: 0; }

    .info-row i {
        color: var(--branch-brand);
        font-size: 1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }

    .form-label { color: #334155; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title"><?php echo e(__('branch::branch.edit_title')); ?></h1>
        <p class="page-subtitle mb-0"><?php echo e($branch->name); ?> &mdash; <?php echo e($branch->shop?->name); ?></p>
    </div>
    <a href="<?php echo e(route('branch.show', $branch->id)); ?>" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i><?php echo e(__('branch::branch.back')); ?>

    </a>
</div>

<div class="row g-4">
    
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-pencil-square"></i><?php echo e(__('branch::branch.edit_title')); ?>

                </h5>
            </div>
            <div class="p-4">
                <form action="<?php echo e(route('branch.update', $branch->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="row g-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">
                                <?php echo e(__('branch::branch.shop')); ?> <span class="text-danger">*</span>
                            </label>
                            <select name="shop_id" class="form-select <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value=""><?php echo e(__('branch::branch.shop_placeholder')); ?></option>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($shop->id); ?>" <?php echo e(old('shop_id', $branch->shop_id) == $shop->id ? 'selected' : ''); ?>>
                                        <?php echo e($shop->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">
                                <?php echo e(__('branch::branch.location')); ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="location" value="<?php echo e(old('location', $branch->location)); ?>"
                                class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="<?php echo e(__('branch::branch.location_placeholder')); ?>" required>
                            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold"><?php echo e(__('branch::branch.phone')); ?></label>
                            <input type="text" name="phone" value="<?php echo e(old('phone', $branch->phone)); ?>"
                                class="form-control"
                                placeholder="<?php echo e(__('branch::branch.phone_placeholder')); ?>">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold"><?php echo e(__('branch::branch.email')); ?></label>
                            <input type="email" name="email" value="<?php echo e(old('email', $branch->email)); ?>"
                                class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="<?php echo e(__('branch::branch.email_placeholder')); ?>">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>



                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="is_active" <?php echo e(old('is_active', $branch->is_active) ? 'checked' : ''); ?>>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    <?php echo e(__('branch::branch.active')); ?>

                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top flex-wrap gap-2">
                        <a href="<?php echo e(route('branch.show', $branch->id)); ?>" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i><?php echo e(__('branch::branch.back')); ?>

                        </a>
                        <button type="submit" class="btn-branch-theme">
                            <i class="bi bi-check-circle"></i><?php echo e(__('branch::branch.update_btn')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-info-circle"></i>Current Details
                </h5>
            </div>
            <div class="p-4">
                <div class="info-row">
                    <i class="bi bi-shop"></i>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem"><?php echo e(__('branch::branch.shop')); ?></div>
                        <strong><?php echo e($branch->shop?->name ?? '-'); ?></strong>
                    </div>
                </div>
                <div class="info-row">
                    <i class="bi bi-diagram-3"></i>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem"><?php echo e(__('branch::branch.name')); ?></div>
                        <strong><?php echo e($branch->name); ?></strong>
                    </div>
                </div>
                <?php if($branch->location): ?>
                <div class="info-row">
                    <i class="bi bi-geo-alt"></i>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem"><?php echo e(__('branch::branch.location')); ?></div>
                        <?php echo e($branch->location); ?>

                    </div>
                </div>
                <?php endif; ?>
                <?php if($branch->phone): ?>
                <div class="info-row">
                    <i class="bi bi-telephone"></i>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem"><?php echo e(__('branch::branch.phone')); ?></div>
                        <?php echo e($branch->phone); ?>

                    </div>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <i class="bi bi-calendar3"></i>
                    <div>
                        <div class="text-muted" style="font-size:0.75rem"><?php echo e(__('app.created_at')); ?></div>
                        <?php echo e($branch->created_at?->format('F d, Y')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Arpa\self_project\byabshaTrack\Modules/Branch\resources/views/edit.blade.php ENDPATH**/ ?>