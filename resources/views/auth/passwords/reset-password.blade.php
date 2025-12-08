<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Credify Bank - Reset Password</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1E40AF',
                        secondary: '#64748B',
                        accent: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" />

    <style>
        body { font-family: 'Inter', sans-serif; transition: background-color 0.3s, color 0.3s; }
        .input-focus:focus {
            @apply ring-2 ring-primary/20 border-primary;
        }
        .password-toggle {
            cursor: pointer;
            transition: color 0.2s;
        }
        .password-toggle:hover { @apply text-primary; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4 transition-colors">

    <div class="w-full max-w-md relative">
        <!-- Theme Toggle -->
        <button id="themeToggle" class="absolute top-4 right-4 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition z-10">
            <i class="ti ti-sun text-lg inline dark:hidden"></i>
            <i class="ti ti-moon text-lg hidden dark:inline"></i>
        </button>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 p-8 mt-12">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-2xl">CB</span>
                </div>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-2">Reset Password</h2>
            <p class="text-center text-gray-600 dark:text-gray-400 mb-8">Enter your new password below</p>

            <!-- Form -->
            <form method="POST" action="{{ route('password.update', $token) }}" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- New Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        New Password <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            minlength="8"
                            placeholder="Enter new password"
                            class="w-full px-4 py-3 pr-11 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus transition"
                        />
                        <i class="ti ti-eye password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl" id="togglePassword"></i>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirm Password <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            minlength="8"
                            placeholder="Confirm new password"
                            class="w-full px-4 py-3 pr-11 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus transition"
                        />
                        <i class="ti ti-eye password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl" id="togglePasswordConfirm"></i>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary/50 flex items-center justify-center space-x-2"
                >
                    <span>Reset Password</span>
                    <i class="ti ti-lock"></i>
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary font-medium inline-flex items-center space-x-1">
                    <i class="ti ti-arrow-left"></i>
                    <span>Back to Login</span>
                </a>
            </div>

           
        </div>

         <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} Credify Bank. All rights reserved.
            </p>
    </div>

    <!-- Scripts -->
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
        });

        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirm = document.getElementById('password_confirmation');

        togglePassword.addEventListener('click', () => {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;
            togglePassword.classList.toggle('ti-eye');
            togglePassword.classList.toggle('ti-eye-off');
        });

        togglePasswordConfirm.addEventListener('click', () => {
            const type = passwordConfirm.type === 'password' ? 'text' : 'password';
            passwordConfirm.type = type;
            togglePasswordConfirm.classList.toggle('ti-eye');
            togglePasswordConfirm.classList.toggle('ti-eye-off');
        });

        // Form submission loading state
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('submitBtn');

        resetPasswordForm.addEventListener('submit', (e) => {
            const passwordValue = password.value;
            const confirmValue = passwordConfirm.value;

            // Check if passwords match
            if (passwordValue !== confirmValue) {
                e.preventDefault();
                iziToast.error({
                    title: 'Error',
                    message: 'Passwords do not match',
                    position: 'topRight'
                });
                return;
            }

            // Check password requirements
            const hasLength = passwordValue.length >= 8;
           
          // Check if passwords match
            if (passwordValue !== confirmValue) {
                e.preventDefault();
                iziToast.error({
                    title: 'Error',
                    message: 'Passwords do not match',
                    position: 'topRight'
                });
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Resetting...</span>
            `;
        });
    </script>

    <!-- iziToast JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

    @if (session('success') || session('error') || session('info') || session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                iziToast.success({
                    title: 'Success',
                    message: @json(session('success')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('error'))
                iziToast.error({
                    title: 'Error',
                    message: @json(session('error')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('info'))
                iziToast.info({
                    title: 'Info',
                    message: @json(session('info')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('warning'))
                iziToast.warning({
                    title: 'Warning',
                    message: @json(session('warning')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @endif
        });
    </script>
    @endif
</body>
</html>