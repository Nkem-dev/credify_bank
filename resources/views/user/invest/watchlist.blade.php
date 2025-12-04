
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
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Watchlist</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Monitor your favorite stocks</p>
                </div>
            </div>
            <a href="{{ route('user.invest.stocks.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                <i class="ti ti-plus"></i>
                Add More Stocks
            </a>
        </div>

        @if($watchlist->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-16 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="ti ti-star-off text-5xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Stocks in Watchlist</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Add stocks to your watchlist to monitor their performance</p>
                <a href="{{ route('user.invest.stocks.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                    <i class="ti ti-search"></i>
                    Browse Stocks
                </a>
            </div>
        @else
            <!-- Watchlist Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                    <i class="ti ti-star text-3xl text-yellow-600 dark:text-yellow-400 mb-4"></i>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Watching</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $watchlist->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Stocks monitored</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                    <i class="ti ti-trending-up text-3xl text-green-600 dark:text-green-400 mb-4"></i>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Top Gainer</p>
                    @php
                        $topGainer = $watchlist->sortByDesc(fn($w) => $w->stock->price_change_percentage)->first();
                    @endphp
                    @if($topGainer)
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $topGainer->stock->symbol }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">+{{ number_format($topGainer->stock->price_change_percentage, 2) }}%</p>
                    @else
                        <p class="text-xl font-bold text-gray-500">—</p>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                    <i class="ti ti-trending-down text-3xl text-red-600 dark:text-red-400 mb-4"></i>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Top Loser</p>
                    @php
                        $topLoser = $watchlist->sortBy(fn($w) => $w->stock->price_change_percentage)->first();
                    @endphp
                    @if($topLoser)
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $topLoser->stock->symbol }}</p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ number_format($topLoser->stock->price_change_percentage, 2) }}%</p>
                    @else
                        <p class="text-xl font-bold text-gray-500">—</p>
                    @endif
                </div>
            </div>

            <!-- Watchlist Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($watchlist as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-lg transition">
                        <!-- Stock Header -->
                        <div class="p-6 border-b dark:border-gray-700">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ substr($item->stock->symbol, 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $item->stock->symbol }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->stock->category_name }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('user.invest.watchlist.remove', $item->stock) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition" title="Remove from Watchlist">
                                        <i class="ti ti-star-filled text-lg"></i>
                                    </button>
                                </form>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $item->stock->name }}</p>
                        </div>

                        <!-- Stock Stats -->
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Current Price</span>
                                <span class="text-xl font-bold text-gray-900 dark:text-white">₦{{ number_format($item->stock->current_price, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Change</span>
                                <div class="text-right">
                                    <span class="font-bold {{ $item->stock->isPriceUp() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $item->stock->price_change >= 0 ? '+' : '' }}₦{{ number_format($item->stock->price_change, 2) }}
                                    </span>
                                    <span class="ml-2 px-2 py-1 text-xs font-bold rounded-full {{ $item->stock->isPriceUp() ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $item->stock->price_change >= 0 ? '+' : '' }}{{ number_format($item->stock->price_change_percentage, 2) }}%
                                    </span>
                                </div>
                            </div>

                            <div class="pt-3 border-t dark:border-gray-700">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">Day Range</span>
                                    <span class="text-xs font-medium text-gray-900 dark:text-white">
                                        ₦{{ number_format($item->stock->day_low, 2) }} - ₦{{ number_format($item->stock->day_high, 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">Volume</span>
                                    <span class="text-xs font-medium text-gray-900 dark:text-white">{{ number_format($item->stock->volume) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-6 pb-6 flex gap-2">
                            <a href="{{ route('user.invest.stocks.show', $item->stock) }}" 
                               class="flex-1 px-4 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition text-center font-medium text-sm">
                                View Details
                            </a>
                            <button onclick="openQuickBuyModal({{ $item->stock->id }}, '{{ addslashes($item->stock->name) }}', '{{ $item->stock->symbol }}', {{ $item->stock->current_price }})"
                                    class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                <i class="ti ti-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Watchlist Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Watched Stocks</h3>
                </div>
                <div class="overflow-x-auto fancy-scrollbar">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Current Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Change</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Day High/Low</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Volume</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Added</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($watchlist as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                                {{ substr($item->stock->symbol, 0, 2) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $item->stock->symbol }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $item->stock->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-white">
                                        ₦{{ number_format($item->stock->current_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <p class="font-bold {{ $item->stock->isPriceUp() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $item->stock->price_change >= 0 ? '+' : '' }}₦{{ number_format($item->stock->price_change, 2) }}
                                            </p>
                                            <p class="text-xs {{ $item->stock->isPriceUp() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $item->stock->price_change >= 0 ? '+' : '' }}{{ number_format($item->stock->price_change_percentage, 2) }}%
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        ₦{{ number_format($item->stock->day_high, 2) }} / ₦{{ number_format($item->stock->day_low, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ number_format($item->stock->volume) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('user.invest.stocks.show', $item->stock) }}" 
                                               class="p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition"
                                               title="View Details">
                                                <i class="ti ti-eye text-lg"></i>
                                            </a>
                                            <button onclick="openQuickBuyModal({{ $item->stock->id }}, '{{ addslashes($item->stock->name) }}', '{{ $item->stock->symbol }}', {{ $item->stock->current_price }})"
                                                    class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 transition"
                                                    title="Buy Stock">
                                                <i class="ti ti-shopping-cart text-lg"></i>
                                            </button>
                                            <form action="{{ route('user.invest.watchlist.remove', $item->stock) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition"
                                                        title="Remove from Watchlist">
                                                    <i class="ti ti-star-filled text-lg"></i>
                                                </button>
                                            </form>
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

{{-- Quick Buy Modal --}}
<div id="quickBuyModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="ti ti-shopping-cart text-xl text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Quick Buy</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="stockSymbolDisplay"></p>
                </div>
            </div>
            <button onclick="closeQuickBuyModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>

        <form action=" route('user.invest.stocks.buy') }}" id="quickBuyForm" method="POST">
            @csrf
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Buying: <strong id="stockNameDisplay"></strong>
                </p>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Shares</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="1" required
                           oninput="calculateQuickBuyTotal()"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:text-white">
                </div>

                <!-- Price Summary -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Price per share</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="pricePerShare">0.00</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Total amount</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="totalAmount">0.00</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Transaction fee (0.5%)</span>
                        <span class="font-medium text-gray-900 dark:text-white">₦<span id="transactionFee">0.00</span></span>
                    </div>
                    <div class="border-t dark:border-gray-600 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900 dark:text-white">Total Payable</span>
                            <span class="font-bold text-green-600 dark:text-green-400">₦<span id="netAmount">0.00</span></span>
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
                <button type="button" onclick="closeQuickBuyModal()"
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

@push('scripts')
<script>
    let currentStockPrice = 0;

    function openQuickBuyModal(stockId, stockName, stockSymbol, stockPrice) {
        currentStockPrice = stockPrice;
        
        const modal = document.getElementById('quickBuyModal');
        const form = document.getElementById('quickBuyForm');
        const stockNameDisplay = document.getElementById('stockNameDisplay');
        const stockSymbolDisplay = document.getElementById('stockSymbolDisplay');
        const pricePerShare = document.getElementById('pricePerShare');
        const quantityInput = document.getElementById('quantity');

        form.action = `/invest/stocks/${stockId}/buy`;
        stockNameDisplay.textContent = stockName;
        stockSymbolDisplay.textContent = stockSymbol;
        pricePerShare.textContent = stockPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        quantityInput.value = 1;

        calculateQuickBuyTotal();
        modal.classList.remove('hidden');
    }

    function closeQuickBuyModal() {
        document.getElementById('quickBuyModal').classList.add('hidden');
    }

    function calculateQuickBuyTotal() {
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        const total = quantity * currentStockPrice;
        const fee = total * 0.005;
        const net = total + fee;

        document.getElementById('totalAmount').textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('transactionFee').textContent = fee.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('netAmount').textContent = net.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    document.getElementById('quickBuyModal').addEventListener('click', (e) => {
        if (e.target.id === 'quickBuyModal') closeQuickBuyModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeQuickBuyModal();
    });
</script>

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
@endpush

@endsection