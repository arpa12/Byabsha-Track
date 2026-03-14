<?php

use Illuminate\Support\Facades\Route;
use Modules\Stock\Http\Controllers\StockController;

Route::middleware('auth')->prefix('stocks')->name('stock.')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index');


});
