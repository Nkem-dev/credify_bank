@extends('layouts.user')

@section('content')
<main class="flex-1 pt-16 p-6">
    <!-- Page Header -->
    <div class="mb-8 mt-10">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Transfer Money
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2">Send money to Credify Bank users or other banks instantly.</p>
    </div>

    <!-- Available Balance Card -->
    <div class="bg-white dark:bg-gray-800  shadow-sm dark:border-gray-700 overflow-hidden hover:shadow-lg p-6 rounded-xl border  mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Available Balance</p>
                <p class="text-3xl font-bold text-primary font-mono">
                    ₦{{ number_format(Auth::user()->balance ?? 0, 2) }}
                </p>
            </div>
            <i class="ti ti-wallet text-5xl text-primary/30"></i>
        </div>
    </div>

    <!-- Transfer Type Selection -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Internal Transfer Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 cursor-pointer group" onclick="showInternalTransfer()">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="ti ti-building-bank text-4xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Credify Bank Transfer</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Send money to other Credify Bank users instantly
                </p>
                <div class="flex items-center justify-center space-x-2 text-sm text-primary font-medium">
                    <i class="ti ti-bolt"></i>
                    <span>Instant & Free</span>
                </div>
            </div>
            <div class="bg-primary/5 dark:bg-primary/10 p-4 text-center">
                <a  href="{{ route('user.transfers.internal.create') }}" class="text-primary font-semibold hover:underline">
                    Start Transfer <i class="ti ti-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- External Transfer Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 cursor-pointer group" onclick="showExternalTransfer()">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i class="ti ti-building text-4xl text-accent"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Other Banks Transfer</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Transfer to any Nigerian bank account
                </p>
                <div class="flex items-center justify-center space-x-2 text-sm text-accent font-medium">
                    <i class="ti ti-bolt"></i>
                    <span>Charges applied</span>
                </div>
            </div>
            <div class="bg-accent/5 dark:bg-accent/10 p-4 text-center">
                <a  href="{{ route('user.transfers.external.create') }}" class="text-accent font-semibold hover:underline">
                    Start Transfer <i class="ti ti-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

   
</main>

@push('scripts')

@endpush
@endsection