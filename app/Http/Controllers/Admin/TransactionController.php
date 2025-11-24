<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display list of all transactions
     */
    public function index(Request $request)
    {
        // Statistics
        // total successful transactions
        $totalTransactions = Transfer::where('status', 'successful')->sum('amount');
        // pending transfers
        $totalPending = Transfer::where('status', 'pending')->count();
        // failed transfers
        $totalFailed = Transfer::where('status', 'failed')->count();
        // daily transactions
        $todayTransactions = Transfer::whereDate('created_at', today())->where('status', 'successful')->sum('amount');

        // Build query with eager loading with user's transfers
        $query = Transfer::with('user');

        // Search by reference, account number or account name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('recipient_account_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by type; data, deposit, airtime etc
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status; succesful, pending, failed
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range: start date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // end date
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range; amount from
        if ($request->filled('amount_from')) {
            $query->where('amount', '>=', $request->amount_from);
        }

        // amount to
        if ($request->filled('amount_to')) {
            $query->where('amount', '<=', $request->amount_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at'); //newest transactions
        $sortOrder = $request->get('sort_order', 'desc'); //
        $query->orderBy($sortBy, $sortOrder);

        // execute the query and return 20 per page
        $transactions = $query->paginate(20);

        // Get users for filter dropdown
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        // return view and compact with transaction details
        return view('admin.transactions.index', compact(
            'transactions',
            'totalTransactions',
            'totalPending',
            'totalFailed',
            'todayTransactions',
            'users'
        ));
    }

    /**
     * Show transaction details
     */
    public function show(Transfer $transaction) //model binding
    {
        $transaction->load('user'); //load the related user

        // return view with the user's transaction details
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Reverse a transaction
     */
    public function reverse(Request $request, Transfer $transaction)
    {
        // Only successful transactions can be reversed
        if ($transaction->status !== 'successful') {
            return back()->with('error', 'Only successful transactions can be reversed.');
        }

        // Prevent reversing deposits
        if ($transaction->type === 'deposit') {
            return back()->with('error', 'Deposit transactions cannot be reversed. Use debit instead.');
        }

        // validate the inputs
        $request->validate([
            'reason' => 'required|string|max:500',
            'transaction_pin' => 'required|digits:4',
        ]);

        $admin = auth()->user(); //get the currently logged in user that is an admin

        // Verify admin transaction pin
        if (!Hash::check($request->transaction_pin, $admin->transaction_pin)) {
            return back()->with('error', 'Incorrect transaction PIN.');
        }

        DB::beginTransaction(); //begin task

        try {
            $user = $transaction->user; //get the user transaction

            // Credit back the amount to user(refund)
            $user->increment('balance', $transaction->amount);

            // Mark transaction as reversed 
            $transaction->update([
                'status' => 'failed',
                'metadata' => json_encode([
                    'reversed_by' => $admin->name . ' (Admin)',
                    'reversed_at' => now(),
                    'reversal_reason' => $request->reason,
                    'original_metadata' => $transaction->metadata,
                ]),
            ]);

            // Create reversal record
            Transfer::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $transaction->amount,
                'fee' => 0,
                'total_amount' => $transaction->amount,
                'status' => 'successful',
                'description' => 'Reversal of ' . $transaction->reference . ' - ' . $request->reason,
                'reference' => 'REV-' . strtoupper(substr(uniqid(), -8)),
                'recipient_account_number' => $user->account_number,
                'recipient_name' => $user->name,
                'completed_at' => now(),
                'metadata' => json_encode([
                    'reversed_by' => $admin->name . ' (Admin)',
                    'original_transaction' => $transaction->reference,
                ]),
            ]);

            // commit to database
            DB::commit();

            // log message
            Log::channel('admin_actions')->info('Admin reversed transaction', [
                'admin_id' => $admin->id,
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference,
                'amount' => $transaction->amount,
                'reason' => $request->reason,
            ]);

            // return with success message
            return back()->with('success', 'Transaction reversed successfully!');

        } catch (Exception $e) { //if it fails
            DB::rollBack(); //rollback changes
            Log::error('Transaction reversal failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to reverse transaction.');
        }
    }

    /**
     * Mark transaction as failed only when the  transaction is pending
     */
    public function markFailed(Request $request, Transfer $transaction)
    {
        // Only pending transactions can be marked as failed
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be marked as failed.');
        }

        // validate input
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            // update the transaction
            $transaction->update([
                'status' => 'failed',
                'metadata' => json_encode([
                    'failed_by' => auth()->user()->name . ' (Admin)',
                    'failed_at' => now(),
                    'failure_reason' => $request->reason,
                ]),
            ]);

            // log to channel admin_actions
            Log::channel('admin_actions')->info('Admin marked transaction as failed', [
                'admin_id' => auth()->id(),
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference,
                'reason' => $request->reason,
            ]);

            // return with success message 
            return back()->with('success', 'Transaction marked as failed!');

        } catch (Exception $e) { //if it fails
            Log::error('Mark failed transaction error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update transaction.');
        }
    }

    /**
     * Download transaction report
     */
    public function downloadReport(Request $request)
    {
        // start query with relationship; lodas transaction plus the user that owns the transaction
        $query = Transfer::with('user'); 

        //saerch by account number, reference 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('recipient_account_number', 'like', "%{$search}%");
            });
        }

        // search by user name
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // search by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // search by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // search by data from 
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // date to
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // transactions from newest to oldest
        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV file name
        $filename = 'transactions_report_' . date('Y-m-d_His') . '.csv';
       
        // headers to tell the browser to download the file
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Stream the CSV output
        // CSV is generated using a streaming callback, which is memory efficient
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w'); //open the stream

            // CSV header row
            fputcsv($file, [
                'Reference',
                'Date',
                'User Name',
                'User Email',
                'Type',
                'Amount',
                'Fee',
                'Status',
                'Recipient',
                'Description'
            ]);

            // Transaction Data rows
            foreach ($transactions as $transaction) {
                //  fputcsv formats each line to csv format
                fputcsv($file, [
                    $transaction->reference,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->user->name ?? 'N/A',
                    $transaction->user->email ?? 'N/A',
                    ucfirst($transaction->type),
                    $transaction->amount,
                    $transaction->fee,
                    ucfirst($transaction->status),
                    $transaction->recipient_name,
                    $transaction->description,
                ]);
            }

            fclose($file); //close file stream
        };

        // This line tells Laravel to stream a response back to the browser.
// Instead of generating the whole CSV file in memory first, Laravel sends the file piece by piece as it is being created.
        return response()->stream($callback, 200, $headers);
    }
}