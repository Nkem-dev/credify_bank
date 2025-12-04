@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">

    <!-- Welcome Message (no bell) -->
    <div class="mb-8 mt-10">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Welcome back,
            <span class="text-primary">
                @auth {{ Auth::user()->name }} @else User @endauth
            </span>!
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2">Manage your finances seamlessly with Credify Bank.</p>
    </div>

    <!-- Account Summary Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Account Number with Copy -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account Number</h3>
            </div>
            <div class="flex items-center space-x-2">
                <p id="accountDisplay"
                   class="text-xl font-bold text-primary font-mono tracking-wider cursor-pointer select-none transition hover:text-primary/80">
                    @auth {{ Auth::user()->account_number ?? '1234567890' }} @else 1234567890 @endauth
                </p>
                <button id="copyAccountBtn" class="text-gray-500 hover:text-primary transition" title="Copy">
                    <i class="ti ti-copy text-lg"></i>
                </button>
                <span id="accountToast"
                      class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 bg-green-100 dark:bg-green-900/30 rounded-full opacity-0 scale-0 transition-all duration-300">
                    <i class="ti ti-check mr-1"></i> Copied!
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                @auth {{ Auth::user()->account_name ?? 'Primary Account' }} @else Primary Account @endauth
            </p>
        </div>

        <!-- Main Balance with Hide/Show -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available Balance</h3>
                <button id="toggleBalanceBtn" class="text-gray-500 hover:text-primary transition">
                    <i class="ti ti-eye text-xl" id="balanceEyeIcon"></i>
                </button>
            </div>
            <p id="balanceDisplay"
               data-amount="{{ Auth::check() ? (Auth::user()->balance ?? 0) : 0 }}"
               class="text-2xl font-bold text-primary font-mono tracking-wider">
                ₦{{ number_format(Auth::check() ? (Auth::user()->balance ?? 0) : 0, 2) }}
            </p>
            <p id="balanceHidden" class="text-2xl font-mono text-gray-400 hidden">••••••••</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                Last updated: <span id="lastUpdated">{{ now()->format('M d, Y \a\t g:i A') }}</span>
            </p>
        </div>


<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
    <div class="flex items-center justify-between mb-2">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Savings</h3>
        <button id="toggleSavingsBtn" class="text-gray-500 hover:text-green-600 dark:hover:text-green-400 transition">
            <i class="ti ti-eye text-xl" id="savingsEyeIcon"></i>
        </button>
    </div>

    @auth
        @php
            $total = auth()->user()->savings_balance;   // <-- the accessor
        @endphp
    @else
        @php $total = 125000.00; @endphp
    @endauth

    <p id="savingsDisplay"
       data-amount="{{ $total }}"
       class="text-2xl font-bold text-green-600 dark:text-green-400 font-mono">
        ₦{{ number_format($total, 2) }}
    </p>

    <p id="savingsHidden" class="text-2xl font-mono text-gray-400 hidden">••••••••</p>
    <p class="text-xs text-green-600 dark:text-green-400 mt-1">+{{ $interestRate ?? '5.2' }}% interest</p>
</div>

        <!-- Investments Summary with Hide/Show -->
        {{-- <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Investments</h3>
                <button id="toggleInvestmentsBtn" class="text-gray-500 hover:text-purple-600 dark:hover:text-purple-400 transition">
                    <i class="ti ti-eye text-xl" id="investmentsEyeIcon"></i>
                </button>
            </div>
            <p id="investmentsDisplay"
               data-amount="{{ Auth::check() ? (Auth::user()->investment_value ?? 890000.00) : 890000.00 }}"
               class="text-2xl font-bold text-purple-600 dark:text-purple-400 font-mono">
                ₦{{ number_format(Auth::check() ? (Auth::user()->investment_value ?? 890000.00) : 890000.00, 2) }}
            </p>
            <p id="investmentsHidden" class="text-2xl font-mono text-gray-400 hidden">••••••••</p>
            <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">+12.4% YTD</p>
        </div> --}}

        <!-- Investments Summary  -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
    <div class="flex items-center justify-between mb-2">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Investments</h3>
        <button id="toggleInvestmentsBtn" class="text-gray-500 hover:text-purple-600 dark:hover:text-purple-400 transition">
            <i class="ti ti-eye text-xl" id="investmentsEyeIcon"></i>
        </button>
    </div>
    <p id="investmentsDisplay"
       data-amount="{{ Auth::user()->investment ? Auth::user()->investment->current_value : 0 }}"
       class="text-2xl font-bold text-purple-600 dark:text-purple-400 font-mono">
        ₦{{ number_format(Auth::user()->investment ? Auth::user()->investment->current_value : 0, 2) }}
    </p>
    <p id="investmentsHidden" class="text-2xl font-mono text-gray-400 hidden">••••••••</p>
    <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
        {{ Auth::user()->investment && Auth::user()->investment->ytd_return > 0 ? '+' : '' }}{{ number_format(Auth::user()->investment ? Auth::user()->investment->ytd_return : 0, 1) }}% YTD
    </p>
