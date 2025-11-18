<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\SavingsPlan;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
     public function adminDashboard() 
    {
        // dd('Welcome to Admin dashboard');
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count(); 
        $inactiveUsers = User::where('status', 'inactive')->count(); 
        $suspendedUsers = User::where('status', 'suspended')->count(); 
        $newUsersToday = User::whereDate('created_at', today())->count();
        $newUsersThisWeek = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();

        // ==================== TRANSACTION STATISTICS ====================
        // Total Transactions
        $totalTransactionsToday = Transfer::whereDate('created_at', today())->count();
        $totalTransactionsWeek = Transfer::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $totalTransactionsMonth = Transfer::whereMonth('created_at', now()->month)->count();
        $totalTransactionsAll = Transfer::count();

        // Transaction Volume (Amount)
        $transactionVolumeToday = Transfer::where('status', 'successful')
            ->whereDate('created_at', today())
            ->sum('amount');
        $transactionVolumeWeek = Transfer::where('status', 'successful')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');
        $transactionVolumeMonth = Transfer::where('status', 'successful')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        $transactionVolumeAll = Transfer::where('status', 'successful')->sum('amount');

        // Deposits
        $totalDeposits = Transfer::where('type', 'deposit')
            ->where('status', 'successful')
            ->sum('amount');
        $depositsToday = Transfer::where('type', 'deposit')
            ->where('status', 'successful')
            ->whereDate('created_at', today())
            ->sum('amount');
        $depositsCount = Transfer::where('type', 'deposit')
            ->where('status', 'successful')
            ->count();

        // Withdrawals
        $totalWithdrawals = Transfer::whereIn('type', ['internal', 'airtime', 'data', 'withdrawal'])
            ->where('status', 'successful')
            ->sum('amount');
        $withdrawalsToday = Transfer::whereIn('type', ['internal', 'airtime', 'data', 'withdrawal'])
            ->where('status', 'successful')
            ->whereDate('created_at', today())
            ->sum('amount');
        $withdrawalsCount = Transfer::whereIn('type', ['internal', 'airtime', 'data', 'withdrawal'])
            ->where('status', 'successful')
            ->count();

        // ==================== SAVINGS STATISTICS ====================
        $activeSavings = SavingsPlan::count();
        $totalSavingsBalance = SavingsPlan::sum('current_balance');
        $savingsGrowthThisMonth = SavingsPlan::whereMonth('created_at', now()->month)->count();

        // ==================== LOAN STATISTICS ====================
        $activeLoans = Loan::whereIn('status', ['pending', 'approved'])->count();
        $pendingLoanApplications = Loan::where('status', 'pending')->count();
        $totalLoansAmount = Loan::where('status', 'approved')->sum('amount');
        $loansRepaidAmount = Loan::where('status', 'completed')->sum('amount');

        // ==================== REVENUE STATISTICS ====================
        $revenueToday = Transfer::whereDate('created_at', today())->sum('fee');
        $revenueThisWeek = Transfer::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('fee');
        $revenueThisMonth = Transfer::whereMonth('created_at', now()->month)->sum('fee');
        $revenueTotal = Transfer::sum('fee');

        // ==================== PENDING ACTIONS ====================
        $pendingKYC = 0; // Implement when you have KYC system

        // ==================== CHART DATA ====================
        
        // User Registration Trend (Last 12 months)
        $userRegistrationTrend = User::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month');

        // Transaction Volume Trend (Last 30 days)
        $transactionVolumeTrend = Transfer::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount) as total')
        )
        ->where('status', 'successful')
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->pluck('total', 'date');

        // Revenue Trend (Last 12 months)
        $revenueTrend = Transfer::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(fee) as total')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

        // Transaction Status Distribution
        $transactionStatusDistribution = Transfer::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Transaction Type Distribution
        $transactionTypeDistribution = Transfer::select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type');

        // Recent Transactions
        $recentTransactions = Transfer::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent Users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
           // Users
    'totalUsers' => $totalUsers,
    'activeUsers' => $activeUsers,
    'inactiveUsers' => $inactiveUsers,
    'suspendedUsers' => $suspendedUsers,
    'newUsersToday' => $newUsersToday,
    'newUsersThisWeek' => $newUsersThisWeek,
    'newUsersThisMonth' => $newUsersThisMonth,
    
    // Transactions
    'totalTransactionsToday' => $totalTransactionsToday,
    'totalTransactionsWeek' => $totalTransactionsWeek,
    'totalTransactionsMonth' => $totalTransactionsMonth,
    'totalTransactionsAll' => $totalTransactionsAll,
    'transactionVolumeToday' => $transactionVolumeToday,
    'transactionVolumeWeek' => $transactionVolumeWeek,
    'transactionVolumeMonth' => $transactionVolumeMonth,
    'transactionVolumeAll' => $transactionVolumeAll,
    
    // Deposits & Withdrawals
    'totalDeposits' => $totalDeposits,
    'depositsToday' => $depositsToday,
    'depositsCount' => $depositsCount,
    'totalWithdrawals' => $totalWithdrawals,
    'withdrawalsToday' => $withdrawalsToday,
    'withdrawalsCount' => $withdrawalsCount,
    
    // Savings
    'activeSavings' => $activeSavings,
    'totalSavingsBalance' => $totalSavingsBalance,
    'savingsGrowthThisMonth' => $savingsGrowthThisMonth,
    
    // Loans
    'activeLoans' => $activeLoans,
    'pendingLoanApplications' => $pendingLoanApplications,
    'totalLoansAmount' => $totalLoansAmount,
    'loansRepaidAmount' => $loansRepaidAmount,
    
    // Pending
    'pendingKYC' => $pendingKYC,
    
   
    // Recent Data
    'recentTransactions' => $recentTransactions,
    'recentUsers' => $recentUsers,
        ]);
        
    }
}
