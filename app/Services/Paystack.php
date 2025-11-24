<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Paystack
{
    /**
     * Initialize a transaction with Paystack
     * This creates a payment session and returns the authorization URL
     * where the user will be redirected to complete payment
     *
     * @param array $data Contains: email, amount, transfer_id, transfer_reference
     * @return array Response from Paystack API
     * @throws Exception If validation or API request fails
     */
    public static function initializeTransaction(array $data): array
    {
        // Validate that Paystack secret key is configured
        self::validateEnvironment();

        // Ensure amount is greater than zero
        self::validateAmount($data['amount']);

        try {
            // Make API request to Paystack
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                'Cache-Control' => 'no-cache',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email' => $data['email'],
                'amount' => (int)($data['amount'] * 100), // Convert to kobo (Paystack uses smallest currency unit)
                'reference' => $data['reference'], // Use our own reference
                'callback_url' => route('user.payment.callback.paystack'), // Where Paystack redirects after payment
                'metadata' => [
                    'transfer_id' => $data['recipient_id'],
                    'transfer_reference' => $data['reference'],
                    'cancel_action' => route('user.dashboard'), // Where to go if cancelled
                ]
            ]);

            return self::handleResponse($response);
        } catch (Exception $e) {
            Log::error('Paystack transaction initialization failed: ' . $e->getMessage());
            throw new Exception('Failed to initialize payment. Please try again.');
        }
    }

    /**
     * Verify a transaction with Paystack
     * Called in the callback to confirm payment was successful
     *
     * @param string $reference Paystack transaction reference
     * @return array Response from Paystack API
     * @throws Exception If verification fails
     */
    public static function verifyTransaction(string $reference): array
    {
        self::validateEnvironment();

        try {
            // Query Paystack to verify the transaction
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                'Cache-Control' => 'no-cache',
            ])->get("https://api.paystack.co/transaction/verify/$reference");

            return self::handleResponse($response);
        } catch (Exception $e) {
            Log::error('Paystack transaction verification failed: ' . $e->getMessage());
            throw new Exception('Failed to verify payment. Please contact support.');
        }
    }

    /**
     * Validate that Paystack secret key is configured
     *
     * @throws Exception
     */
    protected static function validateEnvironment(): void
    {
        if (empty(config('services.paystack.secret_key'))) {
            throw new Exception('Paystack is not properly configured. Please contact support.');
        }
    }

    /**
     * Validate that amount is greater than zero
     *
     * @param int|float $amount
     * @throws Exception
     */
    protected static function validateAmount(int|float $amount): void
    {
        if ($amount <= 0) {
            throw new Exception('Invalid payment amount.');
        }
    }

    /**
     * Handle API response from Paystack
     *
     * @param Response $response
     * @return array
     * @throws Exception
     */
    protected static function handleResponse(Response $response): array
    {
        // Check if request failed
        if ($response->failed()) {
            $error = $response->json();
            $message = $error['message'] ?? 'Payment gateway error. Please try again.';
            throw new Exception($message);
        }

        return $response->json();
    }
}