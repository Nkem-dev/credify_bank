@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    Add New Stock
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    Create a new stock for investment
                </p>
            </div>
            <a href="{{ route('admin.investments.stocks.all') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="ti ti-arrow-left"></i> Back to Stocks
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.investments.stocks.store') }}" method="POST" class="max-w-4xl">
        @csrf
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
            <!-- Basic Information -->
            <div class="p-6 border-b dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock Symbol *</label>
                        <input type="text" name="symbol" value="{{ old('symbol') }}" required 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white @error('symbol') border-red-500 @enderror"
                               placeholder="e.g., AAPL">
                        @error('symbol')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                               placeholder="e.g., Apple Inc.">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                        <select name="category" required 
                                class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="technology" {{ old('category') == 'technology' ? 'selected' : '' }}>Technology</option>
                            <option value="finance" {{ old('category') == 'finance' ? 'selected' : '' }}>Finance</option>
                            <option value="healthcare" {{ old('category') == 'healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="energy" {{ old('category') == 'energy' ? 'selected' : '' }}>Energy</option>
                            <option value="consumer" {{ old('category') == 'consumer' ? 'selected' : '' }}>Consumer</option>
                            <option value="industrial" {{ old('category') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="real_estate" {{ old('category') == 'real_estate' ? 'selected' : '' }}>Real Estate</option>
                            <option value="telecommunications" {{ old('category') == 'telecommunications' ? 'selected' : '' }}>Telecommunications</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo URL (Optional)</label>
                        <input type="text" name="logo" value="{{ old('logo') }}" 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                               placeholder="https://example.com/logo.png">
                    </div>
                </div>
            </div>

            <!-- Price Information -->
            <div class="p-6 border-b dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Price Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Price (₦) *</label>
                        <input type="number" name="current_price" value="{{ old('current_price') }}" step="0.01" required 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white @error('current_price') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('current_price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Opening Price (₦) *</label>
                        <input type="number" name="opening_price" value="{{ old('opening_price') }}" step="0.01" required 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white @error('opening_price') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('opening_price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Day High (₦)</label>
                        <input type="number" name="day_high" value="{{ old('day_high') }}" step="0.01" 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Day Low (₦)</label>
                        <input type="number" name="day_low" value="{{ old('day_low') }}" step="0.01" 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">52 Week High (₦)</label>
                        <input type="number" name="week_high" value="{{ old('week_high') }}" step="0.01" 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">52 Week Low (₦)</label>
                        <input type="number" name="week_low" value="{{ old('week_low') }}" step="0.01" 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                               placeholder="0.00">
                    </div>
                </div>
            </div>

            <!-- Market Information -->
            <div class="p-6 border-b dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Market Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Market Cap (₦)</label>
                        <input type="number" name="market_cap" value="{{ old('market_cap') }}" step="0.01" 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                               placeholder="0.00">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total market value of the company</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Volume</label>
                        <input type="number" name="volume" value="{{ old('volume') }}" 
                               class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                               placeholder="0">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Number of shares traded</p>
                    </div>
                </div>
            </div>

            <!-- Description & Status -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Details</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                                  placeholder="Brief description about the stock...">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Activate stock immediately (users can trade this stock)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end space-x-3">
                <a href="{{ route('admin.investments.stocks.all') }}" 
                   class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    <i class="ti ti-plus"></i> Create Stock
                </button>
            </div>
        </div>
    </form>
</main>
@endsection