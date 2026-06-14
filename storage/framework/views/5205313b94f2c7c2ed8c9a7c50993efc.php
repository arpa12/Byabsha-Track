<?php $__env->startSection('title', __('report.exchanges_report')); ?>

<?php $__env->startSection('content'); ?>
<div class="report-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-5 mb-8 report-header">
        <div>
            <span class="report-kicker"><i class="bi bi-arrow-left-right"></i> <?php echo e(__('app.analytics')); ?></span>
            <h1 class="dashboard-title display-font text-3xl font-black text-slate-900 leading-none mb-1"><?php echo e(__('report.exchanges_report')); ?></h1>
            <p class="dashboard-subtitle text-slate-500 text-sm mt-1.5"><?php echo e(__('report.exchanges_report_subtitle')); ?></p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('report.export.exchanges-pdf', request()->query())); ?>" class="btn-brand-custom" style="background: linear-gradient(140deg, #ef4444, #b91c1c); box-shadow: 0 14px 28px rgba(239, 68, 68, 0.28);">
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
            <form action="<?php echo e(route('report.exchanges')); ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="shop_id" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.shop')); ?></label>
                        <select class="form-select" id="shop_id" name="shop_id" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                            <option value=""><?php echo e(__('report.all_shops')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e(($filters['shop_id'] ?? '') == $shop->id ? 'selected' : ''); ?>>
                                    <?php echo e($shop->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="exchange_type" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('sale.exchange_type')); ?></label>
                        <select class="form-select" id="exchange_type" name="exchange_type" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                            <option value=""><?php echo e(__('sale.all_types')); ?></option>
                            <option value="replacement" <?php echo e(($filters['exchange_type'] ?? '') === 'replacement' ? 'selected' : ''); ?>>
                                <?php echo e(__('sale.exchange_type_replacement')); ?>

                            </option>
                            <option value="return_only" <?php echo e(($filters['exchange_type'] ?? '') === 'return_only' ? 'selected' : ''); ?>>
                                <?php echo e(__('sale.exchange_type_return_only')); ?>

                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.start_date')); ?></label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e($filters['start_date'] ?? ''); ?>" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label fw-semibold text-secondary small text-uppercase tracking-wider"><?php echo e(__('report.end_date')); ?></label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e($filters['end_date'] ?? ''); ?>" style="border-radius: 10px; border-color: #cedce9; background-color: #f8fafc;">
                    </div>
                    <div class="col-md-2">
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
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.exchanges_count')); ?></div>
                <div class="stat-value"><?php echo e($exchangeSummary->total ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #0f766e, #0d5969);">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.replacements_count')); ?></div>
                <div class="stat-value text-teal" style="color: #0f766e !important;"><?php echo e($exchangeSummary->replacements ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #f59e0b, #d97706);">
                    <i class="bi bi-arrow-return-left"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.returns_count')); ?></div>
                <div class="stat-value text-warning"><?php echo e($exchangeSummary->returns ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon text-white" style="background: linear-gradient(145deg, #10b981, #047857);">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-label"><?php echo e(__('report.cost_difference')); ?></div>
                <div class="stat-value text-success">
                    <?php
                        $totalDiff = (float)($exchangeSummary->cost_difference ?? 0);
                        $sign = $totalDiff > 0 ? '+' : '';
                    ?>
                    <?php echo e($sign); ?><?php echo e(currency_symbol()); ?><?php echo e(number_format($totalDiff, 2)); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Exchanges Table -->
    <div class="content-card">
        <div class="content-card-header">
            <h5 class="content-card-title">
                <i class="bi bi-arrow-left-right"></i>
                <?php echo e(__('sale.exchange_title')); ?>

            </h5>
        </div>
        <?php if($exchanges->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?php echo e(__('report.sale_reference')); ?></th>
                        <th><?php echo e(__('report.shop')); ?></th>
                        <th><?php echo e(__('report.product_name')); ?></th>
                        <th><?php echo e(__('report.original_batch')); ?></th>
                        <th><?php echo e(__('report.replacement_batch')); ?></th>
                        <th class="text-center"><?php echo e(__('sale.quantity')); ?></th>
                        <th><?php echo e(__('sale.exchange_date')); ?></th>
                        <th><?php echo e(__('sale.exchange_type')); ?></th>
                        <th class="text-end"><?php echo e(__('sale.exchange_cost_difference')); ?></th>
                        <th><?php echo e(__('sale.reason')); ?></th>
                        <th class="text-center"><?php echo e(__('report.action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $exchanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exchange): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $diffVal = (float)$exchange->cost_difference;
                        $diffClass = $diffVal > 0 ? 'positive' : ($diffVal < 0 ? 'negative' : 'neutral');
                        $diffSign = $diffVal > 0 ? '+' : '';

                        $typeClass = $exchange->exchange_type === 'replacement' ? 'replacement' : 'return';
                        $typeIcon = $exchange->exchange_type === 'replacement' ? 'bi-arrow-left-right' : 'bi-arrow-return-left';
                        
                        $exchangeData = [
                            'id' => $exchange->id,
                            'sale_ref' => '#' . $exchange->sale_id,
                            'shop_name' => $exchange->shop->name ?? '-',
                            'product_name' => $exchange->originalBatch->product->name ?? ($exchange->sale->product->name ?? '-'),
                            'original_batch' => $exchange->originalBatch->batch_code ?? '-',
                            'replacement_batch' => ($exchange->exchange_type === 'replacement' && $exchange->replacementBatch) ? $exchange->replacementBatch->batch_code : '-',
                            'replacement_product' => ($exchange->exchange_type === 'replacement' && $exchange->replacementBatch && $exchange->replacementBatch->product) ? $exchange->replacementBatch->product->name : '-',
                            'quantity' => $exchange->quantity,
                            'exchange_date' => $exchange->exchange_date->format('M d, Y'),
                            'exchange_type' => $exchange->exchange_type,
                            'cost_difference' => $diffSign . currency_symbol() . number_format($diffVal, 2),
                            'cost_diff_val' => $diffVal,
                            'reason' => ucfirst(str_replace('_', ' ', $exchange->reason)),
                            'note' => $exchange->note ?: '-',
                            'creator' => $exchange->creator->name ?? '-',
                            'customer_name' => $exchange->sale->customer_name ?: '-',
                            'customer_phone' => $exchange->sale->customer_phone ?: '-',
                        ];
                    ?>
                    <tr>
                        <td><strong class="text-teal"><i class="bi bi-receipt"></i> #<?php echo e($exchange->sale_id); ?></strong></td>
                        <td><span class="badge bg-light text-dark border"><?php echo e($exchange->shop->name); ?></span></td>
                        <td>
                            <strong><?php echo e($exchange->originalBatch->product->name ?? ($exchange->sale->product->name ?? '-')); ?></strong>
                            <?php if($exchange->originalBatch && $exchange->originalBatch->attribute_summary !== '-'): ?>
                                <div class="text-muted small"><?php echo e($exchange->originalBatch->attribute_summary); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><code class="text-dark"><?php echo e($exchange->originalBatch->batch_code ?? '-'); ?></code></td>
                        <td>
                            <?php if($exchange->exchange_type === 'replacement' && $exchange->replacementBatch): ?>
                                <code class="text-dark"><?php echo e($exchange->replacementBatch->batch_code); ?></code>
                                <?php if($exchange->replacementBatch->product && $exchange->replacementBatch->product->id !== ($exchange->originalBatch->product->id ?? null)): ?>
                                    <div class="text-muted small">(<?php echo e($exchange->replacementBatch->product->name); ?>)</div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center fw-bold"><?php echo e($exchange->quantity); ?></td>
                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-calendar3"></i>
                                <?php echo e($exchange->exchange_date->format('M d, Y')); ?>

                            </span>
                        </td>
                        <td>
                            <span class="exchange-badge exchange-badge-<?php echo e($typeClass); ?>">
                                <i class="bi <?php echo e($typeIcon); ?>"></i>
                                <?php echo e(__('sale.exchange_type_' . $exchange->exchange_type)); ?>

                            </span>
                        </td>
                        <td class="text-end">
                            <span class="diff-badge diff-badge-<?php echo e($diffClass); ?>">
                                <?php echo e($diffSign); ?><?php echo e(currency_symbol()); ?><?php echo e(number_format($diffVal, 2)); ?>

                            </span>
                        </td>
                        <td>
                            <span class="text-muted small" title="<?php echo e($exchange->note); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $exchange->reason))); ?>

                                <?php if($exchange->note): ?>
                                    <i class="bi bi-info-circle text-secondary ms-1" data-bs-toggle="tooltip" title="<?php echo e($exchange->note); ?>"></i>
                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#exchangeDetailsModal"
                                data-exchange='<?php echo json_encode($exchangeData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG, 512) ?>'
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="p-3">
            <?php echo e($exchanges->links()); ?>

        </div>
        <?php else: ?>
        <div class="p-5 text-center text-muted">
            <i class="bi bi-arrow-repeat fs-1 d-block mb-2 text-secondary"></i>
            <p class="mb-0"><?php echo e(__('report.no_exchanges_found')); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Exchange Details Modal -->
<div class="modal fade" id="exchangeDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content report-modal">
            <div class="modal-header">
                <div>
                    <p class="modal-kicker">Exchange details</p>
                    <h5 class="modal-title mb-0" id="exchangeDetailsModalTitle">Exchange/Return Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Left Column: Customer & Transaction Info -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Customer & Shop</h6>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Customer Name</div>
                                    <div class="fw-bold fs-6" id="modalExchangeCustomerName">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Customer Phone</div>
                                    <div class="fw-bold" id="modalExchangeCustomerPhone">-</div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-xs text-muted mb-0.5">Shop</div>
                                    <div class="fw-bold" id="modalExchangeShopName">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Exchange Info -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100 rounded-3">
                            <div class="card-body">
                                <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Exchange Parameters</h6>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Sale Reference</div>
                                    <div class="fw-bold text-primary" id="modalExchangeSaleRef">-</div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-xs text-muted mb-0.5">Exchange Date</div>
                                    <div class="fw-bold" id="modalExchangeDate">-</div>
                                </div>
                                <div class="mb-0">
                                    <div class="text-xs text-muted mb-0.5">Exchange Type</div>
                                    <div class="fw-bold" id="modalExchangeType">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width: Batches & Product Data -->
                    <div class="col-12">
                        <div class="card border-0 border-top border-slate-200 rounded-0 mt-2 pt-3">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-3 tracking-wider">Product & Batch Comparison</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border">
                                        <div class="text-xs text-muted mb-1">Returned Product</div>
                                        <div class="fw-bold text-dark" id="modalExchangeOriginalProduct">-</div>
                                        <div class="text-xs text-muted mt-1">Batch Code: <code class="text-dark" id="modalExchangeOriginalBatch">-</code></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border" id="modalExchangeReplacementSection">
                                        <div class="text-xs text-muted mb-1">Replacement Product</div>
                                        <div class="fw-bold text-dark" id="modalExchangeReplacementProduct">-</div>
                                        <div class="text-xs text-muted mt-1">Batch Code: <code class="text-dark" id="modalExchangeReplacementBatch">-</code></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width: Reason, notes, cost difference -->
                    <div class="col-12">
                        <div class="p-3 bg-light border rounded-3">
                            <div class="row g-3">
                                <div class="col-6 col-md-4">
                                    <span class="text-xs text-muted d-block">Quantity:</span>
                                    <strong class="text-dark" id="modalExchangeQty">0</strong>
                                </div>
                                <div class="col-6 col-md-4">
                                    <span class="text-xs text-muted d-block">Cost Difference:</span>
                                    <strong class="fs-5" id="modalExchangeCostDiff">0.00</strong>
                                </div>
                                <div class="col-12 col-md-4">
                                    <span class="text-xs text-muted d-block">Reason:</span>
                                    <strong class="text-dark" id="modalExchangeReason">-</strong>
                                </div>
                            </div>
                            <div class="border-top border-slate-200 mt-2 pt-2">
                                <span class="text-xs text-muted">Notes:</span>
                                <p class="mb-0 text-dark small" id="modalExchangeNote">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Meta details: Creator -->
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-light border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-xs text-muted">Processed By:</span>
                                <strong class="text-dark ms-1" id="modalExchangeCreator">-</strong>
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

    /* Exchange type badge */
    .exchange-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 6px;
        padding: 0.2rem 0.55rem;
        font-size: 0.74rem;
        font-weight: 700;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .exchange-badge-replacement {
        background-color: color-mix(in srgb, var(--report-brand) 8%, transparent);
        color: var(--report-brand);
        border-color: color-mix(in srgb, var(--report-brand) 18%, transparent);
    }

    .exchange-badge-return {
        background-color: rgba(245, 158, 11, 0.08);
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.18);
    }

    /* Cost Diff Badges */
    .diff-badge {
        font-weight: 700;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.82rem;
        display: inline-block;
    }

    .diff-badge-positive {
        background-color: rgba(16, 185, 129, 0.08);
        color: #047857;
    }

    .diff-badge-negative {
        background-color: rgba(220, 38, 38, 0.08);
        color: #b91c1c;
    }

    .diff-badge-neutral {
        background-color: #f1f5f9;
        color: #475569;
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
        const modalEl = document.getElementById('exchangeDetailsModal');
        if (!modalEl) return;

        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const ex = JSON.parse(button.getAttribute('data-exchange') || '{}');
            
            document.getElementById('modalExchangeCustomerName').textContent = ex.customer_name || '-';
            document.getElementById('modalExchangeCustomerPhone').textContent = ex.customer_phone || '-';
            document.getElementById('modalExchangeShopName').textContent = ex.shop_name || '-';
            document.getElementById('modalExchangeSaleRef').textContent = ex.sale_ref || '-';
            document.getElementById('modalExchangeDate').textContent = ex.exchange_date || '-';
            
            const typeEl = document.getElementById('modalExchangeType');
            const typeLabel = ex.exchange_type === 'replacement' ? 'REPLACEMENT' : 'RETURN ONLY';
            const typeClass = ex.exchange_type === 'replacement' ? 'bg-teal text-white px-2.5 py-1 rounded-pill small' : 'bg-warning text-dark px-2.5 py-1 rounded-pill small';
            typeEl.innerHTML = `<span class="${typeClass}">${typeLabel}</span>`;
            
            document.getElementById('modalExchangeOriginalProduct').textContent = ex.product_name || '-';
            document.getElementById('modalExchangeOriginalBatch').textContent = ex.original_batch || '-';
            
            const replacementSection = document.getElementById('modalExchangeReplacementSection');
            if (ex.exchange_type === 'replacement') {
                replacementSection.style.display = 'block';
                document.getElementById('modalExchangeReplacementProduct').textContent = ex.replacement_product || '-';
                document.getElementById('modalExchangeReplacementBatch').textContent = ex.replacement_batch || '-';
            } else {
                replacementSection.style.display = 'none';
            }
            
            document.getElementById('modalExchangeQty').textContent = ex.quantity || '0';
            
            const costDiffEl = document.getElementById('modalExchangeCostDiff');
            costDiffEl.textContent = ex.cost_difference || '0.00';
            const costVal = Number(ex.cost_diff_val || 0);
            if (costVal > 0) {
                costDiffEl.className = 'fs-5 text-success fw-bold';
            } else if (costVal < 0) {
                costDiffEl.className = 'fs-5 text-danger fw-bold';
            } else {
                costDiffEl.className = 'fs-5 text-dark fw-bold';
            }
            
            document.getElementById('modalExchangeReason').textContent = ex.reason || '-';
            document.getElementById('modalExchangeNote').textContent = ex.note || '-';
            document.getElementById('modalExchangeCreator').textContent = ex.creator || '-';
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\personal\Byabsha-Track\Modules/Report\resources/views/exchanges.blade.php ENDPATH**/ ?>