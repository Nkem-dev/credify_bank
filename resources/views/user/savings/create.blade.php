@extends('layouts.user')

@section('content')
<div class="mt-16 max-w-md mx-auto px-4">
    <h1 class="text-2xl font-bold text-primary text-center mb-8">Create Savings Plan</h1>

    <form method="POST" action="{{ route('user.savings.store') }}" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
        @csrf

        <!-- Plan Name -->
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Plan Name
            </label>
            <input type="text"
                   name="name"
                   required
                   class="w-full px-4 py-3 border rounded-lg text-black dark:text-white bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-primary focus:border-primary"
                   placeholder="e.g. My House Rent 2025"
                   value="{{ old('name') }}">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Target Amount (Optional) -->
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Target Amount (Optional)
            </label>
            <input type="number"
                   name="target_amount"
                   class="w-full px-4 py-3 border rounded-lg text-black dark:text-white bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-primary"
                   placeholder="e.g. 500000"
                   min="1000"
                   value="{{ old('target_amount') }}">
            @error('target_amount')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Fixed Interest Rate (Hidden) -->
        <input type="hidden" name="daily_interest_rate" value="0.0002">

        <!-- Display Only -->
        <div class="mb-6 p-4 bg-indigo-50 dark:bg-transparent rounded-lg border border-indigo-200 dark:border-primary">
            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-300">
                Daily Interest Rate: <strong>0.02%</strong>
            </p>
            <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-1">
                Earns <strong>~7.3% per year</strong> (compounded daily)
            </p>
            <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-2 italic">
                Interest rate is fixed and applied automatically every day.
            </p>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full bg-primary hover:bg-indigo-700 text-white font-medium py-3 rounded-xl transition shadow-md">
            Create Savings Plan
        </button>
    </form>

    <!-- Back Link -->
    <div class="mt-6 text-center">
        <a href="{{ route('user.savings.index') }}"
           class="text-primary hover:underline text-sm font-medium">
            Back to Savings
        </a>
    </div>
</div>
@endsection