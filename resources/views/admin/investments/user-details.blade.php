@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $user->name }}'s Investment Portfolio
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    {{ $user->email }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.investments.all') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Investment Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Invested -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-6 rounded-xl border border-purple-200 dark:border-purple-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-wallet text-2xl text-purple-600 dark:text-purple-400"></i>
                <span class="text-xs bg-purple-200 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400 px-2 py-1 rounded-full">Total</span>
            </div>
            <h3 class="text-xs text-purple-700 dark:text-purple-400 mb-1">Total Invested</h3>
            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">₦{{ number_format($investment->total_invested ?? 0, 2) }}</p>
        </div>

        <!-- Current Value -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-xl border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-chart-line text-2xl text-blue-600 dark:text-blue-400"></i>
                <span class="text-xs bg-blue-200 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 px-2 py-1 rounded-full">Current</span>
            </div>
            <h3 class="text-xs text-blue-700 dark:text-blue-400 mb-1">Current Value</h3>
            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">₦{{ number_format($investment->current_value ?? 0, 2) }}</p>
        </div>

        <!-- Profit/Loss -->
        @php
            $profitLoss = $investment->total_profit_loss ?? 0;
            $color = $profitLoss >= 0 ? 'green' : 'red';
        @endphp
        <div class="bg-gradient-to-br from-{{ $color }}-50 to-{{ $color }}-100 dark:from-{{ $color }}-900/20 dark:to-{{ $color }}-800/20 p-6 rounded-xl border border-{{ $color }}-200 dark:border-{{ $color }}-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-trending-{{ $profitLoss >= 0 ? 'up' : 'down' }} text-2xl text-{{ $color }}-600 dark:text-{{ $color }}-400"></i>
                <span class="text-xs bg-{{ $color }}-200 dark:bg-{{ $color }}-900/50 text-{{ $color }}-700 dark:text-{{ $color }}-400 px-2 py-1 rounded-full">
                    {{ number_format($investment->profit_loss_percentage ?? 0, 2) }}%
                </span>
            </div>
            <h3 class="text-xs text-{{ $color }}-700 dark:text-{{ $color }}-400 mb-1">Total {{ $profitLoss >= 0 ? 'Profit' : 'Loss' }}</h3>
            <p class="text-2xl font-bold text-{{ $color }}-900 dark:text-{{ $color }}-100">
                {{ $profitLoss >= 0 ? '+' : '' }}₦{{ number_format($profitLoss, 2) }}
            </p>
        </div>

        <!-- Stocks Owned -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-6 rounded-xl border border-orange-200 dark:border-orange-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-building-bank text-2xl text-orange-600 dark:text-orange-400"></i>
                <span class="text-xs bg-orange-200 dark:bg-orange-900/50 text-orange-700 dark:text-orange-400 px-2 py-1 rounded-full">Active</span>
            </div>
            <h3 class="text-xs text-orange-700 dark:text-orange-400 mb-1">Stocks Owned</h3>
            <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ $userStocks->count() }}</p>
        </div>
    </div>

    <!-- Portfolio & Activity Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 mb-6">
        <div class="border-b dark:border-gray-700">
            <nav class="flex space-x-4 px-6" id="tabs">
                <button onclick="switchTab('portfolio')" class="tab-btn py-4 px-2 border-b-2 border-primary text-primary font-medium text-sm" data-tab="portfolio">
                    Portfolio
                </button>
                <button onclick="switchTab('transactions')" class="tab-btn py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium text-sm" data-tab="transactions">
                    Transactions
                </button>
                <button onclick="switchTab('watchlist')" class="tab-btn py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 font-medium text-sm" data-tab="watchlist">
                    Watchlist
                </button>
            </nav>
        </div>

        <!-- Portfolio Tab -->
        <div id="portfolio-tab" class="tab-content p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Stock Holdings</h3>
            <div class="overflow-x-auto fancy-scrollbar">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Avg Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Current Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Value</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Profit/Loss</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($userStocks as $userStock)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center">
                                            <span class="text-xs font-bold text-white">{{ $userStock->stock->symbol }}</span>
                                        </div>
                                        
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $userStock->stock->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $userStock->stock->category }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ number_format($userStock->quantity) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">₦{{ number_format($userStock->average_price, 2) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">₦{{ number_format($userStock->stock->current_price, 2) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">₦{{ number_format($userStock->total_value, 2) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-semibold {{ $userStock->profit_loss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $userStock->profit_loss >= 0 ? '+' : '' }}₦{{ number_format($userStock->profit_loss, 2) }}
                                        </span>
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ $userStock->profit_loss >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                            {{ number_format($userStock->profit_loss_percentage, 2) }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-chart-line-off text-4xl mb-2 block"></i>
                                    <p class="text-sm">No stocks in portfolio</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transactions Tab -->
        <div id="transactions-tab" class="tab-content hidden p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Transaction History</h3>
            <div class="space-y-3">
                @forelse($transactions as $transaction)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="w-10 h-10 rounded-lg bg-{{ $transaction->transaction_type === 'buy' ? 'green' : 'red' }}-100 dark:bg-{{ $transaction->transaction_type === 'buy' ? 'green' : 'red' }}-900/30 flex items-center justify-center">
                                    <i class="ti ti-{{ $transaction->transaction_type === 'buy' ? 'arrow-down-left' : 'arrow-up-right' }} text-{{ $transaction->transaction_type === 'buy' ? 'green' : 'red' }}-600"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ ucfirst($transaction->transaction_type) }} {{ $transaction->stock->symbol }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ number_format($transaction->quantity) }} shares @ ₦{{ number_format($transaction->price_per_share, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right ml-3">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">₦{{ number_format($transaction->total_amount, 2) }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                    ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="ti ti-receipt-off text-4xl mb-2 block"></i>
                        <p class="text-sm">No transactions yet</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        <!-- Watchlist Tab -->
        <div id="watchlist-tab" class="tab-content hidden p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Watchlist</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($watchlist as $watch)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $watch->stock->symbol }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $watch->stock->name }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $watch->stock->price_change >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $watch->stock->price_change >= 0 ? '+' : '' }}{{ number_format($watch->stock->price_change_percentage, 2) }}%
                            </span>
                        </div>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mb-2">₦{{ number_format($watch->stock->current_price, 2) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Added {{ $watch->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="ti ti-eye-off text-4xl mb-2 block"></i>
                        <p class="text-sm">No stocks in watchlist</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

<style>
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
</style>

@push('scripts')
<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        });
        
        // Show selected tab
        document.getElementById(tabName + '-tab').classList.remove('hidden');
        
        // Add active class to selected button
        const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
        activeBtn.classList.add('border-primary', 'text-primary');
        activeBtn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    }
</script>
@endpush
@endsection