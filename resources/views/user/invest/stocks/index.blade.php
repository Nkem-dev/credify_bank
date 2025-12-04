{{-- resources/views/user/invest/stocks/index.blade.php --}}
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
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Stock Marketplace</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Browse and invest in available stocks</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('user.invest.stocks.index') }}" class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search stocks by name or symbol..."
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:text-white">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="w-full md:w-64">
                    <select name="category" 
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:text-white">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ $category == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Button -->
                <button type="submit" 
                        class="px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition font-medium">
                    <i class="ti ti-filter mr-2"></i>Filter
                </button>

                @if($search || $category)
                    <a href="{{ route('user.invest.stocks.index') }}" 
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg transition font-medium">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Stock Grid -->
        @if($stocks->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-16 text-center">
                <i class="ti ti-chart-line-off text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Stocks Found</h3>
                <p class="text-gray-600 dark:text-gray-400">Try adjusting your search or filters</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($stocks as $stock)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-lg transition overflow-hidden">
                        <!-- Stock Header -->
                        <div class="p-6 border-b dark:border-gray-700">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold">
                                        {{ substr($stock->symbol, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $stock->symbol }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stock->category_name }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $stock->isPriceUp() ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $stock->price_change >= 0 ? '+' : '' }}{{ number_format($stock->price_change_percentage, 2) }}%
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $stock->name }}</p>
                        </div>

                        <!-- Stock Stats -->
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Current Price</span>
                                <span class="text-xl font-bold text-gray-900 dark:text-white">₦{{ number_format($stock->current_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Day High/Low</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    ₦{{ number_format($stock->day_high, 2) }} / ₦{{ number_format($stock->day_low, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Volume</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($stock->volume) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Market Cap</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">₦{{ number_format($stock->market_cap / 1000000000, 2) }}B</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-6 pt-0 flex gap-2">
                            <a href="{{ route('user.invest.stocks.show', $stock) }}" 
                               class="flex-1 px-4 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition text-center font-medium">
                                View Details
                            </a>
                            <button onclick="openQuickBuyModal({{ $stock->id }}, '{{ $stock->name }}', '{{ $stock->symbol }}', {{ $stock->current_price }})"
                                    class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                <i class="ti ti-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($stocks->hasPages())
                <div class="mt-6">
                    {{ $stocks->links() }}
                </div>
            @endif
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

        <form id="quickBuyForm" method="POST">
            @csrf
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Buying: <strong id="stockNameDisplay"></strong>
                </p>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Number of Shares</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="1" required
                           onchange="calculateQuickBuyTotal()"
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

        // Set form action
        form.action = `/invest/stocks/${stockId}/buy`;

        // Set stock details
        stockNameDisplay.textContent = stockName;
        stockSymbolDisplay.textContent = stockSymbol;
        pricePerShare.textContent = stockPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // Reset quantity
        quantityInput.value = 1;

        // Calculate initial total
        calculateQuickBuyTotal();

        // Show modal
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

    // Close modal on outside click
    document.getElementById('quickBuyModal').addEventListener('click', (e) => {
        if (e.target.id === 'quickBuyModal') {
            closeQuickBuyModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeQuickBuyModal();
        }
    });
</script>
@endpush

@endsection