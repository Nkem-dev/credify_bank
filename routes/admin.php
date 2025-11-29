<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VirtualCardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('auth', 'can:access-admin-dashboard')->group(function () {

    Route::get('dashboard', [AdminDashboardController::class, 'adminDashboard'])->name('dashboard');


    // user management route
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        
        // Account Actions
        Route::post('/{user}/activate', [UserController::class, 'activate'])->name('activate');
        Route::post('/{user}/deactivate', [UserController::class, 'deactivate'])->name('deactivate');
        Route::post('/{user}/suspend', [UserController::class, 'suspend'])->name('suspend');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // Soft delete & restore
    // Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    // Route::post('/{user}/restore', [UserController::class, 'restore'])->name('restore');

    Route::get('/trashed', [UserController::class, 'trashed'])->name('trashed');

    
    Route::post('/{user}/restore', [UserController::class, 'restore'])->name('restore');
    Route::delete('/{user}/force', [UserController::class, 'forceDelete'])->name('force-delete');
       
        Route::post('manage/{user}/credit', [UserController::class, 'credit'])->name('credit');
        Route::post('manage/{user}/debit', [UserController::class, 'debit'])->name('debit');
        
        // Route::post('/{user}/credit', [UserController::class, 'credit'])->name('credit');
        // Route::post('/{user}/debit', [UserController::class, 'debit'])->name('debit');
        Route::post('/{user}/upgrade-tier', [UserController::class, 'upgradeTier'])->name('upgrade-tier');
        
        // Activity Log
        Route::get('/{user}/activity-log', [UserController::class, 'activityLog'])->name('activity-log');

    });

    // Admin Transaction Management Routes
Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    Route::post('/{transaction}/reverse', [TransactionController::class, 'reverse'])->name('reverse');
    Route::post('/{transaction}/mark-failed', [TransactionController::class, 'markFailed'])->name('mark-failed');
    Route::get('/download/report', [TransactionController::class, 'downloadReport'])->name('download');
});

// Admin Loan Management Routes
Route::prefix('loans')->name('loans.')->group(function () {
    Route::get('/', [LoanController::class, 'index'])->name('index');
    Route::get('/{loan}', [LoanController::class, 'show'])->name('show');
    Route::post('/{loan}/approve', [LoanController::class, 'approve'])->name('approve');
    Route::post('/{loan}/reject', [LoanController::class, 'reject'])->name('reject');
    Route::post('/{loan}/mark-paid', [LoanController::class, 'markPaid'])->name('mark-paid');
    Route::get('/download/report', [LoanController::class, 'downloadReport'])->name('download');
});

// Admin Virtual Card Management Routes
Route::prefix('virtual-cards')->name('virtual-cards.')->group(function () {
    Route::get('/', [VirtualCardController::class, 'index'])->name('index');
    Route::get('/{virtualCard}', [VirtualCardController::class, 'show'])->name('show');
    Route::post('/{virtualCard}/block', [VirtualCardController::class, 'block'])->name('block');
    Route::post('/{virtualCard}/unblock', [VirtualCardController::class, 'unblock'])->name('unblock');
    Route::delete('/{virtualCard}', [VirtualCardController::class, 'destroy'])->name('destroy');
    Route::get('/download/report', [VirtualCardController::class, 'downloadReport'])->name('download');
});
});
