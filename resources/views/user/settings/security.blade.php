@extends('layouts.user')

@section('content')
<div class="mt-16 max-w-4xl mx-auto px-4 py-20">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('user.settings.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary mr-3">
                <i class="ti ti-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Account Security</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage your password and transaction PIN</p>
            </div>
        </div>
    </div>

 

    <div class="grid gap-6">
        <!-- Change Password Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700">
            <div class="p-6 border-b dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-lock text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Change Password</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your account login password</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('user.settings.update.password') }}">
                    @csrf

                    <!-- Current Password -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Current Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password"
                                   name="current_password"
                                   id="current_password"
                                   required
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary pr-12"
                                   placeholder="Enter current password">
                            <button type="button" 
                                    onclick="togglePassword('current_password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <i class="ti ti-eye text-xl" id="current_password_icon"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password"
                                   name="new_password"
                                   id="new_password"
                                   required
                                   minlength="8"
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary pr-12"
                                   placeholder="Enter new password">
                            <button type="button" 
                                    onclick="togglePassword('new_password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <i class="ti ti-eye text-xl" id="new_password_icon"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <i class="ti ti-info-circle"></i> Password must be at least 8 characters long
                        </p>
                        @error('new_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password"
                                   name="new_password_confirmation"
                                   id="new_password_confirmation"
                                   required
                                   minlength="8"
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary pr-12"
                                   placeholder="Confirm new password">
                            <button type="button" 
                                    onclick="togglePassword('new_password_confirmation')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <i class="ti ti-eye text-xl" id="new_password_confirmation_icon"></i>
                            </button>
                        </div>
                        @error('new_password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-primary hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition flex items-center justify-center">
                        <i class="ti ti-check mr-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Transaction PIN Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700">
            <div class="p-6 border-b dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-key text-2xl text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Change Transaction PIN</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your 4-digit security PIN for transactions</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('user.settings.update.pin') }}">
                    @csrf

                    <!-- Current PIN -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Current PIN <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               name="current_pin"
                               id="current_pin"
                               maxlength="4"
                               pattern="[0-9]{4}"
                               required
                               class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg text-center text-2xl tracking-widest font-mono dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary"
                               placeholder="••••"
                               inputmode="numeric"
                               autocomplete="off">
                        @error('current_pin')
                            <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New PIN -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New PIN <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               name="new_pin"
                               id="new_pin"
                               maxlength="4"
                               pattern="[0-9]{4}"
                               required
                               onkeyup="checkPINStrength()"
                               class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg text-center text-2xl tracking-widest font-mono dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary"
                               placeholder="••••"
                               inputmode="numeric"
                               autocomplete="off">
                        
                        <!-- PIN Strength Warning -->
                        <div id="pin_warning" class="hidden mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <div class="flex items-start">
                                <i class="ti ti-alert-triangle text-yellow-600 dark:text-yellow-400 mr-2 mt-0.5"></i>
                                <p class="text-xs text-yellow-800 dark:text-yellow-300">
                                    <strong>Weak PIN detected!</strong> Avoid obvious patterns like 1234, 0000, or repeated digits.
                                </p>
                            </div>
                        </div>

                        
                        @error('new_pin')
                            <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New PIN -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New PIN <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               name="new_pin_confirmation"
                               id="new_pin_confirmation"
                               maxlength="4"
                               pattern="[0-9]{4}"
                               required
                               class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg text-center text-2xl tracking-widest font-mono dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary"
                               placeholder="••••"
                               inputmode="numeric"
                               autocomplete="off">
                        @error('new_pin_confirmation')
                            <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition flex items-center justify-center">
                        <i class="ti ti-check mr-2"></i> Update Transaction PIN
                    </button>
                </form>
            </div>
        </div>

        <!-- Security Tips -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <div class="flex items-start">
                <i class="ti ti-info-circle text-blue-600 dark:text-blue-400 text-2xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-bold text-blue-900 dark:text-blue-200 mb-2">Security Best Practices</h3>
                    <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-2">
                        <li class="flex items-start">
                            <i class="ti ti-check text-xs mr-2 mt-1"></i>
                            <span>Use a strong, unique password that you don't use anywhere else</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ti ti-check text-xs mr-2 mt-1"></i>
                            <span>Never share your PIN with anyone, including Credify staff</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ti ti-check text-xs mr-2 mt-1"></i>
                            <span>Change your password and PIN regularly (every 3-6 months)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ti ti-check text-xs mr-2 mt-1"></i>
                            <span>Avoid using personal information in your password or PIN</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ti ti-check text-xs mr-2 mt-1"></i>
                            <span>Always log out from shared or public devices</span>
                        </li>
                        <li class="flex items-start">
                            <i class="ti ti-check text-xs mr-2 mt-1"></i>
                            <span>Enable two-factor authentication for extra security (coming soon)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('ti-eye');
            icon.classList.add('ti-eye-off');
        } else {
            field.type = 'password';
            icon.classList.remove('ti-eye-off');
            icon.classList.add('ti-eye');
        }
    }

    // Check PIN strength
    function checkPINStrength() {
        const pin = document.getElementById('new_pin').value;
        const warning = document.getElementById('pin_warning');
        
        const weakPins = ['0000', '1111', '2222', '3333', '4444', '5555', '6666', '7777', '8888', '9999', '1234', '4321', '1122', '2211'];
        
        if (pin.length === 4 && weakPins.includes(pin)) {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    }

    // Auto-format PIN inputs (numeric only)
    document.querySelectorAll('input[inputmode="numeric"]').forEach(input => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endsection