</div>
    </div>

   @auth
    @php $user = Auth::user(); @endphp

    @if($user->active_loan ?? false)
    <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 p-6 rounded-xl shadow-sm border dark:border-gray-700 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Active Loan</h3>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                    ₦{{ number_format($user->loan_amount ?? 450000, 2) }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Due: {{ \Carbon\Carbon::parse($user->loan_due_date ?? now()->addMonths(6))->format('M d, Y') }}
                </p>
            </div>
            <a href="#" class="text-red-600 dark:text-red-400 font-medium hover:underline text-sm">Repay Now</a>
        </div>
    </div>
    @endif
@endauth

    <!-- Quick Actions -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('user.deposit.create') }}" class="bg-blue-100 dark:bg-blue-900/30 p-4 rounded-lg text-center hover:bg-blue-200 dark:hover:bg-blue-800/50 transition group">
                <i class="ti ti-arrow-up-right text-2xl text-primary mb-2 block group-hover:scale-110 transition"></i>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Fund Wallet</p>
            </a>
            <a href="{{ route('user.transfers.index') }}" class="bg-green-100 dark:bg-green-900/30 p-4 rounded-lg text-center hover:bg-green-200 dark:hover:bg-green-800/50 transition group">
                <i class="ti ti-arrows-exchange text-2xl text-accent mb-2 block group-hover:scale-110 transition"></i>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Transfer</p>
            </a>
            <a href="{{ route('user.loans.index') }}" class="bg-yellow-100 dark:bg-yellow-900/30 p-4 rounded-lg text-center hover:bg-yellow-200 dark:hover:bg-yellow-800/50 transition group">
                <i class="ti ti-cash text-2xl text-warning mb-2 block group-hover:scale-110 transition"></i>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Borrow</p>
            </a>
            <a href="{{ route('user.cards.index') }}" class="bg-purple-100 dark:bg-purple-900/30 p-4 rounded-lg text-center hover:bg-purple-200 dark:hover:bg-purple-800/50 transition group">
                <i class="ti ti-credit-card text-2xl text-purple-600 mb-2 block group-hover:scale-110 transition"></i>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Virtual Card</p>
            </a>
            <a href="{{ route('user.transfers.airtime.create') }}" class="bg-indigo-100 dark:bg-indigo-900/30 p-4 rounded-lg text-center hover:bg-indigo-200 dark:hover:bg-indigo-800/50 transition group">
                <i class="ti ti-phone text-2xl text-indigo-600 mb-2 block group-hover:scale-110 transition"></i>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Airtime</p>
            </a>
             <a href="{{ route('user.transfers.data.create') }}" class="bg-indigo-100 dark:bg-indigo-900/30 p-4 rounded-lg text-center hover:bg-indigo-200 dark:hover:bg-indigo-800/50 transition">
                        <i class="ti ti-wifi text-2xl text-indigo-600 mb-2 block"></i>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Data</p>
                    </a>
        </div>
    </div>

   

        <!-- Transaction History Section -->
