<?php $__env->startSection('title', __('damage.create_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1"><?php echo e(__('damage.create_title')); ?></h1>
            <p class="text-muted mb-0"><?php echo e(__('damage.create_subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('damage.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?php echo e(__('damage.back_to_list')); ?>

        </a>
    </div>

    <form method="POST" action="<?php echo e(route('damage.store')); ?>" id="damageForm">
        <?php echo csrf_field(); ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><strong><?php echo e(__('damage.summary')); ?></strong></div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label"><?php echo e(__('damage.shop')); ?> *</label>
                    <select class="form-select <?php $__errorArgs = ['shop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shop_id" name="shop_id" required>
                        <option value=""><?php echo e(__('damage.select_shop')); ?></option>
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
                <div class="col-md-4">
                    <label class="form-label"><?php echo e(__('damage.damage_date')); ?> *</label>
                    <input type="date" class="form-control <?php $__errorArgs = ['damage_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="damage_date" value="<?php echo e(old('damage_date', now()->toDateString())); ?>" required>
                    <?php $__errorArgs = ['damage_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo e(__('damage.note')); ?></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="note" value="<?php echo e(old('note')); ?>">
                    <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong><?php echo e(__('damage.line_items')); ?></strong>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addLineBtn"><?php echo e(__('damage.add_line')); ?></button>
            </div>
            <div class="card-body">
                <div id="lineItems"></div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-danger"><?php echo e(__('damage.record_damage')); ?></button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const batchesByShopUrl = <?php echo json_encode(route('damage.batches-by-shop'), 15, 512) ?>;
    const lineItemsWrap = document.getElementById('lineItems');
    const addLineBtn = document.getElementById('addLineBtn');
    const shopSelect = document.getElementById('shop_id');

    let lineIndex = 0;
    let availableBatches = [];

    function reasonOptions(selected = '') {
        const options = [
            { value: 'damaged', label: <?php echo json_encode(__('damage.reason_damaged'), 15, 512) ?> },
            { value: 'expired', label: <?php echo json_encode(__('damage.reason_expired'), 15, 512) ?> },
            { value: 'spoiled', label: <?php echo json_encode(__('damage.reason_spoiled'), 15, 512) ?> },
            { value: 'missing', label: <?php echo json_encode(__('damage.reason_missing'), 15, 512) ?> },
            { value: 'adjustment', label: <?php echo json_encode(__('damage.reason_adjustment'), 15, 512) ?> },
        ];

        return options.map((opt) => {
            const isSelected = String(opt.value) === String(selected) ? 'selected' : '';
            return `<option value="${opt.value}" ${isSelected}>${opt.label}</option>`;
        }).join('');
    }

    function batchOptions(selectedBatchId = '') {
        const first = `<option value="">${<?php echo json_encode(__('damage.select_batch'), 15, 512) ?>}</option>`;

        const rows = availableBatches.map((batch) => {
            const label = `${batch.product_name} | ${batch.batch_code} | ${batch.attribute_summary || '-'} | ${<?php echo json_encode(__('damage.available_stock'), 15, 512) ?>}: ${batch.stock_quantity}`;
            const selected = String(batch.id) === String(selectedBatchId) ? 'selected' : '';

            return `<option value="${batch.id}" data-product-id="${batch.product_id}" data-stock="${batch.stock_quantity}" data-purchase-price="${batch.purchase_price}" ${selected}>${label}</option>`;
        }).join('');

        return first + rows;
    }

    function addLine(defaults = {}) {
        const idx = lineIndex++;
        const row = document.createElement('div');
        row.className = 'row g-3 align-items-end border rounded p-3 mb-3 line-item';
        row.innerHTML = `
            <div class="col-lg-4">
                <label class="form-label">${<?php echo json_encode(__('damage.batch'), 15, 512) ?>} *</label>
                <select class="form-select batch-select" name="items[${idx}][product_batch_id]" required>
                    ${batchOptions(defaults.product_batch_id || '')}
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label">${<?php echo json_encode(__('damage.quantity'), 15, 512) ?>} *</label>
                <input type="number" min="1" class="form-control quantity-input" name="items[${idx}][quantity]" value="${defaults.quantity || 1}" required>
            </div>
            <div class="col-lg-2">
                <label class="form-label">${<?php echo json_encode(__('damage.purchase_price'), 15, 512) ?>}</label>
                <input type="text" class="form-control purchase-price-input" readonly>
            </div>
            <div class="col-lg-2">
                <label class="form-label">${<?php echo json_encode(__('damage.reason'), 15, 512) ?>}</label>
                <select class="form-select" name="items[${idx}][reason]">
                    ${reasonOptions(defaults.reason || 'damaged')}
                </select>
            </div>
            <div class="col-lg-2 text-end">
                <button type="button" class="btn btn-outline-danger remove-line-btn">${<?php echo json_encode(__('damage.remove_line'), 15, 512) ?>}</button>
            </div>
            <div class="col-12">
                <label class="form-label">${<?php echo json_encode(__('damage.reason_note'), 15, 512) ?>}</label>
                <input type="text" class="form-control" name="items[${idx}][reason_note]" value="${defaults.reason_note || ''}">
            </div>
            <input type="hidden" class="product-id-input" name="items[${idx}][product_id]" value="${defaults.product_id || ''}">
        `;

        const batchSelect = row.querySelector('.batch-select');
        const qtyInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.purchase-price-input');
        const productIdInput = row.querySelector('.product-id-input');

        function syncBatchMeta() {
            const selected = batchSelect.options[batchSelect.selectedIndex];
            const productId = selected ? selected.getAttribute('data-product-id') : '';
            const stock = selected ? parseInt(selected.getAttribute('data-stock') || '0', 10) : 0;
            const purchasePrice = selected ? parseFloat(selected.getAttribute('data-purchase-price') || '0') : 0;

            productIdInput.value = productId || '';
            qtyInput.max = stock > 0 ? String(stock) : '';
            priceInput.value = purchasePrice ? Number(purchasePrice).toFixed(2) : '';
        }

        batchSelect.addEventListener('change', syncBatchMeta);
        row.querySelector('.remove-line-btn').addEventListener('click', () => row.remove());

        lineItemsWrap.appendChild(row);
        syncBatchMeta();
    }

    async function loadBatches(shopId) {
        availableBatches = [];

        if (!shopId) {
            lineItemsWrap.innerHTML = '';
            return;
        }

        const response = await fetch(`${batchesByShopUrl}?shop_id=${encodeURIComponent(shopId)}`);
        availableBatches = await response.json();

        lineItemsWrap.querySelectorAll('.line-item').forEach((line) => line.remove());
        addLine();
    }

    shopSelect.addEventListener('change', (event) => {
        loadBatches(event.target.value).catch(() => {
            availableBatches = [];
            lineItemsWrap.innerHTML = '';
        });
    });

    addLineBtn.addEventListener('click', () => addLine());

    document.addEventListener('DOMContentLoaded', () => {
        const oldItems = <?php echo json_encode(old('items', []), 512) ?>;
        const selectedShop = shopSelect.value;

        if (!selectedShop) {
            addLine();
            return;
        }

        loadBatches(selectedShop)
            .then(() => {
                lineItemsWrap.innerHTML = '';

                if (Array.isArray(oldItems) && oldItems.length > 0) {
                    oldItems.forEach((item) => addLine(item));
                } else {
                    addLine();
                }
            })
            .catch(() => {
                addLine();
            });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Arpa\self_project\byabshaTrack\Modules/Damage\resources/views/create.blade.php ENDPATH**/ ?>