<?php

use Illuminate\Support\Facades\Route;
use Modules\Reconciliation\Http\Controllers\ReconciliationController;
use Modules\Reconciliation\Http\Controllers\LedgerController;

Route::middleware(['auth', 'verified', 'module.access:reconciliation'])->group(function () {
    Route::prefix('reconciliations')->name('reconciliation.')->group(function () {
        Route::get('/', [ReconciliationController::class, 'index'])->name('index');
        Route::post('/open', [ReconciliationController::class, 'open'])->name('open');
        Route::post('/{id}/close', [ReconciliationController::class, 'close'])->name('close');
        Route::get('/history', [ReconciliationController::class, 'history'])->name('history');
        Route::get('/{id}', [ReconciliationController::class, 'show'])->name('show');
    });

    Route::prefix('ledger')->name('ledger.')->group(function () {
        Route::post('/transaction', [LedgerController::class, 'storeTransaction'])->name('transaction');
        Route::post('/receivable', [LedgerController::class, 'storeReceivable'])->name('receivable');
        Route::post('/receivable/{id}/repay', [LedgerController::class, 'repayReceivable'])->name('repay');
    });
});

