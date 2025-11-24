<?php

namespace App\Http\Controllers\Paystack;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentStatusController extends Controller
{
    /**
     * Show payment success page
     * Displays deposit confirmation details
     */
    public function success(Request $request, string $reference): View
    {
        // Get the transfer by reference
        $transfer = Transfer::where('reference', $reference)
            ->where('user_id', Auth::id()) // Ensure user owns this transfer
            ->where('type', 'deposit')
            ->firstOrFail();

        return view('user.payments.success', [
            'transfer' => $transfer,
        ]);
    }
}