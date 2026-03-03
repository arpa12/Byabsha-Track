<?php

use Illuminate\Support\Facades\Route;
use Modules\Capital\Http\Controllers\CapitalController;

Route::middleware('auth')->prefix('capitals')->name('capital.')->group(function () {
    Route::get('/', [CapitalController::class, 'index'])->name('index');
    Route::post('/update-all', [CapitalController::class, 'updateAll'])->name('update-all');
    Route::post('/update-shop/{shopId}', [CapitalController::class, 'updateShop'])->name('update-shop');
});
