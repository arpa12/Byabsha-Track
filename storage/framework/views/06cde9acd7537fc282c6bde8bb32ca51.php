<?php $__env->startSection('title', __('sale.title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .shop-switcher .btn {
        border-radius: 999px;
        font-weight: 700;
    }

    .shop-switcher .btn.active {
        background: #0f766e;
        color: #fff;
        border-color: #0f766e;
    }

    .product-table-wrap {
        background: #fff;
        border: 1px solid #d8e4ee;
        border-radius: 16px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    #shopProductsTable thead th {
        white-space: nowrap;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .profit-positive {
        color: #047857;
        font-weight: 700;
    }

    .profit-negative {
        color: #b91c1c;
        font-weight: 700;
    }

    .profit-neutral {
        color: #475569;
        font-weight: 700;
    }

    .stock-hint {
        font-size: 0.86rem;
        color: #64748b;
    }

    .attribute-list {
        display: grid;
        gap: 0.2rem;
        line-height: 1.35;
        word-break: break-word;
    }

    .attribute-item-label {
        font-weight: 700;
        color: #0f172a;
    }

    .attribute-item-value {
        color: #334155;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="sale-shell">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 sale-header">
        <div>
            <span class="sale-kicker"><i class="bi bi-receipt-cutoff"></i><?php echo e(__('sale.title')); ?></span>
            <h1 class="page-title display-font"><?php echo e(__('sale.shop_products_title')); ?></h1>
            <p class="page-subtitle mb-0"><?php echo e(__('sale.shop_products_subtitle')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('sale.warranties.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-shield-check"></i> <?php echo e(__('sale.warranty_title')); ?>

            </a>
            <a href="<?php echo e(route('sale.exchanges.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left-right"></i> <?php echo e(__('sale.exchange_title')); ?>

            </a>
            <a href="<?php echo e(route('sale.create')); ?>" class="btn-new-sale">
                <i class="bi bi-plus-circle"></i> <?php echo e(__('sale.new_sale')); ?>

            </a>
        </div>
    </div>

    <div class="content-card mb-3 p-3">
        <div class="d-flex flex-wrap align-items-center gap-2 shop-switcher" id="shopSwitcher">
            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button
                    type="button"
                    class="btn btn-outline-secondary btn-sm <?php echo e((int) $selectedShopId === (int) $shop->id ? 'active' : ''); ?>"
                    data-shop-id="<?php echo e($shop->id); ?>"
                    data-shop-name="<?php echo e($shop->name); ?>"
                >
                    <?php echo e($shop->name); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php if($shops->isEmpty()): ?>
            <div class="text-muted small"><?php echo e(__('sale.no_shops_found')); ?></div>
        <?php endif; ?>
    </div>

    <div class="product-table-wrap p-3">
        <table id="shopProductsTable" class="table table-striped table-bordered mb-0" style="width:100%">
            <thead>
                <tr>
                    <th><?php echo e(__('sale.table_product_name')); ?></th>
                    <th><?php echo e(__('sale.table_batch')); ?></th>
                    <th><?php echo e(__('sale.table_attributes')); ?></th>
                    <th><?php echo e(__('sale.table_buying_price')); ?></th>
                    <th><?php echo e(__('sale.table_category')); ?></th>
                    <th><?php echo e(__('sale.table_stock')); ?></th>
                    <th><?php echo e(__('sale.table_profit')); ?></th>
                    <th><?php echo e(__('sale.table_loss')); ?></th>
                    <th><?php echo e(__('sale.col_actions')); ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="quickSaleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('sale.quick_sale_title')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickSaleForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" id="qsShopId" name="shop_id">
                    <input type="hidden" id="qsProductId" name="product_id">
                    <input type="hidden" id="qsProductBatchId" name="product_batch_id">

                    <div class="mb-2">
                        <strong id="qsProductName"></strong>
                    </div>
                    <div class="mb-2 stock-hint" id="qsBatchHint"></div>
                    <div class="mb-2 stock-hint" id="qsAttributeHint"></div>
                    <div class="mb-3 stock-hint" id="qsStockHint"></div>

                    <div class="mb-3 border rounded p-3 bg-white">
                        <div class="small text-uppercase fw-bold text-muted mb-2"><?php echo e(__('sale.table_attributes')); ?></div>
                        <div id="qsAttributeDetails" class="small"></div>
                    </div>

                    <div class="mb-3">
                        <label for="qsSalePrice" class="form-label"><?php echo e(__('sale.quick_sale_price')); ?></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="qsSalePrice" name="sale_price" required>
                    </div>

                    <div class="mb-3">
                        <label for="qsDiscount" class="form-label"><?php echo e(__('sale.quick_sale_discount')); ?></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="qsDiscount" name="discount" value="0">
                        <div class="form-text"><?php echo e(__('sale.quick_sale_discount_hint')); ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="qsQuantity" class="form-label"><?php echo e(__('sale.quick_sale_quantity')); ?></label>
                        <input type="number" min="1" class="form-control" id="qsQuantity" name="quantity" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="qsCustomerName" class="form-label"><?php echo e(__('sale.quick_sale_customer_name')); ?></label>
                        <input type="text" class="form-control" id="qsCustomerName" name="customer_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="qsCustomerPhone" class="form-label"><?php echo e(__('sale.quick_sale_customer_phone')); ?></label>
                        <input type="text" class="form-control" id="qsCustomerPhone" name="customer_phone">
                    </div>

                    <div class="mb-3">
                        <label for="qsCustomerAddress" class="form-label"><?php echo e(__('sale.quick_sale_customer_address')); ?></label>
                        <input type="text" class="form-control" id="qsCustomerAddress" name="customer_address">
                    </div>

                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between">
                            <span><?php echo e(__('sale.quick_sale_effective_unit_price')); ?></span>
                            <strong id="qsEffectiveUnitPrice">0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><?php echo e(__('sale.quick_sale_total_amount')); ?></span>
                            <strong id="qsTotalAmount">0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span><?php echo e(__('sale.quick_sale_total_cost')); ?></span>
                            <strong id="qsTotalCost">0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span><?php echo e(__('sale.quick_sale_profit_loss')); ?></span>
                            <strong id="qsProfitLoss" class="profit-neutral">0.00</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="qsSaleDate" class="form-label"><?php echo e(__('sale.sale_date_label')); ?></label>
                        <input type="date" class="form-control" id="qsSaleDate" name="sale_date" value="<?php echo e(now()->toDateString()); ?>">
                    </div>

                    <div class="alert alert-info d-none" id="qsServicePreview">
                        <div class="small text-uppercase fw-bold mb-1"><?php echo e(__('sale.quick_sale_free_service_preview')); ?></div>
                        <div><strong><?php echo e(__('sale.free_service_start')); ?>:</strong> <span id="qsServiceStart">-</span></div>
                        <div><strong><?php echo e(__('sale.free_service_expiry')); ?>:</strong> <span id="qsServiceExpiry">-</span></div>
                        <div><strong><?php echo e(__('sale.warranty_period')); ?>:</strong> <span id="qsServiceDuration">-</span></div>
                        <div class="mt-1" id="qsServiceTermsWrap">
                            <strong><?php echo e(__('sale.warranty_terms')); ?>:</strong> <span id="qsServiceTerms">-</span>
                        </div>
                    </div>

                    <div class="alert alert-danger d-none" id="quickSaleError"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('app.cancel')); ?></button>
                    <button type="submit" class="btn btn-success" id="quickSaleSubmitBtn">
                        <i class="bi bi-check-circle"></i> <?php echo e(__('sale.quick_sale_submit')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    let selectedShopId = <?php echo json_encode($selectedShopId, 15, 512) ?>;
    const productsTableUrl = <?php echo json_encode(route('sale.products-table'), 15, 512) ?>;
    const quickSaleUrl = <?php echo json_encode(route('sale.quick-sale'), 15, 512) ?>;
    const quickSaleModalEl = document.getElementById('quickSaleModal');
    const quickSaleModal = new bootstrap.Modal(quickSaleModalEl);
    const qsSalePriceEl = document.getElementById('qsSalePrice');
    const qsDiscountEl = document.getElementById('qsDiscount');
    const qsQuantityEl = document.getElementById('qsQuantity');
    const qsEffectiveUnitPriceEl = document.getElementById('qsEffectiveUnitPrice');
    const qsTotalAmountEl = document.getElementById('qsTotalAmount');
    const qsTotalCostEl = document.getElementById('qsTotalCost');
    const qsProfitLossEl = document.getElementById('qsProfitLoss');
    const qsBatchHintEl = document.getElementById('qsBatchHint');
    const qsAttributeHintEl = document.getElementById('qsAttributeHint');
    const qsAttributeDetailsEl = document.getElementById('qsAttributeDetails');
    const qsSaleDateEl = document.getElementById('qsSaleDate');
    const qsServicePreviewEl = document.getElementById('qsServicePreview');
    const qsServiceStartEl = document.getElementById('qsServiceStart');
    const qsServiceExpiryEl = document.getElementById('qsServiceExpiry');
    const qsServiceDurationEl = document.getElementById('qsServiceDuration');
    const qsServiceTermsWrapEl = document.getElementById('qsServiceTermsWrap');
    const qsServiceTermsEl = document.getElementById('qsServiceTerms');
    let currentPurchasePrice = 0;
    let currentFreeService = {
        enabled: false,
        durationValue: null,
        durationUnit: null,
        terms: '',
    };

    const durationUnitLabels = {
        day: <?php echo json_encode(__('sale.duration_unit_day'), 15, 512) ?>,
        month: <?php echo json_encode(__('sale.duration_unit_month'), 15, 512) ?>,
        year: <?php echo json_encode(__('sale.duration_unit_year'), 15, 512) ?>,
    };

    const numberFormatter = new Intl.NumberFormat(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderAttributeList(attributes) {
        const items = Array.isArray(attributes) ? attributes : [];
        const visibleItems = items.filter((item) => item && typeof item === 'object' && String(item.value ?? '').trim() !== '');

        if (!visibleItems.length) {
            return '<span class="text-muted">-</span>';
        }

        return '<div class="attribute-list">' + visibleItems.map((item) => {
            const label = escapeHtml(item.label || item.field_key || 'Attribute');
            const value = escapeHtml(item.value || '');
            return '<div><span class="attribute-item-label">' + label + ':</span> <span class="attribute-item-value">' + value + '</span></div>';
        }).join('') + '</div>';
    }

    const table = $('#shopProductsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: productsTableUrl,
            data: function (d) {
                d.shop_id = selectedShopId;
            }
        },
        columns: [
            { data: 'name', name: 'products.name' },
            { data: 'batch_label', name: 'product_batches.batch_code' },
            {
                data: 'attribute_values',
                name: 'attribute_summary',
                orderable: false,
                render: function (data, type, row) {
                    if (type !== 'display') {
                        return row.attribute_summary || '-';
                    }

                    return renderAttributeList(data);
                }
            },
            { data: 'purchase_price', name: 'product_batches.purchase_price', className: 'text-end' },
            { data: 'category_name', name: 'products.category' },
            { data: 'stock_quantity', name: 'product_batches.remaining_quantity', className: 'text-end' },
            {
                data: 'latest_profit',
                name: 'latest_profit',
                className: 'text-end',
                render: function (data, type) {
                    const value = parseFloat(data || 0);
                    if (type !== 'display') {
                        return value;
                    }
                    const klass = value > 0 ? 'profit-positive' : (value < 0 ? 'profit-negative' : 'profit-neutral');
                    return '<span class="' + klass + '">' + numberFormatter.format(value) + '</span>';
                }
            },
            {
                data: 'latest_loss',
                name: 'latest_loss',
                className: 'text-end',
                orderable: false,
                searchable: false,
                render: function (data, type) {
                    const value = parseFloat(data || 0);
                    if (type !== 'display') {
                        return value;
                    }
                    return '<span class="' + (value > 0 ? 'profit-negative' : 'profit-neutral') + '">' + numberFormatter.format(value) + '</span>';
                }
            },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
        ],
        order: [[0, 'asc']],
    });

    function recalculateQuickSalePreview() {
        const salePrice = parseFloat(qsSalePriceEl.value || '0');
        const discount = parseFloat(qsDiscountEl.value || '0');
        const quantity = parseInt(qsQuantityEl.value || '0', 10);
        const effectiveUnitPrice = Math.max(salePrice - (isNaN(discount) ? 0 : discount), 0);
        const totalAmount = effectiveUnitPrice * quantity;
        const totalCost = currentPurchasePrice * quantity;
        const profitLoss = totalAmount - totalCost;

        qsEffectiveUnitPriceEl.textContent = numberFormatter.format(isNaN(effectiveUnitPrice) ? 0 : effectiveUnitPrice);
        qsTotalAmountEl.textContent = numberFormatter.format(isNaN(totalAmount) ? 0 : totalAmount);
        qsTotalCostEl.textContent = numberFormatter.format(isNaN(totalCost) ? 0 : totalCost);
        qsProfitLossEl.textContent = numberFormatter.format(isNaN(profitLoss) ? 0 : profitLoss);

        qsProfitLossEl.classList.remove('profit-positive', 'profit-negative', 'profit-neutral');
        if (profitLoss > 0) {
            qsProfitLossEl.classList.add('profit-positive');
        } else if (profitLoss < 0) {
            qsProfitLossEl.classList.add('profit-negative');
        } else {
            qsProfitLossEl.classList.add('profit-neutral');
        }

        recalculateServicePreview();
    }

    function formatDateISO(dateObj) {
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    function recalculateServicePreview() {
        if (!currentFreeService.enabled || !currentFreeService.durationValue || !currentFreeService.durationUnit) {
            qsServicePreviewEl.classList.add('d-none');
            return;
        }

        const rawSaleDate = qsSaleDateEl.value;
        const baseDate = rawSaleDate ? new Date(rawSaleDate + 'T00:00:00') : new Date();
        if (Number.isNaN(baseDate.getTime())) {
            qsServicePreviewEl.classList.add('d-none');
            return;
        }

        const expiryDate = new Date(baseDate.getTime());
        const durationValue = parseInt(currentFreeService.durationValue, 10);
        if (!Number.isFinite(durationValue) || durationValue <= 0) {
            qsServicePreviewEl.classList.add('d-none');
            return;
        }

        if (currentFreeService.durationUnit === 'day') {
            expiryDate.setDate(expiryDate.getDate() + durationValue);
        } else if (currentFreeService.durationUnit === 'month') {
            expiryDate.setMonth(expiryDate.getMonth() + durationValue);
        } else if (currentFreeService.durationUnit === 'year') {
            expiryDate.setFullYear(expiryDate.getFullYear() + durationValue);
        }

        const unitLabel = durationUnitLabels[currentFreeService.durationUnit] || currentFreeService.durationUnit;
        qsServiceStartEl.textContent = formatDateISO(baseDate);
        qsServiceExpiryEl.textContent = formatDateISO(expiryDate);
        qsServiceDurationEl.textContent = String(durationValue) + ' ' + unitLabel;

        if (currentFreeService.terms) {
            qsServiceTermsEl.textContent = currentFreeService.terms;
            qsServiceTermsWrapEl.classList.remove('d-none');
        } else {
            qsServiceTermsEl.textContent = '-';
            qsServiceTermsWrapEl.classList.add('d-none');
        }

        qsServicePreviewEl.classList.remove('d-none');
    }

    document.getElementById('shopSwitcher').addEventListener('click', function (event) {
        const button = event.target.closest('button[data-shop-id]');
        if (!button) {
            return;
        }

        selectedShopId = parseInt(button.dataset.shopId, 10);

        document.querySelectorAll('#shopSwitcher button[data-shop-id]').forEach((item) => {
            item.classList.remove('active');
        });
        button.classList.add('active');

        table.ajax.reload();
    });

    document.addEventListener('click', function (event) {
        const saleBtn = event.target.closest('.js-sale-btn');
        if (!saleBtn) {
            return;
        }

        const stock = parseInt(saleBtn.dataset.stock || '0', 10);
        currentPurchasePrice = parseFloat(saleBtn.dataset.purchasePrice || '0');
        const defaultSalePrice = currentPurchasePrice;
        let attributeValues = [];

        try {
            attributeValues = saleBtn.dataset.attributeValues ? JSON.parse(saleBtn.dataset.attributeValues) : [];
        } catch (error) {
            attributeValues = [];
        }

        document.getElementById('qsShopId').value = saleBtn.dataset.shopId;
        document.getElementById('qsProductId').value = saleBtn.dataset.productId;
        document.getElementById('qsProductBatchId').value = saleBtn.dataset.batchId;
        document.getElementById('qsProductName').textContent = saleBtn.dataset.productName;
        qsBatchHintEl.textContent = 'Batch: ' + (saleBtn.dataset.batchCode || '-');
        qsAttributeHintEl.textContent = '<?php echo e(__('sale.table_attributes')); ?>: ' + (saleBtn.dataset.attributeSummary || '-');
        document.getElementById('qsStockHint').textContent = '<?php echo e(__('sale.available_stock')); ?>: ' + stock;
        document.getElementById('qsQuantity').max = stock;
        document.getElementById('qsQuantity').value = 1;
        document.getElementById('qsSalePrice').value = Number.isFinite(defaultSalePrice) ? defaultSalePrice : 0;
        document.getElementById('qsDiscount').value = 0;
        document.getElementById('qsSaleDate').value = '<?php echo e(now()->toDateString()); ?>';
        document.getElementById('qsCustomerName').value = '';
        document.getElementById('qsCustomerPhone').value = '';
        document.getElementById('qsCustomerAddress').value = '';
        document.getElementById('quickSaleError').classList.add('d-none');
        document.getElementById('quickSaleError').textContent = '';

        if (qsAttributeDetailsEl) {
            qsAttributeDetailsEl.innerHTML = renderAttributeList(attributeValues);
        }

        currentFreeService = {
            enabled: parseInt(saleBtn.dataset.hasFreeService || '0', 10) === 1,
            durationValue: saleBtn.dataset.freeServiceDurationValue || null,
            durationUnit: saleBtn.dataset.freeServiceDurationUnit || null,
            terms: saleBtn.dataset.freeServiceTerms || '',
        };

        recalculateQuickSalePreview();

        quickSaleModal.show();
    });

    qsSalePriceEl.addEventListener('input', recalculateQuickSalePreview);
    qsDiscountEl.addEventListener('input', recalculateQuickSalePreview);
    qsQuantityEl.addEventListener('input', recalculateQuickSalePreview);
    qsSaleDateEl.addEventListener('input', recalculateServicePreview);

    document.getElementById('quickSaleForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const submitBtn = document.getElementById('quickSaleSubmitBtn');
        const errorEl = document.getElementById('quickSaleError');
        const formData = new FormData(event.target);

        submitBtn.disabled = true;
        errorEl.classList.add('d-none');
        errorEl.textContent = '';

        try {
            const response = await fetch(quickSaleUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const payload = await response.json();

            if (!response.ok) {
                const message = payload.message || '<?php echo e(__('app.validation_error')); ?>';
                throw new Error(message);
            }

            quickSaleModal.hide();
            table.ajax.reload(null, false);
        } catch (error) {
            errorEl.textContent = error.message;
            errorEl.classList.remove('d-none');
        } finally {
            submitBtn.disabled = false;
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Arpa\self_project\byabshaTrack\Modules/Sale\resources/views/index.blade.php ENDPATH**/ ?>