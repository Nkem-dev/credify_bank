<header class="fixed inset-x-0 top-0 z-50 bg-white dark:bg-gray-800 shadow-sm border-b dark:border-gray-700 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- LOGO -->
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center transition-transform group-hover:scale-105">
                    <span class="text-white font-bold text-xl">CB</span>
                </div>
                <span class="hidden sm:inline text-xl font-bold text-primary group-hover:text-primary/90">
                    Credify Bank
                </span>
            </a>

            <!-- DESKTOP NAV -->
            <nav class="hidden md:flex items-center space-x-8">
                <!-- Online Banking -->
                <div class="relative group">
                    <button class="flex items-center space-x-1 text-gray-700 dark:text-gray-300 hover:text-primary font-medium">
                        <span>Online Banking</span>
                        <i class="ti ti-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none group-hover:pointer-events-auto">
                        <a href="#" class="block px-4 py-2.5 text-sm hover:bg-primary/5 hover:text-primary">Money Transfer Demo - 1</a>
                        <a href="#" class="block px-4 py-2.5 text-sm hover:bg-primary/5 hover:text-primary">Money Transfer Demo - 2</a>
                        <a href="#" class="block px-4 py-2.5 text-sm text-primary font-medium bg-primary/5 rounded-b-xl">Online Banking Demo</a>
                    </div>
                </div>

                <a href="/about" class="text-gray-700 dark:text-gray-300 hover:text-primary font-medium">About Us</a>
                <a href="/pricing" class="text-gray-700 dark:text-gray-300 hover:text-primary font-medium">Compare Pricing</a>

                <!-- Blog -->
                <div class="relative group">
                    <button class="flex items-center space-x-1 text-gray-700 dark:text-gray-300 hover:text-primary font-medium">
                        <span>Blog</span>
                        <i class="ti ti-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none group-hover:pointer-events-auto">
                        <a href="/blog" class="block px-4 py-2.5 text-sm hover:bg-primary/5 hover:text-primary rounded-t-xl">Blog</a>
                        <a href="/blog-details" class="block px-4 py-2.5 text-sm hover:bg-primary/5 hover:text-primary rounded-b-xl">Blog Details</a>
                    </div>
                </div>

                <a href="/contact" class="text-gray-700 dark:text-gray-300 hover:text-primary font-medium">Contact</a>
            </nav>

            <!-- RIGHT SIDE -->
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-primary font-medium rounded-lg hover:bg-primary/10 transition">Log In</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 bg-primary hover:bg-primary/90 text-white font-medium rounded-lg shadow-sm transition">Register Now</a>
                </div>

                <button id="themeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i class="ti ti-sun text-lg inline dark:hidden"></i>
                    <i class="ti ti-moon text-lg hidden dark:inline"></i>
                </button>

                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="ti ti-menu-2 text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div id="mobileMenu" class="hidden md:hidden bg-white dark:bg-gray-800 border-t dark:border-gray-700">
        <div class="px-4 py-3 space-y-1">
            <div class="border-b dark:border-gray-700 pb-2">
                <button id="mobileBankToggle" class="w-full flex justify-between py-2 text-gray-700 dark:text-gray-300 font-medium hover:text-primary">
                    Online Banking <i class="ti ti-chevron-down text-sm" id="mobileBankIcon"></i>
                </button>
                <div id="mobileBankMenu" class="hidden pl-4 space-y-1 mt-1">
                    <a href="#" class="block py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-primary">Money Transfer Demo - 1</a>
                    <a href="#" class="block py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-primary">Money Transfer Demo - 2</a>
                    <a href="#" class="block py-1.5 text-sm text-primary font-medium">Online Banking Demo</a>
                </div>
            </div>

            <a href="/about" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary">About Us</a>
            <a href="/pricing" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary">Compare Pricing</a>

            <div class="border-b dark:border-gray-700 pb-2">
                <button id="mobileBlogToggle" class="w-full flex justify-between py-2 text-gray-700 dark:text-gray-300 font-medium hover:text-primary">
                    Blog <i class="ti ti-chevron-down text-sm" id="mobileBlogIcon"></i>
                </button>
                <div id="mobileBlogMenu" class="hidden pl-4 space-y-1 mt-1">
                    <a href="/blog" class="block py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-primary">Blog</a>
                    <a href="/blog-details" class="block py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-primary">Blog Details</a>
                </div>
            </div>

            <a href="/contact" class="block py-2 text-gray-700 dark:text-gray-300 hover:text-primary">Contact</a>

            <div class="pt-3 border-t dark:border-gray-700 space-y-2">
                <a href="{{ route('login') }}" class="block text-center py-2 text-primary font-medium rounded-lg hover:bg-primary/10">Log In</a>
                <a href="{{ route('register') }}" class="block text-center py-2 bg-primary hover:bg-primary/90 text-white font-medium rounded-lg">Register Now</a>
            </div>
        </div>
    </div>
</header>

<!-- Push content below fixed header -->
<div class="h-16"></div>

<script>
    // Mobile menu
    document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // Mobile dropdowns
    function setupDrop(toggle, menu, icon) {
        document.getElementById(toggle)?.addEventListener('click', () => {
            document.getElementById(menu).classList.toggle('hidden');
            document.getElementById(icon).classList.toggle('rotate-180');
        });
    }
    setupDrop('mobileBankToggle', 'mobileBankMenu', 'mobileBankIcon');
    setupDrop('mobileBlogToggle', 'mobileBlogMenu', 'mobileBlogIcon');
</script>