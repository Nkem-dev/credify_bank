<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Services\Paystack;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DepositController extends Controller
{
    /**
     * Show deposit form
     */
    public function create(): View
    {
        return view('user.deposits.create');
    }

    /**
     * Initialize deposit transaction with Paystack
     */
    public function initialize(Request $request): RedirectResponse
    {
        // Validate deposit amount
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100|max:1000000',
        ], [
            'amount.required' => 'Please enter deposit amount.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Minimum deposit amount is ₦100.',
            'amount.max' => 'Maximum deposit amount is ₦1,000,000.',
        ]);

        $amount = $validated['amount'];
        $user = Auth::user(); //get the current user initiationg the transaction

        // Start database transaction
        DB::beginTransaction();

        try {
            // Create a pending deposit transfer record
            $transfer = Transfer::create([
                'user_id' => $user->id,
                'reference' => 'DEP-' . strtoupper(uniqid()),
                'recipient_id' => null, // No recipient for deposits
                'recipient_account_number' => $user->account_number,
                'recipient_name' => $user->name,
                'recipient_bank_name' => 'Credify Bank',
                'recipient_bank_code' => null,
                'type' => 'deposit',
                'amount' => $amount,
                'fee' => 0, // No fees for deposits
                'total_amount' => $amount,
                'description' => 'Wallet Funding via Paystack',
                'status' => 'pending',
            ]);

            // Prepare data for Paystack 
            $paymentData = [
                'email' => $user->email,
                'amount' => $amount,
                'recipient_id' => $transfer->id, 
                'reference' => $transfer->reference, 
            ];

            // Initialize Paystack transaction using paystack service class
            $response = Paystack::initializeTransaction($paymentData);

            // Check if initialization was successful
            if ($response['status'] !== true) {
                throw new Exception($response['message'] ?? 'Failed to initialize payment.');
            }

            // Store transfer ID in session for callback verification
            $request->session()->put('pending_transfer_id', $transfer->id);

            //commit to databse
            DB::commit(); 

            // Redirect user to Paystack payment page
            return redirect()->away($response['data']['authorization_url']);

           
        } catch (Exception $e) {  // if it fails
            DB::rollBack(); //rollback changes
            
            // log error
            Log::error('Deposit initialization failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'amount' => $amount,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to process deposit. ' . $e->getMessage());
        }
    }
}