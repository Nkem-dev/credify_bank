@extends('layouts.user')

@section('content')
<div class="mt-16 max-w-4xl mx-auto px-4 py-20">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('user.settings.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary mr-3">
                <i class="ti ti-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Preferences</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Customize your experience</p>
            </div>
        </div>
    </div>

   

    <form method="POST" action="{{ route('user.settings.update.preferences') }}">
        @csrf

        <!-- Language Settings Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 mb-6">
            <div class="p-6 border-b dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-language text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Language</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Choose your preferred language</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($languages as $code => $lang)
                        <label class="relative cursor-pointer">
                            <input type="radio" 
                                   name="language" 
                                   value="{{ $code }}" 
                                   {{ $settings->language === $code ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 transition-all hover:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-3xl mr-3">{{ $lang['flag'] }}</span>
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white">{{ $lang['name'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ strtoupper($code) }}</p>
                                        </div>
                                    </div>
                                    <i class="ti ti-check text-2xl text-green-600 hidden peer-checked:block"></i>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('language')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Theme Settings Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 mb-6">
            <div class="p-6 border-b dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-palette text-2xl text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Theme</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Choose your display theme</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($themes as $theme_code => $theme_info)
                        <label class="relative cursor-pointer">
                            <input type="radio" 
                                   name="theme" 
                                   value="{{ $theme_code }}" 
                                   {{ $settings->theme === $theme_code ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-6 transition-all hover:border-indigo-500 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 text-center">
                                <i class="ti {{ $theme_info['icon'] }} text-4xl mb-3 text-gray-600 dark:text-gray-400 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400"></i>
                                <p class="font-bold text-gray-900 dark:text-white mb-1">{{ $theme_info['name'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($theme_code === 'light')
                                        Always light mode
                                    @elseif($theme_code === 'dark')
                                        Always dark mode
                                    @else
                                        Follows device setting
                                    @endif
                                </p>
                                <i class="ti ti-check text-xl text-indigo-600 mt-2 hidden peer-checked:block"></i>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('theme')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Currency Format Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 mb-6">
            <div class="p-6 border-b dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/20 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-currency-naira text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Currency Format</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Select your preferred currency display</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" 
                               name="currency_format" 
                               value="NGN" 
                               {{ $settings->currency_format === 'NGN' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 transition-all hover:border-yellow-500 peer-checked:border-yellow-600 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">Nigerian Naira</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">₦ NGN</p>
                                </div>
                                <i class="ti ti-check text-2xl text-yellow-600 hidden peer-checked:block"></i>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" 
                               name="currency_format" 
                               value="USD" 
                               {{ $settings->currency_format === 'USD' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 transition-all hover:border-yellow-500 peer-checked:border-yellow-600 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">US Dollar</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">$ USD</p>
                                </div>
                                <i class="ti ti-check text-2xl text-yellow-600 hidden peer-checked:block"></i>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" 
                               name="currency_format" 
                               value="EUR" 
                               {{ $settings->currency_format === 'EUR' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 transition-all hover:border-yellow-500 peer-checked:border-yellow-600 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">Euro</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">€ EUR</p>
                                </div>
                                <i class="ti ti-check text-2xl text-yellow-600 hidden peer-checked:block"></i>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" 
                               name="currency_format" 
                               value="GBP" 
                               {{ $settings->currency_format === 'GBP' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 transition-all hover:border-yellow-500 peer-checked:border-yellow-600 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">British Pound</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">£ GBP</p>
                                </div>
                                <i class="ti ti-check text-2xl text-yellow-600 hidden peer-checked:block"></i>
                            </div>
                        </div>
                    </label>
                </div>
                @error('currency_format')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                    <i class="ti ti-info-circle"></i> This is for display purposes only. All transactions are still in NGN.
                </p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit"
                    class="bg-primary hover:bg-indigo-700 text-white font-medium px-8 py-3 rounded-lg transition flex items-center">
                <i class="ti ti-check mr-2"></i> Save Preferences
            </button>
        </div>
    </form>

    <!-- Preview Card -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 mt-6">
        <h4 class="font-bold text-blue-900 dark:text-blue-200 mb-3 flex items-center">
            <i class="ti ti-eye mr-2"></i> Current Settings Preview
        </h4>
        <div class="text-sm text-blue-800 dark:text-blue-300 space-y-2">
            <div class="flex items-center justify-between">
                <span>Language:</span>
                <span class="font-bold">{{ $languages[$settings->language]['name'] }} {{ $languages[$settings->language]['flag'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span>Theme:</span>
                <span class="font-bold capitalize">{{ $settings->theme }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span>Currency:</span>
                <span class="font-bold">{{ $settings->currency_format }}</span>
            </div>
        </div>
    </div>
</div>
@endsection