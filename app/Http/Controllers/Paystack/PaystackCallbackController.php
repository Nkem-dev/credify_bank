<?php

namespace App\Http\Controllers\Paystack;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\User;
use App\Services\Paystack;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaystackCallbackController extends Controller
{
    /**
     * Handle Paystack payment callback
     * This is called when user returns from Paystack after payment
     * Paystack sends transaction reference in the URL
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            // Validate that Paystack sent the required data
            $validated = $request->validate([
                'trxref' => 'required|string',   // Transaction reference
                'reference' => 'required|string', // Same as trxref (Paystack sends both)
            ]);

            // Verify the transaction with Paystack 
            $responseData = Paystack::verifyTransaction($validated['reference']);

            // Check if payment was successful
            if (!$this->isTransactionSuccessful($responseData)) {
                return $this->handleFailedPayment($responseData);
            }

            // Extract transaction details from Paystack response
            $transactionDetails = $this->extractTransactionDetails($responseData);

            // Process the successful payment
            return $this->processSuccessfulPayment($transactionDetails);

        } catch (Exception $e) {
            Log::error('Paystack Callback Error: ' . $e->getMessage());

            return redirect()->route('user.dashboard')
                ->with('error', 'An error occurred while processing your payment. Please contact support.');
        }
    }

    /**
     * Check if the Paystack transaction was successful
     */
    protected function isTransactionSuccessful(array $responseData): bool
    { //checks if
        return $responseData['status'] == 1 && $responseData['data']['status'] === 'success';
    }

    /**
     * Extract relevant transaction details from Paystack response
     */
    protected function extractTransactionDetails(array $responseData): array
    {
        $data = $responseData['data'];
        $metadata = $data['metadata'] ?? [];

        return [
            'reference' => $data['reference'],
            'channel' => $data['channel'], // card, bank, ussd, etc
            'amount' => $data['amount'] / 100, // Convert from kobo to naira
            'transfer_id' => $metadata['transfer_id'] ?? null,
            'paid_at' => $data['paid_at'] ?? now(),
        ];
    }

    /**
     * Process successful payment
     * Updates transfer status and credits user account
     */
    protected function processSuccessfulPayment(array $transactionDetails): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Get the transfer record
            $transfer = Transfer::findOrFail($transactionDetails['transfer_id']);

            // Verify amounts match (security check)
            if ($transfer->amount != $transactionDetails['amount']) {
                throw new Exception('Amount mismatch detected');
            }

            // Get the user
            $user = User::findOrFail($transfer->user_id);

            // Credit user's account
            $user->balance += $transfer->amount;
            $user->save();

            // Update transfer status
            $transfer->update([
                'status' => 'successful',
                'payment_method' => 'Paystack',
                'channel' => $transactionDetails['channel'],
                'completed_at' => $transactionDetails['paid_at'],
                'metadata' => json_encode($transactionDetails),
            ]);

            DB::commit();

            // Clear session data
            session()->forget('pending_transfer_id');

            // Redirect to success page
            return redirect()->route('user.payment.success', $transfer->reference)
                ->with('success', 'Deposit successful! Your account has been credited.');

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'transaction_details' => $transactionDetails,
            ]);

            return redirect()->route('user.dashboard')
                ->with('error', 'Payment verified but account credit failed. Please contact support with reference: ' . $transactionDetails['reference']);
        }
    }

    /**
     * Handle failed payment
     */
    protected function handleFailedPayment(array $responseData): RedirectResponse
    {
        $metadata = $responseData['data']['metadata'] ?? [];
        $transferId = $metadata['transfer_id'] ?? null;

        // Update transfer status to failed if we have the transfer ID
        if ($transferId) {
            try {
                $transfer = Transfer::find($transferId);
                if ($transfer) {
                    $transfer->update([
                        'status' => 'failed',
                        'metadata' => json_encode($responseData),
                    ]);
                }
            } catch (Exception $e) {
                Log::error('Failed to update transfer status: ' . $e->getMessage());
            }
        }

        $message = $responseData['message'] ?? 'Payment verification failed.';

        return redirect()->route('user.dashboard')
            ->with('error', $message);
    }
}