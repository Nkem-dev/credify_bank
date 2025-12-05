@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-6">
    <div class="max-w-3xl mx-auto mt-10">

        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.customer-care.index') }}"
               class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <i class="ti ti-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Customer Care Staff</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Update {{ $customerCare->name }}'s information</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-8">
            <form action="{{ route('admin.customer-care.update', $customerCare) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $customerCare->name) }}"
                               required
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:text-white"
                               placeholder="Enter full name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-600">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $customerCare->email) }}"
                               required
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:text-white"
                               placeholder="staff@credifybank.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phone Number <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $customerCare->phone) }}"
                               required
                               maxlength="10"
                               pattern="[0-9]{10}"
                               inputmode="numeric"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:text-white"
                               placeholder="7038599749">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <i class="ti ti-info-circle"></i> Enter 10-digit phone number without country code
                        </p>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Date of Birth <span class="text-red-600">*</span>
                        </label>
                        <input type="date" 
                               id="dob" 
                               name="dob" 
                               value="{{ old('dob', $customerCare->dob ? $customerCare->dob->format('Y-m-d') : '') }}"
                               required
                               max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:text-white"
                               placeholder="Select date of birth">
                        @error('dob')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <i class="ti ti-info-circle"></i> Staff must be at least 18 years old
                        </p>
                    </div>

                    <!-- Account Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Status <span class="text-red-600">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:text-white">
                            <option value="active" {{ old('status', $customerCare->status) === 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status', $customerCare->status) === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                            <option value="suspended" {{ old('status', $customerCare->status) === 'suspended' ? 'selected' : '' }}>
                                Suspended
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <i class="ti ti-info-circle"></i> Inactive or suspended accounts cannot login
                        </p>
                    </div>

                    <!-- Account Details (Read-only) -->
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Account Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Account Number</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $customerCare->account_number }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Role</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    <span class="px-2 py-1 bg-primary/10 text-primary rounded text-xs">
                                        {{ ucfirst(str_replace('_', ' ', $customerCare->role)) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Joined Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $customerCare->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Last Updated</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $customerCare->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Box -->
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <div class="flex gap-3">
                            <i class="ti ti-alert-triangle text-yellow-600 dark:text-yellow-400 text-xl flex-shrink-0"></i>
                            <div class="text-sm text-yellow-900 dark:text-yellow-300">
                                <p class="font-medium mb-1">Important Notes:</p>
                                <ul class="list-disc list-inside space-y-1 text-yellow-800 dark:text-yellow-400">
                                    <li>Changes will take effect immediately</li>
                                    <li>The staff member will need to re-login if their account was suspended</li>
                                    <li>To reset the password, use the "Reset Password" button from the staff list</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3 mt-8">
                    <a href="{{ route('admin.customer-care.index') }}"
                       class="flex-1 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="flex-1 px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg transition font-medium">
                        <i class="ti ti-device-floppy mr-2"></i>
                        Update Staff Account
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<script>
    // Phone number validation - only allow numbers
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to 10 digits
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });

    // Prevent non-numeric characters on keypress
    document.getElementById('phone').addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight' && e.key !== 'Tab') {
            e.preventDefault();
        }
    });
</script>
@endsection