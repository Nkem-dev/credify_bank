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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Customer Care Staff</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Create a new customer care account</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-8">
            <form action="{{ route('admin.customer-care.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
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
                               value="{{ old('email') }}"
                               required
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:text-white"
                               placeholder="staff@credifybank.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <i class="ti ti-info-circle"></i> Login credentials will be sent to this email
                        </p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phone Number <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
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
                               value="{{ old('dob') }}"
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

                    <!-- Info Box -->
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex gap-3">
                            <i class="ti ti-info-circle text-blue-600 dark:text-blue-400 text-xl flex-shrink-0"></i>
                            <div class="text-sm text-blue-900 dark:text-blue-300">
                                <p class="font-medium mb-1">What happens next:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-800 dark:text-blue-400">
                                    <li>A secure random password will be generated</li>
                                    <li>Login credentials will be emailed to the staff member</li>
                                    <li>The staff can login and access the customer care dashboard</li>
                                    <li>You can reset their password anytime from the staff list</li>
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
                        <i class="ti ti-user-plus mr-2"></i>
                        Create Staff Account
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