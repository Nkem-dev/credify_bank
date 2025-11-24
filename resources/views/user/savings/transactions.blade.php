@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-6xl mx-auto mt-10">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('user.savings.index') }}" 
               class="inline-flex items-center text-primary hover:text-primary/80 mb-4 transition">
                <i class="ti ti-arrow-left mr-2"></i>
                Back to Savings
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Transaction History</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Current Balance</p>
                    <p class="text-2xl font-bold text-primary font-mono">
                        ₦{{ number_format($plan->current_balance, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Deposits -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Deposits</p>
                        <p class="text-xl font-bold text-green-600 dark:text-green-400">
                            ₦{{ number_format($stats['total_deposits'], 2) }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                        <i class="ti ti-arrow-down-left text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- Total Withdrawals -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Withdrawals</p>
                        <p class="text-xl font-bold text-red-600 dark:text-red-400">
                            ₦{{ number_format($stats['total_withdrawals'], 2) }}
                        </p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-lg">
                        <i class="ti ti-arrow-up-right text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
            </div>

            <!-- Total Interest -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Interest</p>
                        <p class="text-xl font-bold text-purple-600 dark:text-purple-400">
                            ₦{{ number_format($stats['total_interest'], 2) }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                        <i class="ti ti-percentage text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Transactions</p>
                        <p class="text-xl font-bold text-primary">
                            {{ number_format($stats['transaction_count']) }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                        <i class="ti ti-receipt text-2xl text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="ti ti-history text-primary mr-2"></i>
                    All Transactions
                </h3>
            </div>

            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date & Time
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Description
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <!-- Date & Time -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $transaction->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $transaction->created_at->format('g:i A') }}
                                        </div>
                                    </td>

                                    <!-- Type -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->type === 'deposit')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                <i class="ti ti-arrow-down-left mr-1"></i>
                                                Deposit
                                            </span>
                                        @elseif($transaction->type === 'withdrawal')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                <i class="ti ti-arrow-up-right mr-1"></i>
                                                Withdrawal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                                <i class="ti ti-percentage mr-1"></i>
                                                Interest
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Description -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $transaction->description ?? 'No description' }}
                                        </div>
                                    </td>

                                    <!-- Amount -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if($transaction->type === 'deposit' || $transaction->type === 'interest')
                                            <span class="text-lg font-semibold text-green-600 dark:text-green-400">
                                                + ₦{{ number_format($transaction->amount, 2) }}
                                            </span>
                                        @else
                                            <span class="text-lg font-semibold text-red-600 dark:text-red-400">
                                                - ₦{{ number_format($transaction->amount, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 border-t dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="ti ti-receipt-off text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 mb-2">No transactions yet</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500">
                        Start saving to see your transaction history
                    </p>
                </div>
            @endif
        </div>

        <!-- Plan Info Card -->
        <div class="mt-8 bg-gradient-to-r from-primary/10 to-accent/10 dark:from-primary/20 dark:to-accent/20 p-6 rounded-xl border dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Plan Details</h4>
                    <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                        <p><strong>Daily Interest Rate:</strong> {{ $plan->daily_interest_rate * 100 }}%</p>
                        @if($plan->target_amount)
                            <p><strong>Target Amount:</strong> ₦{{ number_format($plan->target_amount, 2) }}</p>
                        @endif
                        <p><strong>Created:</strong> {{ $plan->created_at->format('M d, Y') }}</p>
                        <p><strong>Last Interest Applied:</strong> {{ $plan->last_interest_applied_at?->diffForHumans() ?? 'Never' }}</p>
                    </div>
                </div>
                @if($plan->target_amount)
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Progress to Target</p>
                        <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-2">
                            <div class="bg-primary h-3 rounded-full transition-all"
                                 style="width: {{ min(100, ($plan->current_balance / $plan->target_amount) * 100) }}%">
                            </div>
                        </div>
                        <p class="text-lg font-bold text-primary">
                            {{ number_format(min(100, ($plan->current_balance / $plan->target_amount) * 100), 1) }}%
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection