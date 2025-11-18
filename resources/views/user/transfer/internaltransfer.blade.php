@extends('layouts.user')

@section('content')
<div id="internalTransferForm" class="mt-20">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 mb-6 mt-20">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Credify Bank Transfer
            </h2>
        </div>

        <!-- Display Errors -->
        {{-- @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <i class="ti ti-alert-circle text-red-600 dark:text-red-400 text-xl"></i>
                    <p class="text-red-900 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                <ul class="list-disc list-inside text-red-700 dark:text-red-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <form id="internalForm">
            @csrf

            <!-- Recipient Lookup Method -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Find Recipient By
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <button type="button" class="internal-lookup-btn active" data-method="account_number">
                        Account Number
                    </button>
                    <button type="button" class="internal-lookup-btn" data-method="email">
                        Email
                    </button>
                    <button type="button" class="internal-lookup-btn" data-method="phone">
                        Phone
                    </button>
                    <button type="button" class="internal-lookup-btn" data-method="username">
                        Username
                    </button>
                </div>
            </div>

            <!-- Recipient Input -->
            <div class="mb-6">
                <label for="internal_recipient_identifier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <span id="internal-lookup-label">Account Number</span>
                </label>
                <div class="relative">
                    <input type="text" id="internal_recipient_identifier" name="recipient_identifier"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                           placeholder="Enter account number" required>
                    <button type="button" id="internalLookupBtn"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-primary hover:text-primary/80 font-medium disabled:opacity-50">
                        Lookup
                    </button>
                </div>
                <input type="hidden" id="internal_lookup_method" name="lookup_method" value="account_number">
            </div>

            <!-- Recipient Details -->
            <div id="internalRecipientDetails" class="hidden mb-6">
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            <span id="internalRecipientInitials"></span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white" id="internalRecipientName"></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400" id="internalRecipientAccount"></p>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="internal_recipient_id" name="recipient_id">
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="internal_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Amount
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₦</span>
                    <input type="number" id="internal_amount" name="amount" step="0.01" min="1"
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                           placeholder="0.00" required>
                </div>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                    Instant transfer • No fees
                </p>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="internal_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description (Optional)
                </label>
                <textarea id="internal_description" name="description" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                          placeholder="What's this transfer for?"></textarea>
            </div>

            <!-- Save Beneficiary -->
            <div class="mb-6">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" id="save_beneficiary" name="save_beneficiary" class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Save as beneficiary for quick transfers</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="internalSubmitBtn"
                    class="w-full bg-primary text-white py-4 rounded-lg font-semibold hover:bg-primary/90 transition flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                Continue to Pin
            </button>
        </form>
    </div>

    <!-- PIN Modal -->
    <div id="pinModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Enter Transaction PIN</h3>
            <form id="pinForm" action="{{ route('user.transfers.internal.store') }}" method="POST">
                @csrf
                <input type="hidden" name="prevalidated" value="1">
                <!-- Hidden fields will be inserted here by JavaScript -->

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">4-digit PIN</label>
                    <input type="password" name="pin" id="pin_input" maxlength="4" inputmode="numeric"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-center text-2xl tracking-widest focus:ring-2 focus:ring-primary"
                           placeholder="••••" required autocomplete="off">
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-primary text-white py-3 rounded-lg font-medium hover:bg-primary/90">
                        Confirm Transfer
                    </button>
                    <button type="button" id="closePinModal"
                            class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    'use strict';

    const form = document.getElementById('internalForm');
    const recipientInput = document.getElementById('internal_recipient_identifier');
    const lookupBtn = document.getElementById('internalLookupBtn');
    const submitBtn = document.getElementById('internalSubmitBtn');
    const lookupMethodInput = document.getElementById('internal_lookup_method');
    const lookupLabel = document.getElementById('internal-lookup-label');
    const recipientDetails = document.getElementById('internalRecipientDetails');
    const pinModal = document.getElementById('pinModal');
    const pinForm = document.getElementById('pinForm');

    const methodConfig = {
        'account_number': { label: 'Account Number', placeholder: 'Enter 10-digit account number' },
        'email': { label: 'Email Address', placeholder: 'Enter email address' },
        'phone': { label: 'Phone Number', placeholder: 'Enter phone number' },
        'username': { label: 'Username', placeholder: 'Enter username' }
    };

    // Lookup method buttons
    document.querySelectorAll('.internal-lookup-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.internal-lookup-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const method = this.dataset.method;
            lookupMethodInput.value = method;
            lookupLabel.textContent = methodConfig[method].label;
            recipientInput.placeholder = methodConfig[method].placeholder;
            recipientInput.value = '';
            recipientDetails.classList.add('hidden');
        });
    });

    // Lookup recipient (API call)
    lookupBtn.addEventListener('click', async () => {
        const identifier = recipientInput.value.trim();
        const method = lookupMethodInput.value;
        
        if (!identifier) {
            return showAlert('Enter a value to lookup', 'error');
        }

        lookupBtn.disabled = true;
        lookupBtn.innerHTML = 'Loading...';

        try {
            const res = await fetch('{{ route("user.transfers.internal.lookup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ identifier, method })
            });
            
            const data = await res.json();

            if (data.success) {
                recipientDetails.classList.remove('hidden');
                document.getElementById('internalRecipientName').textContent = data.user.name;
                document.getElementById('internalRecipientAccount').textContent = data.user.account_number;
                document.getElementById('internalRecipientInitials').textContent = data.user.name.substring(0, 2).toUpperCase();
                document.getElementById('internal_recipient_id').value = data.user.id;
                showAlert('Recipient found!', 'success');
            } else {
                recipientDetails.classList.add('hidden');
                showAlert(data.message || 'User not found', 'error');
            }
        } catch (err) {
            console.error('Lookup error:', err);
            showAlert('Lookup failed', 'error');
        } finally {
            lookupBtn.disabled = false;
            lookupBtn.innerHTML = 'Lookup';
        }
    });

    // Enter key → lookup
    recipientInput.addEventListener('keypress', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            lookupBtn.click();
        }
    });

    // Form submit → Validate → Show PIN Modal
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const recipientId = document.getElementById('internal_recipient_id').value;
        const amount = parseFloat(document.getElementById('internal_amount').value);
        const description = document.getElementById('internal_description').value;

        if (!recipientId) {
            return showAlert('Please select a recipient first', 'error');
        }
        
        if (isNaN(amount) || amount <= 0) {
            return showAlert('Enter a valid amount', 'error');
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Validating...';

        const formData = new FormData(this);

        try {
            const res = await fetch('{{ route("user.transfers.internal.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    recipient_id: recipientId,
                    amount: amount,
                    description: description
                })
            });
            
            const data = await res.json();

            if (data.success) {
                // Clear previous hidden inputs
                pinForm.querySelectorAll('input[type=hidden]:not([name="_token"]):not([name="prevalidated"])').forEach(el => el.remove());
                
                // Add form data as hidden inputs
                const hiddenRecipientId = document.createElement('input');
                hiddenRecipientId.type = 'hidden';
                hiddenRecipientId.name = 'recipient_id';
                hiddenRecipientId.value = recipientId;
                pinForm.appendChild(hiddenRecipientId);

                const hiddenAmount = document.createElement('input');
                hiddenAmount.type = 'hidden';
                hiddenAmount.name = 'amount';
                hiddenAmount.value = amount;
                pinForm.appendChild(hiddenAmount);

                if (description) {
                    const hiddenDescription = document.createElement('input');
                    hiddenDescription.type = 'hidden';
                    hiddenDescription.name = 'description';
                    hiddenDescription.value = description;
                    pinForm.appendChild(hiddenDescription);
                }

                // Show modal
                pinModal.classList.remove('hidden');
                pinModal.classList.add('flex');
                document.getElementById('pin_input').focus();
            } else {
                showAlert(data.message, 'error');
            }
        } catch (err) {
            console.error('Validation error:', err);
            showAlert('Validation failed', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Continue to Pin';
        }
    });

    // Close modal
    document.getElementById('closePinModal').addEventListener('click', () => {
        pinModal.classList.add('hidden');
        pinModal.classList.remove('flex');
        document.getElementById('pin_input').value = '';
    });

    // Close modal on outside click
    pinModal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
            document.getElementById('pin_input').value = '';
        }
    });

    // Alert helper
    function showAlert(message, type = 'info') {
        const colors = { 
            success: 'bg-green-600', 
            error: 'bg-red-600', 
            info: 'bg-blue-600' 
        };
        const icons = { 
            success: 'ti-check', 
            error: 'ti-x', 
            info: 'ti-info-circle' 
        };
        
        const alert = document.createElement('div');
        alert.className = `fixed top-20 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
        alert.innerHTML = `<i class="ti ${icons[type]} mr-2"></i>${message}`;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    }
})();
</script>

<style>
.internal-lookup-btn {
    @apply flex flex-col items-center justify-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer transition-all hover:border-primary dark:hover:border-primary;
}
.internal-lookup-btn.active {
    @apply border-primary bg-primary/5 dark:bg-primary/10 text-primary;
}
</style>
@endpush
@endsection