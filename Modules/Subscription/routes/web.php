<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;
use Modules\Subscription\Http\Controllers\Admin\AdminSubscriptionController;

Route::middleware(['web', 'auth'])->group(function () {

    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
        Route::get('/my', [SubscriptionController::class, 'mySubscription'])->name('my');
        Route::post('/payment', [SubscriptionController::class, 'submitPayment'])->name('payment.submit');
    });

    Route::middleware(['role:superadmin'])
        ->prefix('admin/subscriptions')
        ->name('admin.subscriptions.')
        ->group(function () {
            Route::get('/', [AdminSubscriptionController::class, 'index'])->name('index');
            Route::get('/active', [AdminSubscriptionController::class, 'active'])->name('active');
            Route::get('/{paymentRequest}', [AdminSubscriptionController::class, 'show'])->name('show');
            Route::post('/{paymentRequest}/approve', [AdminSubscriptionController::class, 'approve'])->name('approve');
            Route::post('/{paymentRequest}/reject', [AdminSubscriptionController::class, 'reject'])->name('reject');
        });
});
