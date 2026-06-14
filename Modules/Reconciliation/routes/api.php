<?php

use Illuminate\Support\Facades\Route;
use Modules\Reconciliation\Http\Controllers\ReconciliationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('reconciliations', ReconciliationController::class)->names('reconciliation');
});
