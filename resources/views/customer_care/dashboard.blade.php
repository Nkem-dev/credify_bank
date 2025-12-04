@extends('layouts.customer_care')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Welcome Message -->
    <div class="mb-6 md:mb-8 mt-20">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            Welcome, 
            <span class="text-primary">{{ Auth::user()->name }}</span>
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
            Monitor and manage customer support operations
        </p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Tickets -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-ticket text-xl md:text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <span class="text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded-full">
                    Today
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Total Tickets</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">24</p>
            <div class="mt-3 flex items-center text-xs">
                <span class="text-green-600 dark:text-green-400 flex items-center">
                    <i class="ti ti-arrow-up text-sm mr-1"></i>
                    12%
                </span>
                <span class="text-gray-500 dark:text-gray-400 ml-2">vs yesterday</span>
            </div>
        </div>

        <!-- Pending Tickets -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-clock text-xl md:text-2xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <span class="text-xs bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 px-2 py-1 rounded-full">
                    Urgent
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Pending Tickets</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">8</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                Requires attention
            </p>
        </div>

        <!-- Resolved Today -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-check text-xl md:text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                <span class="text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded-full">
                    Completed
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Resolved Today</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">16</p>
            <div class="mt-3 flex items-center text-xs">
                <span class="text-green-600 dark:text-green-400 flex items-center">
                    <i class="ti ti-arrow-up text-sm mr-1"></i>
                    8%
                </span>
                <span class="text-gray-500 dark:text-gray-400 ml-2">vs yesterday</span>
            </div>
        </div>

        <!-- Average Response Time -->
        <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow-sm border dark:border-gray-700 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <i class="ti ti-hourglass text-xl md:text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
                <span class="text-xs bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 px-2 py-1 rounded-full">
                    Avg
                </span>
            </div>
            <h3 class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1">Response Time</h3>
            <p class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">2.5h</p>
            <div class="mt-3 flex items-center text-xs">
                <span class="text-green-600 dark:text-green-400 flex items-center">
                    <i class="ti ti-arrow-down text-sm mr-1"></i>
                    15%
                </span>
                <span class="text-gray-500 dark:text-gray-400 ml-2">faster than avg</span>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Customer Satisfaction -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 md:p-6 rounded-xl shadow-sm border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between mb-4">
                <i class="ti ti-star text-2xl md:text-3xl text-green-600 dark:text-green-400"></i>
                <span class="text-xs font-medium text-green-700 dark:text-green-400">This Month</span>
            </div>
            <h3 class="text-xs md:text-sm text-green-700 dark:text-green-400 mb-1">Customer Satisfaction</h3>
            <p class="text-xl md:text-2xl font-bold text-green-800 dark:text-green-300">4.8/5.0</p>
            <p class="text-xs text-green-600 dark:text-green-500 mt-2">Based on 156 ratings</p>
        </div>

        <!-- Active Chats -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 md:p-6 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between mb-4">
                <i class="ti ti-messages text-2xl md:text-3xl text-blue-600 dark:text-blue-400"></i>
                <span class="text-xs font-medium text-blue-700 dark:text-blue-400">Live Now</span>
            </div>
            <h3 class="text-xs md:text-sm text-blue-700 dark:text-blue-400 mb-1">Active Chats</h3>
            <p class="text-xl md:text-2xl font-bold text-blue-800 dark:text-blue-300">12</p>
            <p class="text-xs text-blue-600 dark:text-blue-500 mt-2">3 awaiting response</p>
        </div>

        <!-- Knowledge Base Views -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 p-4 md:p-6 rounded-xl shadow-sm border border-indigo-200 dark:border-indigo-800">
            <div class="flex items-center justify-between mb-4">
                <i class="ti ti-book text-2xl md:text-3xl text-indigo-600 dark:text-indigo-400"></i>
                <span class="text-xs font-medium text-indigo-700 dark:text-indigo-400">This Week</span>
            </div>
            <h3 class="text-xs md:text-sm text-indigo-700 dark:text-indigo-400 mb-1">KB Article Views</h3>
            <p class="text-xl md:text-2xl font-bold text-indigo-800 dark:text-indigo-300">1,248</p>
            <p class="text-xs text-indigo-600 dark:text-indigo-500 mt-2">Top: Password Reset</p>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Recent Tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="p-4 md:p-6 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white">Recent Support Tickets</h3>
                <a href="#" class="text-primary text-xs md:text-sm font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[400px] overflow-y-auto fancy-scrollbar">
                <!-- Ticket 1 -->
                <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-2 md:space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold flex-shrink-0 text-xs md:text-sm">
                                JD
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-xs md:text-sm truncate">John Doe</h4>
                                    <span class="px-2 py-0.5 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded flex-shrink-0">Pending</span>
                                </div>
                                <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-1">Unable to complete transaction - Error code 502</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <i class="ti ti-ticket"></i>
                                        #TKT-1234
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="ti ti-clock"></i>
                                        5 mins ago
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button class="ml-2 p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex-shrink-0">
                            <i class="ti ti-message-circle text-primary text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Ticket 2 -->
                <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-2 md:space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold flex-shrink-0 text-xs md:text-sm">
                                SA
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-xs md:text-sm truncate">Sarah Anderson</h4>
                                    <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded flex-shrink-0">In Progress</span>
                                </div>
                                <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-1">Account verification documents review needed</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <i class="ti ti-ticket"></i>
                                        #TKT-1233
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="ti ti-clock"></i>
                                        15 mins ago
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button class="ml-2 p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex-shrink-0">
                            <i class="ti ti-message-circle text-primary text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Ticket 3 -->
                <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-2 md:space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold flex-shrink-0 text-xs md:text-sm">
                                MJ
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-xs md:text-sm truncate">Michael Johnson</h4>
                                    <span class="px-2 py-0.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded flex-shrink-0">Resolved</span>
                                </div>
                                <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-1">Password reset request completed successfully</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <i class="ti ti-ticket"></i>
                                        #TKT-1232
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="ti ti-clock"></i>
                                        1 hour ago
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button class="ml-2 p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex-shrink-0">
                            <i class="ti ti-message-circle text-gray-400 text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Ticket 4 -->
                <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-2 md:space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white font-bold flex-shrink-0 text-xs md:text-sm">
                                EW
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-xs md:text-sm truncate">Emma Wilson</h4>
                                    <span class="px-2 py-0.5 text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded flex-shrink-0">Urgent</span>
                                </div>
                                <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-1">Unauthorized transaction detected on account</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <i class="ti ti-ticket"></i>
                                        #TKT-1231
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="ti ti-clock"></i>
                                        2 hours ago
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button class="ml-2 p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition flex-shrink-0">
                            <i class="ti ti-message-circle text-primary text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Customers -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="p-4 md:p-6 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white">Recent Customer Interactions</h3>
                <a href="#" class="text-primary text-xs md:text-sm font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[400px] overflow-y-auto fancy-scrollbar">
                <!-- Customer 1 -->
                <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 md:space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-primary font-semibold text-xs md:text-sm">AD</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 dark:text-white text-xs md:text-sm truncate">Alice Davis</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">alice.davis@email.com</p>
                            </div>
                        </div>
                        <div class="text-right ml-2 flex-shrink-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">10 mins ago</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                Inquiry
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer 2 -->
                <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 md:space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-primary font-semibold text-xs md:text-sm">BT</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 dark:text-white text-xs md:text-sm truncate">Bob Taylor</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">bob.taylor@email.com</p>
                            </div>
                        </div>
                        <div class="text-right ml-2 flex-shrink-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">25 mins ago</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                Resolved
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer 3 -->
                <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 md:space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-primary font-semibold text-xs md:text-sm">CM</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 dark:text-white text-xs md:text-sm truncate">Carol Martinez</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">carol.m@email.com</p>
                            </div>
                        </div>
                        <div class="text-right ml-2 flex-shrink-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">1 hour ago</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Waiting
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer 4 -->
                <div class="p-3 md:p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 md:space-x-3 flex-1 min-w-0">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-primary font-semibold text-xs md:text-sm">DW</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 dark:text-white text-xs md:text-sm truncate">David White</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">d.white@email.com</p>
                            </div>
                        </div>
                        <div class="text-right ml-2 flex-shrink-0">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">2 hours ago</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                Follow-up
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection