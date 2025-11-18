<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Credify Bank - Create Account</title>

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

    <style>
        body { font-family: 'Inter', sans-serif; transition: background-color 0.3s, color 0.3s; }
        .input-focus:focus {
            @apply ring-2 ring-primary/20 border-primary;
        }
        .input-error {
            @apply border-danger ring-2 ring-danger/20;
        }
        .password-toggle {
            cursor: pointer;
            transition: color 0.2s;
        }
        .password-toggle:hover { @apply text-primary; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4 transition-colors">

    <!-- Register Card -->
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
            <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-2">Create Your Account</h2>
            <p class="text-center text-gray-600 dark:text-gray-400 mb-8">Join Credify Bank today</p>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-accent/10 border border-accent rounded-lg flex items-start space-x-3">
                    <i class="ti ti-circle-check text-accent text-xl mt-0.5"></i>
                    <p class="text-sm text-accent font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- General Error Message -->
            @if($errors->has('error'))
                <div class="mb-6 p-4 bg-danger/10 border border-danger rounded-lg flex items-start space-x-3">
                    <i class="ti ti-alert-circle text-danger text-xl mt-0.5"></i>
                    <p class="text-sm text-danger font-medium">{{ $errors->first('error') }}</p>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('user.create') }}" method="POST" id="registerForm">
                @csrf

                <!-- Full Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Full Name <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        class="w-full px-4 py-3 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus transition"
                       
                    />
                    @error('name')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address <span class="text-danger">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full px-4 py-3 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus transition"
                        
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number <span class="text-danger">*</span>
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 py-3 text-gray-500 bg-gray-100 dark:bg-gray-700 border border-r-0 dark:border-gray-600 rounded-l-lg">
                            +234
                        </span>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            required
                            maxlength="10"
                            inputmode="numeric"
                            pattern="[0-9]{10}"
                            class="flex-1 px-4 py-3 rounded-r-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus transition "
                            placeholder="8012345678"
                        />
                    </div>
                    @error('phone')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Enter 10-digit Nigerian phone number without country code
                    </p>
                </div>

                <!-- Date of Birth -->
                <div class="mb-6">
                    <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Date of Birth <span class="text-danger">*</span>
                    </label>
                    <input
                        type="date"
                        id="dob"
                        name="dob"
                        value="{{ old('dob') }}"
                        required
                        max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                        class="w-full px-4 py-3 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none input-focus transition "
                    />
                    @error('dob')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        You must be at least 18 years old
                    </p>
                </div>


                <!-- Inside the form, after DOB -->
            <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Gender <span class="text-danger">*</span>
        </label>
        <div class="grid grid-cols-3 gap-3">
        @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="radio" name="gender" value="{{ $value }}"
                       {{ old('gender') == $value ? 'checked' : '' }}
                       class="w-4 h-4 text-primary focus:ring-primary" required>
                <span class="text-gray-700 dark:text-gray-300">{{ $label }}</span>
            </label>
        @endforeach
    </div>
    @error('gender')
        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
            <i class="ti ti-alert-circle"></i>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus pr-12 transition"
                            placeholder="••••••••"
                        />
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 password-toggle text-gray-500 dark:text-gray-400">
                            <i class="ti ti-eye text-xl" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Must be at least 8 characters long
                    </p>
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
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none input-focus pr-12 transition @error('password_confirmation') input-error @enderror"
                            placeholder="••••••••"
                        />
                        <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 password-toggle text-gray-500 dark:text-gray-400">
                            <i class="ti ti-eye text-xl" id="confirmEyeIcon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-danger flex items-center space-x-1">
                            <i class="ti ti-alert-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 rounded-lg transition duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary/50 flex items-center justify-center space-x-2"
                >
                    <span>Create Account</span>
                    <i class="ti ti-arrow-right"></i>
                </button>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">
                            Log in
                        </a>
                    </p>
                </div>
            </form>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} Credify Bank. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // === Password Toggles ===
        function setupToggle(buttonId, inputId, iconId) {
            const btn = document.getElementById(buttonId);
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            btn?.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('ti-eye');
                icon.classList.toggle('ti-eye-off');
            });
        }

        setupToggle('togglePassword', 'password', 'eyeIcon');
        setupToggle('toggleConfirmPassword', 'password_confirmation', 'confirmEyeIcon');

        // === Theme Toggle ===
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        themeToggle?.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                html.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });

        // === Phone Number Validation ===
        const phoneInput = document.getElementById('phone');
        phoneInput?.addEventListener('input', (e) => {
            // Only allow numbers
            e.target.value = e.target.value.replace(/\D/g, '');
            
            // Limit to 10 digits
            if (e.target.value.length > 10) {
                e.target.value = e.target.value.slice(0, 10);
            }
        });

        // === Form Submission Loading State ===
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');

        form?.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Creating Account...</span>
            `;
        });
    </script>
</body>
</html>