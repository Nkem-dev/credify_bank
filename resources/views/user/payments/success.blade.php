@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-2xl mx-auto mt-10">
        
        <!-- Success Animation -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full mb-4 animate-bounce">
                <i class="ti ti-check text-4xl text-green-600 dark:text-green-400"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Payment Successful!</h1>
            <p class="text-gray-600 dark:text-gray-300">Your wallet has been funded successfully</p>
        </div>

        <!-- Transaction Details Card -->
        <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm border dark:border-gray-700 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                <i class="ti ti-receipt text-primary mr-2"></i>
                Transaction Receipt
            </h2>

            <div class="space-y-4">
                <!-- Amount -->
                <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Amount Deposited</span>
                    <span class="text-2xl font-bold text-green-600 dark:text-green-400 font-mono">
                        ₦{{ number_format($transfer->amount, 2) }}
                    </span>
                </div>

                <!-- Reference -->
                <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Transaction Reference</span>
                    <div class="flex items-center space-x-2">
                        <span class="font-mono text-sm text-gray-900 dark:text-white" id="refText">
                            {{ $transfer->reference }}
                        </span>
                        <button onclick="copyReference()" class="text-primary hover:text-primary/80 transition" title="Copy">
                            <i class="ti ti-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Payment Method</span>
                    <span class="text-gray-900 dark:text-white font-medium">
                        {{ $transfer->payment_method ?? 'Paystack' }}
                        @if($transfer->channel)
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ ucfirst($transfer->channel) }})</span>
                        @endif
                    </span>
                </div>

                <!-- Date & Time -->
                <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Date & Time</span>
                    <span class="text-gray-900 dark:text-white">
                        {{ $transfer->completed_at?->format('M d, Y \a\t g:i A') }}
                    </span>
                </div>

                <!-- Status -->
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-600 dark:text-gray-400">Status</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full text-sm font-semibold">
                        Successful
                    </span>
                </div>
            </div>

            <!-- New Balance -->
            <div class="mt-6 p-4 bg-primary dark:from-primary/20 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">New Account Balance</p>
                <p class="text-3xl font-bold text-gray-600 dark:text-gray-300  font-mono">
                    ₦{{ number_format(Auth::user()->balance, 2) }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid md:grid-cols-2 gap-4 mb-8">
            <a href="{{ route('user.dashboard') }}" 
               class="flex items-center justify-center space-x-2 bg-primary hover:bg-primary/90 text-white font-semibold py-4 rounded-lg transition">
                <i class="ti ti-home"></i>
                <span>Back to Dashboard</span>
            </a>
            <a href="{{ route('user.transfers.index') }}" 
               class="flex items-center justify-center space-x-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-semibold py-4 rounded-lg border dark:border-gray-700 transition">
                <i class="ti ti-arrows-exchange"></i>
                <span>Make a Transfer</span>
            </a>
        </div>

        <!-- Download Receipt (Optional) -->
        <div class="text-center">
            <button onclick="window.print()" class="text-primary hover:text-primary/80 font-medium text-sm inline-flex items-center space-x-2">
                <i class="ti ti-printer"></i>
                <span>Print Receipt</span>
            </button>
        </div>

        <!-- Success Message Toast -->
        <div id="copyToast" class="fixed bottom-8 right-8 px-6 py-3 bg-green-600 text-white rounded-lg shadow-lg opacity-0 scale-0 transition-all duration-300 flex items-center space-x-2">
            <i class="ti ti-check"></i>
            <span>Reference copied!</span>
        </div>
    </div>
</main>

@push('scripts')
<script>
function copyReference() {
    const refText = document.getElementById('refText').textContent.trim();
    navigator.clipboard.writeText(refText);
    
    const toast = document.getElementById('copyToast');
    toast.classList.remove('opacity-0', 'scale-0');
    toast.classList.add('opacity-100', 'scale-100');
    
    setTimeout(() => {
        toast.classList.remove('opacity-100', 'scale-100');
        toast.classList.add('opacity-0', 'scale-0');
    }, 2000);
}

// Print styling
@media print {
    .no-print {
        display: none !important;
    }
}
</script>
@endpush
@endsection