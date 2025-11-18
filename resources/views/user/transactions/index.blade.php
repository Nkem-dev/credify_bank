@extends('layouts.user')

@section('content')
<div class="mt-16 max-w-7xl mx-auto px-4 py-20">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Transaction History</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">View and manage all your transactions</p>
            </div>
            <a href="{{ route('user.dashboard') }}" 
               class="text-gray-600 dark:text-gray-400 hover:text-primary text-sm font-medium flex items-center">
                <i class="ti ti-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                    <i class="ti ti-receipt text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Transactions</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
        </div>

        <!-- Total Money In -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                    <i class="ti ti-arrow-down-left text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Money In</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">₦{{ number_format($stats['total_amount_in'], 2) }}</p>
        </div>

        <!-- Total Money Out -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                    <i class="ti ti-arrow-up-right text-2xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Money Out</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">₦{{ number_format($stats['total_amount_out'], 2) }}</p>
        </div>

        <!-- Status Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/20 rounded-full flex items-center justify-center">
                    <i class="ti ti-chart-pie text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Status Breakdown</p>
            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span class="text-green-600 dark:text-green-400">Successful:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['successful'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-yellow-600 dark:text-yellow-400">Pending:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['pending'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-red-600 dark:text-red-400">Failed:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $stats['failed'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Export -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 mb-6">
        <form method="GET" action="{{ route('user.transactions.index') }}" class="grid md:grid-cols-5 gap-4">
            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                <select name="type" 
                        class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="deposit" {{ $type === 'deposit' ? 'selected' : '' }}>Deposits</option>
                    <option value="internal" {{ $type === 'internal' ? 'selected' : '' }}>Transfers</option>
                    <option value="airtime" {{ $type === 'airtime' ? 'selected' : '' }}>Airtime</option>
                    <option value="data" {{ $type === 'data' ? 'selected' : '' }}>Data</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" 
                        class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="successful" {{ $status === 'successful' ? 'selected' : '' }}>Successful</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ $dateFrom }}"
                       class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ $dateTo }}"
                       class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary">
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 bg-primary hover:bg-indigo-700 text-white py-2 rounded-lg transition flex items-center justify-center">
                    <i class="ti ti-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('user.transactions.index') }}" 
                   class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="ti ti-x"></i>
                </a>
            </div>
        </form>

        <!-- Export Button -->
        <div class="mt-4 pt-4 border-t dark:border-gray-700">
            <a href="{{ route('user.transactions.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                <i class="ti ti-download mr-2"></i> Export to CSV
            </a>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $tx->created_at->format('M d, Y') }}
                                <span class="block text-xs text-gray-500 dark:text-gray-400">
                                    {{ $tx->created_at->format('g:i A') }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                @if($tx->type === 'deposit')
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-arrow-down-left text-green-600"></i>
                                        <span class="font-medium">Wallet Funding</span>
                                    </div>
                                @elseif($tx->type === 'internal')
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-arrow-up-right text-red-600"></i>
                                        <div>
                                            <p class="font-medium">Transfer to {{ $tx->recipient_name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tx->recipient_account_number }}</p>
                                        </div>
                                    </div>
                                @elseif($tx->type === 'airtime')
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-phone text-accent"></i>
                                        <div>
                                            <p class="font-medium">Airtime Purchase</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tx->recipient_account_number }} ({{ ucfirst($tx->recipient_name) }})</p>
                                        </div>
                                    </div>
                                @elseif($tx->type === 'data')
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-wifi text-blue-600"></i>
                                        <div>
                                            <p class="font-medium">Data Bundle</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tx->description }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="font-medium">{{ ucfirst($tx->type) }}</span>
                                @endif

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Ref: {{ $tx->reference }}
                                </p>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(in_array($tx->type, ['deposit']))
                                    <span class="text-green-600 font-semibold">+ ₦{{ number_format($tx->amount, 2) }}</span>
                                @else
                                    <span class="text-red-600 font-semibold">- ₦{{ number_format($tx->amount, 2) }}</span>
                                @endif
                                @if($tx->fee > 0)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Fee: ₦{{ number_format($tx->fee, 2) }}</p>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                    @if($tx->status === 'successful') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($tx->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('user.transactions.show', $tx->reference) }}"
                                   class="text-primary hover:text-primary/80 font-medium">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="ti ti-receipt text-4xl mb-3 block"></i>
                                <p>No transactions found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t dark:border-gray-700">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection