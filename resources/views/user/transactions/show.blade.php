@extends('layouts.user')

@section('content')
<div class="mt-16 max-w-4xl mx-auto px-4 py-20">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('user.transactions.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary mb-4 inline-flex items-center">
            <i class="ti ti-arrow-left mr-2"></i> Back to Transactions
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-4">Transaction Details</h1>
    </div>

    <!-- Transaction Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 overflow-hidden">
        <!-- Status Header -->
        <div class="px-6 py-4 border-b dark:border-gray-700 
            @if($transaction->status === 'successful') bg-green-50 dark:bg-green-900/20
            @elseif($transaction->status === 'pending') bg-yellow-50 dark:bg-yellow-900/20
            @else bg-red-50 dark:bg-red-900/20 @endif">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Transaction Status</p>
                    <p class="text-2xl font-bold 
                        @if($transaction->status === 'successful') text-green-600 dark:text-green-400
                        @elseif($transaction->status === 'pending') text-yellow-600 dark:text-yellow-400
                        @else text-red-600 dark:text-red-400 @endif">
                        {{ ucfirst($transaction->status) }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Amount</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        ₦{{ number_format($transaction->amount, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Reference</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->reference }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Type</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($transaction->type) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Date</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->created_at->format('M d, Y H:i A') }}</p>
                </div>
                @if($transaction->fee > 0)
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Fee</p>
                    <p class="font-semibold text-gray-900 dark:text-white">₦{{ number_format($transaction->fee, 2) }}</p>
                </div>
                @endif
            </div>

            @if($transaction->description)
            <div class="pt-4 border-t dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Description</p>
                <p class="text-gray-900 dark:text-white">{{ $transaction->description }}</p>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700 flex gap-3">
            <a href="{{ route('user.transfers.receipt', $transaction->reference) }}" 
               class="flex-1 bg-primary hover:bg-indigo-700 text-white text-center py-3 rounded-lg transition">
                <i class="ti ti-receipt mr-2"></i> View Receipt
            </a>
            <button onclick="window.print()" 
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="ti ti-printer"></i>
            </button>
        </div>
    </div>
</div>
@endsection