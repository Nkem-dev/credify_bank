@extends('layouts.user')

@section('content')
<div class="mt-20">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 mb-6 mt-20 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Buy Data Bundle
            </h2>
        </div>

        <form id="dataForm">
            @csrf

            <!-- Network Selection -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                    Select Network
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button type="button" class="network-btn p-5 rounded-xl border-2 transition-all" data-network="mtn">
                        <img src="{{ asset('assets/images/mtn-logo.svg') }}" alt="MTN" class="w-10 h-5 mx-auto mb-2">
                        <span class="text-sm font-medium">MTN</span>
                    </button>
                    <button type="button" class="network-btn p-5 rounded-xl border-2 transition-all" data-network="airtel">
                        <img src="{{ asset('assets/images/airtel-logo_0.png') }}" alt="Airtel" class="w-10 h-5 mx-auto mb-2">
                        <span class="text-sm font-medium">Airtel</span>
                    </button>
                    <button type="button" class="network-btn p-5 rounded-xl border-2 transition-all" data-network="glo">
                        <img src="{{ asset('assets/images/glo logo.png') }}" alt="Glo" class="w-10 h-10 mx-auto mb-2">
                        <span class="text-sm font-medium">Glo</span>
                    </button>
                    <button type="button" class="network-btn p-5 rounded-xl border-2 transition-all" data-network="9mobile">
                        <img src="{{ asset('assets/images/9mobilelogo.png') }}" alt="9mobile" class="w-12 h-10 mx-auto mb-2">
                        <span class="text-sm font-medium">9mobile</span>
                    </button>
                </div>
                <input type="hidden" id="selectedNetwork" name="network" value="mtn">
            </div>

            <!-- Quick Plans -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Quick Plans</h3>
                <div id="quickPlans" class="grid grid-cols-2 md:grid-cols-3 gap-4"></div>
            </div>

            <!-- All Plans Dropdown -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">All Data Plans</h3>
                <select id="dataPlanSelect" class="w-full px-5 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white text-lg">
                    <option value="">Choose a plan...</option>
                </select>
            </div>

            <!-- Phone Number -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Phone Number
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">0</span>
                    <input type="tel" id="phone" name="phone"
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                           placeholder="8012345678" 
                           maxlength="10"
                           pattern="[7-9][0-1]\d{8}"
                           required>
                </div>
                {{-- <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <i class="ti ti-info-circle"></i> Enter 10 digits starting with 70, 80, 81, or 90
                </p> --}}
            </div>

            <!-- Selected Plan Display -->
            <div id="selectedPlanInfo" class="hidden bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 mb-6 border border-primary/20">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-2xl font-bold text-primary" id="planSize"></p>
                        <p class="text-sm text-gray-600 dark:text-gray-400" id="planValidity"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white" id="planPrice"></p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" id="submitBtn"
                    class="w-full bg-primary text-white py-5 rounded-xl font-bold text-lg hover:bg-primary/90 transition flex items-center justify-center disabled:opacity-50">
                <i class="ti ti-wifi mr-3"></i>
                Continue to PIN
            </button>
        </form>
    </div>

    <!-- PIN Modal -->
    <div id="pinModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
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
                            class="flex-1 bg-primary text-white py-3 rounded-lg font-medium hover:bg-primary/90">
                        Buy Data
                    </button>
                    <button type="button" id="closePinModal"
                            class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded-lg font-medium">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
