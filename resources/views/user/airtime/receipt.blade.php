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
                Airtime Purchase Successful!
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ $transfer->description }}
            </p>
        </div>

        <!-- Receipt Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 overflow-hidden mb-6">
            <!-- Amount -->
            <div class="bg-gradient-to-r from-primary/5 to-accent/5 dark:from-primary/10 dark:to-accent/10 p-8 text-center border-b dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Amount Paid</p>
                <p class="text-5xl font-bold text-primary dark:text-primary mb-1">
                    ₦{{ number_format($transfer->amount, 2) }}
                </p>
                <p class="text-sm text-green-600 dark:text-green-400 mt-2">
                    <i class="ti ti-bolt"></i> No fees • Instant recharge
                </p>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Reference</span>
                    <div class="flex items-center space-x-2">
                        <span class="font-mono font-semibold text-gray-900 dark:text-white text-sm" id="reference">
                            {{ $transfer->reference }}
                        </span>
                        <button onclick="copyReference()" class="text-primary hover:text-primary/80 p-1" title="Copy Reference">
                            <i class="ti ti-copy text-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        <i class="ti ti-check mr-1"></i> Successful
                    </span>
                </div>

                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Network</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ Str::title($transfer->recipient_name) }}
                    </span>
                </div>

                <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Phone Number</span>
                    <span class="font-mono font-semibold text-gray-900 dark:text-white">
                        {{ $transfer->recipient_account_number }}
                    </span>
                </div>

                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Date & Time</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ $transfer->created_at->format('M d, Y • g:i A') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <button onclick="window.print()" class="bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary/90 transition flex items-center justify-center">
                <i class="ti ti-printer mr-2"></i> Print Receipt
            </button>
            <button onclick="shareReceipt()" class="bg-accent text-white py-3 rounded-lg font-semibold hover:bg-accent/90 transition flex items-center justify-center">
                <i class="ti ti-share mr-2"></i> Share
            </button>
            <a href="{{ route('user.dashboard') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white py-3 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center justify-center">
                <i class="ti ti-home mr-2"></i> Dashboard
            </a>
        </div>
    </div>
</main>

@push('scripts')
<script>
function copyReference() {
    const ref = document.getElementById('reference').textContent;
    navigator.clipboard.writeText(ref).then(() => {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.innerHTML = '<i class="ti ti-check mr-2"></i> Reference Copied!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}

function shareReceipt() {
    if (navigator.share) {
        navigator.share({
            title: 'Airtime Purchase Receipt',
            text: `₦{{ number_format($transfer->amount, 2) }} Airtime to {{ $transfer->recipient_account_number }} ({{ $transfer->recipient_name }}) - Ref: {{ $transfer->reference }}`,
            url: window.location.href
        }).catch(() => copyReference());
    } else {
        copyReference();
    }
}

// Clean print styles
const printStyle = document.createElement('style');
printStyle.textContent = `
    @media print {
        body * { visibility: hidden; }
        .max-w-2xl, .max-w-2xl * { visibility: visible; }
        .max-w-2xl { position: absolute; left: 0; top: 0; width: 100%; }
        button, .grid { display: none !important; }
        .bg-gradient-to-r { background: #f0fdf4 !important; -webkit-print-color-adjust: exact; }
    }
`;
document.head.appendChild(printStyle);
</script>
@endpush
@endsection