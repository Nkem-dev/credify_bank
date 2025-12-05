@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    All Watchlists
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    View all stocks being watched by users
                </p>
            </div>
            <a href="{{ route('admin.investments.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="ti ti-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-xl border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-eye text-2xl text-blue-600 dark:text-blue-400"></i>
            </div>
            <h3 class="text-xs text-blue-700 dark:text-blue-400 mb-1">Total Watchlist Items</h3>
            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($watchlists->total()) }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-6 rounded-xl border border-purple-200 dark:border-purple-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-users text-2xl text-purple-600 dark:text-purple-400"></i>
            </div>
            <h3 class="text-xs text-purple-700 dark:text-purple-400 mb-1">Active Watchers</h3>
            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ number_format($watchlists->pluck('user_id')->unique()->count()) }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 rounded-xl border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-chart-line text-2xl text-green-600 dark:text-green-400"></i>
            </div>
            <h3 class="text-xs text-green-700 dark:text-green-400 mb-1">Unique Stocks Watched</h3>
            <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ number_format($watchlists->pluck('stock_id')->unique()->count()) }}</p>
        </div>
    </div>

    <!-- Watchlists Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto fancy-scrollbar">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Current Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Change</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Added On</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($watchlists as $watchlist)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary font-semibold text-sm">{{ substr($watchlist->user->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $watchlist->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $watchlist->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                     <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold text-xl">
                                        <span class="text-white font-bold text-xs">{{ $watchlist->stock->symbol }}</span>
                        
                    </div>
                                    {{-- <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">{{ $watchlist->stock->symbol }}</span>
                                    </div> --}}
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $watchlist->stock->symbol }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $watchlist->stock->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">₦{{ number_format($watchlist->stock->current_price, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold {{ $watchlist->stock->price_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $watchlist->stock->price_change >= 0 ? '+' : '' }}₦{{ number_format($watchlist->stock->price_change, 2) }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $watchlist->stock->price_change >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $watchlist->stock->price_change >= 0 ? '+' : '' }}{{ number_format($watchlist->stock->price_change_percentage, 2) }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $watchlist->created_at->format('M d, Y') }}</span>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $watchlist->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.investments.user-details', $watchlist->user_id) }}" class="text-primary hover:text-primary/80 transition" title="View User Portfolio">
                                        <i class="ti ti-user"></i>
                                    </a>
                                    <a href="{{ route('admin.investments.stocks.show', $watchlist->stock_id) }}" class="text-blue-600 hover:text-blue-800 transition" title="View Stock Details">
                                        <i class="ti ti-chart-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="ti ti-eye-off text-5xl text-gray-400 dark:text-gray-600 mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No watchlists found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t dark:border-gray-700">
            {{ $watchlists->links() }}
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
@endsection