<?php $__env->startSection('title', __('sale.exchange_create_btn')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --sale-ink-900: #0f172a;
        --sale-ink-700: #334155;
        --sale-ink-500: #64748b;
        --sale-brand: #0f766e;
        --sale-brand-deep: #155e75;
        --sale-line: #d8e4ee;
    }

    .sale-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--sale-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.02em;
    }

    .sale-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(15, 118, 110, 0.08);
        color: var(--sale-brand);
        border: 1px solid rgba(15, 118, 110, 0.18);
        border-radius: 999px;
        padding: 0.3rem 0.8rem;
        font-size: 0.74rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-title {
        font-size: clamp(1.4rem, 2.5vw, 1.85rem);
        font-weight: 800;
        color: var(--sale-ink-900);
    }

    .page-subtitle {
        color: var(--sale-ink-500);
        font-size: 0.88rem;
    }

    .card-premium {
        background: #ffffff;
        border: 1px solid var(--sale-line);
        border-radius: 16px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
    }

    .form-label-custom {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--sale-ink-700);
        margin-bottom: 0.4rem;
    }

    .form-control-custom, .form-select-custom {
        border-radius: 10px !important;
        border: 1px solid #cedce9 !important;
        background-color: #f8fafc !important;
        font-size: 0.88rem !important;
        padding: 0.48rem 0.95rem !important;
        color: var(--sale-ink-900) !important;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        background-color: #ffffff !important;
        border-color: var(--sale-brand) !important;
        box-shadow: 0 0 0 0.15rem rgba(15, 118, 110, 0.15) !important;
    }

    .form-control-custom.is-invalid, .form-select-custom.is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff8f8 !important;
    }

    .btn-submit-premium {
        background: linear-gradient(140deg, var(--sale-brand), var(--sale-brand-deep));
        color: #fff;
        border: 0;
        border-radius: 12px;
        padding: 0.55rem 1.4rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 8px 20px rgba(15, 118, 110, 0.2);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-submit-premium:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.28);
    }

    .btn-back-custom {
        border-radius: 10px;
        padding: 0.48rem 0.95rem;
        font-weight: 600;
        font-size: 0.84rem;
        border: 1px solid var(--sale-line);
        background: #ffffff;
        color: var(--sale-ink-700);
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-back-custom:hover {
        background: #f8fafc;
        color: var(--sale-brand);
        border-color: #cbd5e1;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="sale-shell">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <span class="sale-kicker"><i class="bi bi-arrow-left-right"></i> <?php echo e(__('sale.title')); ?></span>
            <h1 class="page-title display-font mb-1"><i class="bi bi-plus-circle"></i> <?php echo e(__('sale.exchange_create_btn')); ?></h1>
            <p class="page-subtitle mb-0"><?php echo e(__('sale.exchange_create_subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('sale.exchanges.index')); ?>" class="btn-back-custom">
            <i class="bi bi-arrow-left"></i> <?php echo e(__('sale.back_to_list')); ?>

        </a>
    </div>

    
    <div class="alert border-0 rounded-3 mb-4 p-3 d-flex align-items-start gap-3" style="background: rgba(15, 118, 110, 0.05); border: 1px solid rgba(15, 118, 110, 0.15) !important;">
        <i class="bi bi-info-circle-fill text-teal" style="font-size: 1.2rem; color: var(--sale-brand);"></i>
        <div>
            <strong style="color: var(--sale-ink-900); font-size: 0.88rem;"><?php echo e(__('sale.why_exchange_title')); ?></strong>
            <p class="mb-0 text-muted mt-1" style="font-size: 0.82rem; line-height: 1.45;">
                <?php echo e(__('sale.why_exchange_desc')); ?>

            </p>
        </div>
    </div>

    <div class="card card-premium border-0">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo e(route('sale.exchanges.store')); ?>" class="row g-4">
                <?php echo csrf_field(); ?>

                <div class="col-md-4">
                    <label class="form-label form-label-custom"><?php echo e(__('sale.shop')); ?></label>
                    <select name="shop_id" id="shop_id" class="form-select form-select-custom <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value=""><?php echo e(__('sale.select_shop')); ?></option>
                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($shop->id); ?>" <?php echo e(old('shop_id') == $shop->id ? 'selected' : ''); ?>><?php echo e($shop->name); ?></option>
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

                <div class="col-md-8">
                    <label class="form-label form-label-custom"><?php echo e(__('sale.sale_reference')); ?></label>
                    <select name="sale_id" id="sale_id" class="form-select form-select-custom <?php $__errorArgs = ['sale_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value=""><?php echo e(__('sale.select_sale')); ?></option>
                        <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sale->id); ?>" data-shop-id="<?php echo e($sale->shop_id); ?>" <?php echo e(old('sale_id') == $sale->id ? 'selected' : ''); ?>>
                                #<?php echo e($sale->id); ?> | <?php echo e($sale->shop?->name); ?> | <?php echo e($sale->product?->name); ?> | <?php echo e($sale->sale_date?->format('d M Y')); ?> | <?php echo e(__('sale.quantity')); ?>: <?php echo e($sale->quantity); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['sale_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label form-label-custom"><?php echo e(__('sale.exchange_type')); ?></label>
                    <select name="exchange_type" class="form-select form-select-custom <?php $__errorArgs = ['exchange_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="replacement" <?php echo e(old('exchange_type', 'replacement') === 'replacement' ? 'selected' : ''); ?>><?php echo e(__('sale.exchange_type_replacement')); ?></option>
                        <option value="return_only" <?php echo e(old('exchange_type') === 'return_only' ? 'selected' : ''); ?>><?php echo e(__('sale.exchange_type_return_only')); ?></option>
                    </select>
                    <?php $__errorArgs = ['exchange_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label form-label-custom"><?php echo e(__('sale.quantity')); ?></label>
                    <input type="number" name="quantity" min="1" value="<?php echo e(old('quantity', 1)); ?>" class="form-control form-control-custom <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label form-label-custom"><?php echo e(__('sale.exchange_date')); ?></label>
                    <input type="date" name="exchange_date" value="<?php echo e(old('exchange_date', now()->toDateString())); ?>" class="form-control form-control-custom <?php $__errorArgs = ['exchange_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['exchange_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-3">
                    <label class="form-label form-label-custom"><?php echo e(__('sale.reason')); ?></label>
                    <input type="text" name="reason" value="<?php echo e(old('reason', 'defective')); ?>" class="form-control form-control-custom <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-12">
                    <label class="form-label form-label-custom"><?php echo e(__('sale.replacement_batch_optional')); ?></label>
                    <select name="replacement_batch_id" id="replacement_batch_id" class="form-select form-select-custom <?php $__errorArgs = ['replacement_batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value=""><?php echo e(__('sale.no_replacement_batch')); ?></option>
                        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($batch->id); ?>" data-shop-id="<?php echo e($batch->shop_id); ?>" <?php echo e(old('replacement_batch_id') == $batch->id ? 'selected' : ''); ?>>
                                <?php echo e($batch->shop?->name ?? ''); ?> | <?php echo e($batch->product?->name ?? '-'); ?> | <?php echo e($batch->batch_code); ?> | <?php echo e(__('sale.available_stock')); ?>: <?php echo e($batch->remaining_quantity); ?> | <?php echo e(__('sale.col_sale_price')); ?>: <?php echo e(number_format((float)$batch->purchase_price, 2)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['replacement_batch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-12">
                    <label class="form-label form-label-custom"><?php echo e(__('sale.note')); ?></label>
                    <textarea name="note" rows="3" class="form-control form-control-custom <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('note')); ?></textarea>
                    <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-12 mt-4 pt-2">
                    <button type="submit" class="btn btn-submit-premium">
                        <i class="bi bi-check-circle"></i> <?php echo e(__('sale.exchange_create_btn')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const shopSelect = document.getElementById('shop_id');
    const saleSelect = document.getElementById('sale_id');
    const batchSelect = document.getElementById('replacement_batch_id');
    
    if (shopSelect) {
        let originalSaleOptions = [];
        if (saleSelect) {
            originalSaleOptions = Array.from(saleSelect.options).slice(1);
        }
        
        let originalBatchOptions = [];
        if (batchSelect) {
            originalBatchOptions = Array.from(batchSelect.options).slice(1);
        }
        
        function filterShopDependencies() {
            const selectedShopId = shopSelect.value;
            
            // 1. Filter Sales Reference dropdown
            if (saleSelect) {
                const currentSelectedSale = saleSelect.value;
                saleSelect.innerHTML = '';
                
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = "<?php echo e(__('sale.select_sale')); ?>";
                saleSelect.appendChild(placeholder);
                
                let saleRestored = false;
                originalSaleOptions.forEach(option => {
                    const shopId = option.getAttribute('data-shop-id');
                    if (!selectedShopId || shopId === selectedShopId) {
                        const newOpt = option.cloneNode(true);
                        saleSelect.appendChild(newOpt);
                        if (newOpt.value === currentSelectedSale) {
                            newOpt.selected = true;
                            saleRestored = true;
                        }
                    }
                });
                
                if (!saleRestored) {
                    saleSelect.value = '';
                }
            }
            
            // 2. Filter Replacement Batch dropdown
            if (batchSelect) {
                const currentSelectedBatch = batchSelect.value;
                batchSelect.innerHTML = '';
                
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = "<?php echo e(__('sale.no_replacement_batch')); ?>";
                batchSelect.appendChild(placeholder);
                
                let batchRestored = false;
                originalBatchOptions.forEach(option => {
                    const shopId = option.getAttribute('data-shop-id');
                    if (!selectedShopId || shopId === selectedShopId) {
                        const newOpt = option.cloneNode(true);
                        batchSelect.appendChild(newOpt);
                        if (newOpt.value === currentSelectedBatch) {
                            newOpt.selected = true;
                            batchRestored = true;
                        }
                    }
                });
                
                if (!batchRestored) {
                    batchSelect.value = '';
                }
            }
        }
        
        shopSelect.addEventListener('change', filterShopDependencies);
        
        // Trigger on load if there's an old value selected
        if (shopSelect.value) {
            filterShopDependencies();
        }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\personal\Byabsha-Track\Modules/Sale\resources/views/exchanges/create.blade.php ENDPATH**/ ?>