(() => {
    'use strict';

    const networkBtns = document.querySelectorAll('.network-btn');
    const quickPlans = document.getElementById('quickPlans');
    const dataPlanSelect = document.getElementById('dataPlanSelect');
    const selectedNetworkInput = document.getElementById('selectedNetwork');
    const selectedPlanInfo = document.getElementById('selectedPlanInfo');
    const planSize = document.getElementById('planSize');
    const planValidity = document.getElementById('planValidity');
    const planPrice = document.getElementById('planPrice');
    const pinModal = document.getElementById('pinModal');
    const pinInput = document.getElementById('pinInput');
    const confirmPinBtn = document.getElementById('confirmPinBtn');
    const closePinModal = document.getElementById('closePinModal');
    const form = document.getElementById('dataForm');
    const submitBtn = document.getElementById('submitBtn');
    const phoneInput = document.getElementById('phone');

    let selectedPlan = null;
    let formData = {};

    const plans = {
        mtn: [
            { size: '1GB', validity: 'Daily', price: 300 },
            { size: '2GB', validity: 'Weekly', price: 500 },
            { size: '10GB', validity: 'Monthly', price: 2500 },
            { size: '25GB', validity: 'Monthly', price: 5500 },
            { size: '50GB', validity: 'Monthly', price: 9500 },
        ],
        airtel: [
            { size: '1GB', validity: 'Daily', price: 300 },
            { size: '3GB', validity: 'Weekly', price: 700 },
            { size: '15GB', validity: 'Monthly', price: 3000 },
            { size: '40GB', validity: 'Monthly', price: 7500 },
        ],
        glo: [
            { size: '1.1GB', validity: 'Daily', price: 300 },
            { size: '2.9GB', validity: 'Weekly', price: 500 },
            { size: '12GB', validity: 'Monthly', price: 2500 },
            { size: '30GB', validity: 'Monthly', price: 6000 },
        ],
        '9mobile': [
            { size: '1GB', validity: 'Daily', price: 300 },
            { size: '2.5GB', validity: 'Weekly', price: 600 },
            { size: '11GB', validity: 'Monthly', price: 2800 },
            { size: '40GB', validity: 'Monthly', price: 8000 },
        ]
    };

    // Phone number validation and formatting
    phoneInput.addEventListener('input', function(e) {
        // Remove all non-digits
        let value = e.target.value.replace(/\D/g, '');
        
        // Limit to 10 digits
        if (value.length > 10) {
            value = value.slice(0, 10);
        }
        
        e.target.value = value;
    });

    phoneInput.addEventListener('blur', function(e) {
        const value = e.target.value;
        
        // Validate Nigerian phone format (must start with 70, 80, 81, or 90)
        if (value.length === 10) {
            const firstTwo = value.substring(0, 2);
            const validPrefixes = ['70', '80', '81', '90', '91'];
            
            if (!validPrefixes.includes(firstTwo)) {
                showAlert('Phone number must start with 70, 80, 81, 90, or 91', 'error');
                e.target.classList.add('border-red-500');
            } else {
                e.target.classList.remove('border-red-500');
            }
        }
    });

    function validatePhoneNumber(phone) {
        // Must be exactly 10 digits
        if (phone.length !== 10) {
            return { valid: false, message: 'Phone number must be 10 digits' };
        }
        
        // Must be all digits
        if (!/^\d{10}$/.test(phone)) {
            return { valid: false, message: 'Phone number must contain only digits' };
        }
        
        // Must start with valid prefix
        const firstTwo = phone.substring(0, 2);
        const validPrefixes = ['70', '80', '81', '90', '91'];
        
        if (!validPrefixes.includes(firstTwo)) {
            return { valid: false, message: 'Phone number must start with 70, 80, 81, 90, or 91' };
        }
        
        return { valid: true };
    }

    function updateNetwork(network) {
        selectedNetworkInput.value = network;
        networkBtns.forEach(b => b.classList.remove('border-primary', 'bg-primary/5', 'dark:bg-primary/10'));
        document.querySelector(`[data-network="${network}"]`).classList.add('border-primary', 'bg-primary/5', 'dark:bg-primary/10');
        loadQuickPlans(network);
        loadAllPlans(network);
    }

    function loadQuickPlans(network) {
        const top3 = plans[network].slice(0, 3);
        const colors = {
            mtn: 'yellow-400 to-orange-500',
            airtel: 'red-500 to-red-600',
            glo: 'green-500 to-green-600',
            '9mobile': 'emerald-500 to-emerald-600'
        };
        
        quickPlans.innerHTML = top3.map(p => `
            <button type="button" class="plan-card bg-gradient-to-br from-${colors[network]} text-white p-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-1 transition" data-plan='${JSON.stringify(p)}'>
                <div class="text-2xl font-bold">${p.size}</div>
                <div class="text-sm opacity-90">${p.validity}</div>
                <div class="text-lg font-bold mt-2">₦${p.price.toLocaleString()}</div>
            </button>
        `).join('');
        attachPlanListeners();
    }

    function loadAllPlans(network) {
        dataPlanSelect.innerHTML = '<option value="">Choose a plan...</option>' + 
            plans[network].map((p, i) => `
                <option value="${i}">${p.size} - ${p.validity} - ₦${p.price.toLocaleString()}</option>
            `).join('');
    }

    function attachPlanListeners() {
        document.querySelectorAll('.plan-card').forEach(card => {
            card.onclick = () => {
                selectedPlan = JSON.parse(card.dataset.plan);
                showSelectedPlan(selectedPlan);
            };
        });
    }

    function showSelectedPlan(plan) {
        selectedPlanInfo.classList.remove('hidden');
        planSize.textContent = plan.size;
        planValidity.textContent = plan.validity;
        planPrice.textContent = '₦' + plan.price.toLocaleString();
    }

    dataPlanSelect.onchange = () => {
        const idx = dataPlanSelect.value;
        if (idx !== '') {
            selectedPlan = plans[selectedNetworkInput.value][idx];
            showSelectedPlan(selectedPlan);
        }
    };

    networkBtns.forEach(btn => btn.onclick = () => updateNetwork(btn.dataset.network));

    form.onsubmit = async e => {
        e.preventDefault();

        const phone = phoneInput.value.trim();
        
        // Validate phone number
        const phoneValidation = validatePhoneNumber(phone);
        if (!phoneValidation.valid) {
            return showAlert(phoneValidation.message, 'error');
        }

        if (!selectedPlan) {
            return showAlert('Please select a data plan', 'error');
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Validating...';

        try {
            const res = await axios.post('{{ route("user.transfers.data.validate") }}', {
                phone: phone,
                network: selectedNetworkInput.value,
                amount: selectedPlan.price
            }, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            if (res.data.success) {
                formData = {
                    phone: phone,
                    network: selectedNetworkInput.value,
                    amount: selectedPlan.price,
                    plan: selectedPlan
                };
                
                pinModal.classList.remove('hidden');
                pinModal.classList.add('flex');
                pinInput.focus();
                pinInput.value = '';
            }
        } catch (err) {
            showAlert(err.response?.data?.message || 'Validation failed', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-wifi mr-3"></i>Continue to PIN';
        }
    };

    confirmPinBtn.onclick = async () => {
        const pin = pinInput.value.trim();
        
        if (pin.length !== 4 || !/^\d+$/.test(pin)) {
            return showAlert('Enter 4-digit PIN', 'error');
        }

        confirmPinBtn.disabled = true;
        confirmPinBtn.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Processing...';

        try {
            const res = await axios.post('{{ route("user.transfers.data.store") }}', {
                phone: formData.phone,
                network: formData.network,
                amount: formData.amount,
                plan: JSON.stringify(formData.plan),
                pin: pin,
                _token: '{{ csrf_token() }}'
            });

            if (res.data.success) {
                showAlert('Data bundle purchased successfully!', 'success');
                setTimeout(() => {
                    window.location.href = res.data.receipt_url;
                }, 1500);
            }
        } catch (err) {
            const msg = err.response?.data?.error || err.response?.data?.message || 'Purchase failed';
            showAlert(msg, 'error');
            confirmPinBtn.disabled = false;
            confirmPinBtn.innerHTML = 'Buy Data';
        }
    };

    closePinModal.onclick = () => {
        pinModal.classList.add('hidden');
        pinModal.classList.remove('flex');
        pinInput.value = '';
    };

    pinModal.onclick = e => {
        if (e.target === pinModal) {
            pinModal.classList.add('hidden');
            pinModal.classList.remove('flex');
            pinInput.value = '';
        }
    };

    function showAlert(msg, type = 'error') {
        const colors = {
            error: 'bg-red-600',
            success: 'bg-green-600',
            info: 'bg-blue-600'
        };
        
        const icons = {
            error: 'ti-x',
            success: 'ti-check',
            info: 'ti-info-circle'
        };
        
        const el = document.createElement('div');
        el.className = `fixed top-20 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2`;
        el.innerHTML = `<i class="ti ${icons[type]}"></i><span>${msg}</span>`;
        document.body.appendChild(el);
        
        setTimeout(() => {
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 300);
        }, 4000);
    }

    // Initialize with MTN
    updateNetwork('mtn');
})();
</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endpush
@endsection