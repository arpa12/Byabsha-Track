<?php $__env->startSection('title', __('shop.show_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .btn-shop-theme {
        background: linear-gradient(140deg, #0f766e, #155e75);
        color: #fff;
        border: 0;
        border-radius: 999px;
        padding: 0.48rem 0.9rem;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.24);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-shop-theme:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 16px 28px rgba(15, 118, 110, 0.28);
    }

    .shop-products-card .content-card-header {
        padding: 1rem 1.2rem;
    }

    .shop-products-card .content-card-title {
        font-size: 0.92rem;
    }

    .shop-products-card .table-responsive {
        padding: 0 1.2rem 1.2rem;
    }

    .shop-products-table thead th {
        white-space: nowrap;
    }

    .shop-products-table tbody td {
        vertical-align: middle;
    }

    .shop-products-empty {
        padding: 2rem 1rem;
        text-align: center;
        color: #475569;
    }

    .shop-products-empty i {
        display: block;
        margin-bottom: 0.55rem;
        font-size: 2rem;
        color: #94a3b8;
    }

    .shop-products-empty strong {
        display: block;
        font-size: 0.96rem;
        margin-bottom: 0.2rem;
    }

    .shop-branch-item {
        border: 1px solid #e7edf4;
        border-radius: 14px;
        padding: 0.85rem 0.95rem;
        background: #fbfdff;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><?php echo e($shop->name); ?></h1>
        <p class="page-subtitle"><?php echo e(__('shop.show_subtitle')); ?></p>
    </div>
    <div>
        <a href="<?php echo e(route('shop.edit', $shop->id)); ?>" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> <?php echo e(__('app.edit')); ?>

        </a>
        <a href="<?php echo e(route('branch.index', ['shop_id' => $shop->id])); ?>" class="btn btn-primary me-2">
            <i class="bi bi-diagram-3"></i> <?php echo e(__('shop.manage_branches')); ?>

        </a>
        <a href="<?php echo e(route('shop.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> <?php echo e(__('app.back')); ?>

        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Shop Info Card -->
    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-info-circle"></i>
                    <?php echo e(__('shop.shop_information')); ?>

                </h5>
            </div>
            <div class="p-4">
                <div class="mb-3">
                    <label class="text-muted small"><?php echo e(__('shop.shop_name')); ?></label>
                    <p class="mb-0 fw-semibold"><?php echo e($shop->name); ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><?php echo e(__('shop.location')); ?></label>
                    <p class="mb-0"><?php echo e($shop->location ?: '-'); ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><?php echo e(__('shop.address')); ?></label>
                    <p class="mb-0"><?php echo e($shop->address ?: '-'); ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><?php echo e(__('shop.created_date')); ?></label>
                    <p class="mb-0"><?php echo e($shop->created_at->format('F d, Y')); ?></p>
                </div>
                <div class="mb-0">
                    <label class="text-muted small"><?php echo e(__('shop.last_updated')); ?></label>
                    <p class="mb-0"><?php echo e($shop->updated_at->format('F d, Y')); ?></p>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="content-card mt-4">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="bi bi-bar-chart"></i>
                    <?php echo e(__('shop.statistics')); ?>

                </h5>
            </div>
            <div class="p-4">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-3 bg-light rounded">
                            <h3 class="mb-0" style="color: #2563eb;"><?php echo e($shop->products_count); ?></h3>
                            <small class="text-muted"><?php echo e(__('shop.col_products')); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded">
                            <h3 class="mb-0" style="color: #10b981;"><?php echo e($shop->sales_count); ?></h3>
                            <small class="text-muted"><?php echo e(__('shop.col_sales')); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-light rounded">
                            <h3 class="mb-0" style="color: #0f766e;"><?php echo e($shop->branches_count); ?></h3>
                            <small class="text-muted"><?php echo e(__('shop.branches_count')); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card mt-4">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h5 class="content-card-title">
                    <i class="bi bi-diagram-3"></i>
                    <?php echo e(__('shop.branches')); ?>

                </h5>
                <a href="<?php echo e(route('branch.create', ['shop_id' => $shop->id])); ?>" class="btn btn-sm btn-shop-theme">
                    <i class="bi bi-plus-circle"></i> <?php echo e(__('shop.add_branch')); ?>

                </a>
            </div>
            <div class="p-4">
                <?php $__empty_1 = true; $__currentLoopData = $shop->branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="shop-branch-item mb-2 d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div>
                            <div class="fw-semibold"><?php echo e($branch->name); ?></div>
                            <div class="small text-muted"><?php echo e($branch->location ?: '-'); ?></div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge <?php echo e($branch->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                                <?php echo e($branch->is_active ? __('shop.active') : __('shop.inactive')); ?>

                            </span>
                            <a href="<?php echo e(route('branch.show', $branch->id)); ?>" class="btn btn-sm btn-outline-info"><?php echo e(__('app.view')); ?></a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="shop-products-empty">
                        <i class="bi bi-diagram-3"></i>
                        <strong><?php echo e(__('shop.no_branches')); ?></strong>
                        <p class="mb-0 small text-muted"><?php echo e(__('shop.no_branches_sub')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="col-md-8">
        <div class="content-card shop-products-card">
            <div class="content-card-header d-flex justify-content-between align-items-center">
                <h5 class="content-card-title">
                    <i class="bi bi-box-seam"></i>
                    <?php echo e(__('shop.recent_products')); ?>

                </h5>
                <a href="<?php echo e(route('product.create')); ?>?shop_id=<?php echo e($shop->id); ?>" class="btn btn-sm btn-shop-theme">
                    <i class="bi bi-plus-circle"></i> <?php echo e(__('shop.add_product')); ?>

                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom shop-products-table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('app.name')); ?></th>
                            <th><?php echo e(__('product.category')); ?></th>
                            <th><?php echo e(__('product.brand')); ?></th>
                            <th><?php echo e(__('product.purchase_price')); ?></th>
                            <th><?php echo e(__('product.sale_price')); ?></th>
                            <th><?php echo e(__('product.stock')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $shop->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($product->name); ?></strong></td>
                            <td><?php echo e($product->category); ?></td>
                            <td><?php echo e($product->brand); ?></td>
                            <td>৳<?php echo e(number_format($product->purchase_price, 2)); ?></td>
                            <td>৳<?php echo e(number_format($product->sale_price, 2)); ?></td>
                            <td>
                                <span class="badge <?php echo e($product->stock_quantity < 10 ? 'bg-danger' : 'bg-info'); ?>">
                                    <?php echo e($product->stock_quantity); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">
                                <div class="shop-products-empty">
                                    <i class="bi bi-box"></i>
                                    <strong><?php echo e(__('shop.no_products')); ?></strong>
                                    <p class="mb-0 small text-muted"><?php echo e(__('shop.add_product')); ?></p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Arpa\self_project\byabshaTrack\Modules/Shop\resources/views/show.blade.php ENDPATH**/ ?>