<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col border-r dark:border-gray-700">
    <!-- Logo -->
    <div class="flex items-center justify-between p-5 border-b dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center transition-transform hover:scale-110">
                <span class="text-white font-bold text-xl">CB</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-primary transition-colors hover:text-primary/90">Credify Bank</h1>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto fancy-scrollbar">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
               hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary dark:hover:text-primary
               {{ request()->is('admin') || request()->is('admin/dashboard') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300' }}">
            <i class="ti ti-layout-dashboard text-lg transition-colors group-hover:text-primary dark:group-hover:text-primary
                   {{ request()->is('admin') || request()->is('admin/dashboard') ? 'text-white' : '' }}"></i>
            <span>Dashboard</span>
        </a>

        <!-- Users -->
        <a href="{{ route('admin.users.index') }}" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
               hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-[#5E84ff] dark:hover:text-[#5E84ff]
               {{ request()->is('admin/users*') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300' }}">
            <i class="ti ti-users text-lg transition-colors group-hover:text-[#5E84ff] dark:group-hover:text-[#5E84ff]
                   {{ request()->is('admin/users*') ? 'text-white' : '' }}"></i>
            <span>Users</span>
        </a>

        <!-- Accounts -->
        <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
               hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary dark:hover:text-primary
               {{ request()->is('admin/accounts*') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300' }}">
            <i class="ti ti-credit-card text-lg transition-colors group-hover:text-primary dark:group-hover:text-primary
                   {{ request()->is('admin/accounts*') ? 'text-white' : '' }}"></i>
            <span>Accounts</span>
        </a>

        <!-- Transactions -->
        <a href="{{ route('admin.transactions.index') }}" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
               hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary dark:hover:text-primary
               {{ request()->is('admin/transactions*') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300' }}">
            <i class="ti ti-transfer-in text-lg transition-colors group-hover:text-primary dark:group-hover:text-primary
                   {{ request()->is('admin/transactions*') ? 'text-white' : '' }}"></i>
            <span>Transactions</span>
        </a>

        <!-- Reports -->
        <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
               hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary dark:hover:text-primary
               {{ request()->is('admin/reports*') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300' }}">
            <i class="ti ti-file-analytics text-lg transition-colors group-hover:text-primary dark:group-hover:text-primary
                   {{ request()->is('admin/reports*') ? 'text-white' : '' }}"></i>
            <span>Reports</span>
        </a>

       <!-- Loans -->
<a href="{{ route('admin.loans.index') }}" 
   class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
          hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary dark:hover:text-primary
          {{ request()->routeIs('admin.loans*') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300' }}">
    <i class="ti ti-cash text-lg transition-colors group-hover:text-primary dark:group-hover:text-primary
           {{ request()->routeIs('admin.loans*') ? 'text-white' : '' }}"></i>
    <span>Loans</span>
</a>

<!-- Virtual Cards -->
<a href="{{ route('admin.virtual-cards.index') }}" 
   class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
          hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary dark:hover:text-primary
          {{ request()->routeIs('admin.virtual-cards*') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300' }}">
    <i class="ti ti-credit-card text-lg transition-colors group-hover:text-primary dark:group-hover:text-primary
           {{ request()->routeIs('admin.virtual-cards*') ? 'text-white' : '' }}"></i>
    <span>Virtual Cards</span>
</a>

        <!-- Settings -->
        <a href="#" class="group sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200
               hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary dark:hover:text-primary
               {{ request()->is('admin/settings*') ? 'sidebar-active bg-primary text-white hover:bg-primary/90' : 'text-gray-700 dark:text-gray-300' }}">
            <i class="ti ti-settings text-lg transition-colors group-hover:text-primary dark:group-hover:text-primary
                   {{ request()->is('admin/settings*') ? 'text-white' : '' }}"></i>
            <span>Settings</span>
        </a>
    </nav>

    <!-- User Info -->
    <div class="p-4 border-t dark:border-gray-700">
        <div class="flex items-center space-x-3 group">
            <div class="w-10 h-10 bg-gradient-to-br from-primary to-blue-600 rounded-full ring-2 ring-white dark:ring-gray-800 transition-transform group-hover:scale-110"></div>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-primary transition-colors">
                    @auth {{ Auth::user()->name }} @else Admin @endauth
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-primary/80 transition-colors">
                    @auth {{ Auth::user()->email }} @else admin@credify.com @endauth
                </p>
            </div>
        </div>
    </div>
</aside>
<style>
    /* Fancy Scrollbar - Works for both Admin & User Sidebars */
    .fancy-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 #f1f5f9;
    }

    .fancy-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .fancy-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 6px;
    }

    .fancy-scrollbar::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 6px;
        transition: background 0.3s ease;
    }

    .fancy-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    /* Dark Mode */
    .dark .fancy-scrollbar {
        scrollbar-color: #64748b #1f2937;
    }

    .dark .fancy-scrollbar::-webkit-scrollbar-track {
        background: #1f2937;
    }

    .dark .fancy-scrollbar::-webkit-scrollbar-thumb {
        background: #64748b;
    }

    .dark .fancy-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Smooth hover shine effect on sidebar links */
    .sidebar-link {
        position: relative;
        overflow: hidden;
    }

    .sidebar-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.15), transparent);
        transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-link:hover::before {
        left: 100%;
    }
</style>