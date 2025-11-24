@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Loan Management
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Review, approve, and manage loan applications</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Total Loans -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-primary/10 rounded-lg">
                        <i class="ti ti-cash text-2xl text-primary"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($totalLoans, 2) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Disbursed</p>
            </div>

            <!-- Pending Loans -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <i class="ti ti-clock text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($pendingLoans) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pending Applications</p>
            </div>

            <!-- Active Loans -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="ti ti-check text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($activeLoans) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Active Loans</p>
            </div>

            <!-- Overdue Loans -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <i class="ti ti-alert-triangle text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($overdueLoans) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Overdue</p>
            </div>

        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('admin.loans.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search User</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Name or email..."
                           class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment</label>
                    <select name="payment_status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">All</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Unpaid</option>
                        <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="ti ti-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.loans.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="ti ti-x"></i>
                    </a>
                    <button type="submit" 
                            formaction="{{ route('admin.loans.download') }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="ti ti-download"></i>
                    </button>
                </div>

            </form>
        </div>

        <!-- Loans Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">S/N</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Due Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <!-- ID -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-mono font-semibold text-gray-900 dark:text-white">#{{ $loan->id }}</p>
                                </td>

                                <!-- User -->
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $loan->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $loan->user->email }}</p>
                                    </div>
                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-primary">₦{{ number_format($loan->amount, 2) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $loan->interest_rate }}% interest</p>
                                </td>

                                <!-- Duration -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $loan->term_months }} months</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">₦{{ number_format($loan->monthly_payment, 2) }}/mo</p>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($loan->status == 'approved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($loan->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @endif">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>

                                <!-- Payment Status -->
                                <td class="px-6 py-4">
                                    @if($loan->payment_status)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($loan->payment_status == 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($loan->payment_status == 'partial') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                        @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($loan->payment_status) }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Due Date -->
                                <td class="px-6 py-4">
                                    @if($loan->due_date)
                                        @php
                                            $dueDate = \Carbon\Carbon::parse($loan->due_date);
                                            $isOverdue = $dueDate->isPast() && $loan->payment_status !== 'completed';
                                        @endphp
                                        <p class="text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-900 dark:text-white' }}">
                                            {{ $dueDate->format('M d, Y') }}
                                        </p>
                                        @if($isOverdue)
                                            <p class="text-xs text-red-500">Overdue</p>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.loans.show', $loan) }}" 
                                       class="text-primary hover:text-primary/80 font-medium text-sm">
                                        View Details →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-cash text-4xl mb-3 block"></i>
                                    <p>No loan applications found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($loans->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 border-t dark:border-gray-700">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
@endsection