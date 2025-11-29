{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">

        <!-- Header + Back Button -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}"
                   class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.edit', $user) }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                <i class="ti ti-edit"></i>
                Edit User
            </a>
        </div>

        <!-- Account Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Account Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Account Number</p>
                    <p class="font-mono text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $user->account_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Phone</p>
                    <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $user->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Balance</p>
                    <p class="text-2xl font-bold text-primary mt-1">₦{{ number_format($user->balance, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Member Since</p>
                    <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="px-3 py-1 text-xs font-bold rounded-full 
                            @if($user->status === 'active') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                            @elseif($user->status === 'suspended') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                            @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                            {{ ucfirst($user->status) }}
                        </span>
                        @if($user->trashed())
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                Trashed
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tier</p>
                    <span class="inline-block mt-1 px-4 py-1.5 text-xs font-bold rounded-full text-white
                        @if($user->tier === 'tier 3') bg-gradient-to-r from-purple-600 to-pink-600
                        @elseif($user->tier === 'tier 2') bg-gradient-to-r from-blue-600 to-cyan-600
                        @elseif($user->tier === 'tier 1') bg-gradient-to-r from-green-600 to-emerald-600
                        @else bg-gray-500 @endif">
                        {{ ucwords(str_replace('_', ' ', $user->tier ?? 'tier 1')) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">KYC Status</p>
                    <div class="mt-1">
                        @if($user->kyc_status === 'verified')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Verified</span>
                        @elseif($user->kyc_status === 'pending')
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                        @else
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Not Verified</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">Quick Actions</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <button onclick="openCreditModal()" class="flex flex-col items-center gap-3 p-5 rounded-xl bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 transition group">
                    <i class="ti ti-plus text-2xl text-green-600 dark:text-green-400"></i>
                    <span class="text-sm font-medium">Credit</span>
                </button>
                <button onclick="openDebitModal()" class="flex flex-col items-center gap-3 p-5 rounded-xl bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 transition group">
                    <i class="ti ti-minus text-2xl text-red-600 dark:text-red-400"></i>
                    <span class="text-sm font-medium">Debit</span>
                </button>
                <button onclick="openTierModal()" class="flex flex-col items-center gap-3 p-5 rounded-xl bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/40 transition group">
                    <i class="ti ti-star text-2xl text-purple-600 dark:text-purple-400"></i>
                    <span class="text-sm font-medium">Upgrade Tier</span>
                </button>
                @if($user->status === 'active')
                    <button onclick="openDeactivateModal()" class="flex flex-col items-center gap-3 p-5 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition group">
                        <i class="ti ti-lock text-2xl text-gray-600 dark:text-gray-400"></i>
                        <span class="text-sm font-medium">Deactivate</span>
                    </button>
                    <button onclick="openSuspendModal()" class="flex flex-col items-center gap-3 p-5 rounded-xl bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/40 transition group">
                        <i class="ti ti-alert-triangle text-2xl text-orange-600 dark:text-orange-400"></i>
                        <span class="text-sm font-medium">Suspend</span>
                    </button>
                @else
                    <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="contents">
                        @csrf
                        <button type="submit" class="flex flex-col items-center gap-3 p-5 rounded-xl bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 transition group">
                            <i class="ti ti-user-check text-2xl text-green-600 dark:text-green-400"></i>
                            <span class="text-sm font-medium">Activate</span>
                        </button>
                    </form>
                @endif
                @if(!$user->trashed())
                    <button onclick="openDeleteModal()" class="flex flex-col items-center gap-3 p-5 rounded-xl bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 transition group">
                        <i class="ti ti-trash text-2xl text-red-600 dark:text-red-400"></i>
                        <span class="text-sm font-medium text-red-600 dark:text-red-400">Delete</span>
                    </button>
                @else
                    <form action="{{ route('admin.users.restore', $user) }}" method="POST" class="contents">
                        @csrf
                        <button type="submit" class="flex flex-col items-center gap-3 p-5 rounded-xl bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 transition group">
                            <i class="ti ti-restore text-2xl text-green-600 dark:text-green-400"></i>
                            <span class="text-sm font-medium">Restore</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Transactions</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">₦{{ number_format($totalTransactions ?? 0, 2) }}</p>
                    </div>
                    <i class="ti ti-transfer-in text-3xl text-primary/70"></i>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Savings</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">₦{{ number_format($totalSavings ?? 0, 2) }}</p>
                    </div>
                    <i class="ti ti-pig-money text-3xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Outstanding Loans</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">₦{{ number_format($totalLoans ?? 0, 2) }}</p>
                    </div>
                    <i class="ti ti-cash-banknote text-3xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex overflow-x-auto">
                    <button class="tab-btn active px-8 py-4 text-sm font-medium border-b-2 border-primary text-primary" data-tab="transactions">Transactions</button>
                    <button class="tab-btn px-8 py-4 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" data-tab="savings">Savings</button>
                    <button class="tab-btn px-8 py-4 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" data-tab="loans">Loans</button>
                    <button class="tab-btn px-8 py-4 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" data-tab="login">Login History</button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Transactions Tab -->
                <div id="transactions-tab" class="tab-content">
                    <div class="overflow-x-hidden">
                        <table class="w-full min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ref</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($user->transfers as $transfer)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            {{ $transfer->created_at->format('M d, Y') }}
                                            <br><span class="text-xs text-gray-500">{{ $transfer->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-xs font-bold rounded-full text-white {{ $transfer->type === 'deposit' ? 'bg-green-600' : 'bg-red-600' }}">
                                                {{ ucfirst($transfer->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-bold {{ $transfer->type === 'deposit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $transfer->type === 'deposit' ? '+' : '-' }}₦{{ number_format($transfer->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-xs font-bold rounded-full
                                                {{ $transfer->status === 'successful' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                                   ($transfer->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                                {{ ucfirst($transfer->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                            {{ $transfer->description ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-xs font-mono text-gray-500 dark:text-gray-400">
                                            {{ $transfer->reference }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-16 text-gray-500 dark:text-gray-400">
                                            <i class="ti ti-receipt-off text-6xl mb-4 opacity-50"></i>
                                            <p class="text-lg font-medium">No transactions yet</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Savings Plans Tab -->
                <div id="savings-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($user->savingsPlans as $plan)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-bold text-lg text-gray-900 dark:text-white">{{ $plan->plan_name }}</h4>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        {{ $plan->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                           ($plan->status === 'completed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">
                                        {{ ucfirst($plan->status) }}
                                    </span>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Target</span>
                                        <span class="font-semibold">₦{{ number_format($plan->target_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Saved</span>
                                        <span class="font-bold text-green-600 dark:text-green-400">₦{{ number_format($plan->current_balance, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                                        @php $progress = $plan->target_amount > 0 ? ($plan->current_balance / $plan->target_amount) * 100 : 0 @endphp
                                        <div class="bg-primary h-3 rounded-full transition-all" style="width: {{ min($progress, 100) }}%"></div>
                                    </div>
                                    <div class="text-right text-xs text-gray-500">
                                        {{ number_format($progress, 1) }}% Complete
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-16 text-gray-500 dark:text-gray-400">
                                <i class="ti ti-pig-money text-6xl mb-4 opacity-50"></i>
                                <p class="text-lg font-medium">No savings plans</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Loans Tab -->
                <div id="loans-tab" class="tab-content hidden">
                    <div class="space-y-4">
                        @forelse($user->loans as $loan)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-bold text-lg">Loan #{{ $loan->id }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $loan->loan_type ?? 'Personal Loan' }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        {{ $loan->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                           ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400">Amount</p>
                                        <p class="font-bold">₦{{ number_format($loan->amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400">Paid</p>
                                        <p class="font-bold text-green-600 dark:text-green-400">₦{{ number_format($loan->amount_paid ?? 0, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400">Balance</p>
                                        <p class="font-bold text-red-600 dark:text-red-400">₦{{ number_format($loan->amount - ($loan->amount_paid ?? 0), 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400">Due Date</p>
                                        <p class="font-medium">{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') : '—' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                <i class="ti ti-cash-banknote text-6xl mb-4 opacity-50"></i>
                                <p class="text-lg font-medium">No loan records</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Login History Tab -->
                <div id="login-tab" class="tab-content hidden">
                    <div class="space-y-4">
                        @forelse($loginHistory as $login)
                            <div class="flex items-center justify-between p-5 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                                        <i class="ti ti-device-desktop text-xl text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $login->ip_address ?? 'Unknown IP' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($login->user_agent ?? 'Unknown Device', 50) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::createFromTimestamp($login->last_activity)->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::createFromTimestamp($login->last_activity)->format('H:i A') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                <i class="ti ti-login text-6xl mb-4 opacity-50"></i>
                                <p class="text-lg font-medium">No login history</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- Credit Modal --}}
<div id="creditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="ti ti-plus text-xl text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Credit Account</h3>
            </div>
            <button onclick="closeCreditModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.users.credit', $user) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">₦</span>
                        <input type="number" name="amount" step="0.01" required
                               class="w-full pl-8 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:text-white"
                               placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:text-white"
                              placeholder="Add a note..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeCreditModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
                    Credit Account
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Debit Modal --}}
<div id="debitModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="ti ti-minus text-xl text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Debit Account</h3>
            </div>
            <button onclick="closeDebitModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.users.debit', $user) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">₦</span>
                        <input type="number" name="amount" step="0.01" required max="{{ $user->balance }}"
                               class="w-full pl-8 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:text-white"
                               placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Available balance: ₦{{ number_format($user->balance, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:text-white"
                              placeholder="Add a note..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeDebitModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                    Debit Account
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Tier Upgrade Modal --}}
<div id="tierModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="ti ti-star text-xl text-purple-600 dark:text-purple-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Update Tier</h3>
            </div>
            <button onclick="closeTierModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form action="" method="POST">
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Select Tier</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-green-500 dark:hover:border-green-500 transition {{ $user->tier === 'tier 1' ? 'bg-green-50 dark:bg-green-900/20 border-green-500' : '' }}">
                            <input type="radio" name="tier" value="tier 1" {{ $user->tier === 'tier 1' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
                            <span class="ml-3 flex-1">
                                <span class="block font-medium text-gray-900 dark:text-white">Tier 1</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Basic tier</span>
                            </span>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 dark:hover:border-blue-500 transition {{ $user->tier === 'tier 2' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : '' }}">
                            <input type="radio" name="tier" value="tier 2" {{ $user->tier === 'tier 2' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="ml-3 flex-1">
                                <span class="block font-medium text-gray-900 dark:text-white">Tier 2</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Intermediate tier</span>
                            </span>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-purple-500 dark:hover:border-purple-500 transition {{ $user->tier === 'tier 3' ? 'bg-purple-50 dark:bg-purple-900/20 border-purple-500' : '' }}">
                            <input type="radio" name="tier" value="tier 3" {{ $user->tier === 'tier 3' ? 'checked' : '' }} class="w-4 h-4 text-purple-600">
                            <span class="ml-3 flex-1">
                                <span class="block font-medium text-gray-900 dark:text-white">Tier 3</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Premium tier</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeTierModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition font-medium">
                    Update Tier
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Suspend Modal --}}
<div id="suspendModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <i class="ti ti-alert-triangle text-xl text-orange-600 dark:text-orange-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Suspend User</h3>
            </div>
            <button onclick="closeSuspendModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <p class="text-gray-600 dark:text-gray-400">Are you sure you want to suspend <strong>{{ $user->name }}</strong>? They will not be able to access their account.</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason (Optional)</label>
                    <textarea name="reason" rows="3"
                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 dark:text-white"
                              placeholder="Suspension reason..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeSuspendModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition font-medium">
                    Suspend User
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Deactivate Modal --}}
<div id="deactivateModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="ti ti-lock text-xl text-gray-600 dark:text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Deactivate User</h3>
            </div>
            <button onclick="closeDeactivateModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.users.deactivate', $user) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <p class="text-gray-600 dark:text-gray-400">Are you sure you want to deactivate <strong>{{ $user->name }}</strong>? They will not be able to access their account.</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason (Optional)</label>
                    <textarea name="reason" rows="3"
                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-500 dark:text-white"
                              placeholder="Deactivation reason..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeDeactivateModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition font-medium">
                    Deactivate User
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="ti ti-trash text-xl text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Delete User</h3>
            </div>
            <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="space-y-4">
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-800 dark:text-red-300">
                        <i class="ti ti-alert-triangle mr-2"></i>
                        <strong>Warning:</strong> This action will soft delete the user. They can be restored later from the trash.
                    </p>
                </div>
                <p class="text-gray-600 dark:text-gray-400">Are you sure you want to delete <strong>{{ $user->name }}</strong>?</p>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                    Delete User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Tab Switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetTab = btn.dataset.tab;
            
            // Update button states
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active', 'border-primary', 'text-primary');
                b.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400');
            });
            btn.classList.add('active', 'border-primary', 'text-primary');
            btn.classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400');
            
            // Update tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`${targetTab}-tab`).classList.remove('hidden');
        });
    });

    // Modal Functions
    function openCreditModal() {
        document.getElementById('creditModal').classList.remove('hidden');
    }
    function closeCreditModal() {
        document.getElementById('creditModal').classList.add('hidden');
    }

    function openDebitModal() {
        document.getElementById('debitModal').classList.remove('hidden');
    }
    function closeDebitModal() {
        document.getElementById('debitModal').classList.add('hidden');
    }

    function openTierModal() {
        document.getElementById('tierModal').classList.remove('hidden');
    }
    function closeTierModal() {
        document.getElementById('tierModal').classList.add('hidden');
    }

    function openSuspendModal() {
        document.getElementById('suspendModal').classList.remove('hidden');
    }
    function closeSuspendModal() {
        document.getElementById('suspendModal').classList.add('hidden');
    }

    function openDeactivateModal() {
        document.getElementById('deactivateModal').classList.remove('hidden');
    }
    function closeDeactivateModal() {
        document.getElementById('deactivateModal').classList.add('hidden');
    }

    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modals on outside click
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    // Close modals on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                modal.classList.add('hidden');
            });
        }
    });
</script>
@endpush

@endsection