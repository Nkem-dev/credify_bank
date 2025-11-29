@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-6xl mx-auto mt-10">
        
        <!-- Back Navigation -->
        <a href="{{ route('admin.virtual-cards.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-primary mb-6">
            <i class="ti ti-arrow-left mr-2"></i> Back to Virtual Cards
        </a>

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Virtual Card Details</h1>
                    <p class="text-gray-600 dark:text-gray-400">Card #{{ $virtualCard->id }} - {{ $virtualCard->reference }}</p>
                </div>
                <span class="px-4 py-2 text-sm font-semibold rounded-full
                    @if($virtualCard->status == 'active') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                    @elseif($virtualCard->status == 'blocked') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                    @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                    @endif">
                    <i class="ti ti-{{ $virtualCard->status == 'active' ? 'check' : 'ban' }} mr-1"></i>
                    {{ ucfirst($virtualCard->status) }}
                </span>
            </div>
        </div>


        <div class="grid md:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-2 space-y-6">
                
                <!-- Virtual Card Display -->
                <div class="relative bg-gradient-to-br from-primary to-gray-900 dark:from-gray-900 dark:to-black rounded-2xl p-8 text-white shadow-2xl overflow-hidden border border-gray-700">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-5">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
                    </div>

                    <!-- Accent Line -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary via-accent to-primary"></div>

                    <!-- Card Content -->
                    <div class="relative z-10">
                        <!-- Card Header -->
                        <div class="flex justify-between items-start mb-12">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Credify Virtual Card</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $virtualCard->reference }}</p>
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
                                    **** **** **** {{ substr($virtualCard->card_number, -4) }}
                                </p>
                                <button onclick="toggleCardDetails()" class="hover:bg-white/10 p-2 rounded-lg transition" title="Toggle card details" id="toggleBtn">
                                    <i class="ti ti-eye text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Card Details -->
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Card Holder</p>
                                <p class="font-semibold text-lg">{{ strtoupper($virtualCard->user->name) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Valid Thru</p>
                                <p class="font-mono text-lg">{{ $virtualCard->expiry_month }}/{{ substr($virtualCard->expiry_year, -2) }}</p>
                            </div>
                            <div class="text-right" id="cvvDisplay">
                                <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">CVV</p>
                                <p class="font-mono text-lg">***</p>
                            </div>
                        </div>

                        <!-- Balance Display -->
                        <div class="mt-8 pt-6 border-t border-white/20">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Current Balance</p>
                                    <p class="text-2xl font-bold">₦{{ number_format($virtualCard->balance, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Status</p>
                                    <span class="px-3 py-1 bg-{{ $virtualCard->status == 'active' ? 'green' : 'red' }}-500/20 border border-{{ $virtualCard->status == 'active' ? 'green' : 'red' }}-500/30 text-{{ $virtualCard->status == 'active' ? 'green' : 'red' }}-400 text-xs rounded-full font-medium">
                                        <i class="ti ti-{{ $virtualCard->status == 'active' ? 'check' : 'ban' }} text-xs mr-1"></i>
                                        {{ ucfirst($virtualCard->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="ti ti-info-circle mr-2 text-primary"></i> Card Information
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Card ID</span>
                            <span class="font-semibold text-gray-900 dark:text-white">#{{ $virtualCard->id }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Reference</span>
                            <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ $virtualCard->reference }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Card Type</span>
                            <span class="font-semibold text-gray-900 dark:text-white">Visa Virtual</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Created On</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $virtualCard->created_at->format('M d, Y - h:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $virtualCard->updated_at->format('M d, Y - h:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Expires On</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $virtualCard->expiry_month }}/{{ $virtualCard->expiry_year }}</span>
                        </div>
                    </div>

                    @if($virtualCard->description)
                    <div class="mt-6 pt-6 border-t dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Description</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $virtualCard->description }}</p>
                    </div>
                    @endif
                </div>

                <!-- Expiry Status Alert -->
                @php
                    $expiryDate = \Carbon\Carbon::createFromDate($virtualCard->expiry_year, $virtualCard->expiry_month, 1)->endOfMonth();
                    $isExpired = $expiryDate->isPast();
                    $isExpiringSoon = !$isExpired && $expiryDate->diffInDays() <= 30;
                @endphp

                @if($isExpired)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="ti ti-alert-circle text-red-600 dark:text-red-400 text-xl mr-3 mt-1 flex-shrink-0"></i>
                        <div>
                            <h4 class="text-sm font-bold text-red-800 dark:text-red-200 mb-1">Card Expired</h4>
                            <p class="text-sm text-red-700 dark:text-red-300">
                                This card expired on {{ $expiryDate->format('M d, Y') }}. It can no longer be used for transactions.
                            </p>
                        </div>
                    </div>
                </div>
                @elseif($isExpiringSoon)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="ti ti-alert-triangle text-yellow-600 dark:text-yellow-400 text-xl mr-3 mt-1 flex-shrink-0"></i>
                        <div>
                            <h4 class="text-sm font-bold text-yellow-800 dark:text-yellow-200 mb-1">Card Expires In:</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                This card will expire in {{ $expiryDate->diffInDays() }} days ({{ $expiryDate->format('M d, Y') }}).
                            </p>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="md:col-span-1 space-y-6">
                
                <!-- Card Owner -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="ti ti-user mr-2 text-primary"></i> Card Owner
                    </h3>

                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ti ti-user text-3xl text-primary"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $virtualCard->user->name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $virtualCard->user->email }}</p>
                    </div>

                    <div class="space-y-3 border-t dark:border-gray-700 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Account Number</span>
                            <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white">{{ $virtualCard->user->account_number }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Wallet Balance</span>
                            <span class="text-sm font-bold text-primary">₦{{ number_format($virtualCard->user->balance, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Account Status</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $virtualCard->user->status ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $virtualCard->user->status ? 'active' : 'inactive'  }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('admin.users.show', $virtualCard->user) }}" 
                       class="mt-4 w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2.5 px-4 rounded-lg transition text-center flex items-center justify-center">
                        <i class="ti ti-external-link mr-2"></i>
                        View User Profile
                    </a>
                </div>

                <!-- Card Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="ti ti-settings mr-2 text-primary"></i> Card Actions
                    </h3>

                    <div class="space-y-3">
                        @if($virtualCard->status === 'active')
                        <button onclick="document.getElementById('blockModal').classList.remove('hidden')" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="ti ti-ban mr-2"></i> Block Card
                        </button>
                        @elseif($virtualCard->status === 'blocked')
                        <button onclick="document.getElementById('unblockModal').classList.remove('hidden')" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="ti ti-check mr-2"></i> Unblock Card
                        </button>
                        @endif

                        <button onclick="document.getElementById('deleteModal').classList.remove('hidden')" 
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="ti ti-trash mr-2"></i> Delete Card
                        </button>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="bg-primary/5 dark:bg-primary/10 border border-primary/20 dark:border-primary/30 rounded-xl p-6">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center">
                        <i class="ti ti-shield-lock mr-2 text-primary"></i> Security
                    </h4>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start">
                            <i class="ti ti-check text-primary mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Card details are encrypted</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ti ti-check text-primary mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>All actions require PIN verification</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ti ti-check text-primary mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Auto-hide details after 30 seconds</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</main>

<!-- Block Modal -->
<div id="blockModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full border dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Block Virtual Card</h3>
                <button onclick="document.getElementById('blockModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Blocking this card will prevent all transactions until it is unblocked.
                </p>
            </div>

            <form action="{{ route('admin.virtual-cards.block', $virtualCard) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Transaction PIN <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="transaction_pin" 
                           required
                           maxlength="4"
                           class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter 4-digit PIN">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              required
                              rows="3"
                              class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Enter reason for blocking..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('blockModal').classList.add('hidden')"
                            class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Block Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Unblock Modal -->
<div id="unblockModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full border dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Unblock Virtual Card</h3>
                <button onclick="document.getElementById('unblockModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 dark:text-gray-300">
                    This will reactivate the card and allow transactions to proceed normally.
                </p>
            </div>

            <form action="{{ route('admin.virtual-cards.unblock', $virtualCard) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Transaction PIN <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="transaction_pin" 
                           required
                           maxlength="4"
                           class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter 4-digit PIN">
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('unblockModal').classList.add('hidden')"
                            class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Unblock Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full border dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Delete Virtual Card</h3>
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                    <p class="text-sm text-red-800 dark:text-red-200 flex items-start">
                        <i class="ti ti-alert-triangle mr-2 mt-0.5 flex-shrink-0"></i>
                        <span><strong>Warning:</strong> This action cannot be undone. The card will be permanently deleted.</span>
                    </p>
                </div>
                @if($virtualCard->balance > 0)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <p class="text-sm text-blue-800 dark:text-blue-200 flex items-start">
                        <i class="ti ti-info-circle mr-2 mt-0.5 flex-shrink-0"></i>
                        <span>The remaining balance of <strong>₦{{ number_format($virtualCard->balance, 2) }}</strong> will be refunded to the user's wallet.</span>
                    </p>
                </div>
                @endif
            </div>

            <form action="{{ route('admin.virtual-cards.destroy', $virtualCard) }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Transaction PIN <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="transaction_pin" 
                           required
                           maxlength="4"
                           class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Enter 4-digit PIN">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Deletion <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              required
                              rows="3"
                              class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Enter reason for deletion..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('deleteModal').classList.add('hidden')"
                            class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        Delete Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
    let showDetails = false;
    let autoHideTimer;

    function toggleCardDetails() {
        const cardNumberDisplay = document.getElementById('cardNumberDisplay');
        const cvvDisplay = document.getElementById('cvvDisplay').querySelector('p:last-child');
        const toggleBtn = document.getElementById('toggleBtn');
        const icon = toggleBtn.querySelector('i');

        if (!showDetails) {
            // Show full card number and CVV
            cardNumberDisplay.textContent = 
                '{{ chunk_split($virtualCard->card_number, 4, " ") }}'.trim();
            cvvDisplay.textContent = '{{ $virtualCard->cvv }}';

            // Update button icon
            icon.classList.remove('ti-eye');
            icon.classList.add('ti-eye-off');

            toggleBtn.setAttribute('title', 'Hide card details');

            showDetails = true;

            // Auto-hide after 30 seconds
            clearTimeout(autoHideTimer);
            autoHideTimer = setTimeout(() => {
                hideCardDetails();
            }, 30000); // 30 seconds
        } else {
            hideCardDetails();
        }
    }

    function hideCardDetails() {
        const cardNumberDisplay = document.getElementById('cardNumberDisplay');
        const cvvDisplay = document.getElementById('cvvDisplay').querySelector('p:last-child');
        const toggleBtn = document.getElementById('toggleBtn');
        const icon = toggleBtn.querySelector('i');

        // Mask the sensitive data again
        cardNumberDisplay.textContent = '**** **** **** {{ substr($virtualCard->card_number, -4) }}';
        cvvDisplay.textContent = '***';

        // Reset button
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
        toggleBtn.setAttribute('title', 'Show card details');

        showDetails = false;
        clearTimeout(autoHideTimer);
    }

    // Optional: Hide details when clicking outside the card
    document.addEventListener('click', function(e) {
        const cardContainer = document.querySelector('.bg-gradient-to-br');
        const toggleButton = document.getElementById('toggleBtn');

        if (showDetails && !cardContainer.contains(e.target) && e.target !== toggleButton) {
            hideCardDetails();
        }
    });

    // Optional: Also hide on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && showDetails) {
            hideCardDetails();
        }
    });
</script>
@endpush
@endsection