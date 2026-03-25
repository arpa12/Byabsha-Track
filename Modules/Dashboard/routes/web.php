<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth', 'module.access:dashboard'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/shop-details/{shop}', [DashboardController::class, 'shopDetails'])->name('shop-details');
});
