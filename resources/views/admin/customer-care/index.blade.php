@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Customer Care Staff</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage customer care team members</p>
            </div>
            <a href="{{ route('admin.customer-care.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                <i class="ti ti-plus"></i>
                Add New Staff
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <i class="ti ti-users text-3xl text-primary mb-4"></i>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Staff</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $customerCareStaff->total() }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <i class="ti ti-user-check text-3xl text-green-600 dark:text-green-400 mb-4"></i>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Active Staff</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $customerCareStaff->where('status', 'active')->count() }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
                <i class="ti ti-user-off text-3xl text-red-600 dark:text-red-400 mb-4"></i>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Inactive Staff</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $customerCareStaff->where('status', '!=', 'active')->count() }}</p>
            </div>
        </div>

        <!-- Staff Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="overflow-x-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($customerCareStaff as $staff)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold">
                                            {{ substr($staff->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $staff->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $staff->account_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $staff->email }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $staff->phone }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full 
                                        {{ $staff->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ ucfirst($staff->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $staff->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.customer-care.edit', $staff) }}" 
                                           class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition"
                                           title="Edit">
                                            <i class="ti ti-edit text-lg"></i>
                                        </a>
                                        <form action="{{ route('admin.customer-care.reset-password', $staff) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition"
                                                    onclick="return confirm('Reset password for {{ $staff->name }}? New credentials will be emailed.')"
                                                    title="Reset Password">
                                                <i class="ti ti-lock-open text-lg"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.customer-care.destroy', $staff) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition"
                                                    onclick="return confirm('Are you sure you want to delete {{ $staff->name }}?')"
                                                    title="Delete">
                                                <i class="ti ti-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <i class="ti ti-users-off text-5xl mb-3 opacity-50"></i>
                                    <p class="font-medium">No customer care staff found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($customerCareStaff->hasPages())
                <div class="px-6 py-4 border-t dark:border-gray-700">
                    {{ $customerCareStaff->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
@endsection