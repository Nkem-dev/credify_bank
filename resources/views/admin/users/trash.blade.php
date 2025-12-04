{{-- resources/views/admin/users/trash.blade.php --}}
@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-7xl mx-auto mt-10">

        

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}"
                   class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Deleted Users</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage trashed user accounts</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg font-semibold">
                    {{ $trashedUsers->total() }} Deleted
                </span>
            </div>
        </div>

        @if($trashedUsers->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-16 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="ti ti-trash-off text-5xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Deleted Users</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">There are no users in the trash.</p>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition">
                    <i class="ti ti-arrow-left"></i>
                    Back to Users
                </a>
            </div>
        @else
            <!-- Deleted Users Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700">
                <div class="overflow-x-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Account Number</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Balance</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deleted At</th>
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($trashedUsers as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center text-white font-bold flex-shrink-0">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-sm text-gray-900 dark:text-white">{{ $user->account_number }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-gray-900 dark:text-white">₦{{ number_format($user->balance, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full 
                                            @if($user->status === 'active') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($user->status === 'suspended') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <p class="text-gray-900 dark:text-white font-medium">{{ $user->deleted_at->format('M d, Y') }}</p>
                                            <p class="text-gray-500 dark:text-gray-400">{{ $user->deleted_at->format('h:i A') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Restore Button -->
                                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50 transition"
                                                        title="Restore User">
                                                    <i class="ti ti-restore text-lg"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Permanent Delete Button -->
                                            <button onclick='openForceDeleteModal({{ $user->id }}, "{{ addslashes($user->name) }}", "{{ route('admin.users.force-delete', $user->id) }}")'
                                                    class="p-2 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition"
                                                    title="Permanently Delete">
                                                <i class="ti ti-trash-x text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($trashedUsers->hasPages())
                    <div class="px-6 py-4 border-t dark:border-gray-700">
                        {{ $trashedUsers->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</main>

{{-- Force Delete Modal --}}
<div id="forceDeleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="ti ti-trash-x text-xl text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Permanently Delete User</h3>
            </div>
            <button onclick="closeForceDeleteModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ti ti-x text-xl"></i>
            </button>
        </div>
        <form id="forceDeleteForm" method="POST" action="{{ route('admin.users.force-delete', ':id') }}">
            @csrf
            @method('DELETE')
            <div class="space-y-4">
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="ti ti-alert-triangle text-2xl text-red-600 dark:text-red-400 mt-0.5"></i>
                        <div>
                            <p class="font-bold text-red-900 dark:text-red-300 mb-2">⚠️ WARNING: This action is irreversible!</p>
                            <p class="text-sm text-red-800 dark:text-red-300">
                                Permanently deleting this user will:
                            </p>
                            <ul class="text-sm text-red-800 dark:text-red-300 mt-2 space-y-1 list-disc list-inside">
                                <li>Remove all user data from the database</li>
                                <li>Delete all associated transactions</li>
                                <li>Remove savings and loan records</li>
                                <li>This <strong>CANNOT be undone</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400">
                    Are you absolutely sure you want to permanently delete <strong id="userNameDisplay"></strong>?
                </p>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeForceDeleteModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                    Delete Permanently
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openForceDeleteModal(userId, userName) {
        const modal = document.getElementById('forceDeleteModal');
        const form = document.getElementById('forceDeleteForm');
        const userNameDisplay = document.getElementById('userNameDisplay');
        
        // Set the form action
        form.action = `/admin/users/${userId}/force-delete`;
        
        // Set the user name in the modal
        userNameDisplay.textContent = userName;
        
        // Show modal
        modal.classList.remove('hidden');
    }

    function closeForceDeleteModal() {
        document.getElementById('forceDeleteModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('forceDeleteModal').addEventListener('click', (e) => {
        if (e.target.id === 'forceDeleteModal') {
            closeForceDeleteModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeForceDeleteModal();
        }
    });
</script>
@endpush

@endsection