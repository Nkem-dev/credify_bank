<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Credify Bank - Set Transaction PIN</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

    <!-- PIN Setup Card -->
    <div class="w-full max-w-md relative">
        <!-- Theme Toggle (Top Right) -->
        <button id="themeToggle" class="absolute top-4 right-4 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
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
            <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-2">Set Your Transaction PIN</h2>
            <p class="text-center text-gray-600 dark:text-gray-400 mb-8">This 4-digit PIN will secure all transactions</p>

           

            <!-- Form -->
            <form action="{{ route('pin.store') }}" method="POST">
                @csrf

                <!-- PIN Input -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Enter 4-Digit PIN <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            name="pin"
                            inputmode="numeric"
                            maxlength="4"
                            required
                            class="w-full px-4 py-3 text-center text-2xl tracking-widest rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus pr-12 transition"
                            placeholder="••••"
                            id="pinInput"
                        />
                        <button
                            type="button"
                            id="togglePin"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 password-toggle text-gray-500 dark:text-gray-400"
                        >
                            <i class="ti ti-eye text-xl" id="pinEyeIcon"></i>
                        </button>
                    </div>
                    @error('pin')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Confirm PIN -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirm PIN <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            name="pin_confirmation"
                            inputmode="numeric"
                            maxlength="4"
                            required
                            class="w-full px-4 py-3 text-center text-2xl tracking-widest rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus pr-12 transition"
                            placeholder="••••"
                            id="confirmInput"
                        />
                        <button
                            type="button"
                            id="toggleConfirm"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 password-toggle text-gray-500 dark:text-gray-400"
                        >
                            <i class="ti ti-eye text-xl" id="confirmEyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary/50 flex items-center justify-center space-x-2"
                >
                    <span>Set PIN & Continue</span>
                    <i class="ti ti-arrow-right"></i>
                </button>
            </form>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} Credify Bank. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // === PIN Toggle (Show/Hide) ===
        function setupPinToggle(inputId, buttonId, iconId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            const icon = document.getElementById(iconId);

            button.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('ti-eye');
                icon.classList.toggle('ti-eye-off');
            });
        }

        setupPinToggle('pinInput', 'togglePin', 'pinEyeIcon');
        setupPinToggle('confirmInput', 'toggleConfirm', 'confirmEyeIcon');

        // === Theme Toggle (Light / Dark) ===
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        // Load saved theme or system preference
        if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        // Toggle on click
        themeToggle.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                html.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });

        // Optional: Auto-hide error messages after 6s
        setTimeout(() => {
            document.querySelectorAll('.text-danger').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
            });
        }, 6000);
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