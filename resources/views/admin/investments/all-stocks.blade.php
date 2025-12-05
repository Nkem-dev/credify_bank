@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    All Stocks
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    Manage all available stocks in the system
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.investments.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
                <a href="{{ route('admin.investments.stocks.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    <i class="ti ti-plus"></i> Add Stock
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.investments.stocks.all') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Symbol or name..." class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <select name="category" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    <option value="">All Categories</option>
                    <option value="technology">Technology</option>
                    <option value="finance">Finance</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="energy">Energy</option>
                    <option value="consumer">Consumer</option>
                    <option value="industrial">Industrial</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                <select name="sort" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    <option value="symbol">Symbol (A-Z)</option>
                    <option value="price_high">Highest Price</option>
                    <option value="price_low">Lowest Price</option>
                    <option value="change_high">Highest Change</option>
                    <option value="change_low">Lowest Change</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    <i class="ti ti-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Stocks Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @forelse($stocks as $stock)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 hover:shadow-lg transition">
                <!-- Stock Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        {{-- <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center">
                           <p class="text-gray-600 dark:text-gray-400">{{ $stock->symbol }}</p>
                        </div> --}}
                         <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($stock->symbol, 0, 2) }}
                    </div>
                        <div>
                           <p class="text-gray-600 dark:text-gray-400">{{ $stock->symbol }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stock->name }}</p>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $stock->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $stock->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <!-- Price Info -->
                <div class="mb-4">
                    <div class="flex items-baseline space-x-2 mb-2">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($stock->current_price, 2) }}</span>
                        <span class="text-sm font-semibold {{ $stock->price_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stock->price_change >= 0 ? '+' : '' }}{{ number_format($stock->price_change_percentage, 2) }}%
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $stock->price_change >= 0 ? '+' : '' }}₦{{ number_format($stock->price_change, 2) }} today
                        </span>
                    </div>
                </div>

                <!-- Stock Details -->
                <div class="space-y-2 mb-4 pb-4 border-b dark:border-gray-700">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Category</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ ucfirst($stock->category) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Opening</span>
                        <span class="text-gray-900 dark:text-white font-medium">₦{{ number_format($stock->opening_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Day Range</span>
                        <span class="text-gray-900 dark:text-white font-medium">₦{{ number_format($stock->day_low, 2) }} - ₦{{ number_format($stock->day_high, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Volume</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ number_format($stock->volume) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Investors</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ number_format($stock->user_stocks_count ?? 0) }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.investments.stocks.show', $stock->id) }}" class="flex-1 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition text-center text-sm font-medium">
                        <i class="ti ti-eye text-sm"></i> View
                    </a>
                    <a href="{{ route('admin.investments.stocks.edit', $stock->id) }}" class="flex-1 px-3 py-2 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition text-center text-sm font-medium">
                        <i class="ti ti-edit text-sm"></i> Edit
                    </a>
                    <button onclick="toggleStockStatus({{ $stock->id }})" class="px-3 py-2 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition text-sm">
                        <i class="ti ti-toggle-{{ $stock->is_active ? 'left' : 'right' }} text-sm"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-12 text-center">
                <i class="ti ti-chart-line-off text-6xl text-gray-400 dark:text-gray-600 mb-4 block"></i>
                <p class="text-gray-500 dark:text-gray-400 mb-4">No stocks available</p>
                <a href="{{ route('admin.investments.stocks.create') }}" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    <i class="ti ti-plus"></i> Add Your First Stock
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($stocks->hasPages())
        <div class="mt-6">
            {{ $stocks->links() }}
        </div>
    @endif
</main>

@push('scripts')
<script>
    function toggleStockStatus(stockId) {
        if (confirm('Are you sure you want to change the status of this stock?')) {
            fetch(`/admin/investments/stocks/${stockId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endpush
@endsection