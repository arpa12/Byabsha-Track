<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Branch routes (Owner only)
    Route::middleware(['role:owner'])->group(function () {
        Route::apiResource('branches', BranchController::class);
    });

    // Category routes (Owner, Manager)
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::apiResource('categories', CategoryController::class);
    });

    // Product routes (Owner, Manager)
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::get('products/low-stock', [ProductController::class, 'lowStock']);
        Route::apiResource('products', ProductController::class);
    });

    // Supplier routes (Owner, Manager)
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::apiResource('suppliers', SupplierController::class);
    });

    // Purchase routes (Owner, Manager)
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::apiResource('purchases', PurchaseController::class);
    });

    // Sale routes (All authenticated users)
    Route::post('sales/pos', [SaleController::class, 'processPOS']); // POS-specific endpoint
    Route::apiResource('sales', SaleController::class);

    // Expense routes (Owner, Manager)
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::get('expenses/categories', [ExpenseController::class, 'categories']);
        Route::apiResource('expenses', ExpenseController::class);
    });

    // Report routes (Owner, Manager)
    Route::middleware(['role:owner,manager'])->prefix('reports')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard']);
        Route::get('/daily-profit', [ReportController::class, 'dailyProfit']);
        Route::get('/monthly-profit', [ReportController::class, 'monthlyProfit']);
        Route::get('/sales-summary', [ReportController::class, 'salesSummary']);
        Route::get('/purchase-summary', [ReportController::class, 'purchaseSummary']);
        Route::get('/top-selling-products', [ReportController::class, 'topSellingProducts']);

        // New optimized report endpoints
        Route::get('/daily-sales', [ReportController::class, 'dailySales']);
        Route::get('/daily-purchases', [ReportController::class, 'dailyPurchases']);
        Route::get('/monthly-sales', [ReportController::class, 'monthlySales']);
        Route::get('/monthly-profit-per-branch', [ReportController::class, 'monthlyProfitPerBranch']);
        Route::get('/year-over-year', [ReportController::class, 'yearOverYearComparison']);
    });
});
