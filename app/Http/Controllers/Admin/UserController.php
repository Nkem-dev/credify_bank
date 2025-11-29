<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
       
        $total = User::count(); //get the total users
        $active = User::where('status', 'active')->count(); //total active users
        $inactive = User::where('status', 'inactive')->count(); //total inactive users
        $suspended = User::where('status', 'suspended')->count(); //total suspended users
        $admins = User::where('role', 'admin')->count(); //total admin 

        // Users query with search and filters
        $query = User::query();

        // Search users with name, email, account number 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
                //   ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by tier
        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        // Filter by KYC status
        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');    // sort by newest first
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(5); //show 5 users per page

        // return the view with users, total users, active users, inactive users, suspended users and admin
        return view('admin.users.index', compact(
            'users',
            'total',
            'active',
            'inactive',
            'suspended',
            'admins'
        ));
    }

// show user information
      public function show(User $user)
    {
        // Load relationships
        $user->load([
            'transfers' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10); //last 10 transfers
            },
            'savingsPlans', //load savings plans and loans
            'loans'
            // 'walletFundings'
             => function($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            },
            // 'securityAlerts' => function($query) {
            //     $query->orderBy('created_at', 'desc')->limit(10);
            // },
        ]);

        // Get login history from sessions
        $loginHistory = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->limit(10)
            ->get();

        // Transaction statistics
        $totalTransactions = $user->transfers()->where('status', 'successful')->sum('amount'); //total successful outgoing transfers
        // $totalDeposits = $user->walletFundings()->where('status', 'successful')->sum('amount');
        $totalSavings = $user->savingsPlans()->sum('current_balance'); //total savings balance
        $totalLoans = $user->loans()->where('status', 'approved')->sum('amount'); //total loans

        // return view with details
        return view('admin.users.show', compact(
            'user',
            'loginHistory',
            'totalTransactions',
            // 'totalDeposits',
            'totalSavings',
            'totalLoans'
        ));
    }
    // credit user account
public function credit(Request $request, User $user)
{
    // validate inputs
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'description' => 'nullable|string|max:255',
    ]);

    $amount = $request->amount; //amount to credit user
    $description = $request->description ?? 'Manual credit by admin'; //description

    try {
        DB::beginTransaction(); //begin task

        // Update user balance
        $user->balance += $amount; //credit user's account
        $user->save(); //save to database

        // Record transaction
        Transfer::create([
            'user_id' => $user->id,
            'recipient_name' => $user->name,
            'recipient_account_number' => $user->account_number,
            'type' => 'deposit',
            'amount' => $amount,
            'total_amount' => $amount, 
            'status' => 'successful',
            'description' => $description,
            'reference' => 'CRED-' . strtoupper(Str::random(10)),
        ]);

        DB::commit(); //commit to database

        // return woith success message
        return redirect()->back()->with('success', "Successfully credited ₦" . number_format($amount, 2) . " to {$user->name}'s account.");

    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to credit account: ' . $e->getMessage());
    }
}

// debit user's account
public function debit(Request $request, User $user)
{
    // validate inputs
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'description' => 'nullable|string|max:255',
    ]);

    $amount = $request->amount; //get the amount requested to debit
    $description = $request->description ?? 'Manual debit by admin'; //description

    // Check if user has sufficient balance
    if ($user->balance < $amount) {
        return redirect()->back()->with('error', 'Insufficient balance. User only has ₦' . number_format($user->balance, 2));
    }

    try {
        // start task
        DB::beginTransaction();

        // Update balance
        $user->balance -= $amount; //debit user account
        $user->save();

        // Record transaction
        Transfer::create([
            'user_id' => $user->id,
            'recipient_name' => $user->name,
            'recipient_account_number' => $user->account_number,
            'type' => 'withdrawal',
            'amount' => $amount,
            'total_amount' => $amount,
            'status' => 'successful',
            'description' => $description,
            'reference' => 'DEB-' . strtoupper(Str::random(10)),
        ]);

        DB::commit(); //commit to database

        // return with success message 
        return redirect()->back()->with('success', "Successfully debited ₦" . number_format($amount, 2) . " from {$user->name}'s account.");

    } catch (Exception $e) { //if it fails
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to debit account: ' . $e->getMessage());
    }
}

// deactivate user
public function deactivate(Request $request, User $user)
{
    // validate inputs
    $request->validate([
        'reason' => 'nullable|string|max:500',
    ]);

    // Check if user is already deactivated
    if ($user->status === 'inactive') {
        return redirect()->back()->with('error', 'User is already deactivated.');
    }

    try {
        DB::beginTransaction();

        // Update user status
        $user->status = 'inactive';
        $user->save(); //save user status

       
        DB::commit(); //commit to database

        // return with success message
        return redirect()->back()->with('success', "Successfully deactivated {$user->name}'s account.");

    } catch (Exception $e) { //if it fails
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to deactivate account: ' . $e->getMessage());
    }
}

// suspend user
public function suspend(Request $request, User $user)
{
    // validate input
    $request->validate([
        'reason' => 'nullable|string|max:500',
    ]);

    // Check if user is already suspended
    if ($user->status === 'suspended') {
        return redirect()->back()->with('error', 'User is already suspended.');
    }

    try {
        // begin task
        DB::beginTransaction();

        // Update user status
        $user->status = 'suspended';
        $user->save(); //save to database

        DB::commit(); //commit to database

        // return with success message
        return redirect()->back()->with('success', "Successfully suspended {$user->name}'s account.");

    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to suspend account: ' . $e->getMessage());
    }
}

// activate user's account
public function activate(Request $request, User $user)
{
    // Check if user is already active
    if ($user->status === 'active') {
        return redirect()->back()->with('error', 'User is already active.');
    }

    try {
        DB::beginTransaction(); //begin task

        // Update user status
        $user->status = 'active';
        $user->save(); //save to database

       
        DB::commit(); //commit to database

        // return witn success message
        return redirect()->back()->with('success', "Successfully activated {$user->name}'s account.");

    } catch (Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to activate account: ' . $e->getMessage());
    }
}
}
