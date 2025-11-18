@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-4xl mx-auto mt-10">
        <!-- Back Navigation -->
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-primary mb-6">
            <i class="ti ti-arrow-left mr-2"></i> Back to Dashboard
        </a>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">My Visa Card</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your virtual card and view details securely</p>
        </div>

        <!-- PIN Verification Modal -->
        <div id="pinModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl border dark:border-gray-700">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-lock text-3xl text-primary"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Verify Your Identity</h3>
                    <p class="text-gray-600 dark:text-gray-400">Enter your transaction PIN to view card details</p>
                </div>

                <form id="pinForm" onsubmit="verifyPin(event)">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction PIN</label>
                        <input 
                            type="password" 
                            id="pinInput" 
                            maxlength="4" 
                            pattern="[0-9]{4}"
                            class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg text-center text-2xl tracking-widest font-mono bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="••••"
                            required
                            autofocus
                        />
                        <p id="pinError" class="text-red-600 text-sm mt-2 hidden"></p>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary/90 transition flex items-center justify-center"
                    >
                        <i class="ti ti-check mr-2"></i> Verify & Continue
                    </button>
                </form>

                <a href="{{ route('user.dashboard') }}" class="block text-center text-gray-600 dark:text-gray-400 hover:text-primary mt-4 text-sm">
                    Cancel
                </a>
            </div>
        </div>

        <!-- Card Content (Hidden until verified) -->
        <div id="cardContent" class="hidden">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Virtual Card Display -->
                <div class="md:col-span-2">
                    <!-- Card Visual -->
                    <div class="relative bg-gradient-to-br from-primary to-gray-900 dark:from-gray-900 dark:to-black rounded-2xl p-8 text-white shadow-2xl mb-6 overflow-hidden border border-gray-700">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
                        </div>

                        <!-- Subtle accent line -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary via-accent to-primary"></div>

                        <!-- Card Content -->
                        <div class="relative z-10">
                            <!-- Card Header -->
                            <div class="flex justify-between items-start mb-12">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Credify Virtual Card</p>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 bg-green-500/20 border border-green-500/30 text-green-400 text-xs rounded-full font-medium">
                                            <i class="ti ti-check text-xs mr-1"></i> Active
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <i class="ti ti-brand-visa text-5xl opacity-90"></i>
                                </div>
                            </div>

                            <!-- Card Number -->
                            <div class="mb-8">
                                <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Card Number</p>
                                <div class="flex items-center justify-between">
                                    <p class="font-mono text-2xl tracking-wider" id="cardNumberDisplay">
                                        **** **** **** {{ substr($card->card_number, -4) }}
                                    </p>
                                    <button onclick="copyCardNumber()" class="hover:bg-white/10 p-2 rounded-lg transition" title="Copy card number">
                                        <i class="ti ti-copy text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Card Details -->
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Card Holder</p>
                                    <p class="font-semibold text-lg">{{ strtoupper(auth()->user()->name) }}</p>
                                </div>
                                <div class="text-center" id="expiryDisplay">
                                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Valid Thru</p>
                                    <p class="font-mono text-lg">{{ $card->expiry_month }}/{{ substr($card->expiry_year, -2) }}</p>
                                </div>
                                <div class="text-right" id="cvvDisplay">
                                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">CVV</p>
                                    <p class="font-mono text-lg">***</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        <button onclick="toggleCardDetails()" id="toggleBtn" class="bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-primary/90 transition flex items-center justify-center">
                            <i class="ti ti-eye mr-2"></i> Show Full Details
                        </button>
                    </div>

                    <!-- Card Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="ti ti-info-circle mr-2 text-primary"></i> Card Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Card Type</span>
                                <span class="font-semibold text-gray-900 dark:text-white">Visa Virtual</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Card Balance</span>
                                <span class="font-bold text-primary text-lg">₦{{ number_format($card->balance, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                                <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full text-sm font-medium">
                                    <i class="ti ti-check mr-1"></i> {{ ucfirst($card->status) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Created On</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $card->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Expires On</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $card->expires_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button onclick="viewTransactions()" class="w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white py-3 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition flex items-center justify-center">
                                <i class="ti ti-history mr-2"></i> View Transactions
                            </button>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                        <div class="flex items-start">
                            <i class="ti ti-shield-lock text-2xl text-red-600 dark:text-red-400 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-bold text-red-900 dark:text-red-200 mb-2">Security Notice</h4>
                                <p class="text-sm text-red-800 dark:text-red-300 mb-3">
                                    Never share your card details with anyone. Keep your CVV secure.
                                </p>
                                <form method="POST" action="{{ route('user.cards.destroy', $card) }}" onsubmit="return confirm('Are you sure you want to delete this card? This action cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 text-sm font-semibold hover:underline flex items-center">
                                        <i class="ti ti-trash mr-1"></i> Card Compromised? Delete Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Tips -->
                    <div class="bg-primary/5 dark:bg-primary/10 border border-primary/20 dark:border-primary/30 rounded-xl p-6">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="ti ti-bulb mr-2 text-primary"></i> Usage Tips
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <i class="ti ti-check text-primary mr-2 mt-0.5"></i>
                                <span>Use for secure online purchases</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-primary mr-2 mt-0.5"></i>
                                <span>Ensure sufficient balance before payments</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-primary mr-2 mt-0.5"></i>
                                <span>Monitor transactions regularly</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-primary mr-2 mt-0.5"></i>
                                <span>Delete immediately if compromised</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
let cardRevealed = false;
const fullCardNumber = '{{ $card->card_number }}';
const cvvNumber = '{{ $card->cvv }}';
const correctPin = '{{ auth()->user()->transaction_pin }}';

function verifyPin(event) {
    event.preventDefault();
    const pinInput = document.getElementById('pinInput');
    const pinError = document.getElementById('pinError');
    const enteredPin = pinInput.value;

    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i> Verifying...';

    // Verify PIN
    fetch('{{ route("user.cards.verify-pin") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ pin: enteredPin })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Verification failed');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('pinModal').classList.add('hidden');
            document.getElementById('cardContent').classList.remove('hidden');
            showToast('Access granted', 'success');
        } else {
            throw new Error(data.message || 'Incorrect PIN');
        }
    })
    .catch(error => {
        console.error('PIN verification error:', error);
        pinError.textContent = error.message || 'Incorrect PIN. Please try again.';
        pinError.classList.remove('hidden');
        pinInput.value = '';
        pinInput.classList.add('border-red-500');
        
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        setTimeout(() => {
            pinInput.classList.remove('border-red-500');
            pinError.classList.add('hidden');
        }, 3000);
    });
}

function toggleCardDetails() {
    const cardDisplay = document.getElementById('cardNumberDisplay');
    const cvvDisplay = document.getElementById('cvvDisplay').querySelector('p:last-child');
    const toggleBtn = document.getElementById('toggleBtn');
    
    if (!cardRevealed) {
        // Show full details
        cardDisplay.textContent = formatCardNumber(fullCardNumber);
        cvvDisplay.textContent = cvvNumber;
        toggleBtn.innerHTML = '<i class="ti ti-eye-off mr-2"></i> Hide Full Details';
        cardRevealed = true;
        
        // Show toast notification
        showToast('Card details revealed', 'warning');
        
        // Auto-hide after 30 seconds for security
        setTimeout(() => {
            if (cardRevealed) {
                toggleCardDetails();
                showToast('Card details hidden for security', 'info');
            }
        }, 30000);
    } else {
        // Hide details
        cardDisplay.textContent = '**** **** **** {{ substr($card->card_number, -4) }}';
        cvvDisplay.textContent = '***';
        toggleBtn.innerHTML = '<i class="ti ti-eye mr-2"></i> Show Full Details';
        cardRevealed = false;
    }
}

function formatCardNumber(number) {
    return number.match(/.{1,4}/g).join(' ');
}

function copyCardNumber() {
    navigator.clipboard.writeText(fullCardNumber).then(() => {
        showToast('Card number copied to clipboard!', 'success');
    }).catch(() => {
        showToast('Failed to copy card number', 'error');
    });
}

function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-green-600',
        error: 'bg-red-600',
        warning: 'bg-amber-600',
        info: 'bg-blue-600'
    };
    
    const icons = {
        success: 'ti-check',
        error: 'ti-x',
        warning: 'ti-alert-triangle',
        info: 'ti-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2 animate-slide-up`;
    toast.innerHTML = `<i class="ti ${icons[type]}"></i><span>${message}</span>`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function viewTransactions() {
    window.location.href = '{{ route("user.dashboard") }}';
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-slide-up {
        animation: slide-up 0.3s ease-out;
    }
    @media print {
        body * { visibility: hidden; }
        .max-w-4xl, .max-w-4xl * { visibility: visible; }
        .max-w-4xl { position: absolute; left: 0; top: 0; width: 100%; }
        button, .bg-red-50, .bg-primary\/5 { display: none !important; }
    }
`;
document.head.appendChild(style);
</script>
@endpush

@endsection