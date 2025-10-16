<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StockAdjustmentController;

// Authentication routes
require __DIR__.'/auth.php';

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);



    // Roles & Permissions
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

// POS routes (accessible by both admin and cashier)
Route::middleware(['auth', 'role:admin,cashier'])->group(function () {
    Route::get('/pos', [TransactionController::class, 'create'])->name('pos.create');
    Route::post('/pos', [TransactionController::class, 'store'])->name('pos.store');
    Route::get('/pos/history', [TransactionController::class, 'index'])->name('pos.index');
    Route::get('/pos/{transaction}', [TransactionController::class, 'show'])->name('pos.show');

    // Stock Adjustment (accessible by admin and cashier)
    Route::get('/stock-adjustment', [StockAdjustmentController::class, 'index'])->name('stock-adjustment.index');
    Route::get('/stock-adjustment/create', [StockAdjustmentController::class, 'create'])->name('stock-adjustment.create');
    Route::post('/stock-adjustment', [StockAdjustmentController::class, 'store'])->name('stock-adjustment.store');
    Route::get('/stock-adjustment/{stockTransaction}', [StockAdjustmentController::class, 'show'])->name('stock-adjustment.show');
});

// Stock History Route (NEW - Simple and Clean)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/stock-history', [StockAdjustmentController::class, 'history'])->name('stock.history');
});

// Stock Adjustment API routes for authenticated users
Route::middleware(['auth', 'role:admin,cashier'])->group(function () {
    Route::get('/stock-adjustment/history-data', [StockAdjustmentController::class, 'historyData'])->name('stock-adjustment.history-data');
});

// Reports
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/products', [ReportController::class, 'productReport'])->name('reports.products');
    Route::get('reports/financial', [ReportController::class, 'financialReport'])->name('reports.financial');
    Route::get('reports/data', [ReportController::class, 'getData'])->name('reports.data');
});

