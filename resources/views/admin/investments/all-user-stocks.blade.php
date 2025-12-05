@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    All User Portfolios
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    View all user stock holdings
                </p>
            </div>
            <a href="{{ route('admin.investments.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="ti ti-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- User Stocks Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto fancy-scrollbar">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avg Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Current Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Profit/Loss</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($userStocks as $userStock)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary font-semibold text-sm">{{ substr($userStock->user->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $userStock->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $userStock->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $userStock->stock->symbol }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $userStock->stock->symbol }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $userStock->stock->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ number_format($userStock->quantity) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">₦{{ number_format($userStock->average_price, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">₦{{ number_format($userStock->stock->current_price, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">₦{{ number_format($userStock->total_value, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold {{ $userStock->profit_loss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $userStock->profit_loss >= 0 ? '+' : '' }}₦{{ number_format($userStock->profit_loss, 2) }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $userStock->profit_loss >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ number_format($userStock->profit_loss_percentage, 2) }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.investments.user-details', $userStock->user_id) }}" class="text-primary hover:text-primary/80 transition" title="View Portfolio">
                                        <i class="ti ti-user"></i>
                                    </a>
                                    <a href="{{ route('admin.investments.stocks.show', $userStock->stock_id) }}" class="text-blue-600 hover:text-blue-800 transition" title="View Stock">
                                        <i class="ti ti-chart-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="ti ti-chart-line-off text-5xl text-gray-400 dark:text-gray-600 mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No user stocks found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t dark:border-gray-700">
            {{ $userStocks->links() }}
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