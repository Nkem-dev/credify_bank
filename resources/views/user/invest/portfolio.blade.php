{{-- resources/views/user/invest/portfolio.blade.php --}}
@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.invest.index') }}"
                   class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Portfolio</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Track your stock investments and performance</p>
                </div>
            </div>
            <a href="{{ route('user.invest.stocks.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                <i class="ti ti-plus"></i>
                Buy More Stocks
            </a>
        </div>

        <!-- Portfolio Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Value -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-briefcase text-3xl opacity-80"></i>
                </div>
                <p class="text-sm opacity-90 mb-1">Portfolio Value</p>
                <p class="text-3xl font-bold">₦{{ number_format($investment->current_value, 2) }}</p>
            </div>

            <!-- Total Invested -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-coin text-3xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Invested</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($investment->total_invested, 2) }}</p>
            </div>

            <!-- Total Profit/Loss -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-trending-{{ $investment->total_profit_loss >= 0 ? 'up' : 'down' }} text-3xl {{ $investment->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Profit/Loss</p>
                <p class="text-2xl font-bold {{ $investment->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $investment->total_profit_loss >= 0 ? '+' : '' }}₦{{ number_format($investment->total_profit_loss, 2) }}
                </p>
                <p class="text-xs {{ $investment->total_profit_loss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-1">
                    {{ $investment->profit_loss_percentage >= 0 ? '+' : '' }}{{ number_format($investment->profit_loss_percentage, 2) }}%
                </p>
            </div>

            <!-- Holdings Count -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <i class="ti ti-chart-pie text-3xl text-orange-600 dark:text-orange-400"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Holdings</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $portfolio->count() }}</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Different stocks</p>
            </div>
        </div>

        @if($portfolio->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-16 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="ti ti-briefcase-off text-5xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Stocks in Portfolio</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Start building your investment portfolio today</p>
                <a href="{{ route('user.invest.stocks.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                    <i class="ti ti-shopping-cart"></i>
                    Browse Stocks
                </a>
            </div>
        @else
            <!-- Portfolio by Category -->
            @foreach($portfolioByCategory as $category => $stocks)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="ti ti-folder text-primary"></i>
                            {{ ucwords(str_replace('_', ' & ', $category)) }}
                        </h2>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $stocks->count() }} stocks</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($stocks as $userStock)
                            @php
                                $profitLoss = $userStock->current_value - $userStock->total_invested;
                                $profitLossPercentage = $userStock->total_invested > 0 ? ($profitLoss / $userStock->total_invested) * 100 : 0;
                            @endphp
                            
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-lg transition overflow-hidden">
                                <!-- Stock Header -->
                                <div class="p-6 border-b dark:border-gray-700">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold">
                                                {{ substr($userStock->stock->symbol, 0, 2) }}
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $userStock->stock->symbol }}</h3>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $userStock->quantity }} shares</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $userStock->stock->isPriceUp() ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                            {{ $userStock->stock->price_change >= 0 ? '+' : '' }}{{ number_format($userStock->stock->price_change_percentage, 2) }}%
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1">{{ $userStock->stock->name }}</p>
                                </div>

                                <!-- Investment Details -->
                                <div class="p-6 space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Current Price</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($userStock->stock->current_price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Avg. Buy Price</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($userStock->average_buy_price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-3 border-t dark:border-gray-700">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Invested</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($userStock->total_invested, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Current Value</span>
                                        <span class="font-bold text-gray-900 dark:text-white">₦{{ number_format($userStock->current_value, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-3 border-t dark:border-gray-700">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Profit/Loss</span>
                                        <div class="text-right">
                                            <p class="font-bold {{ $profitLoss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $profitLoss >= 0 ? '+' : '' }}₦{{ number_format($profitLoss, 2) }}
                                            </p>
                                            <p class="text-xs {{ $profitLoss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $profitLossPercentage >= 0 ? '+' : '' }}{{ number_format($profitLossPercentage, 2) }}%
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="p-6 pt-0 flex gap-2">
                                    <a href="{{ route('user.invest.stocks.show', $userStock->stock) }}" 
                                       class="flex-1 px-4 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition text-center font-medium text-sm">
                                        View Details
                                    </a>
                                    <button onclick="openQuickSellModal({{ $userStock->stock->id }}, '{{ $userStock->stock->name }}', '{{ $userStock->stock->symbol }}', {{ $userStock->stock->current_price }}, {{ $userStock->quantity }})"
                                            class="px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                        <i class="ti ti-cash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- All Holdings Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 mt-8">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Holdings</h3>
                </div>
                <div class="overflow-x-auto fancy-scrollbar">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Shares</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Avg. Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Current Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Invested</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Current Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Profit/Loss</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($portfolio as $userStock)
                                @php
                                    $profitLoss = $userStock->current_value - $userStock->total_invested;
                                    $profitLossPercentage = $userStock->total_invested > 0 ? ($profitLoss / $userStock->total_invested) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($userStock->stock->symbol, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $userStock->stock->symbol }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $userStock->stock->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($userStock->quantity) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                        ₦{{ number_format($userStock->average_buy_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white">
                                        ₦{{ number_format($userStock->stock->current_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                        ₦{{ number_format($userStock->total_invested, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-white">
                                        ₦{{ number_format($userStock->current_value, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-right">
                                            <p class="font-bold {{ $profitLoss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $profitLoss >= 0 ? '+' : '' }}₦{{ number_format($profitLoss, 2) }}
                                            </p>
                                            <p class="text-sm {{ $profitLoss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $profitLossPercentage >= 0 ? '+' : '' }}{{ number_format($profitLossPercentage, 2) }}%
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('user.invest.stocks.show', $userStock->stock) }}" 
                                               class="p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition"
                                               title="View Details">
                                                <i class="ti ti-eye text-lg"></i>
                                            </a>
                                            <button onclick="openQuickSellModal({{ $userStock->stock->id }}, '{{ addslashes($userStock->stock->name) }}', '{{ $userStock->stock->symbol }}', {{ $userStock->stock->current_price }}, {{ $userStock->quantity }})"
                                                    class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition"
                                                    title="Sell Stock">
                                                <i class="ti ti-cash text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</main>

{{-- Quick Sell Modal --}}
<div id="quickSellModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="ti ti-cash text-xl text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Quick Sell</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="sellStockSymbolDisplay"></p>
                </div>
            </div>
            <button onclick="closeQuickSellModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>

        <form  id="quickSellForm" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Available Shares -->
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-900 dark:text-blue-300">
                        <i class="ti ti-info-circle mr-1"></i>
                        You own <strong id="availableShares">0</strong> shares
                    </p>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Selling: <strong id="sellStockNameDisplay"></strong>
                </p>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Shares to Sell</label>
                    <input type="number" name="quantity" id="sellQuantity" min="1" value="1" required
                           onchange="calculateQuickSellTotal()"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:text-white">
                </div>

                <!-- Price Summary -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Price per share</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="sellPricePerShare">0.00</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Total amount</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="sellTotalAmount">0.00</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Transaction fee (0.5%)</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="sellTransactionFee">0.00</span></span>
                    </div>
                    <div class="border-t dark:border-gray-600 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900 dark:text-white">You'll Receive</span>
                            <span class="font-bold text-green-600 dark:text-green-400">₦<span id="sellNetAmount">0.00</span></span>
                        </div>
                    </div>
                </div>

                <!-- Transaction PIN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction PIN</label>
                    <input type="password" name="transaction_pin" maxlength="4" required
                           placeholder="Enter 4-digit PIN"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:text-white">
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeQuickSellModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                    Sell Now
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentSellStockPrice = 0;
    let maxSellQuantity = 0;

    function openQuickSellModal(stockId, stockName, stockSymbol, stockPrice, ownedQuantity) {
        currentSellStockPrice = stockPrice;
        maxSellQuantity = ownedQuantity;
        
        const modal = document.getElementById('quickSellModal');
        const form = document.getElementById('quickSellForm');
        const stockNameDisplay = document.getElementById('sellStockNameDisplay');
        const stockSymbolDisplay = document.getElementById('sellStockSymbolDisplay');
        const pricePerShare = document.getElementById('sellPricePerShare');
        const quantityInput = document.getElementById('sellQuantity');
        const availableShares = document.getElementById('availableShares');

        // Set form action
        form.action = `/invest/stocks/${stockId}/sell`;

        // Set stock details
        stockNameDisplay.textContent = stockName;
        stockSymbolDisplay.textContent = stockSymbol;
        pricePerShare.textContent = stockPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        availableShares.textContent = ownedQuantity.toLocaleString();

        // Reset quantity
        quantityInput.value = 1;
        quantityInput.max = ownedQuantity;

        // Calculate initial total
        calculateQuickSellTotal();

        // Show modal
        modal.classList.remove('hidden');
    }

    function closeQuickSellModal() {
        document.getElementById('quickSellModal').classList.add('hidden');
    }

    function calculateQuickSellTotal() {
        const quantity = parseInt(document.getElementById('sellQuantity').value) || 0;
        
        if (quantity > maxSellQuantity) {
            document.getElementById('sellQuantity').value = maxSellQuantity;
            return;
        }
        
        const total = quantity * currentSellStockPrice;
        const fee = total * 0.005;
        const net = total - fee;

        document.getElementById('sellTotalAmount').textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('sellTransactionFee').textContent = fee.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('sellNetAmount').textContent = net.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // Close modal on outside click
    document.getElementById('quickSellModal').addEventListener('click', (e) => {
        if (e.target.id === 'quickSellModal') {
            closeQuickSellModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeQuickSellModal();
        }
    });
</script>
@endpush

<!-- Fancy Scrollbar Styles -->
<style>
    .fancy-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 #f1f5f9; /* thumb & track */
    }

    .fancy-scrollbar::-webkit-scrollbar {
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

    /* Dark Mode */
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

    /* Smooth Scroll Behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Optional: Animate on hover */
    .sidebar-link {
        position: relative;
        overflow: hidden;
    }

    .sidebar-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        transition: left 0.5s;
    }

    .sidebar-link:hover::before {
        left: 100%;
    }
</style>

@endsection