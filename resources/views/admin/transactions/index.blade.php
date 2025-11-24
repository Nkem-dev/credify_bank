@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Transaction Management
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Monitor and manage all platform transactions</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Total Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-primary/10 rounded-lg">
                        <i class="ti ti-transfer-in text-2xl text-primary"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($totalTransactions, 2) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Volume</p>
            </div>

            <!-- Today's Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="ti ti-calendar-check text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">₦{{ number_format($todayTransactions, 2) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Today</p>
            </div>

            <!-- Pending Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <i class="ti ti-clock text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($totalPending) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pending</p>
            </div>

            <!-- Failed Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <i class="ti ti-x text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($totalFailed) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Failed</p>
            </div>

        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="space-y-4">
                
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Reference, account number..."
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- User Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">User</label>
                        <select name="user_id" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                        <select name="type" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">All Types</option>
                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                            <option value="internal" {{ request('type') == 'internal' ? 'selected' : '' }}>Internal Transfer</option>
                            <option value="external" {{ request('type') == 'external' ? 'selected' : '' }}>External Transfer</option>
                            <option value="airtime" {{ request('type') == 'airtime' ? 'selected' : '' }}>Airtime</option>
                            <option value="data" {{ request('type') == 'data' ? 'selected' : '' }}>Data</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">All Statuses</option>
                            <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition">
                            <i class="ti ti-filter mr-1"></i> Filter
                        </button>
                    </div>
                </div>

                <!-- Date and Amount Range -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                        <input type="date" 
                               name="date_from" 
                               value="{{ request('date_from') }}"
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                        <input type="date" 
                               name="date_to" 
                               value="{{ request('date_to') }}"
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Amount From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount From</label>
                        <input type="number" 
                               name="amount_from" 
                               value="{{ request('amount_from') }}"
                               placeholder="₦0"
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Amount To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount To</label>
                        <input type="number" 
                               name="amount_to" 
                               value="{{ request('amount_to') }}"
                               placeholder="₦1,000,000"
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Clear and Download -->
                    <div class="flex items-end space-x-2">
                        <a href="{{ route('admin.transactions.index') }}" 
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                            <i class="ti ti-x"></i>
                        </a>
                        <button type="submit" 
                                formaction="{{ route('admin.transactions.download') }}"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            <i class="ti ti-download"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Reference</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <!-- Reference -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-mono text-gray-900 dark:text-white">{{ $transaction->reference }}</p>
                                </td>

                                <!-- User -->
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $transaction->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->user->email ?? 'N/A' }}</p>
                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($transaction->type == 'deposit') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($transaction->type == 'internal') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                        @elseif($transaction->type == 'airtime') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                        @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">₦{{ number_format($transaction->amount, 2) }}</p>
                                    @if($transaction->fee > 0)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Fee: ₦{{ number_format($transaction->fee, 2) }}</p>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($transaction->status == 'successful') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('g:i A') }}</p>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                       class="text-primary hover:text-primary/80 font-medium text-sm">
                                        View Details →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-transfer-in text-4xl mb-3 block"></i>
                                    <p>No transactions found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 border-t dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
@endsection