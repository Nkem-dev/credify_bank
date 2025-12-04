

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col border-r dark:border-gray-700">
    <!-- Logo -->
    <div class="flex items-center justify-between p-5 border-b dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center transition-transform hover:scale-110">
                <span class="text-white font-bold text-xl">CB</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-primary transition-colors hover:text-primary/90">Credify Bank</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Customer Care</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto fancy-scrollbar">
        <a href="{{ route('customer_care.dashboard') }}" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
               {{ request()->routeIs('customer_care.dashboard') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary' }}">
            <i class="ti ti-layout-dashboard text-lg {{ request()->routeIs('customer_care.dashboard') ? 'text-white' : '' }}"></i>
            <span>Dashboard</span>
        </a>

        <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary">
            <i class="ti ti-ticket text-lg"></i>
            <span>Support Tickets</span>
        </a>

        <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary">
            <i class="ti ti-users text-lg"></i>
            <span>Customers</span>
        </a>

        <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary">
            <i class="ti ti-message-circle text-lg"></i>
            <span>Messages</span>
        </a>

        <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary">
            <i class="ti ti-chart-bar text-lg"></i>
            <span>Reports</span>
        </a>

        <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary">
            <i class="ti ti-file-text text-lg"></i>
            <span>Knowledge Base</span>
        </a>

        <div class="mt-8 pt-6 border-t dark:border-gray-700">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Account</p>
            <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary">
                <i class="ti ti-settings text-lg"></i>
                <span>Settings</span>
            </a>
        </div>
    </nav>

    <!-- User Info -->
    <div class="p-4 border-t dark:border-gray-700">
        <div class="flex items-center space-x-3 group">
            <div class="w-10 h-10 bg-gradient-to-br from-primary to-blue-600 rounded-full ring-2 ring-white dark:ring-gray-800 transition-transform group-hover:scale-110"></div>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-primary transition-colors">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-primary/80 transition-colors">
                    {{ Auth::user()->email }}
                </p>
            </div>
        </div>
    </div>
</aside>