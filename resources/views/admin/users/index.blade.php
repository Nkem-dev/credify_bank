@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                User Management
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Manage all registered users on the platform</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            
            <!-- Total Users -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-primary/10 rounded-lg">
                        <i class="ti ti-users text-2xl text-primary"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($total) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Users</p>
            </div>

            <!-- Active Users -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="ti ti-user-check text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($active) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Active</p>
            </div>

            <!-- Inactive Users -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <i class="ti ti-user-x text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($inactive) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Inactive</p>
            </div>

            <!-- Suspended Users -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <i class="ti ti-user-off text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ number_format($suspended) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Suspended</p>
            </div>

            <!-- Admins -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <i class="ti ti-shield text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($admins) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Admins</p>
            </div>

        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Name, email, account number..."
                           class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <!-- Tier Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tier</label>
                    <select name="tier" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">All Tiers</option>
                        <option value="basic" {{ request('tier') == 'tier 1' ? 'selected' : '' }}>Tier 1</option>
                        <option value="silver" {{ request('tier') == 'tier 2' ? 'selected' : '' }}>Tier 2</option>
                        <option value="gold" {{ request('tier') == 'tier 3' ? 'selected' : '' }}>Tier 3</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="ti ti-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="ti ti-x"></i>
                    </a>
                </div>

            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Account</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Balance</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tier</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Joined</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <!-- User Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                            <span class="text-primary font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Account Number -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-mono text-gray-900 dark:text-white">{{ $user->account_number }}</p>
                                </td>

                                <!-- Balance -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-primary">₦{{ number_format($user->balance, 2) }}</p>
                                </td>

                                <!-- Tier -->
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($user->tier == 'diamond') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                        @elseif($user->tier == 'platinum') bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                        @elseif($user->tier == 'gold') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($user->tier == 'silver') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                        @else bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @endif">
                                        {{ ucfirst($user->tier ?? 'tier1') }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($user->status == 'active') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($user->status == 'suspended') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400
                                        @endif">
                                        {{ ucfirst($user->status ?? 'active') }}
                                    </span>
                                </td>

                                <!-- Joined Date -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y') }}</p>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="text-primary hover:text-primary/80 font-medium text-sm">
                                        View Details →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-users text-4xl mb-3 block"></i>
                                    <p>No users found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 border-t dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
@endsection