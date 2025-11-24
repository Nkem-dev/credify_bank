@extends('layouts.user')

@section('content')
<div class="mt-16 max-w-4xl mx-auto px-4">
    <div class="flex justify-between items-center mb-8 mt-20">
        <div>
            <h1 class="text-2xl font-bold text-primary dark:text-primary">My Savings</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Grow your money with daily interest</p>
        </div>
        <a href="{{ route('user.savings.create') }}"
           class="bg-primary hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
            + New Plan
        </a>
    </div>

    @if($plans->count() == 0)
        <div class="text-center py-12">
            <i class="ti ti-pig-money text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No savings plans yet. Create one to start growing!</p>
            <a href="{{ route('user.savings.create') }}" class="text-primary font-medium">Create Plan</a>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2">
            @foreach($plans as $plan)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-primary">{{ $plan->name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Daily Interest: {{ $plan->daily_interest_rate * 100 }}%
                            </p>
                        </div>
                        <div class="flex gap-2 items-center">
                            @if($plan->target_amount)
                                <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-300 px-2 py-1 rounded">
                                    Target: ₦{{ number_format($plan->target_amount) }}
                                </span>
                            @endif
                            
                            <!-- View History Button -->
                            <a href="{{ route('user.savings.transactions', $plan) }}"
                               class="text-primary hover:text-primary/80 p-1"
                               title="View transaction history">
                                <i class="ti ti-history text-lg"></i>
                            </a>
                            
                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('user.savings.destroy', $plan) }}" 
                                  onsubmit="return confirm('Are you sure? Balance will be returned to your wallet.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-500 hover:text-red-700 p-1"
                                        title="Delete plan">
                                    <i class="ti ti-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">Balance</span>
                            <span class="font-bold text-xl text-primary">
                                ₦{{ number_format($plan->current_balance, 2) }}
                            </span>
                        </div>
                        @if($plan->target_amount)
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full transition-all"
                                     style="width: {{ min(100, ($plan->current_balance / $plan->target_amount) * 100) }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ number_format(min(100, ($plan->current_balance / $plan->target_amount) * 100), 1) }}% of target
                            </p>
                        @endif
                    </div>

                    <!-- Fund Form (Save Money) -->
                    <form method="POST" action="{{ route('user.savings.fund', $plan) }}" class="mb-3">
                        @csrf
                        <div class="flex gap-2">
                            <input type="number"
                                   name="amount"
                                   placeholder="Amount to save"
                                   min="1000"
                                   step="100"
                                   class="flex-1 px-3 py-2 border dark:border-gray-600 rounded-lg text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary"
                                   required>
                            <button type="submit"
                                    class="bg-primary hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                <i class="ti ti-plus mr-1"></i> Save
                            </button>
                        </div>
                    </form>

                    <!-- Withdraw Form -->
                    <form method="POST" action="{{ route('user.savings.withdraw', $plan) }}">
                        @csrf
                        <div class="flex gap-2">
                            <input type="number"
                                   name="amount"
                                   placeholder="Amount to withdraw"
                                   min="100"
                                   step="100"
                                   max="{{ $plan->current_balance }}"
                                   class="flex-1 px-3 py-2 border dark:border-gray-600 rounded-lg text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary"
                                   required>
                            <button type="submit"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                                    {{ $plan->current_balance == 0 ? 'disabled' : '' }}>
                                <i class="ti ti-arrow-up mr-1"></i> Withdraw
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 pt-4 border-t dark:border-gray-700">
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="ti ti-clock"></i>
                                Last interest: {{ $plan->last_interest_applied_at?->diffForHumans() ?? 'Never' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Created: {{ $plan->created_at->format('M d, Y') }}
                            </div>
                        </div>
                        
                        <!-- Transaction Count Badge -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('user.savings.transactions', $plan) }}"
                               class="text-xs text-primary hover:text-primary/80 font-medium inline-flex items-center">
                                <i class="ti ti-receipt mr-1"></i>
                                View {{ $plan->transactions->count() }} {{ Str::plural('transaction', $plan->transactions->count()) }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-8 text-center">
        <a href="{{ route('user.dashboard') }}" class="text-primary text-sm font-medium hover:underline">
            <i class="ti ti-arrow-left mr-1"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection