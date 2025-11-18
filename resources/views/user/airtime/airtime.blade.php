@extends('layouts.user')

@section('content')
<div class="mt-20">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 mb-6 mt-20 max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Buy Airtime
            </h2>
        </div>

        <!-- Errors -->
        {{-- @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <i class="ti ti-alert-circle text-red-600 dark:text-red-400 text-xl"></i>
                    <p class="text-red-900 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif --}}

        <form id="airtimeForm">
            @csrf

            <!-- Network Dropdown -->
            <div class="mb-6">
                <label for="network" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Network
                </label>
                <div class="relative">
                    <select id="network" name="network"
                            class="w-full px-4 py-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white appearance-none cursor-pointer">
                        <option value="mtn" selected>
                            MTN
                        </option>
                        <option value="airtel">
                            Airtel
                        </option>
                        <option value="glo">
                            Glo
                        </option>
                        <option value="9mobile">
                            9mobile
                        </option>
                    </select>
                    <!-- Custom Icon (changes with selection) -->
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <img id="networkIcon" src="https://credify.ng/logos/mtn.png" alt="MTN" class="w-6 h-6">
                    </div>
                    <!-- Dropdown Arrow -->
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <i class="ti ti-chevron-down text-gray-500"></i>
                    </div>
                </div>
            </div>

            <!-- Phone Number -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Phone Number
                </label>
                <input type="tel" id="phone" name="phone"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                       placeholder="08012345678" required>
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Amount
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">₦</span>
                    <input type="number" id="amount" name="amount" min="50" step="1"
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                           placeholder="0" required>
                </div>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                    Instant recharge • No fees • Min: ₦50
                </p>
            </div>

            <!-- Submit -->
            <button type="submit" id="submitBtn"
                    class="w-full bg-primary text-white py-4 rounded-lg font-semibold hover:bg-primary/90 transition flex items-center justify-center disabled:opacity-50">
                Continue to PIN
            </button>
        </form>
    </div>

    <!-- PIN Modal -->
    {{-- <div id="pinModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Enter Transaction PIN</h3>
            <form id="pinForm" action="{{ route('user.transfers.airtime.store') }}" method="POST">
                @csrf
                <input type="hidden" name="prevalidated" value="1">
                <input type="hidden" id="hidden_phone" name="phone">
                <input type="hidden" id="hidden_network" name="network">
                <input type="hidden" id="hidden_amount" name="amount">

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">4-digit PIN</label>
                    <input type="password" name="pin" maxlength="4" inputmode="numeric"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-center text-2xl tracking-widest"
                           placeholder="••••" required>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-primary text-white py-3 rounded font-medium hover:bg-primary/90">
                        Buy Airtime
                    </button>
                    <button type="button" id="closePinModal"
                            class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- PIN Modal -->
<div id="pinModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Enter Transaction PIN</h3>
        
        <div id="pinForm">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">4-digit PIN</label>
                <input type="password" id="pinInput" maxlength="4" inputmode="numeric"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-center text-2xl tracking-widest"
                       placeholder="••••" required>
            </div>

            <div class="flex space-x-3">
                <button type="button" id="confirmPinBtn" 
                        class="flex-1 bg-primary text-white py-3 rounded font-medium hover:bg-primary/90">
                    Buy Airtime
                </button>
                <button type="button" id="closePinModal"
                        class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
</div>

{{-- @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
(function() {
    'use strict';

    const form = document.getElementById('airtimeForm');
    const submitBtn = document.getElementById('submitBtn');
    const pinModal = document.getElementById('pinModal');
    const pinForm = document.getElementById('pinForm');
    const networkSelect = document.getElementById('network');
    const networkIcon = document.getElementById('networkIcon');

    // Network logos 
    const networkLogos = {
        mtn: 'https://credify.ng/logos/mtn.png',
        airtel: 'https://credify.ng/logos/airtel.png',
        glo: 'https://credify.ng/logos/glo.png',
        9mobile: 'https://credify.ng/logos/9mobile.png' 
    };

    // Update icon when network changes
    networkSelect.addEventListener('change', function() {
        const selected = this.value;
        networkIcon.src = networkLogos[selected] || networkLogos.mtn;
    });

    // Submit → Validate → Show PIN
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const phone = document.getElementById('phone').value.trim();
        const network = networkSelect.value;
        const amount = parseFloat(document.getElementById('amount').value);

        // if (!/^0[789][01]\d{8}$/.test(phone)) {
        //     return showAlert('Enter a valid Nigerian phone number (e.g., 08012345678)', 'error');
        // }
        if (amount < 50) {
            return showAlert('Minimum airtime is ₦50', 'error');
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Validating...';

        try {
            const res = await axios.post('{{ route("user.transfers.airtime.validate") }}', {
                phone, network, amount
            }, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            if (res.data.success) {
                document.getElementById('hidden_phone').value = phone;
                document.getElementById('hidden_network').value = network;
                document.getElementById('hidden_amount').value = amount;

                pinModal.classList.remove('hidden');
                pinModal.classList.add('flex');
                pinForm.querySelector('input[name="pin"]').focus();
            } else {
                showAlert(res.data.message, 'error');
            }
        } catch (err) {
            showAlert(err.response?.data?.message || 'Validation failed', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Continue to PIN';
        }
    });

    // Close modal
    document.getElementById('closePinModal').addEventListener('click', () => {
        pinModal.classList.add('hidden');
        pinModal.classList.remove('flex');
    });

    pinModal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });

    function showAlert(msg, type = 'info') {
        const colors = { success: 'bg-green-600', error: 'bg-red-600' };
        const alert = document.createElement('div');
        alert.className = `fixed top-20 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity`;
        alert.innerHTML = `<i class="ti ti-x mr-2"></i>${msg}`;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 3000);
    }
})();
</script>
@endpush --}}

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
(function() {
    'use strict';

    const form = document.getElementById('airtimeForm');
    const submitBtn = document.getElementById('submitBtn');
    const pinModal = document.getElementById('pinModal');
    const pinInput = document.getElementById('pinInput');
    const confirmPinBtn = document.getElementById('confirmPinBtn');
    const closePinModal = document.getElementById('closePinModal');
    const networkSelect = document.getElementById('network');
    const networkIcon = document.getElementById('networkIcon');

    let formData = {};

    const networkLogos = {
        mtn: 'https://credify.ng/logos/mtn.png',
        airtel: 'https://credify.ng/logos/airtel.png',
        glo: 'https://credify.ng/logos/glo.png',
        '9mobile': 'https://credify.ng/logos/9mobile.png'
    };

    networkSelect.addEventListener('change', function() {
        networkIcon.src = networkLogos[this.value] || networkLogos.mtn;
    });

    // Step 1: Validate → Store data → Show PIN
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const phone = document.getElementById('phone').value.trim();
        const network = networkSelect.value;
        const amount = parseFloat(document.getElementById('amount').value);

        if (amount < 50) {
            return showAlert('Minimum airtime is ₦50', 'error');
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Validating...';

        try {
            const res = await axios.post('{{ route("user.transfers.airtime.validate") }}', {
                phone, network, amount
            }, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            if (res.data.success) {
                formData = { phone, network, amount };
                pinModal.classList.remove('hidden');
                pinModal.classList.add('flex');
                pinInput.focus();
                pinInput.value = '';
            } else {
                showAlert(res.data.message, 'error');
            }
        } catch (err) {
            showAlert(err.response?.data?.message || 'Network error', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Continue to PIN';
        }
    });

    // Step 2: Confirm PIN → Submit via Axios
    confirmPinBtn.addEventListener('click', async function() {
        const pin = pinInput.value.trim();
        if (pin.length !== 4 || !/^\d+$/.test(pin)) {
            return showAlert('Enter 4-digit PIN', 'error');
        }

        confirmPinBtn.disabled = true;
        confirmPinBtn.innerHTML = 'Processing...';

        try {
            const res = await axios.post('{{ route("user.transfers.airtime.store") }}', {
                ...formData,
                pin: pin,
                _token: '{{ csrf_token() }}'
            });

            showAlert('Airtime purchased successfully!', 'success');
            setTimeout(() => {
                window.location.href = res.data.receipt_url || '{{ route("user.dashboard") }}';
            }, 1500);

        } catch (err) {
            const msg = err.response?.data?.message || err.response?.data?.error || 'Purchase failed';
            showAlert(msg, 'error');
            confirmPinBtn.disabled = false;
            confirmPinBtn.innerHTML = 'Buy Airtime';
        }
    });

    // Close modal
    closePinModal.addEventListener('click', () => {
        pinModal.classList.add('hidden');
        pinModal.classList.remove('flex');
    });

    pinModal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });

    function showAlert(msg, type = 'info') {
        const colors = { success: 'bg-green-600', error: 'bg-red-600' };
        const alert = document.createElement('div');
        alert.className = `fixed top-20 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity`;
        alert.innerHTML = `${msg}`;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 3000);
    }
})();
</script>
@endpush
@endsection