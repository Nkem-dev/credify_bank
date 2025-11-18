@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-2xl mx-auto mt-10">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                <i class="ti ti-check text-4xl text-green-600 dark:text-green-400"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Transfer Successful!
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Your money has been sent successfully
            </p>
        </div>

        <!-- Receipt Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 overflow-hidden mb-6">
            <!-- Amount Section -->
            {{-- main amount --}}
            <div class="bg-gradient-to-r from-primary/5 to-accent/5 dark:from-primary/10 dark:to-accent/10 p-8 text-center border-b dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Amount Sent</p>
                <p class="text-5xl font-bold text-primary dark:text-primary mb-1">
                    ₦{{ number_format($transfer->amount, 2) }}  
                </p>
                {{-- transfer fee if any --}}
                @if($transfer->fee > 0)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    + ₦{{ number_format($transfer->fee, 2) }} fee = ₦{{ number_format($transfer->total_amount, 2) }}
                </p>
                @else 
                {{-- nonfees for internal transfers --}}
                <p class="text-sm text-green-600 dark:text-green-400 mt-2">
                    <i class="ti ti-bolt"></i> No fees
                </p>
                @endif
            </div>

            <!-- Transaction Details -->
            <div class="p-6 space-y-4">
                <!-- Reference Number -->
                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Reference Number</span>
                    <div class="flex items-center space-x-2">
                        <span class="font-mono font-semibold text-gray-900 dark:text-white text-sm" id="reference">{{ $transfer->reference }}</span>
                        <button onclick="copyReference()" class="text-primary hover:text-primary/80 transition p-1" title="Copy">
                            <i class="ti ti-copy text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        <i class="ti ti-check mr-1"></i>{{ ucfirst($transfer->status) }}
                    </span>
                </div>

                <!-- Recipient Name -->
                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Recipient</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $transfer->recipient_name }}</span>
                </div>

                <!-- Account Number -->
                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Account Number</span>
                    <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $transfer->recipient_account_number }}</span>
                </div>

                <!-- Bank Name -->
                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Bank</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $transfer->recipient_bank_name }}</span>
                </div>

                <!-- Transfer Type -->
                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Transfer Type</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                        <i class="ti ti-building-bank mr-1"></i>{{ ucfirst($transfer->type) }}
                    </span>
                </div>

                @if($transfer->description)
                <!-- Description -->
                <div class="flex items-start justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Description</span>
                    <span class="text-right text-gray-900 dark:text-white max-w-xs">{{ $transfer->description }}</span>
                </div>
                @endif

                <!-- Date & Time -->
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Date & Time</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $transfer->created_at->format('M d, Y • g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <button onclick="window.print()" class="bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary/90 transition flex items-center justify-center">
                <i class="ti ti-printer mr-2"></i>
                Print Receipt
            </button>
            <button onclick="shareReceipt()" class="bg-accent text-white py-3 rounded-lg font-semibold hover:bg-accent/90 transition flex items-center justify-center">
                <i class="ti ti-share mr-2"></i>
                Share
            </button>
            <a href="{{ route('user.dashboard') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white py-3 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center justify-center">
                <i class="ti ti-home mr-2"></i>
                Go to Dashboard
            </a>
        </div>

        <!-- Success Info -->
        {{-- <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-start space-x-3">
            <i class="ti ti-shield-check text-green-600 dark:text-green-400 text-xl mt-0.5"></i>
            <div>
                <p class="text-sm font-medium text-green-900 dark:text-green-200">Secure Transaction</p>
                <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                    This transaction was processed securely and your funds have been delivered successfully.
                </p>
            </div>
        </div> --}}
    </div>
</main>

@push('scripts')
<script>
    // copy reference number
function copyReference() {
    const reference = document.getElementById('reference').textContent;
    navigator.clipboard.writeText(reference);
    
    // Show toast when you copy reference number
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.innerHTML = '<i class="ti ti-check mr-2"></i>Reference copied!';
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

// share receipt 
function shareReceipt() {
    if (navigator.share) {
        navigator.share({
            title: 'Transfer Receipt - Credify Bank',
            text: 'Transfer of ₦{{ number_format($transfer->amount, 2) }} to {{ $transfer->recipient_name }} - Reference: {{ $transfer->reference }}',
        }).catch(err => console.log('Share failed:', err));
    } else {
        copyReference();
    }
}

// Print receipt style
const style = document.createElement('style');
style.textContent = `
    @media print {
        body * { visibility: hidden; }
        .max-w-2xl, .max-w-2xl * { visibility: visible; }
        .max-w-2xl { position: absolute; left: 0; top: 0; width: 100%; }
        button, .grid.grid-cols-1 { display: none !important; }
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection