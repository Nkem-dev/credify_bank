@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Welcome Message -->
    <div class="mb-6 md:mb-8 mt-20">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
          Welcome, 
            <span class="text-[#5E84ff]">{{ Auth::user()->name }}</span>
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
            Monitor and manage Credify Bank operations
        </p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-users text-xl md:text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <span class="text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded-full">
                    +{{ $newUsersToday }} today
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Total Users</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
            <div class="mt-3 flex items-center justify-between text-xs">
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                    <span class="text-gray-600 dark:text-gray-400">{{ number_format($activeUsers) }}</span>
                </div>
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                    <span class="text-gray-600 dark:text-gray-400">{{ number_format($inactiveUsers) }}</span>
                </div>
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                    <span class="text-gray-600 dark:text-gray-400">{{ number_format($suspendedUsers) }}</span>
                </div>
            </div>
        </div>

        <!-- Transaction Volume -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-cash text-xl md:text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                <span class="text-xs bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-1 rounded-full">
                    This Month
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Transaction Volume</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($transactionVolumeMonth, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                {{ number_format($totalTransactionsMonth) }} transactions
            </p>
        </div>

        

        <!-- Pending Actions -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-alert-triangle text-xl md:text-2xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <span class="text-xs bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 px-2 py-1 rounded-full">
                    Action Required
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Pending Items</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingLoanApplications + $pendingKYC }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                {{ $pendingLoanApplications }} loans, {{ $pendingKYC }} KYC
            </p>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Deposits -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 md:p-6 rounded-xl shadow-sm border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between mb-4">
                <i class="ti ti-arrow-down-left text-2xl md:text-3xl text-green-600 dark:text-green-400"></i>
                <span class="text-xs font-medium text-green-700 dark:text-green-400">{{ number_format($depositsCount) }} transactions</span>
            </div>
            <h3 class="text-xs md:text-sm text-green-700 dark:text-green-400 mb-1">Total Deposits</h3>
            <p class="text-xl md:text-2xl font-bold text-green-800 dark:text-green-300">₦{{ number_format($totalDeposits, 2) }}</p>
            <p class="text-xs text-green-600 dark:text-green-500 mt-2">Today: ₦{{ number_format($depositsToday, 2) }}</p>
        </div>

        <!-- Withdrawals -->
        <div class="bg-[#F6F5DC] p-4 md:p-6 rounded-xl shadow-sm border border-[#6A6A1A] dark:border-[#6A6A1A]">
            <div class="flex items-center justify-between mb-4">
                <i class="ti ti-arrow-up-right text-2xl md:text-3xl text-[#6A6A1A] dark:text-[#6A6A1A]"></i>
                <span class="text-xs font-medium text-[#6A6A1A] dark:text-[#6A6A1A]">{{ number_format($withdrawalsCount) }} transactions</span>
            </div>
            <h3 class="text-xs md:text-sm text-[#6A6A1A] dark:text-[#6A6A1A] mb-1">Total Withdrawals</h3>
            <p class="text-xl md:text-2xl font-bold text-[#6A6A1A] dark:text-[#6A6A1A]">₦{{ number_format($totalWithdrawals, 2) }}</p>
            <p class="text-xs text-[#6A6A1A] dark:text-[#6A6A1A] mt-2">Today: ₦{{ number_format($withdrawalsToday, 2) }}</p>
        </div>

        <!-- Savings & Loans -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 p-4 md:p-6 rounded-xl shadow-sm border border-indigo-200 dark:border-indigo-800">
            <div class="flex justify-between items-start mb-4">
                <i class="ti ti-pig-money text-2xl md:text-3xl text-indigo-600 dark:text-indigo-400"></i>
                <div class="text-right">
                    <p class="text-xs text-indigo-700 dark:text-indigo-400">Active Savings</p>
                    <p class="text-base md:text-lg font-bold text-indigo-800 dark:text-indigo-300">{{ number_format($activeSavings) }}</p>
                </div>
            </div>
            <h3 class="text-xs md:text-sm text-indigo-700 dark:text-indigo-400 mb-1">Savings Balance</h3>
            <p class="text-xl md:text-2xl font-bold text-indigo-800 dark:text-indigo-300">₦{{ number_format($totalSavingsBalance, 2) }}</p>
            <div class="flex justify-between mt-3 pt-3 border-t border-indigo-200 dark:border-indigo-700">
                <div>
                    <p class="text-xs text-indigo-600 dark:text-indigo-500">Active Loans</p>
                    <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-300">{{ number_format($activeLoans) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-indigo-600 dark:text-indigo-500">Loan Amount</p>
                    <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-300">₦{{ number_format($totalLoansAmount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Recent Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="p-4 md:p-6 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
                <a href="{{ route('admin.transactions.index') }}" class="text-primary text-xs md:text-sm font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[400px] overflow-y-auto">
                @forelse($recentTransactions->take(5) as $transaction)
                    <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 md:space-x-3 flex-1 min-w-0">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $transaction->type === 'deposit' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                    <i class="ti text-sm md:text-base {{ $transaction->type === 'deposit' ? 'ti-arrow-down-left text-green-600' : 'ti-arrow-up-right text-red-600' }}"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white text-xs md:text-sm truncate">{{ $transaction->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ ucfirst($transaction->type) }} • {{ $transaction->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right ml-2 flex-shrink-0">
                                <p class="font-semibold text-xs md:text-sm {{ $transaction->type === 'deposit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'deposit' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                </p>
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    {{ $transaction->status === 'successful' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="ti ti-receipt text-4xl mb-2 block"></i>
                        <p class="text-sm">No recent transactions</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="p-4 md:p-6 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white">New Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-primary text-xs md:text-sm font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[400px] overflow-y-auto">
                @forelse($recentUsers as $user)
                    <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 md:space-x-3 flex-1 min-w-0">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary font-semibold text-xs md:text-sm">{{ substr($user->name, 0, 2) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white text-xs md:text-sm truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right ml-2 flex-shrink-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $user->created_at->diffForHumans() }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    ₦{{ number_format($user->balance, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="ti ti-users text-4xl mb-2 block"></i>
                        <p class="text-sm">No recent users</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection