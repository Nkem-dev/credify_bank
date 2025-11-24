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

    public function showBalanceForm(User $user)
    {
         return view('admin.users.credit', compact('user'));
    }


   public function credit(Request $request, User $user)
{
    $admin = auth()->user();

    $validated = $request->validate([
        'amount'       => 'required|numeric|min:100|max:50000000',
        'description'  => 'required|string|max:255',
        'transaction_pin' => 'required|digits:4',
    ]);

    // Verify Admin Transaction PIN
    if (!Hash::check($validated['transaction_pin'], $admin->transaction_pin)) {
        return back()->with('error', 'Incorrect transaction PIN.');
    }

    DB::beginTransaction();

    try {
        $amount = $validated['amount'];

        // Credit user balance
        $user->increment('balance', $amount);

        // Create transfer record
        $reference = 'CR-' . strtoupper(substr(uniqid(), -8));

        Transfer::create([
            'user_id'     => $user->id,
            'type'        => 'deposit',
            'amount'      => $amount,
            'fee'         => 0,
            'status'      => 'successful',
            'description' => 'Admin Credit: ' . $validated['description'],
            'reference'   => $reference,
            'metadata'    => json_encode([
                'credited_by' => $admin->name . ' (Admin)',
                'ip_address'  => $request->ip(),
            ]),
        ]);

        DB::commit();

        Log::channel('admin_actions')->info('Admin credited user', [
            'admin_id' => $admin->id,
            'user_id'  => $user->id,
            'amount'   => $amount,
            'ref'      => $reference,
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', '₦' . number_format($amount, 2) . ' credited successfully!');

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Admin credit failed: ' . $e->getMessage());

        return back()->with('error', 'Failed to credit account. Please try again.');
    }
}
   

}
