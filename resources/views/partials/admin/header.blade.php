 <header class="fixed top-0 left-0 lg:left-64 right-0 bg-white dark:bg-gray-800 shadow-md z-40 border-b dark:border-gray-700">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Mobile Menu Button -->
                <button id="openSidebar" class="lg:hidden">
                    <i class="ti ti-menu-2 text-xl"></i>
                </button>

                <!-- Page Title -->
                <h2 class="text-xl font-semibold hidden lg:block">Admin Dashboard</h2>

                <!-- Right: Theme Toggle + Notifications + Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button id="themeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="ti ti-moon text-xl hidden dark:inline"></i>
                        <i class="ti ti-sun text-xl inline dark:hidden"></i>
                    </button>

                    <!-- Notifications -->
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="ti ti-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative dropdown">
                        <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full border-2 border-white dark:border-gray-700"></div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium">
                                    @auth {{ Auth::user()->name }} @else User @endauth
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @auth {{ Auth::user()->email }} @else user@credify.com @endauth
                                </p>
                            </div>
                            <i class="ti ti-chevron-down text-xs"></i>
                        </button>

                        <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border dark:border-gray-700 overflow-hidden">
                            <a href="#" class="flex items-center space-x-2 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="ti ti-user"></i>
                                <span>Profile</span>
                            </a>
                            <a href="#" class="flex items-center space-x-2 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="ti ti-settings"></i>
                                <span>Settings</span>
                            </a>
                            <a href="#" class="flex items-center space-x-2 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="ti ti-credit-card"></i>
                                <span>Account</span>
                            </a>
                            <hr class="my-1 border-gray-200 dark:border-gray-700">
                            @auth
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 text-left">
                                        <i class="ti ti-logout"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <i class="ti ti-logout"></i>
                                    <span>Login</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </header>
