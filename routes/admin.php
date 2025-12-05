<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CustomerCareController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VirtualCardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('auth', 'can:access-admin-dashboard')->group(function () {

    Route::get('dashboard', [AdminDashboardController::class, 'adminDashboard'])->name('dashboard');

    // force delete a user
     Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');


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
         Route::post('manage/{user}/credit', [UserController::class, 'credit'])->name('credit');
        Route::post('manage/{user}/debit', [UserController::class, 'debit'])->name('debit');
        Route::post('/{user}/upgrade-tier', [UserController::class, 'upgradeTier'])->name('upgrade-tier');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        
    
        // Trash/Deleted users
    Route::get('/users/trash/list', [UserController::class, 'trash'])->name('trash');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('restore');
//    Route::delete('/admin/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
       
       
        
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

 // Customer Care Management
    Route::get('/customer-care', [CustomerCareController::class, 'index'])->name('customer-care.index');
    Route::get('/customer-care/create', [CustomerCareController::class, 'create'])->name('customer-care.create');
    Route::post('/customer-care', [CustomerCareController::class, 'store'])->name('customer-care.store');
    Route::get('/customer-care/{customerCare}/edit', [CustomerCareController::class, 'edit'])->name('customer-care.edit');
    Route::put('/customer-care/{customerCare}', [CustomerCareController::class, 'update'])->name('customer-care.update');
    Route::delete('/customer-care/{customerCare}', [CustomerCareController::class, 'destroy'])->name('customer-care.destroy');
    Route::post('/customer-care/{customerCare}/reset-password', [CustomerCareController::class, 'resetPassword'])->name('customer-care.reset-password');

    // Investments Routes
    Route::prefix('investments')->name('investments.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\InvestmentController::class, 'index'])->name('index');
        Route::get('/analytics', [InvestmentController::class, 'analytics'])->name('analytics');
        
         Route::get('export', [InvestmentController::class, 'export'])
            ->name('export');
           
        // User Investments
        Route::get('/all', [InvestmentController::class, 'allInvestments'])->name('all');
        Route::get('/user/{userId}', [InvestmentController::class, 'showUserInvestment'])->name('user.show');

        // Route::get('/user-details/{user}', [InvestmentController::class, 'userDetails'])
        //     ->name('user-details');
         Route::get('/user/{userId}', [InvestmentController::class, 'showUserInvestment'])->name('user-details');

            


             
          
        
        // Stocks Management
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [InvestmentController::class, 'allStocks'])->name('all');
            Route::get('/create', [InvestmentController::class, 'createStock'])->name('create');
            Route::post('/', [InvestmentController::class, 'storeStock'])->name('store');
            Route::get('/{stock}/edit', [InvestmentController::class, 'editStock'])->name('edit');
            Route::put('/{stock}', [InvestmentController::class, 'updateStock'])->name('update');
            Route::post('/{stock}/toggle-status', [InvestmentController::class, 'toggleStockStatus'])->name('toggle-status');
            Route::get('/{stock}', [InvestmentController::class, 'showStock'])->name('show');
        });
        
        // Stock Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [InvestmentController::class, 'allTransactions'])->name('all');
            Route::put('/{transaction}/status', [InvestmentController::class, 'updateTransactionStatus'])->name('update-status');

              Route::get('/export', [InvestmentController::class, 'exportTransactions'])->name('export');
        });
        
        // User Stocks (Portfolios)
        Route::get('/user-stocks', [InvestmentController::class, 'allUserStocks'])->name('user-stocks.all');
        
        // Watchlists
        Route::get('/watchlists', [InvestmentController::class, 'allWatchlists'])->name('watchlists.all');

        
        
           

             Route::get('export', [InvestmentController::class, 'export'])
            ->name('analytics.export');

    });
});

