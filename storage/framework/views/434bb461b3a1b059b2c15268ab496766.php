<?php $__env->startSection('title', __('report.sales_report')); ?>

<?php $__env->startSection('content'); ?>
<div class="report-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-5 mb-8 report-header">
        <div>
            <span class="report-kicker"><i class="bi bi-graph-up-arrow"></i> <?php echo e(__('app.analytics')); ?></span>
            <h1 class="dashboard-title display-font text-3xl font-black text-slate-900 leading-none mb-1"><?php echo e(__('report.sales_report')); ?></h1>
            <p class="dashboard-subtitle text-slate-500 text-sm mt-1.5"><?php echo e(__('report.sales_report_subtitle')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('report.export.sales-pdf', request()->query())); ?>" class="btn-brand-custom" style="background: linear-gradient(140deg, #ef4444, #b91c1c); box-shadow: 0 14px 28px rgba(239, 68, 68, 0.28);">
                <i class="bi bi-file-earmark-pdf"></i> <?php echo e(__('report.download_pdf')); ?>

            </a>
            <button onclick="window.print()" class="btn-secondary-custom">
                <i class="bi bi-printer"></i> <?php echo e(__('report.print')); ?>

            </button>
            <a href="<?php echo e(route('report.index')); ?>" class="btn-secondary-custom">
                <i class="bi bi-arrow-left"></i> <?php echo e(__('report.back_to_reports')); ?>

            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card mb-4">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-funnel"></i>
                <?php echo e(__('report.filters')); ?>

            </h5>
        </div>
        <div class="p-4">
            <form action="<?php echo e(route('report.sales')); ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="shop_id" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.shop')); ?></label>
                        <select class="form-select" id="shop_id" name="shop_id" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                            <option value=""><?php echo e(__('report.all_shops')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e($filters['shop_id'] == $shop->id ? 'selected' : ''); ?>>
                                    <?php echo e($shop->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.start_date')); ?></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e($filters['start_date']); ?>" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.end_date')); ?></label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e($filters['end_date']); ?>" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="background-color: #0f766e; border-color: #0f766e; border-radius: 10px; height: 38px;">
                            <i class="bi bi-search"></i> <?php echo e(__('report.apply_filters')); ?>

                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #64748b, #475569);">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.total_sales')); ?></div>
                <div class="stat-value"><?php echo e($salesSummary->total_transactions ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #0f766e, #0d5969);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.total_revenue')); ?></div>
                <div class="stat-value text-teal" style="color: #0f766e !important;"><?php echo e(currency_symbol()); ?><?php echo e(number_format($salesSummary->total_revenue ?? 0, 2)); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #10b981, #047857);">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.total_profit')); ?></div>
                <div class="stat-value text-success"><?php echo e(currency_symbol()); ?><?php echo e(number_format($salesSummary->total_profit ?? 0, 2)); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #f59e0b, #d97706);">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.items_sold')); ?></div>
                <div class="stat-value text-warning"><?php echo e(number_format($salesSummary->total_quantity_sold ?? 0)); ?></div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-receipt"></i>
                <?php echo e(__('report.sales_transactions')); ?>

            </h5>
        </div>
        <?php if($sales->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?php echo e(__('report.shop')); ?></th>
                        <th><?php echo e(__('report.product_name')); ?></th>
                        <th><?php echo e(__('report.sale_date')); ?></th>
                        <th class="text-center"><?php echo e(__('report.quantity')); ?></th>
                        <th class="text-end"><?php echo e(__('report.sale_price')); ?></th>
                        <th class="text-end"><?php echo e(__('report.total_amount')); ?></th>
                        <th class="text-end"><?php echo e(__('report.profit')); ?></th>
                        <th class="text-center"><?php echo e(__('report.action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><span class="badge bg-light text-dark border"><?php echo e($sale->shop->name); ?></span></td>
                        <td><strong><?php echo e($sale->product->name ?? '-'); ?></strong></td>
                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-calendar3"></i>
                                <?php echo e($sale->sale_date->format('M d, Y')); ?>

                            </span>
                        </td>
                        <td class="text-center fw-bold"><?php echo e($sale->quantity); ?></td>
                        <td class="text-end fw-semibold"><?php echo e(currency_symbol()); ?><?php echo e(number_format($sale->sale_price, 2)); ?></td>
                        <td class="text-end fw-bold"><?php echo e(currency_symbol()); ?><?php echo e(number_format($sale->total_amount, 2)); ?></td>
                        <td class="text-end text-success fw-bold"><?php echo e(currency_symbol()); ?><?php echo e(number_format($sale->profit, 2)); ?></td>
                        <td class="text-center">
                            <?php
                                $saleData = [
                                    'id' => $sale->id,
                                    'shop_name' => $sale->shop->name ?? '-',
                                    'product_name' => $sale->product->name ?? '-',
                                    'sale_date' => $sale->sale_date->format('M d, Y'),
                                    'quantity' => $sale->quantity,
                                    'sale_price' => (float)$sale->sale_price,
                                    'purchase_price_per_unit' => (float)($sale->purchase_price_per_unit ?? 0),
                                    'discount' => (float)($sale->discount ?? 0),
                                    'total_amount' => (float)$sale->total_amount,
                                    'profit' => (float)$sale->profit,
                                    'customer_name' => $sale->customer_name ?: '-',
                                    'customer_phone' => $sale->customer_phone ?: '-',
                                    'customer_address' => $sale->customer_address ?: '-',
                                ];
                            ?>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#saleDetailsModal"
                                data-sale='<?php echo json_encode($saleData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG, 512) ?>'
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="3"><?php echo e(__('report.page_total')); ?></td>
                        <td class="text-center"><?php echo e($sales->sum('quantity')); ?></td>
                        <td class="text-end"></td>
                        <td class="text-end"><?php echo e(currency_symbol()); ?><?php echo e(number_format($sales->sum('total_amount'), 2)); ?></td>
                        <td class="text-end text-success"><?php echo e(currency_symbol()); ?><?php echo e(number_format($sales->sum('profit'), 2)); ?></td>
                        <td class="text-center">-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="p-3">
            <?php echo e($sales->links()); ?>

        </div>
        <?php else: ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
            <p class="mb-0"><?php echo e(__('report.no_sales_found')); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Sale Details Modal -->
<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content report-modal">
            <div class="modal-header">
                <div>
                    <p class="modal-kicker"><?php echo e(__('report.sale_details')); ?></p>
                    <h5 class="modal-title mb-0" id="saleDetailsModalTitle"><?php echo e(__('report.sale_details')); ?></h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Left Column: Customer & Transaction Info -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider"><?php echo e(__('report.customer_name')); ?></h6>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5"><?php echo e(__('report.customer_name')); ?></div>
                                    <div class="fw-bold fs-6" id="modalSaleCustomerName">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5"><?php echo e(__('report.customer_phone')); ?></div>
                                    <div class="fw-bold" id="modalSaleCustomerPhone">-</div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-xs text-muted mb-0.5"><?php echo e(__('report.customer_address')); ?></div>
                                    <div class="fw-bold text-wrap" id="modalSaleCustomerAddress">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Sale & Profit Breakdown -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Transaction Info</h6>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5"><?php echo e(__('report.shop')); ?></div>
                                    <div class="fw-bold" id="modalSaleShopName">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5"><?php echo e(__('report.product_name')); ?></div>
                                    <div class="fw-bold text-primary" id="modalSaleProductName">-</div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-xs text-muted mb-0.5">Sale Date</div>
                                    <div class="fw-bold" id="modalSaleDate">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width: Financial Breakdown -->
                    <div class="col-12">
                        <div class="card border-0 border-top border-slate-200 rounded-0 mt-2 pt-3">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Financial Breakdown</h6>
                            <div class="row text-center g-3">
                                <div class="col-6 col-md-3">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="text-xs text-muted">Quantity</div>
                                        <div class="fs-5 fw-bold" id="modalSaleQuantity">0</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="text-xs text-muted">Unit Sale Price</div>
                                        <div class="fs-5 fw-bold text-primary" id="modalSalePrice">0.00</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="text-xs text-muted">Discount</div>
                                        <div class="fs-5 fw-bold text-warning" id="modalSaleDiscount">0.00</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="text-xs text-muted">Total Paid</div>
                                        <div class="fs-5 fw-bold text-success" id="modalSaleTotal">0.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase and Profit Margin -->
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-light border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-xs text-muted">Unit Purchase Cost:</span>
                                <strong class="text-dark ms-1" id="modalSalePurchasePrice">0.00</strong>
                            </div>
                            <div class="text-end">
                                <span class="text-xs text-muted">Net Profit:</span>
                                <strong class="text-success fs-5 ms-1" id="modalSaleProfit">0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    :root {
        --report-ink-900: #0f172a;
        --report-ink-700: #334155;
        --report-ink-500: #64748b;
        --report-brand: var(--brand, #0f766e);
        --report-brand-deep: var(--brand-deep, #155e75);
        --report-line: #d8e4ee;
    }

    .report-shell {
        position: relative;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--report-ink-900);
    }

    .display-font {
        font-family: 'Space Grotesk', 'Segoe UI', sans-serif;
        letter-spacing: -0.03em;
    }

    .report-header {
        gap: 0.9rem;
    }

    .report-kicker {
        display: inline-flex;
        align-items: center;
        gap: 0.48rem;
        background: color-mix(in srgb, var(--report-brand) 12%, transparent);
        color: var(--report-brand);
        border: 1px solid color-mix(in srgb, var(--report-brand) 22%, transparent);
        border-radius: 999px;
        padding: 0.42rem 0.92rem;
        font-size: 0.76rem;
        font-weight: 700;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 18px color-mix(in srgb, var(--report-brand) 13%, transparent);
    }

    .page-title {
        font-size: clamp(1.55rem, 3.2vw, 2.3rem);
        line-height: 1.1;
        color: var(--report-ink-900);
        margin-bottom: 0.45rem;
        font-weight: 800;
    }

    .page-subtitle {
        color: var(--report-ink-700);
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid var(--report-line);
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .content-card-header {
        border-bottom: 1px solid var(--report-line);
        background: #f8fafc;
        padding: 0.85rem 1.2rem;
    }

    .content-card-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--report-ink-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .content-card-title i {
        color: var(--report-brand);
    }

    /* stat card style */
    .stat-card {
        background: #ffffff;
        border: 1px solid var(--report-line);
        border-radius: 18px;
        padding: 1.15rem;
        box-shadow: 0 11px 20px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 28px rgba(15, 23, 42, 0.1);
        border-color: #97b0c8;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 0.75rem;
    }

    .stat-label {
        color: var(--report-ink-500);
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--report-ink-900);
        line-height: 1.2;
    }

    /* button design matching plans */
    .btn-brand-custom {
        background: linear-gradient(140deg, var(--report-brand), var(--report-brand-deep));
        color: white;
        border: none;
        border-radius: 999px;
        padding: 0.52rem 1.1rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 10px 20px color-mix(in srgb, var(--report-brand) 22%, transparent);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-brand-custom:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 14px 24px color-mix(in srgb, var(--report-brand) 30%, transparent);
    }

    .btn-secondary-custom {
        border-radius: 999px;
        padding: 0.52rem 1.1rem;
        font-size: 0.84rem;
        font-weight: 700;
        border: 1px solid #9eb8cb;
        color: #1f3f58;
        background: #f7fbff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-secondary-custom:hover {
        color: #0f172a;
        border-color: #6f93b0;
        background: #ffffff;
    }

    /* table styling */
    .table thead th {
        background: #f8fafc !important;
        border-bottom: 1px solid var(--report-line);
        color: var(--report-ink-700);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.8rem 1rem;
        white-space: nowrap;
    }

    .table tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 0.65rem 1rem !important;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Report Modal details style */
    .report-modal {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
    }

    .report-modal .modal-header {
        background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
        border-bottom: 1px solid #dbe7f3;
    }

    .report-modal .modal-header .modal-kicker {
        margin-bottom: 0;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--report-brand);
        font-weight: 700;
    }

    @media print {
        .top-header,
        .sidebar,
        .sidebar-toggle,
        .content-card.mb-4:has(.bi-funnel),
        .alert,
        .btn,
        a.btn {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        body {
            background: white !important;
            font-size: 11px;
        }
        .content-card {
            border: none !important;
            box-shadow: none !important;
        }
        .table { font-size: 10px; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('saleDetailsModal');
        if (!modalEl) return;

        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const sale = JSON.parse(button.getAttribute('data-sale') || '{}');
            
            document.getElementById('modalSaleCustomerName').textContent = sale.customer_name || '-';
            document.getElementById('modalSaleCustomerPhone').textContent = sale.customer_phone || '-';
            document.getElementById('modalSaleCustomerAddress').textContent = sale.customer_address || '-';
            document.getElementById('modalSaleShopName').textContent = sale.shop_name || '-';
            document.getElementById('modalSaleProductName').textContent = sale.product_name || '-';
            document.getElementById('modalSaleDate').textContent = sale.sale_date || '-';
            document.getElementById('modalSaleQuantity').textContent = sale.quantity || '0';
            
            const currencySymbol = <?php echo json_encode(currency_symbol(), 15, 512) ?>;
            document.getElementById('modalSalePrice').textContent = currencySymbol + Number(sale.sale_price || 0).toFixed(2);
            document.getElementById('modalSaleDiscount').textContent = currencySymbol + Number(sale.discount || 0).toFixed(2);
            document.getElementById('modalSaleTotal').textContent = currencySymbol + Number(sale.total_amount || 0).toFixed(2);
            document.getElementById('modalSalePurchasePrice').textContent = currencySymbol + Number(sale.purchase_price_per_unit || 0).toFixed(2);
            
            const profitVal = Number(sale.profit || 0);
            const profitEl = document.getElementById('modalSaleProfit');
            profitEl.textContent = currencySymbol + profitVal.toFixed(2);
            if (profitVal >= 0) {
                profitEl.className = 'text-success fs-5 ms-1 fw-bold';
            } else {
                profitEl.className = 'text-danger fs-5 ms-1 fw-bold';
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\personal\Byabsha-Track\Modules/Report\resources/views/sales.blade.php ENDPATH**/ ?>