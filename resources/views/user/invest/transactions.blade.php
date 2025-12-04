@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Transaction History</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">View all your stock transactions</p>
            </div>
            <a href="{{ route('user.invest.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-primary dark:bg-primary hover:bg-primary/90 dark:hover:bg-primary/90 text-gray-900 dark:text-white rounded-lg transition">
                <i class="ti ti-arrow-left"></i>
                Back to Investments
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-list-check text-3xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Transactions</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalTransactions) }}</p>
            </div>

            <!-- Buy Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-shopping-cart text-3xl text-green-600 dark:text-green-400"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Buys</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($totalBuys, 2) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $buyCount }} transactions</p>
            </div>

            <!-- Sell Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-cash text-3xl text-orange-600 dark:text-orange-400"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Sells</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($totalSells, 2) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $sellCount }} transactions</p>
            </div>

            <!-- Net Flow -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-trending-{{ ($totalBuys - $totalSells) >= 0 ? 'down' : 'up' }} text-3xl text-purple-600 dark:text-purple-400"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Net Flow</p>
                <p class="text-2xl font-bold {{ ($totalBuys - $totalSells) >= 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                    {{ ($totalBuys - $totalSells) >= 0 ? '-' : '+' }}₦{{ number_format(abs($totalBuys - $totalSells), 2) }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Buy - Sell</p>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 mb-6">
            <form method="GET" action="{{ route('user.invest.transactions') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction Type</label>
                    <select name="type" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary">
                        <option value="">All Types</option>
                        <option value="buy" {{ request('type') == 'buy' ? 'selected' : '' }}>Buy</option>
                        <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>Sell</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary">
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                        <i class="ti ti-filter"></i> Filter
                    </button>
                    <a href="{{ route('user.invest.transactions') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg transition">
                        <i class="ti ti-x"></i> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="p-6 border-b dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Transactions</h3>
            </div>
            <div class="overflow-x-auto fancy-scrollbar">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price/Share</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold text-xs">
                                            {{ substr($transaction->stock->symbol, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $transaction->stock->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->stock->symbol }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $transaction->type === 'buy' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        <i class="ti ti-{{ $transaction->type === 'buy' ? 'shopping-cart' : 'cash' }}"></i>
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white font-medium">
                                    {{ number_format($transaction->quantity) }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">shares</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                    ₦{{ number_format($transaction->price_per_share, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900 dark:text-white">
                                        ₦{{ number_format($transaction->total_amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <i class="ti ti-check"></i> Completed
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-history-off text-5xl mb-3 opacity-50"></i>
                                    <p class="font-medium">No transactions found</p>
                                    <a href="{{ route('user.invest.stocks.index') }}" class="text-primary hover:underline text-sm mt-2 inline-block">
                                        Start investing
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="p-6 border-t dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

    </div>
</main>

<style>
    /* Fancy Scrollbar */
    .fancy-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 #f1f5f9;
    }

    .fancy-scrollbar::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }

    .fancy-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .fancy-scrollbar::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 3px;
        transition: background 0.3s ease;
    }

    .fancy-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    .dark .fancy-scrollbar {
        scrollbar-color: #64748b #1f2937;
    }

    .dark .fancy-scrollbar::-webkit-scrollbar-track {
        background: #1f2937;
    }

    .dark .fancy-scrollbar::-webkit-scrollbar-thumb {
        background: #64748b;
    }

    .dark .fancy-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection