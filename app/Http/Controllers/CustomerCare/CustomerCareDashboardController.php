<?php

namespace App\Http\Controllers\CustomerCare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerCareDashboardController extends Controller
{
    public function customerCareDashboard()
    {
        // dd('customercare dashboard');
        return view('customer_care.dashboard');
    }
}
