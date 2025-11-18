<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Credify Bank - Verify Email</title>

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
        .input-error {
            @apply border-danger ring-2 ring-danger/20;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4 transition-colors">

    <!-- OTP Card -->
    <div class="w-full max-w-lg relative">
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
            <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-2">Verify Your Email</h2>
            <p class="text-center text-gray-600 dark:text-gray-400 mb-8">
                We sent a 6-digit code to <span class="font-medium text-primary">{{ $email }}</span>
            </p>

            <!-- Flash Messages -->
            {{-- @if(session('success'))
                <div class="mb-6 p-4 bg-accent/10 border border-accent rounded-lg flex items-start space-x-3">
                    <i class="ti ti-circle-check text-accent text-xl mt-0.5"></i>
                    <p class="text-sm text-accent font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-danger/10 border border-danger rounded-lg flex items-start space-x-3">
                    <i class="ti ti-alert-circle text-danger text-xl mt-0.5"></i>
                    <p class="text-sm text-danger font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex items-start space-x-3">
                    <i class="ti ti-info-circle text-blue-600 dark:text-blue-400 text-xl mt-0.5"></i>
                    <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">{{ session('info') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 bg-warning/10 border border-warning rounded-lg flex items-start space-x-3">
                    <i class="ti ti-alert-triangle text-warning text-xl mt-0.5"></i>
                    <p class="text-sm text-warning font-medium">{{ session('warning') }}</p>
                </div>
            @endif --}}

            <!-- OTP Form -->
            <form action="{{ route('user.otp-verify', $token) }}" method="POST" id="otpForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}" id="tokenInput">

                <!-- OTP Input -->
                <div class="mb-6">
                    <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        6-Digit Code <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        id="otp"
                        name="otp"
                        maxlength="6"
                        required
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        autocomplete="one-time-code"
                        class="w-full px-4 py-3 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus text-center text-2xl tracking-widest font-mono transition"
                        placeholder="------"
                    />
                    @error('otp')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Timer -->
                <p class="text-center text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Time remaining: <span id="timer" class="font-bold text-primary">--:--</span>
                </p>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary/50 flex items-center justify-center space-x-2"
                >
                    <span>Verify Email</span>
                    <i class="ti ti-arrow-right"></i>
                </button>
            </form>

            <!-- Resend OTP -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Didn't receive the code?
                    <form action="{{ route('user.otp-resend', $token) }}" method="POST" id="resendOtpForm" class="inline">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}" id="resendTokenInput">
                        <button
                            type="submit"
                            id="resendBtn"
                            disabled
                            class="ml-1 text-primary font-medium hover:underline disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Resend OTP
                        </button>
                    </form>
                </p>
            </div>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary font-medium">
                    Back to Login
                </a>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} Credify Bank. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // === Theme Toggle ===
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }

        themeToggle?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
        });

        // === OTP Timer (Server-synced expiry) ===
        const timerElement = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');
        const otpExpiresAt = {{ $otp_expires_at_unix ?? now()->addMinutes(20)->timestamp * 1000 }};

        function updateTimer() {
            const now = Date.now();
            const timeLeft = otpExpiresAt - now;

            if (timeLeft <= 0) {
                timerElement.textContent = "00:00";
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            const minutes = Math.floor(timeLeft / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            timerElement.textContent = 
                `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        updateTimer();
        const interval = setInterval(updateTimer, 1000);

        setTimeout(() => {
            clearInterval(interval);
            resendBtn.disabled = false;
            resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }, otpExpiresAt - Date.now() + 1000);

        // === Form Submission Loading State ===
        const otpForm = document.getElementById('otpForm');
        const submitBtn = document.getElementById('submitBtn');

        otpForm?.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Verifying...</span>
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
                    animateInside: true,
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
                    animateInside: true,
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
                    animateInside: true,
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
                    animateInside: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @endif
        });
    </script>
    @endif
</body>
</html>