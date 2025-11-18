@extends('layouts.user')

@section('content')
<div class="mt-16 max-w-2xl mx-auto px-4">
    <div class="text-center mb-8 mt-20">
        <h1 class="text-2xl font-bold text-primary dark:text-primary">Apply for a Loan</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Fast, secure, and flexible financing</p>
    </div>

    <!-- Eligibility Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg mb-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Transactions</p>
                <p class="text-2xl font-bold text-primary dark:text-primary">
                    ₦{{ number_format($totalTransactions, 0) }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600 dark:text-gray-400">Eligibility</p>
                @if($eligible)
                    <span class="text-green-600 font-medium">Eligible</span>
                @else
                    <span class="text-red-600 font-medium">Not Eligible</span>
                @endif
            </div>
        </div>

        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
            <div class="bg-primary h-3 rounded-full transition-all duration-500"
                 style="width: {{ min(100, ($totalTransactions / 50000) * 100) }}%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Need ₦50,000 in transactions to unlock loans</p>
    </div>

    @if($eligible)
        <!-- Loan Application Form -->
        <form method="POST" action="{{ route('user.loans.store') }}" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Loan Amount
                </label>
                <input type="number"
                       name="amount"
                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                       placeholder="e.g. 75000"
                       min="10000"
                       max="{{ $maxLimit }}"
                       required>
                <p class="text-xs text-gray-500 mt-1">
                    Max limit: <strong>₦{{ number_format($maxLimit) }}</strong>
                </p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Repayment Term
                </label>
                <select name="term_months"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                        required>
                    <option value="">Select term</option>
                    <option value="3">3 months</option>
                    <option value="6">6 months</option>
                    <option value="12">12 months</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-indigo-700 text-white font-medium py-3 rounded-xl transition shadow-md">
                Apply for Loan
            </button>
        </form>

        <!-- Current Loan Status -->
        @if($loan)
            <div class="mt-6 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-5 border-l-4 border-indigo-600">
                <p class="text-sm font-medium text-indigo-800 dark:text-indigo-300">
                    Latest Application
                </p>
                <p class="text-lg font-bold">₦{{ number_format($loan->amount) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Status: <span class="capitalize font-medium">{{ $loan->status }}</span>
                </p>
            </div>
        @endif
    @else
        <!-- Not Eligible -->
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-6 text-center">
            <i class="ti ti-lock text-4xl text-red-600 mb-3"></i>
            <h3 class="text-lg font-semibold text-red-800 dark:text-red-300">
                Loan Access Locked
            </h3>
            <p class="text-sm text-red-700 dark:text-red-400 mt-2">
                You need <strong>₦50,000</strong> in successful transactions to apply.
            </p>
            <a href="{{ route('user.transfers.index') }}"
               class="inline-block mt-4 text-indigo-600 hover:underline text-sm font-medium">
                View Transactions →
            </a>
        </div>
    @endif

    <div class="mt-8 text-center">
        <a href="{{ route('user.dashboard') }}"
           class="text-primary hover:text-primary text-sm font-medium">
            ← Back to Dashboard
        </a>
    </div>
</div>
@endsection