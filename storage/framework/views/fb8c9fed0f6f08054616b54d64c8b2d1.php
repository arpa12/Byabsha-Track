<?php $__env->startSection('title', __('reconciliation.audit_sheet')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

    .khata-shell {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        background-color: #f3f4f6;
        padding: 2rem 1rem;
    }

    /* Print styling and page layout */
    .khata-book {
        background-color: #fdfdfa;
        border: 2px solid #dfd8c0;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        display: flex;
        gap: 3rem;
        position: relative;
        min-height: 1000px;
    }

    /* Book center fold line */
    .khata-book::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 2px;
        background: linear-gradient(to bottom, #e2e8f0 0%, #cbd5e1 50%, #e2e8f0 100%);
    }

    .khata-page {
        flex: 1;
        position: relative;
        padding-left: 2rem;
    }

    /* Red Margin Line */
    .khata-page::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 1.5rem;
        width: 1.5px;
        background-color: #f87171;
    }

    /* Ruled paper lines */
    .khata-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e3a8a; /* Blue ink color */
        border-bottom: 2px solid #ef4444; /* Red ink underline */
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .khata-table {
        width: 100%;
        margin-bottom: 1.5rem;
        border-collapse: collapse;
    }

    .khata-table th {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #ef4444; /* Red headings */
        font-weight: 700;
        padding: 0.5rem;
        border-bottom: 2px solid #94a3b8;
        text-align: left;
    }

    .khata-table td {
        font-size: 0.9rem;
        padding: 0.65rem 0.5rem;
        border-bottom: 1px solid #cbd5e1; /* Ruled lines */
        color: #1d4ed8; /* Blue ink text */
    }

    .khata-table tr:hover td {
        background-color: rgba(30, 58, 138, 0.02);
    }

    .khata-table .num-col {
        font-family: 'Plus Jakarta Sans', sans-serif;
        text-align: right;
    }

    .khata-total-box {
        border-top: 2px double #1e3a8a;
        border-bottom: 2px double #1e3a8a;
        padding: 0.75rem 0.5rem;
        font-weight: 800;
        color: #1e3a8a;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        font-size: 1.05rem;
    }

    .khata-sub-box {
        border-bottom: 1px solid #cbd5e1;
        padding: 0.5rem;
        color: #1e3a8a;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95rem;
    }

    .ink-blue {
        color: #1d4ed8;
    }

    .ink-red {
        color: #ef4444;
    }

    .badge-reconciled {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .badge-discrepancy {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    /* Print styles */
    @media print {
        body {
            background-color: #fff !important;
            font-size: 10px;
        }

        .top-header,
        .sidebar,
        .sidebar-toggle,
        .no-print,
        .btn {
            display: none !important;
        }

        .main-content {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }

        .khata-shell {
            padding: 0 !important;
            background-color: #fff !important;
        }

        .khata-book {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            gap: 2rem;
            min-height: auto;
        }

        .khata-book::after {
            background: #cbd5e1;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="khata-shell">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 border-bottom border-slate-200 pb-4 mb-4 no-print">
        <div>
            <h1 class="page-title display-font text-3xl font-black text-slate-900 leading-none mb-1"><?php echo e(__('reconciliation.audit_sheet')); ?></h1>
            <p class="page-subtitle text-slate-500 text-sm mt-1.5">Ruled notebook representation of register session.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="window.print()" class="btn btn-primary fw-bold px-4 py-2">
                <i class="bi bi-printer me-2"></i> Print Khata Sheet
            </button>
            <a href="<?php echo e(route('reconciliation.history', ['shop_id' => $register->shop_id])); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> <?php echo e(__('reconciliation.back_to_history')); ?>

            </a>
        </div>
    </div>

    <!-- Khata Notebook Container -->
    <div class="khata-book">
        
        <!-- ==================== LEFT PAGE (SALES / INCOME / B/F) ==================== -->
        <div class="khata-page">
            <div class="khata-title">Left Side (Sales & Incomes)</div>
            
            <div class="mb-4 text-xs text-slate-500">
                <strong>Register #REG-<?php echo e($register->id); ?></strong> | 
                Opened: <?php echo e($register->opened_at->format('d/m/Y h:i A')); ?> | 
                Closed: <?php echo e($register->closed_at ? $register->closed_at->format('d/m/Y h:i A') : '-'); ?>

            </div>

            <!-- Sales Table -->
            <table class="khata-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Product Details</th>
                        <th style="width: 15%; text-align: center;">Qty</th>
                        <th style="width: 15%; text-align: right;">Profit</th>
                        <th style="width: 20%; text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $totalSalesSum = 0; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $posSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $totalSalesSum += $sale->total_amount; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($sale->product->name ?? '-'); ?></strong>
                                <div class="text-slate-400 small" style="font-size: 0.75rem;">Time: <?php echo e($sale->created_at->format('h:i A')); ?></div>
                            </td>
                            <td class="text-center"><?php echo e($sale->quantity); ?></td>
                            <td class="num-col text-success">৳<?php echo e(number_format($sale->profit, 2)); ?></td>
                            <td class="num-col">৳<?php echo e(number_format($sale->total_amount, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No POS Sales recorded.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Manual Incomes Table -->
            <?php 
                $manualIncomes = $register->transactions->where('type', 'income')->where('category', '!=', 'POS Sale');
                $totalManualIncome = $manualIncomes->sum('amount');
            ?>
            <?php if($manualIncomes->isNotEmpty()): ?>
                <h6 class="ink-red fw-bold small uppercase mb-2">Other Manual Incomes</h6>
                <table class="khata-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Category</th>
                            <th style="width: 30%;">Notes</th>
                            <th style="width: 20%; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $manualIncomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($item->category); ?></strong></td>
                                <td><?php echo e($item->notes ?? '-'); ?></td>
                                <td class="num-col">৳<?php echo e(number_format($item->amount, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Math Addition Section: B/F + Sales -->
            <div class="mt-4">
                <div class="khata-sub-box">
                    <span>B/F (Brought Forward / Previous Balance):</span>
                    <span class="num-col">৳<?php echo e(number_format($register->opening_balance, 2)); ?></span>
                </div>
                <div class="khata-sub-box">
                    <span>Total Sales (পিওএস বিক্রি) (+):</span>
                    <span class="num-col">৳<?php echo e(number_format($totalSalesSum, 2)); ?></span>
                </div>
                <?php if($totalManualIncome > 0): ?>
                    <div class="khata-sub-box">
                        <span>Manual Income (+)</span>
                        <span class="num-col">৳<?php echo e(number_format($totalManualIncome, 2)); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php
                    $leftPageTotal = $register->opening_balance + $totalSalesSum + $totalManualIncome;
                ?>
                <div class="khata-total-box">
                    <span>Total Expected Cash Flow:</span>
                    <span>৳<?php echo e(number_format($leftPageTotal, 2)); ?></span>
                </div>
            </div>
        </div>
        
        <!-- ==================== RIGHT PAGE (PURCHASES / EXPENSES / RECONCILIATION) ==================== -->
        <div class="khata-page">
            <div class="khata-title">Right Side (Costs & Reconciliation)</div>

            <div class="mb-4 text-xs text-slate-500">
                <strong>Manager:</strong> <?php echo e($register->user->name ?? '-'); ?> | 
                <strong>Shop:</strong> <?php echo e($register->shop->name ?? '-'); ?>

            </div>

            <!-- Expenses / Purchase Table -->
            <?php 
                $expenses = $register->transactions->where('type', 'expense');
                $totalExpensesSum = $expenses->sum('amount');
            ?>
            <table class="khata-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Expense / Purchase</th>
                        <th style="width: 40%;">Notes</th>
                        <th style="width: 20%; text-align: right;">Cost Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($item->category); ?></strong></td>
                            <td><?php echo e($item->notes ?? '-'); ?></td>
                            <td class="num-col text-danger">-৳<?php echo e(number_format($item->amount, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">No expenses recorded.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Subtract Costs to get Expected Balance -->
            <div class="khata-sub-box mt-3">
                <span>Total expected before costs:</span>
                <span>৳<?php echo e(number_format($leftPageTotal, 2)); ?></span>
            </div>
            <div class="khata-sub-box">
                <span class="ink-red">Less Total Cost (খরচ বাদ) (-):</span>
                <span class="text-danger">-৳<?php echo e(number_format($totalExpensesSum, 2)); ?></span>
            </div>
            
            <div class="khata-total-box">
                <span class="ink-red">Expected Balance (expected cash):</span>
                <span>৳<?php echo e(number_format($register->expected_balance, 2)); ?></span>
            </div>

            <!-- Drawer Cash Count -->
            <h6 class="ink-red fw-bold small uppercase mt-4 mb-2">Drawer Cash Count</h6>
            <div class="khata-sub-box bg-light p-2 mb-4 rounded">
                <span>Physical Cash in Drawer:</span>
                <strong class="display-font">৳<?php echo e(number_format($register->cash_in_hand, 2)); ?></strong>
            </div>

            <!-- Reconciliation: Receivables + Cash Count -->
            <h6 class="ink-red fw-bold small uppercase mb-2">Outstanding Dues & Cash (Dena Pawna)</h6>
            <table class="khata-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Due / Asset Description</th>
                        <th style="width: 30%;">Status</th>
                        <th style="width: 20%; text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $receivablesSum = 0; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $register->receivables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php 
                            if ($item->status === 'pending') {
                                $receivablesSum += $item->amount;
                            }
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo e($item->customer_name); ?></strong>
                                <?php if($item->customer_phone): ?>
                                    <div class="text-slate-400 small" style="font-size: 0.75rem;"><?php echo e($item->customer_phone); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo e($item->status === 'pending' ? 'bg-warning text-dark' : 'bg-success'); ?>">
                                    <?php echo e(ucfirst($item->status)); ?>

                                </span>
                            </td>
                            <td class="num-col">৳<?php echo e(number_format($item->amount, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">No dues/receivables recorded.</td>
                        </tr>
                    <?php endif; ?>
                    
                    <!-- Add Cash in Hand row -->
                    <tr>
                        <td><strong>Physical Cash (ড্রয়ারে নগদ)</strong></td>
                        <td><span class="badge bg-info text-white">Drawer Cash</span></td>
                        <td class="num-col">৳<?php echo e(number_format($register->cash_in_hand, 2)); ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Final Reconciliation sum -->
            <?php
                $actualTotalSum = $register->cash_in_hand + $receivablesSum;
            ?>
            <div class="khata-total-box mt-3">
                <span class="ink-blue">Actual Balance (Dues + Cash):</span>
                <span>৳<?php echo e(number_format($actualTotalSum, 2)); ?></span>
            </div>

            <!-- Discrepancy Match Info -->
            <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-slate-50 border rounded-3">
                <div>
                    <span class="small text-slate-500 d-block">Discrepancy</span>
                    <strong class="display-font fs-5">৳<?php echo e(number_format($register->discrepancy, 2)); ?></strong>
                </div>
                <div>
                    <?php if(abs($register->discrepancy) < 0.01): ?>
                        <span class="badge-reconciled"><i class="bi bi-check-circle me-1"></i>Perfect Match</span>
                    <?php else: ?>
                        <span class="badge-discrepancy"><i class="bi bi-exclamation-triangle me-1"></i>Unbalanced</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\personal\Byabsha-Track\Modules/Reconciliation\resources/views/show.blade.php ENDPATH**/ ?>