<div class="mt-12">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
            Transaction History
        </h3>
       
        <a href=" {{ route('user.transactions.index') }}"
           class="text-primary hover:text-primary/80 font-medium text-sm flex items-center space-x-1 transition">
            <span>View all</span>
            <i class="ti ti-arrow-right"></i>
        </a>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $tx->completed_at?->format('M d, Y') }}
                                <span class="block text-xs text-gray-500 dark:text-gray-400">
                                    {{ $tx->completed_at?->format('g:i A') }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                @if($tx->type === 'deposit')
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-arrow-down-left text-green-600"></i>
                                        <span class="font-medium">Wallet Funding</span>
                                    </div>
                                @elseif($tx->type === 'internal')
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-arrow-up-right text-red-600"></i>
                                        <div>
                                            <p class="font-medium">Transfer to {{ $tx->recipient_name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tx->recipient_account_number }}</p>
                                        </div>
                                    </div>
                                @elseif($tx->type === 'airtime')
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-phone text-accent"></i>
                                        <div>
                                            <p class="font-medium">Airtime Purchase</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tx->recipient_account_number }} ({{ ucfirst($tx->recipient_name) }})</p>
                                        </div>
                                    </div>
                                @elseif($tx->type === 'data')
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-wifi text-blue-600"></i>
                                        <div>
                                            <p class="font-medium">Data Bundle</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tx->description }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="font-medium">{{ ucfirst($tx->type) }}</span>
                                @endif

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Ref: {{ $tx->reference }}
                                </p>
                                @if($tx->description && $tx->type !== 'data')
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 italic">
                                        "{{ $tx->description }}"
                                    </p>
                                @endif
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(in_array($tx->type, ['deposit']))
                                    <span class="text-green-600 font-semibold">+ ₦{{ number_format($tx->amount, 2) }}</span>
                                @else
                                    <span class="text-red-600 font-semibold">- ₦{{ number_format($tx->amount, 2) }}</span>
                                @endif
                                @if($tx->fee > 0)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Fee: ₦{{ number_format($tx->fee, 2) }}</p>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                    @if($tx->status === 'successful') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($tx->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                    {{ ucfirst($tx->status) }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('user.transfers.receipt', $tx->reference) }}"
                                   class="text-primary hover:text-primary/80 font-medium">
                                    View Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="ti ti-receipt text-4xl mb-3 block"></i>
                                <p>No transactions yet.</p>
                                {{-- {{ route('transfers.fund.create') }} --}}
                                <a href=""
                                   class="text-primary hover:underline mt-2 inline-block">
                                    Fund your wallet to get started
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 border-t dark:border-gray-700">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

    <!-- Announcements / News -->
    {{-- <div class="bg-gradient-to-r from-primary/5 to-accent/5 dark:from-primary/10 dark:to-accent/10 p-6 rounded-xl border dark:border-gray-700 mb-8 mt-10">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">What's New?</h3>
        <div class="space-y-3">
            <div class="flex items-start space-x-3">
                <i class="ti ti-sparkles text-primary text-xl mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Earn 12% on Fixed Deposits!</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Lock in funds for 6+ months and grow your wealth.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <i class="ti ti-shield-check text-green-600 text-xl mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Your account is secured</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Two-factor authentication is active.</p>
                </div>
            </div>
        </div>
    </div> --}}
</main>

{{-- JavaScript for Interactivity --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---------- Copy Account Number ---------- */
    const copyBtn = document.getElementById('copyAccountBtn');
    const accountDisplay = document.getElementById('accountDisplay');
    const toast = document.getElementById('accountToast');

    copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(accountDisplay.textContent.trim());
        toast.classList.remove('opacity-0', 'scale-0');
        toast.classList.add('opacity-100', 'scale-100');
        setTimeout(() => {
            toast.classList.remove('opacity-100', 'scale-100');
            toast.classList.add('opacity-0', 'scale-0');
        }, 2000);
    });

    /* ---------- Toggle Visibility (generic) ---------- */
    function initToggle(btnId, displayId, hiddenId, eyeId) {
        const btn   = document.getElementById(btnId);
        const disp  = document.getElementById(displayId);
        const hid   = document.getElementById(hiddenId);
        const eye   = document.getElementById(eyeId);
        let visible = true;

        btn.addEventListener('click', () => {
            if (visible) {
                // hide real amount
                hid.classList.remove('hidden');
                disp.classList.add('hidden');
                eye.classList.replace('ti-eye', 'ti-eye-off');
            } else {
                // show real amount (restore formatted value from data-amount)
                const amount = parseFloat(disp.dataset.amount);
                disp.textContent = '₦' + amount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                hid.classList.add('hidden');
                disp.classList.remove('hidden');
                eye.classList.replace('ti-eye-off', 'ti-eye');
            }
            visible = !visible;
        });
    }

    // initialise all three toggles
    initToggle('toggleBalanceBtn',     'balanceDisplay',     'balanceHidden',     'balanceEyeIcon');
    initToggle('toggleSavingsBtn',    'savingsDisplay',    'savingsHidden',    'savingsEyeIcon');
    initToggle('toggleInvestmentsBtn','investmentsDisplay','investmentsHidden','investmentsEyeIcon');

    /* ---------- Auto-refresh last updated ---------- */
    setInterval(() => {
        const now = new Date();
        document.getElementById('lastUpdated').textContent = now.toLocaleString('en-US', {
            month: 'short', day: 'numeric', year: 'numeric',
            hour: 'numeric', minute: '2-digit', hour12: true
        }).replace(',', ' at');
    }, 60000);
});
</script>


@endpush
@endsection