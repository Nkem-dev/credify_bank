<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // display transaction history view
    public function index(Request $request)
    {
        $user = Auth::user(); //get currently logged in user

        // Get filter parameters
        $type = $request->input('type'); //filter by type (deposit, airtime, transfers, data)
        $status = $request->input('status'); //filter by status (successful, pending , failed)
        $dateFrom = $request->input('date_from'); //filter by date from
        $dateTo = $request->input('date_to'); //filter nby date to

        // Query database with eloquent tranfer model to get user's recent tranfers 
        $query = Transfer::where('user_id', $user->id) 
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($type && $type !== 'all') { //filter by type
            $query->where('type', $type);
        }

        if ($status && $status !== 'all') { //filter by ststus
            $query->where('status', $status);
        }

        if ($dateFrom) { //filter by start date
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) { //filter by end date
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Paginate results
        $transactions = $query->paginate(20);

        // Calculate statistics
        $stats = [
            // show total transfers o
            'total' => Transfer::where('user_id', $user->id)->count(),
            // count suceesful transfers
            'successful' => Transfer::where('user_id', $user->id)->where('status', 'successful')->count(),
            // count pending transfers
            'pending' => Transfer::where('user_id', $user->id)->where('status', 'pending')->count(),
            // count failed transfers
            'failed' => Transfer::where('user_id', $user->id)->where('status', 'failed')->count(),
            // count the amount of money that has been deposited
            'total_amount_in' => Transfer::where('user_id', $user->id)
                ->where('status', 'successful')
                ->where('type', 'deposit')
                ->sum('amount'),
                // count amount of money out
            'total_amount_out' => Transfer::where('user_id', $user->id)
                ->where('status', 'successful')
                ->whereNotIn('type', ['deposit'])
                ->sum('amount'),
        ];

        // return the view 
        return view('user.transactions.index', compact('transactions', 'stats', 'type', 'status', 'dateFrom', 'dateTo'));
    }

    // view details
    public function show($reference)
    {
        $user = Auth::user(); 

        $transaction = Transfer::where('user_id', $user->id)
            ->where('reference', $reference)
            ->firstOrFail();

        return view('user.transactions.show', compact('transaction'));
    }

    // allow users download their transactions as csv file
    public function export(Request $request)
    {
        $user = Auth::user(); //get currently logged in user

        // fetch all transactions made by the user from newest to the oldest
        $transactions = Transfer::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get(); //no pagination. export everything

            // generate f
        $filename = 'transactions_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['Date', 'Reference', 'Type', 'Description', 'Amount', 'Fee', 'Status']);

            // Add data
            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->created_at->format('Y-m-d H:i:s'),
                    $tx->reference,
                    ucfirst($tx->type),
                    $tx->description ?? $tx->recipient_name ?? '-',
                    number_format($tx->amount, 2),
                    number_format($tx->fee, 2),
                    ucfirst($tx->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
