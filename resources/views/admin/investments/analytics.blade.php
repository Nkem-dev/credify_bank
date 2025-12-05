@extends('layouts.admin')

@section('content')
<main class="flex-1 pt-16 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8 mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    Investment Analytics
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm md:text-base">
                    Performance metrics and insights
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.investments.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
                <button onclick="exportReport()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="ti ti-file-export"></i> Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- Investment Performance Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Investment Performance (Last 12 Months)</h3>
            <div class="flex items-center space-x-2">
                <span class="text-xs px-2 py-1 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">Total Invested</span>
                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Current Value</span>
                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Profit</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Month</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Invested</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Current Value</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Profit/Loss</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ROI %</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($monthlyData as $data)
                        @php
                            $roi = $data->invested > 0 ? (($data->profit / $data->invested) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($data->month . '-01')->format('M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right text-purple-600 dark:text-purple-400 font-semibold">
                                ₦{{ number_format($data->invested, 2) }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right text-blue-600 dark:text-blue-400 font-semibold">
                                ₦{{ number_format($data->value, 2) }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right font-semibold {{ $data->profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $data->profit >= 0 ? '+' : '' }}₦{{ number_format($data->profit, 2) }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roi >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $roi >= 0 ? '+' : '' }}{{ number_format($roi, 2) }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transaction Volume Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Volume (Last 12 Months)</h3>
            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Completed Transactions Only</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Month</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Transactions</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Volume</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Avg Transaction</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactionVolume as $volume)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($volume->month . '-01')->format('M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right text-gray-900 dark:text-white font-semibold">
                                {{ number_format($volume->count) }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right text-blue-600 dark:text-blue-400 font-semibold">
                                ₦{{ number_format($volume->volume, 2) }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right text-gray-900 dark:text-white">
                                ₦{{ number_format($volume->count > 0 ? $volume->volume / $volume->count : 0, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No transaction data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Total Growth -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 rounded-xl border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-trending-up text-3xl text-green-600 dark:text-green-400"></i>
            </div>
            <h3 class="text-xs text-green-700 dark:text-green-400 mb-1">Total Growth Rate</h3>
            <p class="text-2xl font-bold text-green-900 dark:text-green-100">
                @php
                    $totalInvested = $monthlyData->sum('invested');
                    $totalProfit = $monthlyData->sum('profit');
                    $growthRate = $totalInvested > 0 ? (($totalProfit / $totalInvested) * 100) : 0;
                @endphp
                {{ $growthRate >= 0 ? '+' : '' }}{{ number_format($growthRate, 2) }}%
            </p>
            <p class="text-xs text-green-600 dark:text-green-500 mt-2">Last 12 months</p>
        </div>

        <!-- Best Month -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-xl border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-trophy text-3xl text-blue-600 dark:text-blue-400"></i>
            </div>
            <h3 class="text-xs text-blue-700 dark:text-blue-400 mb-1">Best Month</h3>
            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                @php
                    $bestMonth = $monthlyData->sortByDesc('profit')->first();
                @endphp
                @if($bestMonth)
                    {{ \Carbon\Carbon::parse($bestMonth->month . '-01')->format('M Y') }}
                @else
                    N/A
                @endif
            </p>
            <p class="text-xs text-blue-600 dark:text-blue-500 mt-2">
                @if($bestMonth)
                    +₦{{ number_format($bestMonth->profit, 2) }} profit
                @endif
            </p>
        </div>

        <!-- Avg Monthly Volume -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-6 rounded-xl border border-purple-200 dark:border-purple-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-chart-bar text-3xl text-purple-600 dark:text-purple-400"></i>
            </div>
            <h3 class="text-xs text-purple-700 dark:text-purple-400 mb-1">Avg Monthly Volume</h3>
            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                @php
                    $avgVolume = $transactionVolume->count() > 0 ? $transactionVolume->avg('volume') : 0;
                @endphp
                ₦{{ number_format($avgVolume, 2) }}
            </p>
            <p class="text-xs text-purple-600 dark:text-purple-500 mt-2">
                {{ number_format($transactionVolume->avg('count')) }} transactions/month
            </p>
        </div>

        <!-- Total Transactions -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-6 rounded-xl border border-orange-200 dark:border-orange-800">
            <div class="flex items-center justify-between mb-3">
                <i class="ti ti-receipt text-3xl text-orange-600 dark:text-orange-400"></i>
            </div>
            <h3 class="text-xs text-orange-700 dark:text-orange-400 mb-1">Total Transactions</h3>
            <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">
                {{ number_format($transactionVolume->sum('count')) }}
            </p>
            <p class="text-xs text-orange-600 dark:text-orange-500 mt-2">Last 12 months</p>
        </div>
    </div>
</main>

@push('scripts')
<script>
    function exportReport() {
        window.location.href = '{{ route("admin.investments.analytics.export") }}';
    }
</script>
@endpush
@endsection