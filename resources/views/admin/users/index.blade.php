{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">

        <!-- Header + Trashed Users Link -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">User Management</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Manage all registered users on the platform</p>
            </div>
            <a href="{{ route('admin.users.trash') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <i class="ti ti-trash text-lg"></i>
                Trashed Users
                <span class="font-semibold">({{ \App\Models\User::onlyTrashed()->count() }})</span>
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="p-3 bg-primary/10 rounded-lg w-fit"><i class="ti ti-users text-2xl text-primary"></i></div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-3">{{ number_format($total) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Users</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg w-fit"><i class="ti ti-user-check text-2xl text-green-600 dark:text-green-400"></i></div>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-3">{{ number_format($active) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Active</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg w-fit"><i class="ti ti-user-x text-2xl text-yellow-600 dark:text-yellow-400"></i></div>
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-3">{{ number_format($inactive) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Inactive</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg w-fit"><i class="ti ti-user-off text-2xl text-red-600 dark:text-red-400"></i></div>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-3">{{ number_format($suspended) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Suspended</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg w-fit"><i class="ti ti-shield text-2xl text-purple-600 dark:text-purple-400"></i></div>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-3">{{ number_format($admins) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Admins</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, account..."
                           class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary">
                </div>
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                        <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Suspended</option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tier</label>
                    <select name="tier" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <option value="">All</option>
                        <option value="tier 1" {{ request('tier')=='tier 1'?'selected':'' }}>Tier 1</option>
                        <option value="tier 2" {{ request('tier')=='tier 2'?'selected':'' }}>Tier 2</option>
                        <option value="tier 3" {{ request('tier')=='tier 3'?'selected':'' }}>Tier 3</option>
                    </select>
                </div>
                <div class="flex items-end gap-2 lg:col-span-2">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-white py-2 px-5 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="ti ti-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white p-2 rounded-lg transition">
                        <i class="ti ti-x"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Users Table  -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto fancy-scrollbar">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Account</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Balance</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tier</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition {{ $user->trashed() ? 'opacity-60' : '' }}">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex-shrink-0 {{ $user->trashed() ? 'bg-red-100 dark:bg-red-900/30' : 'bg-primary/10' }} flex items-center justify-center">
                                            <span class="font-bold text-lg {{ $user->trashed() ? 'text-red-600 dark:text-red-400' : 'text-primary' }}">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate {{ $user->trashed() ? 'line-through' : '' }}">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-sm font-mono text-gray-900 dark:text-white">
                                    {{ $user->account_number }}
                                </td>

                                <td class="px-4 py-4 text-sm font-bold text-primary">
                                    ₦{{ number_format($user->balance, 2) }}
                                </td>

                                <td class="px-4 py-4">
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full text-white
                                        @if($user->tier === 'tier 3') bg-gradient-to-r from-purple-600 to-pink-600
                                        @elseif($user->tier === 'tier 2') bg-gradient-to-r from-blue-600 to-cyan-600
                                        @elseif($user->tier === 'tier 1') bg-gradient-to-r from-green-600 to-emerald-600
                                        @else bg-gray-500 @endif">
                                        {{ ucwords(str_replace('_', ' ', $user->tier ?? 'tier1')) }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                            @if($user->status === 'active') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($user->status === 'suspended') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                            @elseif($user->status === 'inactive') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                                            {{ ucfirst($user->status ?? 'inactive') }}
                                        </span>
                                        @if($user->trashed())
                                            <span class="px-2 py-1 text-xs bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-full font-medium">
                                                Trashed
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>

                                <!-- ACTIONS - FIXED WIDTH, NO OVERFLOW -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-4 text-lg">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="text-primary hover:text-primary/80 transition"
                                           title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        @if(!$user->trashed())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Move {{ addslashes($user->name) }} to trash?')"
                                                        class="text-red-600 hover:text-red-800 transition"
                                                        title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.restore', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-800 transition"
                                                        title="Restore">
                                                    <i class="ti ti-restore"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-users text-6xl mb-4 block opacity-50"></i>
                                    <p class="text-lg font-medium">No users found</p>
                                    <p class="text-sm mt-1">Try adjusting your filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t dark:border-gray-700">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</main>

<style>
    /* Fancy Scrollbar */
    .fancy-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 #f1f5f9;
    }

    .fancy-scrollbar::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }

    .fancy-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .fancy-scrollbar::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 3px;
        transition: background 0.3s ease;
    }

    .fancy-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

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
</style>
@endsection