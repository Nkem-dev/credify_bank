@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Investments</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your stock portfolio and investments</p>
            </div>
            <a href="{{ route('user.invest.stocks.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                <i class="ti ti-shopping-cart"></i>
                Browse Stocks
            </a>
        </div>

        <!-- Portfolio Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Portfolio Value -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-chart-line text-3xl text-purple-600 opacity-80"></i>
                    <span class="text-xs bg-purple/20 px-3 py-1 rounded-full">Portfolio</span>
                </div>
                <p class="text-sm opacity-90 mb-1">Total Value</p>
                <p class="text-3xl font-bold">₦{{ number_format($investment->current_value, 2) }}</p>
                <p class="text-xs mt-2 opacity-80">
                    @if($investment->ytd_return > 0)
                        <i class="ti ti-trending-up"></i> +{{ number_format($investment->ytd_return, 2) }}% YTD
                    @elseif($investment->ytd_return < 0)
                        <i class="ti ti-trending-down"></i> {{ number_format($investment->ytd_return, 2) }}% YTD
                    @else
                        <i class="ti ti-minus"></i> 0% YTD
                    @endif
                </p>
            </div>

            <!-- Total Invested -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-coin text-3xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Invested</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($investment->total_invested, 2) }}</p>
            </div>

            <!-- Profit/Loss -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-trending-{{ $investment->total_profit_loss >= 0 ? 'up' : 'down' }} text-3xl {{ $investment->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Profit/Loss</p>
                <p class="text-2xl font-bold {{ $investment->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $investment->total_profit_loss >= 0 ? '+' : '' }}₦{{ number_format($investment->total_profit_loss, 2) }}
                </p>
                <p class="text-xs {{ $investment->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-1">
                    {{ $investment->profit_loss_percentage >= 0 ? '+' : '' }}{{ number_format($investment->profit_loss_percentage, 2) }}%
                </p>
            </div>

            <!-- Total Dividends -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-gift text-3xl text-orange-600 dark:text-orange-400"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Dividends</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($investment->total_dividends, 2) }}</p>
            </div>
        </div>

        <!-- Quick Navigation -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('user.invest.portfolio') }}" 
               class="flex flex-col items-center gap-3 p-6 rounded-xl bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 border border-blue-200 dark:border-blue-800 transition group">
                <i class="ti ti-briefcase text-3xl text-blue-600 dark:text-blue-400"></i>
                <span class="text-sm font-medium text-gray-900 dark:text-white">My Portfolio</span>
                <span class="text-xs text-blue-600 dark:text-blue-400">{{ $portfolio->count() }} stocks</span>
            </a>

            <a href="{{ route('user.invest.stocks.index') }}" 
               class="flex flex-col items-center gap-3 p-6 rounded-xl bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/40 border border-purple-200 dark:border-purple-800 transition group">
                <i class="ti ti-shopping-cart text-3xl text-purple-600 dark:text-purple-400"></i>
                <span class="text-sm font-medium text-gray-900 dark:text-white">Buy Stocks</span>
                <span class="text-xs text-purple-600 dark:text-purple-400">Marketplace</span>
            </a>

            <a href="{{ route('user.invest.watchlist') }}" 
               class="flex flex-col items-center gap-3 p-6 rounded-xl bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 border border-green-200 dark:border-green-800 transition group">
                <i class="ti ti-star text-3xl text-green-600 dark:text-green-400"></i>
                <span class="text-sm font-medium text-gray-900 dark:text-white">Watchlist</span>
                <span class="text-xs text-green-600 dark:text-green-400">{{ $watchlistCount }} stocks</span>
            </a>

            <a href="{{ route('user.invest.transactions') }}" 
               class="flex flex-col items-center gap-3 p-6 rounded-xl bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/40 border border-orange-200 dark:border-orange-800 transition group">
                <i class="ti ti-history text-3xl text-orange-600 dark:text-orange-400"></i>
                <span class="text-sm font-medium text-gray-900 dark:text-white">History</span>
                <span class="text-xs text-orange-600 dark:text-orange-400">Transactions</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- My Portfolio Holdings -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="p-6 border-b dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Portfolio</h3>
                        <a href="{{ route('user.invest.portfolio') }}" class="text-sm text-primary hover:underline">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($portfolio as $item)
                        <div class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition mb-3">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold">
                                    {{ substr($item->stock->symbol, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $item->stock->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->quantity }} shares</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white">₦{{ number_format($item->current_value, 2) }}</p>
                                <p class="text-sm {{ ($item->current_value - $item->total_invested) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    @php
                                        $profitLoss = $item->current_value - $item->total_invested;
                                        $profitLossPercentage = $item->total_invested > 0 ? ($profitLoss / $item->total_invested) * 100 : 0;
                                    @endphp
                                    {{ $profitLossPercentage >= 0 ? '+' : '' }}{{ number_format($profitLossPercentage, 2) }}%
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <i class="ti ti-briefcase-off text-5xl mb-3 opacity-50"></i>
                            <p class="font-medium">No stocks in portfolio</p>
                            <a href="{{ route('user.invest.stocks.index') }}" class="text-primary hover:underline text-sm mt-2 inline-block">Start investing</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Trending Stocks -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="p-6 border-b dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trending Stocks</h3>
                        <a href="{{ route('user.invest.stocks.index') }}" class="text-sm text-primary hover:underline">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    @foreach($trendingStocks as $stock)
                        <a href="{{ route('user.invest.stocks.show', $stock) }}" class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition mb-3">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($stock->symbol, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $stock->symbol }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stock->category_name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white">₦{{ number_format($stock->current_price, 2) }}</p>
                                <p class="text-sm {{ $stock->isPriceUp() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $stock->price_change >= 0 ? '+' : '' }}{{ number_format($stock->price_change_percentage, 2) }}%
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="p-6 border-b dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
                    <a href="{{ route('user.invest.transactions') }}" class="text-sm text-primary hover:underline">View All</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $transaction->stock->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->stock->symbol }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $transaction->type === 'buy' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                    {{ number_format($transaction->quantity) }} shares
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                    ₦{{ number_format($transaction->price_per_share, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white">
                                    ₦{{ number_format($transaction->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-history-off text-5xl mb-3 opacity-50"></i>
                                    <p class="font-medium">No transactions yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>
@endsection