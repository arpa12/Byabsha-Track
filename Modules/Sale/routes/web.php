<?php

use Illuminate\Support\Facades\Route;
use Modules\Sale\Http\Controllers\SaleController;

Route::middleware('auth')->prefix('sales')->name('sale.')->group(function () {
    Route::get('/', [SaleController::class, 'index'])->name('index');
    Route::get('/create', [SaleController::class, 'create'])->name('create');
    Route::get('/products-by-shop', [SaleController::class, 'productsByShop'])->name('products-by-shop');
    Route::post('/', [SaleController::class, 'store'])->name('store');
    Route::get('/{id}', [SaleController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SaleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SaleController::class, 'update'])->name('update');
    Route::delete('/{id}', [SaleController::class, 'destroy'])->name('destroy');
});
