<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo e(__('report.exchanges_report')); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #0f766e; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #0f766e; margin-bottom: 4px; }
        .header .shop-name { font-size: 14px; color: #334155; font-weight: 600; }
        .header .date-range { font-size: 12px; color: #64748b; margin-top: 4px; }
        .header .generated { font-size: 9px; color: #94a3b8; margin-top: 6px; }
        
        .summary { display: table; width: 100%; margin-bottom: 18px; border: 1px solid #e2e8f0; }
        .summary-item { display: table-cell; width: 25%; text-align: center; padding: 10px 6px; border-right: 1px solid #e2e8f0; }
        .summary-item:last-child { border-right: none; }
        .summary-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-value { font-size: 16px; font-weight: 700; margin-top: 2px; }
        
        .color-replacement { color: #0f766e; }
        .color-return { color: #d97706; }
        .color-positive { color: #16a34a; }
        .color-negative { color: #dc2626; }
        .color-neutral { color: #475569; }
        
        table.report-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.report-table thead th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 7px 8px; text-align: left; font-size: 10px; font-weight: 600; color: #334155; text-transform: uppercase; }
        table.report-table tbody td { border: 1px solid #e2e8f0; padding: 6px 8px; font-size: 10px; }
        table.report-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Byabsha Track</h1>
        <div class="shop-name"><?php echo e($shopName); ?></div>
        <div class="date-range"><?php echo e(__('report.exchanges_report')); ?> &mdash; <?php echo e($filters['start_date']); ?> to <?php echo e($filters['end_date']); ?></div>
        <div class="generated"><?php echo e(__('report.generated_at')); ?>: <?php echo e(now()->format('M d, Y h:i A')); ?></div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label"><?php echo e(__('report.exchanges_count')); ?></div>
            <div class="summary-value"><?php echo e($exchangeSummary->total ?? 0); ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label"><?php echo e(__('report.replacements_count')); ?></div>
            <div class="summary-value color-replacement"><?php echo e($exchangeSummary->replacements ?? 0); ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label"><?php echo e(__('report.returns_count')); ?></div>
            <div class="summary-value color-return"><?php echo e($exchangeSummary->returns ?? 0); ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label"><?php echo e(__('report.cost_difference')); ?></div>
            <div class="summary-value
                <?php if($exchangeSummary->cost_difference > 0): ?> color-positive
                <?php elseif($exchangeSummary->cost_difference < 0): ?> color-negative
                <?php else: ?> color-neutral
                <?php endif; ?>
                ">
                <?php
                    $totalDiff = (float)($exchangeSummary->cost_difference ?? 0);
                    $sign = $totalDiff > 0 ? '+' : '';
                ?>
                <?php echo e($sign); ?><?php echo e(currency_symbol()); ?><?php echo e(number_format($totalDiff, 2)); ?>

            </div>
        </div>
    </div>

    <?php if($exchanges->count() > 0): ?>
    <table class="report-table">
        <thead>
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
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $exchanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exchange): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $diffVal = (float)$exchange->cost_difference;
                $diffSign = $diffVal > 0 ? '+' : '';
            ?>
            <tr>
                <td><strong>#<?php echo e($exchange->sale_id); ?></strong></td>
                <td><?php echo e($exchange->shop->name); ?></td>
                <td>
                    <?php echo e($exchange->originalBatch->product->name ?? ($exchange->sale->product->name ?? '-')); ?>

                    <?php if($exchange->originalBatch && $exchange->originalBatch->attribute_summary !== '-'): ?>
                        <br><span style="font-size: 8px; color: #64748b;"><?php echo e($exchange->originalBatch->attribute_summary); ?></span>
                    <?php endif; ?>
                </td>
                <td><code><?php echo e($exchange->originalBatch->batch_code ?? '-'); ?></code></td>
                <td>
                    <?php if($exchange->exchange_type === 'replacement' && $exchange->replacementBatch): ?>
                        <code><?php echo e($exchange->replacementBatch->batch_code); ?></code>
                        <?php if($exchange->replacementBatch->product && $exchange->replacementBatch->product->id !== ($exchange->originalBatch->product->id ?? null)): ?>
                            <br><span style="font-size: 8px; color: #64748b;">(<?php echo e($exchange->replacementBatch->product->name); ?>)</span>
                        <?php endif; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td class="text-center" style="font-weight: bold;"><?php echo e($exchange->quantity); ?></td>
                <td><?php echo e($exchange->exchange_date->format('M d, Y')); ?></td>
                <td class="<?php if($exchange->exchange_type === 'replacement'): ?> color-replacement <?php else: ?> color-return <?php endif; ?>" style="font-weight: bold;">
                    <?php echo e(__('sale.exchange_type_' . $exchange->exchange_type)); ?>

                </td>
                <td class="text-end
                    <?php if($diffVal > 0): ?> color-positive
                    <?php elseif($diffVal < 0): ?> color-negative
                    <?php else: ?> color-neutral
                    <?php endif; ?>
                    " style="font-weight: bold;">
                    <?php echo e($diffSign); ?><?php echo e(currency_symbol()); ?><?php echo e(number_format($diffVal, 2)); ?>

                </td>
                <td><?php echo e(ucfirst(str_replace('_', ' ', $exchange->reason))); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="text-align:center; color:#64748b; padding:30px 0;"><?php echo e(__('report.no_exchanges_found')); ?></p>
    <?php endif; ?>

    <div class="footer">
        Byabsha Track &copy; <?php echo e(date('Y')); ?> &mdash; <?php echo e(__('report.pdf_footer')); ?>

    </div>
</body>
</html>
<?php /**PATH E:\personal\Byabsha-Track\Modules/Report\resources/views/pdf/exchanges-pdf.blade.php ENDPATH**/ ?>