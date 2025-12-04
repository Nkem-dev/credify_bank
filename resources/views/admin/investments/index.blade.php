@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    Investments Management
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    Monitor stocks, portfolios, and user investments
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.investments.stocks.create') }}" 
                   class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition flex items-center space-x-2">
                    <i class="ti ti-plus"></i>
                    <span>Add Stock</span>
                </a>
                <a href="{{ route('admin.investments.analytics') }}" 
                   class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center space-x-2">
                    <i class="ti ti-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Investment Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Invested -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-wallet text-xl md:text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <span class="text-xs bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-1 rounded-full">
                    Invested
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Total Invested</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($totalInvestments, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                {{ number_format($activeInvestors) }} active investors
            </p>
        </div>

        <!-- Current Value -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-trending-up text-xl md:text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                <span class="text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded-full">
                    Current
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Current Value</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($totalCurrentValue, 2) }}</p>
            <p class="text-xs {{ $totalProfitLoss >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                {{ $totalProfitLoss >= 0 ? '+' : '' }}₦{{ number_format($totalProfitLoss, 2) }}
            </p>
        </div>

        <!-- Total Stocks -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-chart-line text-xl md:text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
                <span class="text-xs bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 px-2 py-1 rounded-full">
                    Active
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Total Stocks</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalStocks) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                {{ number_format($totalStockTransactions) }} transactions
            </p>
        </div>

        <!-- Transaction Volume -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-arrow-right-left text-xl md:text-2xl text-orange-600 dark:text-orange-400"></i>
                </div>
                <span class="text-xs bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 px-2 py-1 rounded-full">
                    Volume
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Transaction Volume</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($totalTransactionVolume, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                Buy: {{ $buyTransactions }} | Sell: {{ $sellTransactions }}
            </p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <a href="{{ route('admin.investments.all') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-xl border border-blue-200 dark:border-blue-800 hover:shadow-lg transition group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="ti ti-users text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">User Investments</p>
                    <p class="text-xs text-blue-700 dark:text-blue-400">View all portfolios</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.investments.stocks.all') }}" class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-xl border border-purple-200 dark:border-purple-800 hover:shadow-lg transition group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="ti ti-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-purple-900 dark:text-purple-100">All Stocks</p>
                    <p class="text-xs text-purple-700 dark:text-purple-400">Manage stocks</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.investments.transactions.all') }}" class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 rounded-xl border border-green-200 dark:border-green-800 hover:shadow-lg transition group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="ti ti-transfer-in text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-900 dark:text-green-100">Transactions</p>
                    <p class="text-xs text-green-700 dark:text-green-400">All stock trades</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.investments.watchlists.all') }}" class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-4 rounded-xl border border-orange-200 dark:border-orange-800 hover:shadow-lg transition group">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="ti ti-eye text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-orange-900 dark:text-orange-100">Watchlists</p>
                    <p class="text-xs text-orange-700 dark:text-orange-400">User watchlists</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Top Stocks & Top Investors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Top Performing Stocks -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="p-4 md:p-6 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white">Top Performing Stocks</h3>
                <a href="{{ route('admin.investments.stocks.all') }}" class="text-primary text-xs md:text-sm font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($topStocks as $stock)
                    <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 md:space-x-3 flex-1 min-w-0">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-xs md:text-sm">{{ substr($stock->symbol, 0, 2) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white text-xs md:text-sm truncate">{{ $stock->symbol }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $stock->name }}</p>
                                </div>
                            </div>
                            <div class="text-right ml-2 flex-shrink-0">
                                <p class="font-semibold text-xs md:text-sm text-gray-900 dark:text-white">₦{{ number_format($stock->current_price, 2) }}</p>
                                <span class="text-xs {{ $stock->isPriceUp() ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $stock->isPriceUp() ? '+' : '' }}{{ number_format($stock->price_change_percentage, 2) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="ti ti-chart-line-off text-4xl mb-2 block"></i>
                        <p class="text-sm">No stocks available</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Investors -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="p-4 md:p-6 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white">Top Investors</h3>
                <a href="{{ route('admin.investments.all') }}" class="text-primary text-xs md:text-sm font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($topInvestors as $index => $investor)
                    <a href="{{ route('admin.investments.user.show', $investor->user_id) }}" class="block p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 md:space-x-3 flex-1 min-w-0">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'orange') }}-400 to-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'orange') }}-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-xs md:text-sm">{{ $index + 1 }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white text-xs md:text-sm truncate">{{ $investor->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $investor->user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right ml-2 flex-shrink-0">
                                <p class="font-semibold text-xs md:text-sm text-gray-900 dark:text-white">₦{{ number_format($investor->total_invested, 2) }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $investor->isProfitable() ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $investor->isProfitable() ? '+' : '' }}{{ number_format($investor->profit_loss_percentage, 2) }}%
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="ti ti-users-off text-4xl mb-2 block"></i>
                        <p class="text-sm">No investor data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
        <div class="p-4 md:p-6 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
            <a href="{{ route('admin.investments.transactions.all') }}" class="text-primary text-xs md:text-sm font-medium hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentTransactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $transaction->stock->symbol }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full {{ $transaction->isBuy() ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ number_format($transaction->quantity) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">₦{{ number_format($transaction->total_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="ti ti-transfer-in-off text-4xl mb-2 block"></i>
                                <p class="text-sm">No transactions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection