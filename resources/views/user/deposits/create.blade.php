@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-2xl mx-auto mt-10">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center text-primary hover:text-primary/80 mb-4 transition">
                <i class="ti ti-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Fund Wallet</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Add money to your Credify Bank account</p>
        </div>

        <!-- Current Balance Card -->
        <div class="bg-primary/10  dark:primary/10  p-6 rounded-xl border dark:border-gray-700 mb-8">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Current Balance</p>
            <p class="text-3xl font-bold text-primary font-mono">
                ₦{{ number_format(Auth::user()->balance ?? 0, 2) }}
            </p>
        </div>

        <!-- Deposit Form -->
        <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm border dark:border-gray-700">
            <form action="{{ route('user.deposit.initialize') }}" method="POST" id="depositForm">
                @csrf

                <!-- Amount Input -->
                <div class="mb-6">
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Amount to Deposit <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-lg font-semibold">₦</span>
                        <input 
                            type="number" 
                            name="amount" 
                            id="amount"
                            min="100"
                            max="1000000"
                            step="0.01"
                            value="{{ old('amount') }}"
                            placeholder="0.00"
                            required
                            class="w-full pl-10 pr-4 py-4 text-lg font-mono border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('amount') border-red-500 @enderror">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Minimum: ₦100 | Maximum: ₦1,000,000
                    </p>
                </div>

                <!-- Quick Amount Buttons -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Quick Select
                    </label>
                    <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                        <button type="button" onclick="setAmount(1000)" class="quick-amount-btn">₦1,000</button>
                        <button type="button" onclick="setAmount(5000)" class="quick-amount-btn">₦5,000</button>
                        <button type="button" onclick="setAmount(10000)" class="quick-amount-btn">₦10,000</button>
                        <button type="button" onclick="setAmount(20000)" class="quick-amount-btn">₦20,000</button>
                        <button type="button" onclick="setAmount(50000)" class="quick-amount-btn">₦50,000</button>
                    </div>
                </div>

                <!-- Payment Method Info -->
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-6">
                    <div class="flex items-start space-x-3">
                        <i class="ti ti-info-circle text-blue-600 dark:text-blue-400 text-xl mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-300">Payment via Paystack</p>
                            <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                You'll be redirected to Paystack to complete your payment securely using card, bank transfer, or USSD.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-4 rounded-lg transition-all duration-300 flex items-center justify-center space-x-2 group">
                    <span>Proceed to Payment</span>
                    <i class="ti ti-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>

                <!-- Security Note -->
                <div class="mt-6 flex items-center justify-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class="ti ti-shield-check text-green-600"></i>
                    <span>Secured by Paystack - Your payment information is safe</span>
                </div>
            </form>
        </div>

        <!-- Features Section -->
        <div class="grid md:grid-cols-3 gap-4 mt-8">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 text-center">
                <i class="ti ti-clock text-2xl text-primary mb-2"></i>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Instant Credit</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Funds reflect immediately</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 text-center">
                <i class="ti ti-shield-lock text-2xl text-green-600 mb-2"></i>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Secure Payment</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Bank-level encryption</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 text-center">
                <i class="ti ti-receipt text-2xl text-accent mb-2"></i>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Transaction Receipt</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Instant confirmation</p>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
function setAmount(amount) {
    document.getElementById('amount').value = amount;
}

// Form validation
document.getElementById('depositForm').addEventListener('submit', function(e) {
    const amount = parseFloat(document.getElementById('amount').value);
    
    if (isNaN(amount) || amount < 100) {
        e.preventDefault();
        alert('Please enter a valid amount (minimum ₦100)');
        return false;
    }
    
    if (amount > 1000000) {
        e.preventDefault();
        alert('Maximum deposit amount is ₦1,000,000');
        return false;
    }
});
</script>

<style>
.quick-amount-btn {
    @apply px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-primary hover:text-white transition-all duration-300 font-medium text-sm;
}
</style>
@endpush
@endsection