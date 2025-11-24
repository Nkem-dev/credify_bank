<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SavingsPlan;
use App\Models\SavingsTransaction;
use App\Models\Transfer;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SavingsController extends Controller
{
    public function index()
    {
        $user = auth()->user(); //get current authenticated user
        $plans = $user->savingsPlans()->with('transactions')->get(); //get user's savings plan with savings transactions (the transactions is coming from the relationship in the savings plan)

        // Apply interest to all plans
        foreach ($plans as $plan) {
            $plan->applyDailyInterest();
        }

        // return view and show user's savings plans 
        return view('user.savings.index', compact('plans', 'user'));
    }
public function create()
{ //return the form view to create plan
    return view('user.savings.create');
}

   
public function store(Request $request)
{
    // validate the inputs
    $request->validate([
        'name' => 'required|string|max:255',
        'target_amount' => 'nullable|numeric|min:1000',
        'daily_interest_rate' => 'required|numeric|min:0', 
    ]);

    $user = auth()->user(); //get currently authenticated user

    // create new savings plan record in the database
    SavingsPlan::create([
        'user_id' => $user->id,
        'name' => $request->name,
        'slug' => Str::slug($request->name) . '-' . Str::random(4),
        'target_amount' => $request->target_amount,
        'daily_interest_rate' => $request->daily_interest_rate, 
        'last_interest_applied_at' => now(),
    ]);

    // return redirect to index page with success message
    return redirect()->route('user.savings.index')
        ->with('success', 'Savings plan created successfully!');
}

 public function fund(Request $request, SavingsPlan $plan)
    { //validate input
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        $user = auth()->user(); //get the currently authenticated user

        // Ensure user owns this plan
        if ($plan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if user has enough balance
        if ($user->balance < $request->amount) {
            return back()->with('error', 'Insufficient balance. Available: ₦' . number_format($user->balance, 2));
        }

        // begin task
        DB::beginTransaction();
        try {
            // Deduct from user's main wallet 
            $user->balance -= $request->amount;
            $user->save();

            // Add to savings plan
            $plan->current_balance += $request->amount;
            $plan->save();

            // Record in savings transactions table
            SavingsTransaction::create([
                'savings_plan_id' => $plan->id,
                'amount' => $request->amount,
                'type' => 'deposit',
                'description' => "Deposited ₦" . number_format($request->amount, 2) . " to {$plan->name}",
            ]);

            DB::commit(); //commit to database

            // return with success message
            return back()->with('success', '₦' . number_format($request->amount, 2) . ' saved successfully!');

        } catch (Exception $e) {
            DB::rollBack(); //if failed, rollback changes
            Log::error('Savings fund error: ' . $e->getMessage());
            return back()->with('error', 'Failed to save money: ' . $e->getMessage());
        }
    }

    // withdraw savings
    public function withdraw(Request $request, SavingsPlan $plan)
    {
        //validate input
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $user = auth()->user(); //get currently authenticated user

        //  Ensure user owns this plan
        if ($plan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if savings plan has enough balance
        if ($plan->current_balance < $request->amount) {
            return back()->with('error', 'Insufficient savings balance. Available: ₦' . number_format($plan->current_balance, 2));
        }

        DB::beginTransaction();
        try {
            // Deduct from savings plan
            $plan->current_balance -= $request->amount;
            $plan->save();

            // Add back to user's main wallet
            $user->balance += $request->amount;
            $user->save();

            // Record in savings transactions table
            SavingsTransaction::create([
                'savings_plan_id' => $plan->id,
                'amount' => $request->amount,
                'type' => 'withdrawal',
                'description' => "Withdrawn ₦" . number_format($request->amount, 2) . " from {$plan->name}",
            ]);

            DB::commit(); //commit to datbase

            // return with success message
            return back()->with('success', '₦' . number_format($request->amount, 2) . ' withdrawn to your wallet!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Savings withdrawal error: ' . $e->getMessage());
            return back()->with('error', 'Failed to withdraw: ' . $e->getMessage());
        }
    }

    // delete savings plan
    public function destroy(SavingsPlan $plan)
    {
        $user = auth()->user(); //get the authenticated  user 

        //  Ensure user owns this plan
        if ($plan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction(); //begin task 
        try {
            // If there's money in the plan, return it to wallet first
            if ($plan->current_balance > 0) {
                $user->balance += $plan->current_balance;
                $user->save();

                // Record final withdrawal in savings transactions
                SavingsTransaction::create([
                    'savings_plan_id' => $plan->id,
                    'amount' => $plan->current_balance,
                    'type' => 'withdrawal',
                    'description' => "Plan deleted - ₦" . number_format($plan->current_balance, 2) . " returned to wallet",
                ]);
            }

            // Delete the plan 
            $plan->delete();

            DB::commit(); //commit to database

            return redirect()->route('user.savings.index')
                ->with('success', 'Savings plan deleted. Balance returned to wallet.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Savings plan deletion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete plan: ' . $e->getMessage());
        }
    }

    /**
     * Show transaction history for a specific savings plan
     */
    public function transactions(SavingsPlan $plan)
    {
        $user = auth()->user();

        // Ensure user owns this plan
        if ($plan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Get transactions with pagination
        $transactions = $plan->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate statistics
        $stats = [
            'total_deposits' => $plan->transactions()->where('type', 'deposit')->sum('amount'),
            'total_withdrawals' => $plan->transactions()->where('type', 'withdrawal')->sum('amount'),
            'total_interest' => $plan->transactions()->where('type', 'interest')->sum('amount'),
            'transaction_count' => $plan->transactions()->count(),
        ];

        return view('user.savings.transactions', compact('plan', 'transactions', 'stats'));
    }
}
