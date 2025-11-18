<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\TransactionPinController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('redirect.if.authenticated')->group(function () {
    // route for registration view
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

   // route to create or register user (to submit)
    Route::post('create-user', [RegisteredUserController::class, 'store'])->name('user.create');

     // route to show verification form
        Route::get('otp-verify/{token}', [RegisteredUserController::class, 'showOtpForm'])
        ->name('otp.verify');

         // route to submit otp form
    Route::post('otp-verify/{token}', [RegisteredUserController::class, 'otpVerify'])->name('user.otp-verify');

     // route to resend otp
    Route::post('/resentOtp/{token}', [RegisteredUserController::class, 'resendOtp'])->name('user.otp-resend');

   
   


    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    
});

Route::middleware('auth')->group(function () {

    // route for set transaction pin
     Route::get('/set-pin', [TransactionPinController::class, 'show'])
        ->name('pin.setup');
    
    Route::post('/set-pin', [TransactionPinController::class, 'store'])
        ->name('pin.store');
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
