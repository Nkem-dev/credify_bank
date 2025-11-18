<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
     public function userDashboard() {
        // dd('Welcome to User dashboard');
        $transactions = auth()->user()
        ->transfers()
        ->whereIn('type', ['deposit', 'internal', 'external'])
        ->orderByDesc('completed_at')
        ->paginate(10);

        // Force interest calculation for *all* plans on every dashboard load
    $user = auth()->user();
    foreach ($user->savingsPlans as $plan) {
        $plan->applyDailyInterest();
    }
        return view('user.dashboard', compact('transactions'));
    }
}
