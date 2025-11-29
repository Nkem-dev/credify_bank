@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Virtual Card Management
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Monitor and manage all virtual cards</p>
        </div>

       

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Total Cards -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-primary/10 rounded-lg">
                        <i class="ti ti-credit-card text-2xl text-primary"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCards) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Cards</p>
            </div>

            <!-- Active Cards -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="ti ti-check text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($activeCards) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Active Cards</p>
            </div>

            <!-- Blocked Cards -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <i class="ti ti-ban text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($blockedCards) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Blocked Cards</p>
            </div>

            <!-- Total Balance -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <i class="ti ti-wallet text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">₦{{ number_format($totalBalance, 2) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Balance</p>
            </div>

        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('admin.virtual-cards.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="User, card number, or reference..."
                           class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="ti ti-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.virtual-cards.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="ti ti-x"></i>
                    </a>
                    <button type="submit" 
                            formaction="{{ route('admin.virtual-cards.download') }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="ti ti-download"></i>
                    </button>
                </div>

            </form>
        </div>

        <!-- Cards Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Card</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Balance</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Expires</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Created</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($cards as $card)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                
                                <!-- Card Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg mr-3">
                                            <i class="ti ti-credit-card text-xl text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white font-mono">**** {{ substr($card->card_number, -4) }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $card->reference }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- User -->
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $card->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $card->user->email }}</p>
                                    </div>
                                </td>

                                <!-- Balance -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-primary">₦{{ number_format($card->balance, 2) }}</p>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($card->status == 'active') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($card->status == 'blocked') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($card->status) }}
                                    </span>
                                </td>

                                <!-- Expiry -->
                                <td class="px-6 py-4">
                                    @php
                                        $expiryDate = \Carbon\Carbon::createFromDate($card->expiry_year, $card->expiry_month, 1)->endOfMonth();
                                        $isExpired = $expiryDate->isPast();
                                        $isExpiringSoon = !$isExpired && $expiryDate->diffInDays() <= 30;
                                    @endphp
                                    <p class="text-sm {{ $isExpired ? 'text-red-600 dark:text-red-400' : ($isExpiringSoon ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-white') }}">
                                        {{ $card->expiry_month }}/{{ $card->expiry_year }}
                                    </p>
                                    @if($isExpired)
                                        <p class="text-xs text-red-500">Expired</p>
                                    @elseif($isExpiringSoon)
                                        <p class="text-xs text-yellow-500"></p>
                                    @endif
                                </td>

                                <!-- Created -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $card->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $card->created_at->format('h:i A') }}</p>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.virtual-cards.show', $card) }}" 
                                       class="text-primary hover:text-primary/80 font-medium text-sm">
                                        View Details →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-credit-card-off text-4xl mb-3 block"></i>
                                    <p>No virtual cards found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($cards->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 border-t dark:border-gray-700">
                    {{ $cards->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
@endsection