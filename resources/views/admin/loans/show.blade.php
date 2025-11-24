@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">
        
        <!-- Header with Back Button -->
        <div class="mb-8">
            <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center text-primary hover:text-primary/80 mb-4">
                <i class="ti ti-arrow-left mr-2"></i>
                Back to Loans
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Loan 
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Review loan application details and take action</p>
                </div>
                <div>
                    <span class="px-4 py-2 text-sm font-semibold rounded-full
                        @if($loan->status == 'approved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                        @elseif($loan->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                        @endif">
                        {{ ucfirst($loan->status) }}
                    </span>
                </div>
            </div>
        </div>

      
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column - Loan Details -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Loan Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <i class="ti ti-file-description mr-2"></i>
                        Loan Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Amount -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Loan Amount</p>
                            <p class="text-2xl font-bold text-primary">₦{{ number_format($loan->amount, 2) }}</p>
                        </div>

                        <!-- Interest Rate -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Interest Rate</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $loan->interest_rate }}%</p>
                        </div>

                        <!-- Term -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Loan Term</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $loan->term_months }} Months</p>
                        </div>

                        <!-- Monthly Payment -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Monthly Payment</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">₦{{ number_format($loan->monthly_payment, 2) }}</p>
                        </div>

                        <!-- Total Repayment -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Repayment</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">₦{{ number_format($loan->total_repayment, 2) }}</p>
                        </div>

                        <!-- Total Interest -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Interest</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">₦{{ number_format($loan->total_repayment - $loan->amount, 2) }}</p>
                        </div>

                    </div>
                </div>

                <!-- Purpose & Documents -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="ti ti-notes mr-2"></i>
                        Purpose
                    </h2>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $loan->purpose ?? 'No purpose provided' }}</p>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <i class="ti ti-clock mr-2"></i>
                        Timeline
                    </h2>

                    <div class="space-y-4">
                        
                        <!-- Application Date -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                    <i class="ti ti-file-plus text-primary"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Application Submitted</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $loan->created_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>

                        @if($loan->approved_at)
                        <!-- Approved Date -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                    <i class="ti ti-check text-green-600 dark:text-green-400"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Loan Approved</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $loan->approved_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($loan->disbursed_at)
                        <!-- Disbursed Date -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <i class="ti ti-cash text-blue-600 dark:text-blue-400"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Funds Disbursed</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $loan->disbursed_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($loan->due_date)
                        <!-- Due Date -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @php
                                    $dueDate = \Carbon\Carbon::parse($loan->due_date);
                                    $isOverdue = $dueDate->isPast() && $loan->status !== 'completed';
                                @endphp
                                <div class="w-10 h-10 {{ $isOverdue ? 'bg-red-100 dark:bg-red-900/30' : 'bg-orange-100 dark:bg-orange-900/30' }} rounded-full flex items-center justify-center">
                                    <i class="ti ti-calendar {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }}"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Due Date</p>
                                <p class="text-xs {{ $isOverdue ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $dueDate->format('M d, Y') }}
                                    @if($isOverdue)
                                        <span class="ml-2">(Overdue)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($loan->status === 'completed')
                        <!-- Completed Date -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                    <i class="ti ti-circle-check text-green-600 dark:text-green-400"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Loan Completed</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $loan->updated_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

            </div>

            <!-- Right Column - User Info & Actions -->
            <div class="space-y-6">
                
                <!-- User Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <i class="ti ti-user mr-2"></i>
                        Applicant
                    </h2>

                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ti ti-user text-3xl text-primary"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $loan->user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $loan->user->email }}</p>
                    </div>

                    <div class="space-y-3 border-t dark:border-gray-700 pt-4">
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Account Number</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $loan->user->account_number }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Current Balance</span>
                            <span class="text-sm font-semibold text-primary">₦{{ number_format($loan->user->balance, 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Account Status</span>
                            <span class="text-sm font-semibold {{ $loan->user->status ? 'text-green-600' : 'text-red-600' }}">
                                {{ $loan->user->status ? 'active'  : 'suspended' }}
                            </span>
                        </div>

                    </div>

                    <a href="{{ route('admin.users.show', $loan->user) }}" 
                       class="mt-4 w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2 px-4 rounded-lg transition text-center block">
                        View User Profile
                    </a>
                </div>

                <!-- Actions -->
                @if($loan->status === 'pending')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <i class="ti ti-settings mr-2"></i>
                        Actions
                    </h2>

                    <!-- Approve Button -->
                    <button onclick="document.getElementById('approveModal').classList.remove('hidden')" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition mb-3">
                        <i class="ti ti-check mr-2"></i>
                        Approve Loan
                    </button>

                    <!-- Reject Button -->
                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                        <i class="ti ti-x mr-2"></i>
                        Reject Loan
                    </button>
                </div>
                @endif

                @if($loan->status === 'approved' && $loan->payment_status !== 'completed')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <i class="ti ti-settings mr-2"></i>
                        Payment Actions
                    </h2>

                    <!-- Mark as Paid Button -->
                    <button onclick="document.getElementById('markPaidModal').classList.remove('hidden')" 
                            class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 px-4 rounded-lg transition">
                        <i class="ti ti-circle-check mr-2"></i>
                        Mark as Fully Paid
                    </button>
                </div>
                @endif

            </div>

        </div>

    </div>
</main>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Approve Loan</h3>
                <button onclick="document.getElementById('approveModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    You are about to approve this loan application and disburse <span class="font-bold text-primary">₦{{ number_format($loan->amount, 2) }}</span> to {{ $loan->user->name }}'s account.
                </p>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <i class="ti ti-alert-triangle mr-1"></i>
                        This action cannot be undone. Please enter your transaction PIN to confirm.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.loans.approve', $loan) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Transaction PIN <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="transaction_pin" 
                           required
                           maxlength="4"
                           class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary"
                           placeholder="Enter 4-digit PIN">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="notes" 
                              rows="3"
                              class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary"
                              placeholder="Add any notes..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('approveModal').classList.add('hidden')"
                            class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2 px-4 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Approve & Disburse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Reject Loan</h3>
                <button onclick="document.getElementById('rejectModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 dark:text-gray-300">
                    Please provide a reason for rejecting this loan application. The user will not see this reason.
                </p>
            </div>

            <form action="{{ route('admin.loans.reject', $loan) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              required
                              rows="4"
                              class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary"
                              placeholder="Enter rejection reason..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2 px-4 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Reject Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
<div id="markPaidModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Mark Loan as Paid</h3>
                <button onclick="document.getElementById('markPaidModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Confirm that {{ $loan->user->name }} has fully paid this loan of <span class="font-bold text-primary">₦{{ number_format($loan->total_repayment, 2) }}</span>.
                </p>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <i class="ti ti-info-circle mr-1"></i>
                        This will mark the loan as completed. Enter your PIN to confirm.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.loans.mark-paid', $loan) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Transaction PIN <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="transaction_pin" 
                           required
                           maxlength="4"
                           class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary"
                           placeholder="Enter 4-digit PIN">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="notes" 
                              rows="3"
                              class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary"
                              placeholder="Add any notes..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('markPaidModal').classList.add('hidden')"
                            class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2 px-4 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection