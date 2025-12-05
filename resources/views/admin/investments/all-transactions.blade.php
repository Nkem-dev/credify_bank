@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    All Stock Transactions
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    Monitor all buy and sell transactions
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.investments.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
                <button onclick="exportTransactions()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="ti ti-file-export"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.investments.transactions.all') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="User or stock..." class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                <select name="type" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    <option value="">All Types</option>
                    <option value="buy" {{ request('type') == 'buy' ? 'selected' : '' }}>Buy</option>
                    <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>Sell</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                <select name="sort" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                    <option value="latest">Latest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="highest">Highest Amount</option>
                    <option value="lowest">Lowest Amount</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    <i class="ti ti-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto fancy-scrollbar">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price/Share</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary font-semibold text-sm">{{ substr($transaction->user->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{-- <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-bold text-white">{{ $transaction->stock->symbol ?? 'N/A' }}</span>
                                    </div> --}}
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $transaction->stock->symbol ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($transaction->stock->name ?? 'N/A', 20) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->type === 'buy')
                                    <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <i class="ti ti-arrow-down-left text-xs mr-1"></i>
                                        Buy
                                    </span>
                                @elseif($transaction->type === 'sell')
                                    <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        <i class="ti ti-arrow-up-right text-xs mr-1"></i>
                                        Sell
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                        N/A
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ number_format($transaction->quantity) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">₦{{ number_format($transaction->price_per_share, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">₦{{ number_format($transaction->total_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                    ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                    ($transaction->status === 'cancelled' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' : 
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400')) }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('M d, Y') }}</span>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $transaction->created_at->format('h:i A') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @if($transaction->status === 'pending')
                                        <button onclick="updateStatus({{ $transaction->id }}, 'completed')" class="text-green-600 hover:text-green-800 transition" title="Mark as Completed">
                                            <i class="ti ti-check"></i>
                                        </button>
                                        <button onclick="updateStatus({{ $transaction->id }}, 'cancelled')" class="text-red-600 hover:text-red-800 transition" title="Cancel">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.investments.user-details', $transaction->user_id) }}" class="text-primary hover:text-primary/80 transition" title="View User">
                                        <i class="ti ti-user"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="ti ti-receipt-off text-5xl text-gray-400 dark:text-gray-600 mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No transactions found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t dark:border-gray-700">
            {{ $transactions->links() }}
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

@push('scripts')
<script>
    function exportTransactions() {
        window.location.href = '{{ route("admin.investments.transactions.export") }}';
    }

    function updateStatus(transactionId, status) {
        if (confirm(`Are you sure you want to mark this transaction as ${status}?`)) {
            fetch(`/admin/investments/transactions/${transactionId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: status })
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