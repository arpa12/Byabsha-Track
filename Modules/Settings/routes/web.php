<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/general', [SettingsController::class, 'general'])->name('general');
        Route::get('/business', [SettingsController::class, 'business'])->name('business');
        Route::get('/system', [SettingsController::class, 'system'])->name('system');

        Route::put('/{group?}', [SettingsController::class, 'update'])->name('update');
        Route::get('/clear-cache/{group?}', [SettingsController::class, 'clearCache'])->name('clear-cache');
    });
});
