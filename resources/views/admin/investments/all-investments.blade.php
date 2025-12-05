@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    All Investments
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    View and manage all user investments
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.investments.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
                <button onclick="exportData()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="ti ti-file-export"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.investments.all') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search User</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                <select name="sort" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    <option value="latest">Latest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="highest">Highest Value</option>
                    <option value="lowest">Lowest Value</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    <i class="ti ti-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Investments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto fancy-scrollbar">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Investor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Invested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Current Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Profit/Loss</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stocks Owned</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($investments as $investment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary font-semibold text-sm">{{ substr($investment->user->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $investment->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $investment->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">₦{{ number_format($investment->total_invested, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">₦{{ number_format($investment->current_value, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold {{ $investment->total_profit_loss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $investment->total_profit_loss >= 0 ? '+' : '' }}₦{{ number_format($investment->total_profit_loss, 2) }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $investment->total_profit_loss >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ number_format($investment->profit_loss_percentage, 2) }}%
                                    </span>
                                </div>
                            </td>
                            {{-- <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $investment->userStocks ?? 0 }}</span>
                            </td> --}}
                             <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $investment->user->userStocks()->count() ?? 0 }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $investment->updated_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.investments.user-details', $investment->user_id) }}" class="text-primary hover:text-primary/80 transition" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <button onclick="sendNotification({{ $investment->user_id }})" class="text-blue-600 hover:text-blue-800 transition" title="Send Notification">
                                        <i class="ti ti-bell"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="ti ti-chart-line-off text-5xl text-gray-400 dark:text-gray-600 mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No investments found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t dark:border-gray-700">
            {{ $investments->links() }}
        </div>
    </div>
</main>

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

@push('scripts')
<script>
    function exportData() {
        window.location.href = '{{ route('admin.investments.transactions.export') }}';
    }

    function sendNotification(userId) {
        // Implement notification sending logic
        alert('Send notification to user ID: ' + userId);
    }
</script>
@endpush
@endsection