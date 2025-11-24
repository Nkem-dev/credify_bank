@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-5xl mx-auto mt-10">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.transactions.index') }}" 
               class="inline-flex items-center text-primary hover:text-primary/80 mb-4 transition">
                <i class="ti ti-arrow-left mr-2"></i>
                Back to Transactions
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Transaction Details</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Reference: {{ $transaction->reference }}</p>
                </div>
                <div>
                    <span class="px-4 py-2 rounded-lg text-sm font-semibold
                        @if($transaction->status == 'successful') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                        @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                        @endif">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Transaction Overview -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-8 mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Transaction Overview</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Transaction Amount</p>
                    <p class="text-3xl font-bold text-primary">₦{{ number_format($transaction->amount, 2) }}</p>
                </div>

                <!-- Total with Fee -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Amount (incl. fee)</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">₦{{ number_format($transaction->total_amount, 2) }}</p>
                    @if($transaction->fee > 0)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fee: ₦{{ number_format($transaction->fee, 2) }}</p>
                    @endif
                </div>

                <!-- Transaction Type -->
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Transaction Type</p>
                    <span class="px-3 py-2 text-sm font-semibold rounded-lg
                        @if($transaction->type == 'deposit') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                        @elseif($transaction->type == 'internal') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                        @elseif($transaction->type == 'airtime') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                        @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                        @endif">
                        {{ ucfirst($transaction->type) }}
                    </span>
                </div>

                <!-- Reference -->
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Reference Number</p>
                    <div class="flex items-center space-x-2">
                        <p class="font-mono text-sm font-semibold text-gray-900 dark:text-white" id="refNum">
                            {{ $transaction->reference }}
                        </p>
                        <button onclick="copyReference()" class="text-gray-500 hover:text-primary transition">
                            <i class="ti ti-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Date Created -->
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Date Created</p>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $transaction->created_at->format('M d, Y \a\t g:i A') }}
                    </p>
                </div>

                <!-- Completed Date -->
                @if($transaction->completed_at)
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Completed Date</p>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $transaction->completed_at->format('M d, Y \a\t g:i A') }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-8 mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">User Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">User Name</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->user->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Email</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->user->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Account Number</p>
                    <p class="font-mono font-semibold text-gray-900 dark:text-white">{{ $transaction->user->account_number ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Balance</p>
                    <p class="font-semibold text-primary">₦{{ number_format($transaction->user->balance ?? 0, 2) }}</p>
                </div>

                <div class="md:col-span-2">
                    <a href="{{ route('admin.users.show', $transaction->user) }}" 
                       class="inline-flex items-center text-primary hover:text-primary/80 font-medium">
                        View User Profile <i class="ti ti-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recipient Information -->
        @if($transaction->type !== 'deposit')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-8 mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Recipient Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Recipient Name</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->recipient_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Account Number</p>
                    <p class="font-mono font-semibold text-gray-900 dark:text-white">{{ $transaction->recipient_account_number ?? 'N/A' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Bank Name</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->recipient_bank_name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Description -->
        @if($transaction->description)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-8 mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Description</h3>
            <p class="text-gray-700 dark:text-gray-300 italic">"{{ $transaction->description }}"</p>
        </div>
        @endif

        <!-- Payment Method & Metadata -->
        @if($transaction->payment_method || $transaction->metadata)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-8 mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Additional Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($transaction->payment_method)
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Payment Method</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->payment_method }}</p>
                </div>
                @endif

                @if($transaction->channel)
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Channel</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($transaction->channel) }}</p>
                </div>
                @endif

                @if($transaction->metadata)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Metadata</p>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg text-xs overflow-x-auto">{{ json_encode(json_decode($transaction->metadata), JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Reverse Transaction (Only for successful non-deposit transactions) -->
            @if($transaction->status === 'successful' && $transaction->type !== 'deposit')
            <button onclick="openReverseModal()" 
                    class="flex items-center justify-center space-x-2 px-6 py-4 bg-white dark:bg-gray-800 border-2 border-orange-500 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/20 transition">
                <i class="ti ti-rotate-clockwise-2 text-xl"></i>
                <span class="font-semibold">Reverse Transaction</span>
            </button>
            @endif

            <!-- Mark as Failed (Only for pending transactions) -->
            @if($transaction->status === 'pending')
            <button onclick="openFailedModal()" 
                    class="flex items-center justify-center space-x-2 px-6 py-4 bg-white dark:bg-gray-800 border-2 border-red-500 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                <i class="ti ti-x text-xl"></i>
                <span class="font-semibold">Mark as Failed</span>
            </button>
            @endif

            <!-- Print Receipt -->
            <button onclick="window.print()" 
                    class="flex items-center justify-center space-x-2 px-6 py-4 bg-white dark:bg-gray-800 border-2 border-primary text-primary rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                <i class="ti ti-printer text-xl"></i>
                <span class="font-semibold">Print Receipt</span>
            </button>

        </div>

    </div>
</main>

{{-- Reverse Transaction Modal --}}
<div id="reverseModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Reverse Transaction</h3>
                <button onclick="closeModal('reverseModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-4 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                <p class="text-sm text-orange-800 dark:text-orange-300">
                    <strong>Warning:</strong> This will credit ₦{{ number_format($transaction->amount, 2) }} back to {{ $transaction->user->name }}'s account.
                </p>
            </div>

            <form action="{{ route('admin.transactions.reverse', $transaction) }}" method="POST">
                @csrf
                
                <!-- Reason -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Reversal <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason"
                              rows="3"
                              required
                              placeholder="Explain why this transaction is being reversed..."
                              class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <!-- Transaction PIN -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Your Transaction PIN <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="transaction_pin"
                           maxlength="4"
                           placeholder="••••"
                           required
                           class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-center font-mono text-lg tracking-widest">
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeModal('reverseModal')"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition">
                        Reverse Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Mark as Failed Modal --}}
<div id="failedModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Mark Transaction as Failed</h3>
                <button onclick="closeModal('failedModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <p class="text-sm text-red-800 dark:text-red-300">
                    <strong>Warning:</strong> This action will mark the transaction as failed. This cannot be undone.
                </p>
            </div>

            <form action="{{ route('admin.transactions.mark-failed', $transaction) }}" method="POST">
                @csrf
                
                <!-- Reason -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Failure <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason"
                              rows="3"
                              required
                              placeholder="Explain why this transaction failed..."
                              class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeModal('failedModal')"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        Mark as Failed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openReverseModal() {
    document.getElementById('reverseModal').classList.remove('hidden');
}

function openFailedModal() {
    document.getElementById('failedModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function copyReference() {
    const refNum = document.getElementById('refNum').textContent.trim();
    navigator.clipboard.writeText(refNum);
    
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    icon.classList.replace('ti-copy', 'ti-check');
    
    setTimeout(() => {
        icon.classList.replace('ti-check', 'ti-copy');
    }, 2000);
}

// Close modals on background click
document.addEventListener('DOMContentLoaded', function() {
    ['reverseModal', 'failedModal'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }
    });
});
</script>
@endpush
@endsection