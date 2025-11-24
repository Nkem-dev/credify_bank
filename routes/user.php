<?php

use App\Http\Controllers\Paystack\PaymentStatusController;
use App\Http\Controllers\Paystack\PaystackCallbackController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\LoanController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SavingsController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\TransferController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\VirtualCardController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->middleware('auth', 'can:access-user-dashboard', 'pin.required')->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'userDashboard'])->name('dashboard');


    // user profile route
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upgrade', [ProfileController::class, 'upgrade'])->name('profile.upgrade');

      // Deposit routes
    Route::get('/deposit/create', [DepositController::class, 'create'])->name('deposit.create'); //show the deposit form
    
    Route::post('/deposit/initialize', [DepositController::class, 'initialize'])->name('deposit.initialize');//submit form to initialize payment
    
    // Paystack callback
    Route::get('/payment/callback/paystack', [PaystackCallbackController::class, 'callback'])->name('payment.callback.paystack');
    
    // Payment status
    Route::get('/payment/success/{transaction}', [PaymentStatusController::class, 'success'])->name('payment.success');

      // Account routes
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.update.profile');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.update.password');
    Route::put('/account/security', [AccountController::class, 'updateSecurity'])->name('account.update.security');

// route for transfer
    Route::prefix('transfers')->name('transfers.')->group(function () {
        Route::get('/', [TransferController::class, 'index'])->name('index'); //return transfers view
        
        // show internal form view
        Route::get('/internal', [TransferController::class, 'internalCreate'])
         ->name('internal.create');

        //  submit internal transfer form
        Route::post('/transfer/internal', [TransferController::class, 'internalStore'])
         ->name('internal.store');


         Route::post('/validate', [TransferController::class, 'validateAmount'])
         ->name('internal.validate');

        //  
          Route::post('/internal/lookup', [TransferController::class, 'lookup'])->name('internal.lookup');

          // receipt route
       Route::get('/receipt/{reference}', [TransferController::class, 'receipt'])->name('receipt');


        //  show external form view
        Route::get('/external', [TransferController::class, 'externalCreate'])
         ->name('external.create');

       //  submit external transfer form
        Route::post('/transfer/external', [TransferController::class, 'externalStore'])
         ->name('external.store');

        //  route for airtime
     Route::get('/airtime', [TransferController::class, 'airtimeCreate'])->name('airtime.create');
    Route::post('/airtime/validate', [TransferController::class, 'airtimeValidate'])->name('airtime.validate');
    Route::post('/airtime', [TransferController::class, 'airtimeStore'])->name('airtime.store');

    // route for data
    Route::get('/data', [TransferController::class, 'dataCreate'])
        ->name('data.create');
    Route::post('/data/validate', [TransferController::class, 'dataValidate'])->name('data.validate');
   Route::post('/data', [TransferController::class, 'dataStore'])->name('data.store');
   Route::get('/data/receipt/{reference}', [TransferController::class, 'showDataReceipt'])
    ->name('data.receipt');



  
    });

    // route for virtual card
    Route::prefix('cards')->name('cards.')->group(function () {
    Route::get('/', [VirtualCardController::class, 'index'])->name('index'); //show the view
    Route::get('/create', [VirtualCardController::class, 'create'])->name('create'); 
    Route::post('/', [VirtualCardController::class, 'store'])->name('store');
    Route::get('/{card}', [VirtualCardController::class, 'show'])->name('show');
    Route::delete('/{card}', [VirtualCardController::class, 'destroy'])->name('destroy');
    Route::post('/verify-pin', [VirtualCardController::class, 'verifyPin'])->name('verify-pin');

});

// route for loans

 Route::prefix('loans')->name('loans.')->group(function () {
    Route::get('/loans', [LoanController::class, 'index'])->name('index');
    Route::post('/loans', [LoanController::class, 'store'])->name('store');

    });


    // route for savings
     Route::prefix('savings')->name('savings.')->group(function () {
        Route::get('/', [SavingsController::class, 'index'])->name('index');
        Route::get('/create', [SavingsController::class, 'create'])->name('create');
        Route::post('/', [SavingsController::class, 'store'])->name('store');
        Route::post('/{plan}/fund', [SavingsController::class, 'fund'])->name('fund');
        Route::post('/{plan}/withdraw', [SavingsController::class, 'withdraw'])->name('withdraw'); 
        Route::get('/savings/{plan}/transactions', [SavingsController::class, 'transactions'])->name('transactions');
        Route::delete('/{plan}', [SavingsController::class, 'destroy'])->name('destroy'); 
     });

    //  route for settings
     Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        
        // Security
        Route::get('/security', [SettingsController::class, 'security'])->name('security');
        Route::post('/password', [SettingsController::class, 'updatePassword'])->name('update.password');
        Route::post('/transaction-pin', [SettingsController::class, 'updateTransactionPin'])->name('update.pin');
        
        // Privacy
        Route::get('/privacy', [SettingsController::class, 'privacy'])->name('privacy');
        Route::post('/privacy', [SettingsController::class, 'updatePrivacy'])->name('update.privacy');
        
        // Preferences
        Route::get('/preferences', [SettingsController::class, 'preferences'])->name('preferences');
        Route::post('/preferences', [SettingsController::class, 'updatePreferences'])->name('update.preferences');
        
        // Notifications
        Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
        Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('update.notifications');

        Route::post('/settings/close-account', [SettingsController::class, 'closeAccount'])->name('close-account');
    });

    // Transaction History Routes
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/{reference}', [TransactionController::class, 'show'])->name('show');
        Route::get('/export/csv', [TransactionController::class, 'export'])->name('export');
    });
});

    


 
?>