<?php

use App\Http\Controllers\CustomerCare\CustomerCareDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer_care')->name('customer_care.')->middleware('auth', 'can:access-customer_care-dashboard')->group(function () {

     Route::get('dashboard', [CustomerCareDashboardController::class, 'customerCareDashboard'])->name('dashboard');

});

