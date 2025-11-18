@extends('layouts.user')

@section('content')
<div class="mt-16 max-w-6xl mx-auto px-4 py-20">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Settings</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Manage your account preferences and security</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="ti ti-check-circle mr-3 text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="ti ti-alert-circle mr-3 text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Settings Grid -->
    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6">
        
        <!-- 1. Account Security -->
        <a href="{{ route('user.settings.security') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 group hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-red-50 dark:bg-red-900/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-shield-lock text-3xl text-red-600 dark:text-red-400"></i>
                </div>
                <i class="ti ti-chevron-right text-gray-400 group-hover:text-primary transition"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Account Security</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Manage password, PIN, and authentication
            </p>
            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-lock mr-1"></i>
                <span>Password & PIN</span>
            </div>
        </a>

        <!-- 2. Two-Factor Authentication -->
        <a href="{{ route('user.settings.security') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 group hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-device-mobile text-3xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="flex items-center">
                    @if($settings->two_factor_enabled)
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-xs text-green-600 dark:text-green-400 font-medium">Active</span>
                    @else
                        <span class="text-xs text-gray-400">Inactive</span>
                    @endif
                    <i class="ti ti-chevron-right text-gray-400 group-hover:text-primary transition ml-2"></i>
                </div>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Two-Factor Auth</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Add extra layer of security to your account
            </p>
            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-shield-check mr-1"></i>
                <span>{{ $settings->two_factor_enabled ? 'Enabled' : 'Disabled' }}</span>
            </div>
        </a>

        <!-- 3. Biometric Login -->
        <a href="{{ route('user.settings.security') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 group hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-purple-50 dark:bg-purple-900/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-fingerprint text-3xl text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="flex items-center">
                    @if($settings->biometric_enabled)
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-xs text-green-600 dark:text-green-400 font-medium">Active</span>
                    @else
                        <span class="text-xs text-gray-400">Inactive</span>
                    @endif
                    <i class="ti ti-chevron-right text-gray-400 group-hover:text-primary transition ml-2"></i>
                </div>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Biometric Login</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Use fingerprint or face ID to login
            </p>
            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-scan mr-1"></i>
                <span>{{ $settings->biometric_enabled ? 'Enabled' : 'Disabled' }}</span>
            </div>
        </a>

        <!-- 4. Notifications -->
        <a href="{{ route('user.settings.notifications') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 group hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-bell text-3xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <i class="ti ti-chevron-right text-gray-400 group-hover:text-primary transition"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Notifications</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Configure email and push notifications
            </p>
            <div class="flex flex-wrap gap-2 text-xs">
                @if($settings->email_notifications)
                    <span class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded">
                        <i class="ti ti-mail text-xs"></i> Email
                    </span>
                @endif
                @if($settings->push_notifications)
                    <span class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded">
                        <i class="ti ti-bell text-xs"></i> Push
                    </span>
                @endif
                @if(!$settings->email_notifications && !$settings->push_notifications)
                    <span class="text-gray-400">All disabled</span>
                @endif
            </div>
        </a>

        <!-- 5. Privacy Settings -->
        <a href="{{ route('user.settings.privacy') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 group hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-lock text-3xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <i class="ti ti-chevron-right text-gray-400 group-hover:text-primary transition"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Privacy</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Control your account visibility and data
            </p>
            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-eye-off mr-1"></i>
                <span>Balance: {{ $settings->hide_balance ? 'Hidden' : 'Visible' }}</span>
            </div>
        </a>

        <!-- 6. Preferences -->
        <a href="{{ route('user.settings.preferences') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 group hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-green-50 dark:bg-green-900/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-palette text-3xl text-green-600 dark:text-green-400"></i>
                </div>
                <i class="ti ti-chevron-right text-gray-400 group-hover:text-primary transition"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Preferences</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Customize language, theme, and display
            </p>
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-2 py-1 rounded">
                    <i class="ti ti-language text-xs"></i> 
                    {{ strtoupper($settings->language) }}
                </span>
                <span class="bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-2 py-1 rounded">
                    <i class="ti ti-moon text-xs"></i> 
                    {{ ucfirst($settings->theme) }}
                </span>
            </div>
        </a>

        <!-- 7. Session Management -->
        <a href="{{ route('user.settings.security') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 group hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-orange-50 dark:bg-orange-900/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-clock text-3xl text-orange-600 dark:text-orange-400"></i>
                </div>
                <i class="ti ti-chevron-right text-gray-400 group-hover:text-primary transition"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Session Timeout</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Auto-logout after inactivity
            </p>
            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-hourglass mr-1"></i>
                <span>{{ $settings->session_timeout }} minutes</span>
            </div>
        </a>

        <!-- 8. Security Questions -->
        <a href="{{ route('user.settings.security') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all duration-300 group hover:shadow-xl">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-pink-50 dark:bg-pink-900/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ti ti-help-circle text-3xl text-pink-600 dark:text-pink-400"></i>
                </div>
                <div class="flex items-center">
                    @if($settings->security_questions)
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-xs text-green-600 dark:text-green-400 font-medium">Set</span>
                    @else
                        <span class="text-xs text-gray-400">Not set</span>
                    @endif
                    <i class="ti ti-chevron-right text-gray-400 group-hover:text-primary transition ml-2"></i>
                </div>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Security Questions</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Account recovery questions
            </p>
            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                <i class="ti ti-shield-question mr-1"></i>
                <span>{{ $settings->security_questions ? count($settings->security_questions) . ' questions set' : 'Not configured' }}</span>
            </div>
        </a>

        <!-- 9. Account Status -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border dark:border-gray-700">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 {{ $settings->account_closed ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20' }} rounded-xl flex items-center justify-center">
                    <i class="ti {{ $settings->account_closed ? 'ti-user-off text-red-600 dark:text-red-400' : 'ti-user-check text-green-600 dark:text-green-400' }} text-3xl"></i>
                </div>
                <span class="text-xs px-3 py-1 rounded-full {{ $settings->account_closed ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                    {{ $settings->account_closed ? 'Closed' : 'Active' }}
                </span>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Account Status</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                {{ $settings->account_closed ? 'Your account is closed' : 'Your account is active and healthy' }}
            </p>
            @if(!$settings->account_closed)
                <button onclick="openCloseAccountModal()" 
                        class="text-xs text-red-600 dark:text-red-400 hover:underline flex items-center">
                    <i class="ti ti-x mr-1"></i>
                    Close Account
                </button>
            @else
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    <i class="ti ti-calendar mr-1"></i>
                    Closed: {{ $settings->closed_at?->format('M d, Y') }}
                </div>
                @if($settings->closure_reason)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Reason: {{ $settings->closure_reason }}
                    </p>
                @endif
            @endif
        </div>

    </div>

    <!-- Quick Stats Section -->
    <div class="mt-8 bg-gradient-to-r from-primary/10 to-indigo-100 dark:from-primary/20 dark:to-indigo-900/30 rounded-xl p-6 border border-primary/20">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            <i class="ti ti-chart-dots mr-2"></i>Your Settings Overview
        </h3>
        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-white/50 dark:bg-gray-800/50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-600 dark:text-gray-400">Security Score</span>
                    <i class="ti ti-shield-check text-primary"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ ($settings->two_factor_enabled ? 30 : 0) + ($settings->biometric_enabled ? 30 : 0) + ($settings->security_questions ? 20 : 0) + 20 }}%
                </p>
            </div>
            <div class="bg-white/50 dark:bg-gray-800/50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-600 dark:text-gray-400">Notifications</span>
                    <i class="ti ti-bell text-yellow-600"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ ($settings->email_notifications ? 1 : 0) + ($settings->push_notifications ? 1 : 0) }}/2
                </p>
            </div>
            <div class="bg-white/50 dark:bg-gray-800/50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-600 dark:text-gray-400">Session Timeout</span>
                    <i class="ti ti-clock text-orange-600"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $settings->session_timeout }}m
                </p>
            </div>
            <div class="bg-white/50 dark:bg-gray-800/50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-600 dark:text-gray-400">Language</span>
                    <i class="ti ti-language text-green-600"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ strtoupper($settings->language) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Back Link -->
    <div class="mt-8 text-center">
        <a href="{{ route('user.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary text-sm font-medium inline-flex items-center">
            <i class="ti ti-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- Close Account Modal -->
<div id="closeAccountModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center px-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md w-full shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-alert-triangle text-3xl text-red-600 dark:text-red-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Close Account?</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                This action cannot be undone. Please provide a reason for closing your account.
            </p>
        </div>

        <form method="POST" action="{{ route('user.settings.close-account') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Reason for closing
                </label>
                <textarea name="closure_reason" 
                          rows="3" 
                          required
                          class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500"
                          placeholder="Tell us why you're leaving..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeCloseAccountModal()"
                        class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 py-3 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition">
                    Close Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCloseAccountModal() {
        document.getElementById('closeAccountModal').classList.remove('hidden');
    }

    function closeCloseAccountModal() {
        document.getElementById('closeAccountModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('closeAccountModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCloseAccountModal();
        }
    });
</script>
@endsection