<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
{
    $user = auth()->user(); //get the current authenticated user
    $totalTransactions = $user->totalSuccessfulTransactions(); //calls helper method to get the user's total successful transactions
    $eligible = $user->isEligibleForLoan(); //call the helper method to check if the user is eligible for a loan
    $maxLimit = $user->getMaxLoanLimit(); // calculates how much the user can borrow
    $loan = $user->loans()->latest()->first(); // get the user's loan, orderby latest loans(newest) and get the first one if none exist

    // return view and compact with the user, totaltransactions, eligiblity, maximum limit and loan. show in the view
    return view('user.loans.index', compact(
        'user', 'totalTransactions', 'eligible', 'maxLimit', 'loan'
    ));
}

 public function store(Request $request)
 {
    $user = auth()->user(); //get current authenticated user

    // if the user's has an existing loan where status is pending or approved , return with error message
    if ($user->loans()->whereIn('status', ['pending', 'approved'])->exists()) {
        return back()->with('error', 'You already have an active or pending loan.');
    }

    // if user is not eligible for a loan
    if (!$user->isEligibleForLoan()) {
        return back()->with('error', 'You need at least ₦50,000 in successful transactions to apply for a loan.');
    }

    // validate the inputs
     $request->validate([
        'amount' => 'required|numeric|min:10000',
        'term_months' => 'required|in:3,6,12',
    ]);

    // check loan limit
    $maxLimit = $user->getMaxLoanLimit();
    //if the amount requested for loan exceeds max loan limit return with error message telling the user their limit
    if ($request->amount > $maxLimit) { 
        return back()->with('error', "Maximum loan limit is ₦" . number_format($maxLimit));
    }

    // begin task
        DB::beginTransaction();

    try {
        // calculate montly payment
        $monthly = Loan::calculateMonthlyPayment(
            $request->amount, //loan amount requested
            5.00, // loan interest rate (5%)
            $request->term_months //months to repay back loan
        );

        // create loan record
        Loan::create([
            'user_id' => $user->id, //user that is borrowing
            'amount' => $request->amount, //amount requested
            'interest_rate' => 5.00, //interest rate
            'term_months' => $request->term_months, //months selected to pay back
            'monthly_payment' => round($monthly, 2), //round monthly payment to 2 decimal
            'status' => 'pending', //status (awaiting approval)
        ]);
 
        DB::commit(); //commit to database
        // after succesful submission
        return redirect()->route('user.loans.index')
            ->with('success', 'Loan application submitted! We review it within 24 hours.');
    } catch (Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to apply. Try again.');
    }

 }
}
