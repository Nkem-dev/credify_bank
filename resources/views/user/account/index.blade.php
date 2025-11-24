@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-6xl mx-auto mt-10">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('user.dashboard') }}" 
               class="inline-flex items-center text-primary hover:text-primary/80 mb-4 transition">
                <i class="ti ti-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Account</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Manage your profile and account settings</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 mb-6">
            <div class="border-b dark:border-gray-700">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="showTab('profile')" 
                            class="tab-btn active py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="profile">
                        <i class="ti ti-user mr-2"></i>Profile
                    </button>
                    <button onclick="showTab('security')" 
                            class="tab-btn py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="security">
                        <i class="ti ti-shield-lock mr-2"></i>Security
                    </button>
                    <button onclick="showTab('account-info')" 
                            class="tab-btn py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="account-info">
                        <i class="ti ti-info-circle mr-2"></i>Account Info
                    </button>
                </nav>
            </div>

            <!-- Profile Tab -->
            <div id="profile-tab" class="tab-content p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Profile Information</h3>
                
                <form action="{{ route('user.account.update.profile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+234 XXX XXX XXXX"
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        {{-- <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth
                            </label>
                            <input type="date" 
                                   name="date_of_birth" 
                                   id="date_of_birth"
                                   value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        <!-- Date of Birth (Read-Only) -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth
                            </label>
                            <div class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                {{ $user->dob_formatted ?? $user->formatted_dob ?? 'Not set' }}
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                <i class="ti ti-lock text-xs mr-1"></i>
                                Date of birth cannot be changed
                            </p>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Address
                            </label>
                            <textarea name="address" 
                                      id="address"
                                      rows="3"
                                      placeholder="Enter your residential address"
                                      class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" 
                                class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-medium transition">
                            Save Changes
                        </button>
                    </div>
                </form>

                <!-- Change Password Section -->
                <hr class="my-8 dark:border-gray-700">
                
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Change Password</h3>
                
                <form action="{{ route('user.account.update.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Password -->
                        <div class="md:col-span-2">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password"
                                   required
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password"
                                   required
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition @error('new_password') border-red-500 @enderror">
                            @error('new_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Minimum 8 characters
                            </p>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   id="new_password_confirmation"
                                   required
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" 
                                class="bg-accent hover:bg-accent/90 text-white px-6 py-3 rounded-lg font-medium transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div id="security-tab" class="tab-content hidden p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Security Settings</h3>
                
                <form action="{{ route('user.account.update.security') }}" method="POST" id="securityForm">
                    @csrf
                    @method('PUT')

                    <!-- Change Transaction PIN -->
                    <div class="mb-6 p-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Change Transaction PIN</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Update your 4-digit PIN for secure transactions
                                </p>
                            </div>
                            <i class="ti ti-lock text-3xl text-primary"></i>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Current PIN -->
                            <div>
                                <label for="current_pin" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                    Current PIN
                                </label>
                                <input type="password" 
                                       name="current_pin" 
                                       id="current_pin"
                                       maxlength="4"
                                       pattern="\d{4}"
                                       placeholder="••••"
                                       class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition text-center font-mono text-lg tracking-widest @error('current_pin') border-red-500 @enderror">
                                @error('current_pin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New PIN -->
                            <div>
                                <label for="new_transaction_pin" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                    New PIN
                                </label>
                                <input type="password" 
                                       name="new_transaction_pin" 
                                       id="new_transaction_pin"
                                       maxlength="4"
                                       pattern="\d{4}"
                                       placeholder="••••"
                                       class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition text-center font-mono text-lg tracking-widest @error('new_transaction_pin') border-red-500 @enderror">
                                @error('new_transaction_pin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm New PIN -->
                            <div>
                                <label for="new_transaction_pin_confirmation" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                    Confirm New PIN
                                </label>
                                <input type="password" 
                                       name="new_transaction_pin_confirmation" 
                                       id="new_transaction_pin_confirmation"
                                       maxlength="4"
                                       pattern="\d{4}"
                                       placeholder="••••"
                                       class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white transition text-center font-mono text-lg tracking-widest">
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="flex items-start space-x-2">
                                <i class="ti ti-info-circle text-blue-600 dark:text-blue-400 text-sm mt-0.5"></i>
                                <div>
                                    <p class="text-xs text-blue-900 dark:text-blue-300 font-medium">PIN Status</p>
                                    <p class="text-xs text-blue-700 dark:text-blue-400">
                                        @if($user->transaction_pin)
                                            <span class="text-green-600 dark:text-green-400">✓ Transaction PIN is currently active</span>
                                        @else
                                            <span class="text-amber-600 dark:text-amber-400">⚠ No transaction PIN set</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Two-Factor Authentication -->
                    <div class="mb-6 p-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    Two-Factor Authentication
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Add an extra layer of security to your account
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="two_factor_enabled" 
                                       value="1"
                                       {{ $user->two_factor_enabled ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" 
                                class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-medium transition">
                            Save Security Settings
                        </button>
                    </div>
                </form>

                <!-- Account Activity -->
                <hr class="my-8 dark:border-gray-700">
                
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Recent Activity</h3>
                
                <div class="space-y-3">
                    @forelse($recentTransfers->take(3) as $transfer)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                    <i class="ti ti-{{ $transfer->type === 'deposit' ? 'arrow-down-left text-green-600' : 'arrow-up-right text-red-600' }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ ucfirst($transfer->type) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $transfer->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-semibold {{ $transfer->type === 'deposit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transfer->type === 'deposit' ? '+' : '-' }}₦{{ number_format($transfer->amount, 2) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No recent activity</p>
                    @endforelse
                    
                    @if($recentTransfers->count() > 3)
                        <a href="{{ route('user.transactions.index') }}" 
                           class="block text-center text-primary hover:text-primary/80 text-sm font-medium">
                            View all activity →
                        </a>
                    @endif
                </div>
            </div>

            <!-- Account Info Tab -->
            <div id="account-info-tab" class="tab-content hidden p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Account Information</h3>
                
                <div class="space-y-6">
                    <!-- Account Details -->
                    <div class="bg-white dark:bg-gray-700/50 border dark:border-gray-700 p-6 rounded-xl">
                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-4">Banking Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Account Number</p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-lg font-bold text-primary font-mono" id="accountNum">
                                        {{ $user->account_number }}
                                    </p>
                                    <button type="button" onclick="copyAccountNumber()" class="text-gray-500 hover:text-primary transition">
                                        <i class="ti ti-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Account Name</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Bank Name</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Credify Bank
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Account Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <i class="ti ti-circle-check mr-1"></i> Active
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="bg-white dark:bg-gray-700/50 p-6 rounded-xl border dark:border-gray-700">
                        <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-4">Personal Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Email</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Phone</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->phone ?? 'Not set' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Date of Birth</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->dob_formatted ?? $user->formatted_dob ?? 'Not set' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Member Since</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            @if($user->last_login_at)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Last Login</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Account Statistics -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-gray-700/50 p-4 rounded-xl border dark:border-gray-700 text-center">
                            <i class="ti ti-wallet text-2xl text-primary mb-2"></i>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Balance</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                ₦{{ number_format($user->balance, 2) }}
                            </p>
                        </div>
                        <div class="bg-white dark:bg-gray-700/50 p-4 rounded-xl border dark:border-gray-700 text-center">
                            <i class="ti ti-pig-money text-2xl text-green-600 mb-2"></i>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Savings</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                ₦{{ number_format($user->savings_balance ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="bg-white dark:bg-gray-700/50 p-4 rounded-xl border dark:border-gray-700 text-center">
                            <i class="ti ti-chart-line text-2xl text-purple-600 mb-2"></i>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Investments</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                ₦{{ number_format($user->investment_value ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="bg-white dark:bg-gray-700/50 p-4 rounded-xl border dark:border-gray-700 text-center">
                            <i class="ti ti-receipt text-2xl text-accent mb-2"></i>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Transactions</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ $user->transfers()->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-primary', 'text-primary');
        btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
    activeBtn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    activeBtn.classList.add('active', 'border-primary', 'text-primary');
}

function copyAccountNumber() {
    const accountNum = document.getElementById('accountNum').textContent.trim();
    navigator.clipboard.writeText(accountNum);
    
    // Show feedback
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    icon.classList.replace('ti-copy', 'ti-check');
    
    setTimeout(() => {
        icon.classList.replace('ti-check', 'ti-copy');
    }, 2000);
}

// Restrict all PIN inputs to numbers only
['current_pin', 'new_transaction_pin', 'new_transaction_pin_confirmation'].forEach(id => {
    const input = document.getElementById(id);
    if (input) {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
        });
    }
});
</script>

<style>
.tab-btn.active {
    border-color: #4F46E5;
    color: #4F46E5;
}

.tab-btn:not(.active) {
    border-color: transparent;
    color: #6B7280;
}

.tab-btn:not(.active):hover {
    border-color: #D1D5DB;
    color: #374151;
}
</style>
@endpush
@endsection