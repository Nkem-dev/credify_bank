{{-- resources/views/user/invest/stocks/show.blade.php --}}
@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.invest.stocks.index') }}"
                   class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($stock->symbol, 0, 2) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stock->name }}</h1>
                        <div class="flex items-center gap-3 mt-1">
                            <p class="text-gray-600 dark:text-gray-400">{{ $stock->symbol }}</p>
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-primary/10 text-primary">
                                {{ $stock->category_name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                @if($inWatchlist)
                    <form action="{{ route('user.invest.watchlist.remove', $stock) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-3 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition">
                            <i class="ti ti-star-filled text-xl"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('user.invest.watchlist.add', $stock) }}" method="POST">
                        @csrf
                        <button type="submit" class="p-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <i class="ti ti-star text-xl"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Price Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Current Price Card -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Price</p>
                        <p class="text-4xl font-bold text-gray-900 dark:text-white">₦{{ number_format($stock->current_price, 2) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-lg font-semibold {{ $stock->isPriceUp() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $stock->price_change >= 0 ? '+' : '' }}₦{{ number_format($stock->price_change, 2) }}
                            </span>
                            <span class="px-3 py-1 text-sm font-bold rounded-full {{ $stock->isPriceUp() ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $stock->price_change >= 0 ? '+' : '' }}{{ number_format($stock->price_change_percentage, 2) }}%
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openBuyModal()" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
                            <i class="ti ti-shopping-cart mr-2"></i>Buy
                        </button>
                        @if($userStock)
                            <button onclick="openSellModal()" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                                <i class="ti ti-cash mr-2"></i>Sell
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Market Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Day High</p>
                        <p class="font-bold text-gray-900 dark:text-white">₦{{ number_format($stock->day_high, 2) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Day Low</p>
                        <p class="font-bold text-gray-900 dark:text-white">₦{{ number_format($stock->day_low, 2) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Volume</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ number_format($stock->volume) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Market Cap</p>
                        <p class="font-bold text-gray-900 dark:text-white">₦{{ number_format($stock->market_cap / 1000000000, 2) }}B</p>
                    </div>
                </div>
            </div>

            <!-- Your Holdings Card -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Holdings</h3>
                @if($userStock)
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Shares Owned</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($userStock->quantity) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Average Buy Price</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">₦{{ number_format($userStock->average_buy_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Invested</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">₦{{ number_format($userStock->total_invested, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Current Value</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">₦{{ number_format($userStock->current_value, 2) }}</p>
                        </div>
                        <div class="pt-4 border-t dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Profit/Loss</p>
                            @php
                                $profitLoss = $userStock->current_value - $userStock->total_invested;
                                $profitLossPercentage = $userStock->total_invested > 0 ? ($profitLoss / $userStock->total_invested) * 100 : 0;
                            @endphp
                            <p class="text-xl font-bold {{ $profitLoss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $profitLoss >= 0 ? '+' : '' }}₦{{ number_format($profitLoss, 2) }}
                            </p>
                            <p class="text-sm {{ $profitLoss >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $profitLossPercentage >= 0 ? '+' : '' }}{{ number_format($profitLossPercentage, 2) }}%
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="ti ti-briefcase-off text-5xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">You don't own this stock yet</p>
                        <button onclick="openBuyModal()" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                            Start Investing
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Additional Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Company Information -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Symbol</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $stock->symbol }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Category</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $stock->category_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Opening Price</span>
                        <span class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($stock->opening_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">52-Week High</span>
                        <span class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($stock->week_high, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">52-Week Low</span>
                        <span class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($stock->week_low, 2) }}</span>
                    </div>
                </div>
                @if($stock->description)
                    <div class="mt-6 pt-6 border-t dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $stock->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Recent Transactions</h3>
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition mb-3">
                        <div>
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $transaction->type === 'buy' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ number_format($transaction->quantity) }} shares</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($transaction->net_amount, 2) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">@₦{{ number_format($transaction->price_per_share, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="ti ti-history-off text-4xl mb-2 opacity-50"></i>
                        <p class="text-sm">No transactions yet</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</main>

{{-- Buy Stock Modal --}}
<div id="buyModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="ti ti-shopping-cart text-xl text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Buy {{ $stock->symbol }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stock->name }}</p>
                </div>
            </div>
            <button onclick="closeBuyModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>

        <form action="{{ route('user.invest.stocks.buy', $stock) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Shares</label>
                    <input type="number" name="quantity" id="buyQuantity" min="1" value="1" required
                           onchange="calculateBuyTotal()"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:text-white">
                </div>

                <!-- Price Summary -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Price per share</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦{{ number_format($stock->current_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Total amount</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="buyTotalAmount">{{ number_format($stock->current_price, 2) }}</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Transaction fee (0.5%)</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="buyTransactionFee">{{ number_format($stock->current_price * 0.005, 2) }}</span></span>
                    </div>
                    <div class="border-t dark:border-gray-600 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900 dark:text-white">Total Payable</span>
                            <span class="font-bold text-green-600 dark:text-green-400">₦<span id="buyNetAmount">{{ number_format($stock->current_price * 1.005, 2) }}</span></span>
                        </div>
                    </div>
                </div>

                <!-- Transaction PIN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction PIN</label>
                    <input type="password" name="transaction_pin" maxlength="4" required
                           placeholder="Enter 4-digit PIN"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Your balance: ₦{{ number_format(Auth::user()->balance, 2) }}
                    </p>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeBuyModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
                    Buy Now
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Sell Stock Modal --}}
@if($userStock)
<div id="sellModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="ti ti-cash text-xl text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Sell {{ $stock->symbol }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stock->name }}</p>
                </div>
            </div>
            <button onclick="closeSellModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>

        <form action="{{ route('user.invest.stocks.sell', $stock) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Available Shares -->
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-900 dark:text-blue-300">
                        <i class="ti ti-info-circle mr-1"></i>
                        You own <strong>{{ number_format($userStock->quantity) }} shares</strong>
                    </p>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Shares to Sell</label>
                    <input type="number" name="quantity" id="sellQuantity" min="1" max="{{ $userStock->quantity }}" value="1" required
                           onchange="calculateSellTotal()"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:text-white">
                </div>

                <!-- Price Summary -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Price per share</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦{{ number_format($stock->current_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Total amount</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="sellTotalAmount">{{ number_format($stock->current_price, 2) }}</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Transaction fee (0.5%)</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="sellTransactionFee">{{ number_format($stock->current_price * 0.005, 2) }}</span></span>
                    </div>
                    <div class="border-t dark:border-gray-600 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900 dark:text-white">You'll Receive</span>
                            <span class="font-bold text-green-600 dark:text-green-400">₦<span id="sellNetAmount">{{ number_format($stock->current_price * 0.995, 2) }}</span></span>
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
                <button type="button" onclick="closeSellModal()"
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
@endif

@push('scripts')
<script>
    const stockPrice = {{ $stock->current_price }};

    function openBuyModal() {
        document.getElementById('buyModal').classList.remove('hidden');
        calculateBuyTotal();
    }

    function closeBuyModal() {
        document.getElementById('buyModal').classList.add('hidden');
    }

    function openSellModal() {
        document.getElementById('sellModal').classList.remove('hidden');
        calculateSellTotal();
    }

    function closeSellModal() {
        document.getElementById('sellModal').classList.add('hidden');
    }

    function calculateBuyTotal() {
        const quantity = parseInt(document.getElementById('buyQuantity').value) || 0;
        const total = quantity * stockPrice;
        const fee = total * 0.005;
        const net = total + fee;

        document.getElementById('buyTotalAmount').textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('buyTransactionFee').textContent = fee.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('buyNetAmount').textContent = net.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function calculateSellTotal() {
        const quantity = parseInt(document.getElementById('sellQuantity').value) || 0;
        const total = quantity * stockPrice;
        const fee = total * 0.005;
        const net = total - fee;

        document.getElementById('sellTotalAmount').textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('sellTransactionFee').textContent = fee.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('sellNetAmount').textContent = net.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // Close modals on outside click
    document.getElementById('buyModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'buyModal') closeBuyModal();
    });

    document.getElementById('sellModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'sellModal') closeSellModal();
    });

    // Close modals on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeBuyModal();
            closeSellModal();
        }
    });
</script>
@endpush

@endsection