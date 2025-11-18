@extends('layouts.user')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 transition-colors duration-200">
    <div class="max-w-6xl mx-auto">

        {{-- Success / Error Alerts --}}
        {{-- @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl flex items-center gap-2 animate-fade-in">
                <i class="ti ti-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl flex items-center gap-2 animate-fade-in">
                <i class="ti ti-alert-circle text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif --}}

        {{-- Profile Header Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8 hover:shadow-2xl transition-all duration-300 mt-20">
            <div class="bg-credify-blue/5 dark:bg-credify-blue/10 p-8">
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        {{-- Profile Picture - FIXED + LIVE PREVIEW --}}
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-full overflow-hidden ring-4 ring-white dark:ring-gray-700 shadow-2xl">
                                <img id="profile-preview"
                                     src="{{ auth()->user()->profile_picture_url }}"
                                     alt="Profile"
                                     class="w-full h-full object-cover transition-transform group-hover:scale-105 duration-300">
                            </div>

                            <label for="profile_picture"
                                   class="absolute inset-0 flex items-center justify-center bg-black/60 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 cursor-pointer">
                                <i class="ti ti-camera text-white text-2xl"></i>
                            </label>

                            {{-- INPUT NOW INSIDE FORM --}}
                            <input type="file"
                                   id="profile_picture"
                                   name="profile_picture"
                                   class="hidden"
                                   accept="image/*"
                                   onchange="previewImage(event)">
                        </div>

                        {{-- Name & Email --}}
                        <div class="flex-1 text-center md:text-left">
                            <input type="text" name="name"
                                   value="{{ old('name', auth()->user()->display_name) }}"
                                   class="text-3xl font-bold text-gray-900 dark:text-white bg-transparent border-0 border-b-2 border-transparent focus:border-credify-blue outline-none w-full mb-3 transition-colors duration-200"
                                   placeholder="Your Name" required>

                            <input type="email" name="email"
                                   value="{{ old('email', auth()->user()->email) }}"
                                   class="text-lg text-gray-600 dark:text-gray-300 bg-transparent border-0 border-b-2 border-transparent focus:border-credify-blue outline-none w-full transition-colors duration-200"
                                   required>

                            {{-- <div class="mt-5">
                                <button type="submit" class="btn-primary">
                                    <i class="ti ti-device-floppy"></i>
                                    Save Changes
                                </button>
                            </div> --}}

                            <div class="mt-5">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary/90 transition-all shadow-lg hover:shadow-primary/20">
                                    <i class="ti ti-device-floppy"></i>
                                    Save Changes
                                </button>
                            </div>
                            

                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Main Grid: Personal Info + KYC --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Personal Information Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-7 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-credify-blue/10 dark:bg-credify-blue/20 rounded-xl flex items-center justify-center">
                        <i class="ti ti-user text-credify-blue text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Personal Information</h3>
                </div>

                <div class="space-y-5 text-sm">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Full Legal Name</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Date of Birth</span>
                        <span class="font-semibold text-credify-blue">
                            {{ auth()->user()->dob_formatted }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Registered Phone</span>
                        <span class="font-semibold text-credify-blue">
                            {{ auth()->user()->registration_phone }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Gender</span>
                        <div>
                            @if(auth()->user()->gender)
                                <span class="font-semibold capitalize text-gray-900 dark:text-white">
                                    {{ ucfirst(auth()->user()->gender) }}
                                </span>
                            @else
                                <form action="{{ route('user.profile.update') }}" method="POST" class="inline">
                                    @csrf
                                    <select name="gender"
                                            class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 focus:ring-2 focus:ring-credify-blue focus:outline-none transition-colors"
                                            onchange="this.form.submit()">
                                        <option value="">Select</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-600 dark:text-gray-400">Account Number</span>
                        <span class="font-mono font-bold text-credify-blue text-lg cursor-pointer select-all"
                              onclick="navigator.clipboard.writeText('{{ auth()->user()->account_number }}')">
                            {{ auth()->user()->account_number }}
                            <i class="ti ti-copy ml-1 text-sm opacity-70"></i>
                        </span>
                    </div>
                </div>

                {{-- Bio Section --}}
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">About You</label>
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        <textarea name="bio" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-credify-blue focus:outline-none transition-all duration-200"
                                  placeholder="Share a little about yourself...">{{ old('bio', auth()->user()->profile?->bio) }}</textarea>
                        <button type="submit" class="btn-secondary mt-3">
                            <i class="ti ti-check"></i> Save Bio
                        </button>
                    </form>
                </div>
            </div>

            {{-- KYC & Limits Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-7 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <i class="ti ti-shield-check text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">KYC & Transaction Limits</h3>
                </div>

                {{-- Current Tier Badge --}}
                <div class="p-5 bg-credify-blue/5 dark:bg-credify-blue/10 rounded-2xl mb-6 border border-credify-blue/20 dark:border-credify-blue/30">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Current Tier</p>
                            <p class="text-3xl font-bold text-credify-blue mt-1">{{ auth()->user()->tier_label }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Daily Limit</p>
                            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                ₦{{ number_format(auth()->user()->transaction_limit) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Upgrade Section --}}
                @if(auth()->user()->kyc_tier < 3)
                    <div class="p-5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl mb-6">
                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                            Upgrade to {{ auth()->user()->kyc_tier == 1 ? 'Tier 2' : 'Tier 3' }} for higher limits
                        </p>
                    </div>

                    <form action="{{ route('user.profile.upgrade') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        @if(auth()->user()->kyc_tier == 1)
                            <input type="text" name="nin" placeholder="Enter NIN (11 digits)" maxlength="11"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 focus:ring-2 focus:ring-credify-blue focus:outline-none transition-colors"
                                   required>
                        @elseif(auth()->user()->kyc_tier == 2)
                            <input type="text" name="address" placeholder="Full Home Address"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 focus:ring-2 focus:ring-credify-blue focus:outline-none mb-3 transition-colors"
                                   required>
                            <input type="file" name="address_proof" accept=".jpg,.png,.pdf"
                                   class="w-full px-4 py-3 border border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-credify-blue file:text-white file:font-medium hover:file:bg-credify-blue/90 transition-colors">
                        @endif

                                               <button type="submit"
                                class="w-full bg-primary text-white hover:bg-primary/90 transition-all shadow-lg hover:shadow-primary/20  py-3.5 rounded-xl font-semibold hover:from-primary/90 hover:to-emerald-500  flex items-center justify-center gap-2">
                            <i class="ti ti-arrow-up-right"></i>
                            Upgrade to {{ auth()->user()->kyc_tier == 1 ? 'Tier 2' : 'Tier 3' }}
                        </button>

                    </form>
                @else
                    <div class="text-center p-8 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-200 dark:border-emerald-800">
                        <i class="ti ti-circle-check-filled text-6xl text-emerald-600 dark:text-emerald-400 mb-3"></i>
                        <p class="text-xl font-bold text-emerald-700 dark:text-emerald-300">Fully Verified</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">You have access to all banking features</p>
                    </div>
                @endif

                {{-- KYC Status Summary --}}
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">NIN</span>
                        <span class="font-medium {{ auth()->user()->nin ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500' }}">
                            {{ auth()->user()->nin ? 'Verified' : 'Not Provided' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Address</span>
                        <span class="font-medium {{ auth()->user()->address ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500' }}">
                            {{ auth()->user()->address ? 'Verified' : 'Not Provided' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Verified On</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ auth()->user()->kyc_verified_at?->format('M d, Y') ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Live Preview Script --}}
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('profile-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

{{-- Custom Styles --}}
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }

    /* Reusable Buttons */
    .btn-primary {
        @apply inline-flex items-center gap-2 bg-credify-blue text-white px-6 py-3.5 rounded-xl font-semibold 
               transition-all duration-200 shadow-lg 
               hover:bg-credify-blue/90 hover:shadow-credify-blue/20 
               hover:scale-[1.02] hover:-translate-y-0.5;
    }
    .btn-secondary {
        @apply text-credify-blue font-medium text-sm hover:underline flex items-center gap-1 transition-colors duration-200;
    }

    /* Credify Brand */
    .bg-credify-blue { background-color: #0052CC; }
    .text-credify-blue { color: #0052CC; }
    .focus\:ring-credify-blue:focus { --tw-ring-color: #0052CC; }
    .focus\:border-credify-blue:focus { border-color: #0052CC; }
    .hover\:bg-credify-blue\/90:hover { background-color: #0047B3; }
    .hover\:shadow-credify-blue\/20:hover { 
        box-shadow: 0 10px 15px -3px rgba(0, 82, 204, 0.2), 0 4px 6px -2px rgba(0, 82, 204, 0.1);
    }
</style>
@endsection