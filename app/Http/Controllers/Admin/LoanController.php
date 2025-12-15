<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LoanApproved;
use App\Models\Loan;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoanController extends Controller
{
    /**
     * Display list of all loans
     */
    // public function index(Request $request)
    // {
    //     // Statistics
    //     // total approved loans
    //     $totalLoans = Loan::where('status', 'approved')->sum('amount');
    //     $pendingLoans = Loan::where('status', 'pending')->count(); //pending loans
    //     $activeLoans = Loan::where('status', 'approved')->count(); //active loans
    //     $overdueLoans = Loan::where('status', 'approved') //loan is approved
    //         ->whereNotNull('disbursed_at') //the loan is not null meaning it was actually disbursed
    //         ->whereRaw('DATE_ADD(disbursed_at, INTERVAL term_months MONTH) < NOW()')
    //         ->count(); //if the disbursed date and the term of repayment is less than today(overdue losn)

    //     // Build query:eager load the user and the loan
    //     $query = Loan::with('user');

    //     // Filter by status
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     // Search by user name and email
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->whereHas('user', function($q) use ($search) {
    //             $q->where('name', 'like', "%{$search}%")
    //               ->orWhere('email', 'like', "%{$search}%");
    //         });
    //     }

    //     // Sort by descending order
    //     $sortBy = $request->get('sort_by', 'created_at');
    //     $sortOrder = $request->get('sort_order', 'desc');
    //     $query->orderBy($sortBy, $sortOrder);

    //     $loans = $query->paginate(20); //show 20 per page

    //     // return view with loan details
    //     return view('admin.loans.index', compact(
    //         'loans',
    //         'totalLoans',
    //         'pendingLoans',
    //         'activeLoans',
    //         'overdueLoans'
    //     ));
    // }

    public function index(Request $request)
{
    // Statistics
    $totalLoans = Loan::where('status', 'approved')
        ->whereHas('user') // Only count loans with valid users
        ->sum('amount');
        
    $pendingLoans = Loan::where('status', 'pending')
        ->whereHas('user')
        ->count();
        
    $activeLoans = Loan::where('status', 'approved')
        ->whereHas('user')
        ->count();
        
    $overdueLoans = Loan::where('status', 'approved')
        ->whereNotNull('disbursed_at')
        ->whereHas('user')
        ->whereRaw('DATE_ADD(disbursed_at, INTERVAL term_months MONTH) < NOW()')
        ->count();

    // Build query: eager load the user and the loan
    $query = Loan::with('user')
        ->whereHas('user'); // Only get loans with valid users

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by payment status
    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }

    // Search by user name and email
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Sort by descending order
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);

    $loans = $query->paginate(20);

    return view('admin.loans.index', compact(
        'loans',
        'totalLoans',
        'pendingLoans',
        'activeLoans',
        'overdueLoans'
    ));
}

    /**
     * Show loan details
     */
    public function show(Loan $loan) //route model binding
    {
        $loan->load('user');// eager load the loan and the user

        // return view with user's loan
        return view('admin.loans.show', compact('loan'));
    }

    /**
     * Approve loan and credit user account
     */
    public function approve(Request $request, Loan $loan)
    {
        // Only pending loans can be approved, check if loan is pending
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be approved.');
        }

        // vaalidate inputs
        $request->validate([
            'transaction_pin' => 'required|digits:4',
            'notes' => 'nullable|string|max:500',
        ]);

        // get the currentl logged in admin user
        $admin = auth()->user();

        // Verify admin transaction pin
        if (!Hash::check($request->transaction_pin, $admin->transaction_pin)) {
            return back()->with('error', 'Incorrect transaction PIN.');
        }

        DB::beginTransaction(); //begin task

        try {
            $user = $loan->user; //get the user's loan

            // Update loan status to approved
            $loan->update([
                'status' => 'approved',
                'approved_at' => now(),
                'disbursed_at' => now(),
            ]);

            // Credit user account
            $user->increment('balance', $loan->amount);

            // Create transaction record
            $reference = 'LOAN-' . strtoupper(substr(uniqid(), -8));

            Transfer::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $loan->amount,
                'fee' => 0,
                'total_amount' => $loan->amount,
                'status' => 'successful',
                'description' => 'Loan disbursement - Loan ID: ' . $loan->id . ($request->notes ? ' - ' . $request->notes : ''),
                'reference' => $reference,
                'recipient_account_number' => $user->account_number,
                'recipient_name' => $user->name,
                'completed_at' => now(),
                'metadata' => json_encode([
                    'approved_by' => $admin->name . ' (Admin)',
                    'loan_id' => $loan->id,
                    'interest_rate' => $loan->interest_rate,
                    'term_months' => $loan->term_months,
                    'monthly_payment' => $loan->monthly_payment,
                    'admin_notes' => $request->notes,
                ]),
            ]);

            // Send loan approval email
            Mail::to($user->email)->send(new LoanApproved($loan));

            DB::commit(); //commit to database

            // save record in log file
            Log::channel('admin_actions')->info('Admin approved loan', [
                'admin_id' => $admin->id,
                'loan_id' => $loan->id,
                'user_id' => $user->id,
                'amount' => $loan->amount,
            ]);

            // return with success message
            return back()->with('success', 'Loan approved and ₦' . number_format($loan->amount, 2) . ' credited to user account!');

        } catch (Exception $e) {
            DB::rollBack(); //rollback changes if it fails
            Log::error('Loan approval failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve loan. Please try again.');
        }
    }

    /**
     * Reject loan application
     */
    public function reject(Request $request, Loan $loan)
    {
        // Only pending loans can be rejected
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be rejected.');
        }

        // validate input
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            // update status
            $loan->update([
                'status' => 'rejected',
            ]);

            // save log info
            Log::channel('admin_actions')->info('Admin rejected loan', [
                'admin_id' => auth()->id(),
                'loan_id' => $loan->id,
                'user_id' => $loan->user_id,
                'reason' => $request->reason,
            ]);

            // return with success message
            return back()->with('success', 'Loan application rejected.');

        } catch (Exception $e) {
            Log::error('Loan rejection failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to reject loan.');
        }
    }

    /**
     * Mark loan as fully paid
     */
    public function markPaid(Request $request, Loan $loan) //route model binding
    {
        // Only approved loans can be marked as paid; if the loan is not approved
        if ($loan->status !== 'approved') {
            return back()->with('error', 'Only approved loans can be marked as paid.');
        }

        // if the loan has a status of completed, you cannot mark as paid
        if ($loan->status === 'completed') {
            return back()->with('error', 'This loan is already marked as paid.');
        }

        // validate inputs
        $request->validate([
            'transaction_pin' => 'required|digits:4',
            'notes' => 'nullable|string|max:500',
        ]);

        $admin = auth()->user(); //user that is the admin

        // Verify admin  transaction pin
        if (!Hash::check($request->transaction_pin, $admin->transaction_pin)) {
            return back()->with('error', 'Incorrect transaction PIN.');
        }

        try {
            // update loan status
            $loan->update([
                'status' => 'completed',
            ]);

            // log to admin_actions
            Log::channel('admin_actions')->info('Admin marked loan as paid', [
                'admin_id' => $admin->id,
                'loan_id' => $loan->id,
                'user_id' => $loan->user_id,
                'notes' => $request->notes,
            ]);

            //return with success message
            return back()->with('success', 'Loan marked as fully paid!');

        } catch (Exception $e) {
            Log::error('Mark loan paid failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update loan status.');
        }
    }

    /**
     * Download loan report
     */
    public function downloadReport(Request $request)
    {
        $query = Loan::with('user'); //get the user's loan(eager load)

        // Apply filters; search by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // get the, by descending order(newest)
        $loans = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'loans_report_' . date('Y-m-d_His') . '.csv';
        
        // headers to tell the browser to download the file
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Stream the CSV output
        // CSV is generated using a streaming callback, which is memory efficient
        $callback = function() use ($loans) {
            $file = fopen('php://output', 'w');

              // CSV header row
            fputcsv($file, [
                'Loan ID',
                'User Name',
                'User Email',
                'Amount',
                'Interest Rate',
                'Term (months)',
                'Monthly Payment',
                'Status',
                'Application Date',
                'Approved Date',
                'Disbursed Date'
            ]);

             // loan Data rows
            foreach ($loans as $loan) {
                 //  fputcsv formats each line to csv format
                fputcsv($file, [
                    $loan->id,
                    $loan->user->name ?? 'N/A',
                    $loan->user->email ?? 'N/A',
                    $loan->amount,
                    $loan->interest_rate . '%',
                    $loan->term_months,
                    $loan->monthly_payment,
                    ucfirst($loan->status),
                    $loan->created_at->format('Y-m-d H:i:s'),
                    $loan->approved_at ? $loan->approved_at->format('Y-m-d H:i:s') : 'N/A',
                    $loan->disbursed_at ? $loan->disbursed_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file); //close file stream
        };

        
        // This line tells Laravel to stream a response back to the browser.
// Instead of generating the whole CSV file in memory first, Laravel sends the file piece by piece as it is being created.
        return response()->stream($callback, 200, $headers);
    }
}