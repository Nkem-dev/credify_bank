@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="mb-6 mt-20">
        <a href="{{ route('admin.users.index') }}" class="text-[#5E84ff] hover:text-primary/80 mb-2 inline-flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Users
        </a>
        <div class="flex items-center justify-between mt-2">
            <div>
                <h1 class="text-2xl font-bold text-foreground">{{ $user->name }}</h1>
                <p class="text-muted-foreground mt-1">{{ $user->email }}</p>
            </div>
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="px-4 py-2 bg-[#5E84ff] text-primary-foreground rounded-lg hover:bg-primary/90 transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit User
            </a>
        </div>
    </div>

    <!-- Account Information -->
    <div class="bg-card rounded-lg border border-border shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Account Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="space-y-1">
                <p class="text-sm text-muted-foreground">Account Number</p>
                <p class="font-mono font-semibold text-foreground">{{ $user->account_number }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-muted-foreground">Phone</p>
                <p class="font-semibold text-foreground">{{ $user->phone ?? 'Not provided' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-muted-foreground">Balance</p>
                <p class="text-xl font-bold text-foreground">₦{{ number_format($user->balance, 2) }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-muted-foreground">Status</p>
                @if($user->status === 'active')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-md text-sm font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Active
                    </span>
                @elseif($user->status === 'inactive')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Inactive
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-md text-sm font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                        </svg>
                        Suspended
                    </span>
                @endif
            </div>
            <div class="space-y-1">
                <p class="text-sm text-muted-foreground">Tier</p>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-md text-sm font-medium">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ ucfirst($user->tier) }}
                </span>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-muted-foreground">KYC Status</p>
                @if($user->kyc_status === 'verified')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-md text-sm font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Verified
                    </span>
                @elseif($user->kyc_status === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-md text-sm font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Pending
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-md text-sm font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Rejected
                    </span>
                @endif
            </div>
            <div class="space-y-1">
                <p class="text-sm text-muted-foreground">Member Since</p>
                <p class="font-semibold text-foreground">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-card rounded-lg border border-border shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            <!-- Credit Account -->
            <button onclick="openCreditModal()" 
                    class="px-4 py-3 bg-card border border-border rounded-lg hover:bg-accent transition text-left flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <a href="{{ route('admin.users.credit', $user) }}" class="font-medium text-foreground">Credit Account</a>
                </div>
                <svg class="w-5 h-5 text-muted-foreground group-hover:text-foreground transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Debit Account -->
            <button onclick="openDebitModal()" 
                    class="px-4 py-3 bg-card border border-border rounded-lg hover:bg-accent transition text-left flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <span class="font-medium text-foreground">Debit Account</span>
                </div>
                <svg class="w-5 h-5 text-muted-foreground group-hover:text-foreground transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Upgrade Tier -->
            <button onclick="openTierModal()" 
                    class="px-4 py-3 bg-card border border-border rounded-lg hover:bg-[#5E84ff] transition text-left flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <span class="font-medium text-foreground">Upgrade Tier</span>
                </div>
                <svg class="w-5 h-5 text-muted-foreground group-hover:text-foreground transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Account Status Actions -->
            @if($user->status === 'active')
                <button onclick="openDeactivateModal()" 
                        class="px-4 py-3 bg-card border border-border rounded-lg hover:bg-accent transition text-left flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-foreground">Deactivate Account</span>
                    </div>
                    <svg class="w-5 h-5 text-muted-foreground group-hover:text-foreground transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button onclick="openSuspendModal()" 
                        class="px-4 py-3 bg-card border border-border rounded-lg hover:bg-accent transition text-left flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-foreground">Suspend Account</span>
                    </div>
                    <svg class="w-5 h-5 text-muted-foreground group-hover:text-foreground transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @else
                <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full px-4 py-3 bg-card border border-border rounded-lg hover:bg-accent transition text-left flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-medium text-foreground">Activate Account</span>
                        </div>
                        <svg class="w-5 h-5 text-muted-foreground group-hover:text-foreground transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>
            @endif

            <!-- Delete Account -->
            <button onclick="openDeleteModal()" 
                    class="px-4 py-3 bg-card border border-destructive/50 rounded-lg hover:bg-destructive/10 transition text-left flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <span class="font-medium text-destructive">Delete Account</span>
                </div>
                <svg class="w-5 h-5 text-muted-foreground group-hover:text-destructive transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Transaction Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-card rounded-lg border border-border shadow-sm p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-muted-foreground">Total Transactions</div>
                    <div class="text-2xl font-bold text-foreground">₦{{ number_format($totalTransactions, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="bg-card rounded-lg border border-border shadow-sm p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-muted-foreground">Total Savings</div>
                    <div class="text-2xl font-bold text-foreground">₦{{ number_format($totalSavings, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="bg-card rounded-lg border border-border shadow-sm p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-muted-foreground">Total Loans</div>
                    <div class="text-2xl font-bold text-foreground">₦{{ number_format($totalLoans, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-card rounded-lg border border-border shadow-sm">
        <div class="border-b border-border">
            <nav class="flex -mb-px overflow-x-auto">
                <button class="tab-btn active px-6 py-4 border-b-2 border-primary text-primary font-medium whitespace-nowrap" data-tab="transactions">
                    Transactions
                </button>
                <button class="tab-btn px-6 py-4 border-b-2 border-transparent text-muted-foreground hover:text-foreground hover:border-border font-medium whitespace-nowrap" data-tab="savings">
                    Savings Plans
                </button>
                <button class="tab-btn px-6 py-4 border-b-2 border-transparent text-muted-foreground hover:text-foreground hover:border-border font-medium whitespace-nowrap" data-tab="loans">
                    Loans
                </button>
                <button class="tab-btn px-6 py-4 border-b-2 border-transparent text-muted-foreground hover:text-foreground hover:border-border font-medium whitespace-nowrap" data-tab="login">
                    Login History
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Transactions Tab -->
            <div id="transactions-tab" class="tab-content">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border">
                        <thead>
                            <tr class="text-left">
                                <th class="px-4 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Created at</th>
                                <th class="px-4 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                                 <th class="px-4 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Description</th>
                                  <th class="px-4 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Reference</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($user->transfers as $transfer)
                            <tr class="hover:bg-accent/50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-foreground">
                                    {{ $transfer->created_at->format('M d, Y') }}<br>
                                    <span class="text-xs text-muted-foreground">{{ $transfer->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    @if($transfer->type === 'deposit')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-md text-xs font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                            Deposit
                                        </span>
                                    @elseif($transfer->type === 'withdrawal')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-md text-xs font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                            </svg>
                                            Withdrawal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-md text-xs font-medium">
                                            {{ ucfirst($transfer->type) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold {{ $transfer->type === 'deposit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $transfer->type === 'deposit' ? '+' : '-' }}₦{{ number_format($transfer->amount, 2) }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    @if($transfer->status === 'successful')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-md text-xs font-medium">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Successful
                                        </span>
                                    @elseif($transfer->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-md text-xs font-medium">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-md text-xs font-medium">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ ucfirst($transfer->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-muted-foreground max-w-xs truncate">
                                    {{ $transfer->description ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-mono text-muted-foreground">
                                    {{ $transfer->reference }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-muted-foreground">
                                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <div class="text-lg font-medium">No transactions found</div>
                                        <p class="text-sm mt-1">This user hasn't made any transactions yet</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Savings Plans Tab -->
            <div id="savings-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($user->savingsPlans as $plan)
                    <div class="border border-border rounded-lg p-5 hover:shadow-md transition bg-card">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-semibold text-foreground">{{ $plan->plan_name }}</h4>
                            <span class="px-2.5 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-md text-xs font-medium">
                                {{ ucfirst($plan->status) }}
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Target Amount:</span>
                                <span class="font-semibold text-foreground">₦{{ number_format($plan->target_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Current Balance:</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">₦{{ number_format($plan->current_balance, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Interest Rate:</span>
                                <span class="font-semibold text-foreground">{{ $plan->interest_rate }}% p.a.</span>
                            </div>
                            <div class="w-full bg-secondary rounded-full h-2 mt-3">
                                @php
                                    $progress = $plan->target_amount > 0 ? ($plan->current_balance / $plan->target_amount) * 100 : 0;
                                @endphp
                                <div class="bg-primary h-2 rounded-full transition-all" style="width: {{ min($progress, 100) }}%"></div>
                            </div>
                            <div class="text-xs text-muted-foreground text-right">{{ number_format(min($progress, 100), 1) }}% Complete</div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-border text-xs text-muted-foreground">
                            Started: {{ $plan->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12">
                        <div class="flex flex-col items-center justify-center text-muted-foreground">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div class="text-lg font-medium">No savings plans</div>
                            <p class="text-sm mt-1">This user hasn't created any savings plans yet</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Loans Tab -->
            <div id="loans-tab" class="tab-content hidden">
                <div class="space-y-4">
                    @forelse($user->loans as $loan)
                    <div class="border border-border rounded-lg p-5 hover:shadow-md transition bg-card">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="font-semibold text-foreground">Loan #{{ $loan->id }}</h4>
                                <p class="text-sm text-muted-foreground">{{ $loan->loan_type ?? 'Personal Loan' }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'approved' => 'green',
                                    'pending' => 'yellow',
                                    'rejected' => 'red',
                                ];
                                $status = $loan->status;
                                $color = $statusColors[$status] ?? 'gray';
                            @endphp
                            <span class="px-3 py-1 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-700 dark:text-{{ $color }}-400 rounded-full text-xs font-medium">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <div class="text-xs text-muted-foreground mb-1">Loan Amount</div>
                                <div class="font-semibold text-foreground">₦{{ number_format($loan->amount, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-muted-foreground mb-1">Amount Paid</div>
                                <div class="font-semibold text-green-600 dark:text-green-400">₦{{ number_format($loan->amount_paid ?? 0, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-muted-foreground mb-1">Balance</div>
                                <div class="font-semibold text-red-600 dark:text-red-400">₦{{ number_format(($loan->amount - ($loan->amount_paid ?? 0)), 2) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-muted-foreground mb-1">Interest Rate</div>
                                <div class="font-semibold text-foreground">{{ $loan->interest_rate ?? 0 }}%</div>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-border flex justify-between text-xs text-muted-foreground">
                            <span>Applied: {{ $loan->created_at->format('M d, Y') }}</span>
                            @if($loan->due_date)
                            <span>Due: {{ \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="flex flex-col items-center justify-center text-muted-foreground">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-lg font-medium">No loans</div>
                            <p class="text-sm mt-1">This user hasn't applied for any loans</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Login History Tab -->
            <div id="login-tab" class="tab-content hidden">
                <div class="space-y-3">
                    @forelse($loginHistory as $login)
                    <div class="flex items-center justify-between py-4 px-4 border border-border rounded-lg hover:bg-accent/50 transition">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-foreground">{{ $login->ip_address ?? 'Unknown IP' }}</div>
                                <div class="text-xs text-muted-foreground">{{ Str::limit($login->user_agent ?? 'Unknown Device', 60) }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-foreground">
                                {{ \Carbon\Carbon::createFromTimestamp($login->last_activity)->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{ \Carbon\Carbon::createFromTimestamp($login->last_activity)->format('H:i:s') }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="flex flex-col items-center justify-center text-muted-foreground">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <div class="text-lg font-medium">No login history available</div>
                            <p class="text-sm mt-1">No recent login sessions recorded</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        
        // Update buttons
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'border-primary', 'text-primary');
            b.classList.add('border-transparent', 'text-muted-foreground');
        });
        btn.classList.add('active', 'border-primary', 'text-primary');
        btn.classList.remove('border-transparent', 'text-muted-foreground');
        
        // Update content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById(tab + '-tab').classList.remove('hidden');
    });
});

// Modal functions
function openCreditModal() { 
    const modal = document.getElementById('creditModal');
    if (modal) modal.classList.remove('hidden'); 
}
function openDebitModal() { 
    const modal = document.getElementById('debitModal');
    if (modal) modal.classList.remove('hidden'); 
}
function openTierModal() { 
    const modal = document.getElementById('tierModal');
    if (modal) modal.classList.remove('hidden'); 
}
function openDeactivateModal() { 
    const modal = document.getElementById('deactivateModal');
    if (modal) modal.classList.remove('hidden'); 
}
function openSuspendModal() { 
    const modal = document.getElementById('suspendModal');
    if (modal) modal.classList.remove('hidden'); 
}
function openDeleteModal() { 
    const modal = document.getElementById('deleteModal');
    if (modal) modal.classList.remove('hidden'); 
}

function closeModal(modalId) { 
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('hidden'); 
}

// Close modal on background click
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection 