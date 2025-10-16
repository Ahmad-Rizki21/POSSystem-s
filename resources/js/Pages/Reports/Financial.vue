<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    totalRevenue: Number,
    totalProfit: Number,
    totalCost: Number,
    totalTransactions: Number,
    profitMargin: Number,
    averageTransactionValue: Number,
    financialByPeriod: Array,
    paymentMethods: Array,
    cashierPerformance: Array,
    topProfitableProducts: Array,
    startDate: String,
    endDate: String,
    period: String
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

const formatPercentage = (value) => {
    return `${value.toFixed(2)}%`;
};

const getPeriodLabel = (period, value) => {
    if (period === 'daily') {
        const date = new Date(value);
        return date.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }) +
               ' <span class="text-xs text-gray-500">(' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ')</span>';
    } else if (period === 'weekly') {
        return `Week ${value} <span class="text-xs text-gray-500">(All day)</span>`;
    } else {
        return new Date(2024, value - 1, 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }) +
               ' <span class="text-xs text-gray-500">(Monthly)</span>';
    }
};

const formatDateTime = (dateTime) => {
    if (!dateTime) return 'N/A';
    const date = new Date(dateTime);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
};
</script>

<template>
    <Head title="Financial Report" />

    <AuthenticatedLayout :fullPage="true">
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('reports.index')" class="inline-flex items-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Financial Report
                        </h1>
                        <p class="mt-1 text-xs sm:text-sm text-gray-600">Analyze revenue, profit, costs, and financial performance</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button class="inline-flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                    <button class="inline-flex items-center px-3 sm:px-4 py-2 bg-gradient-to-r from-yellow-600 to-amber-600 text-white text-sm font-medium rounded-lg hover:from-yellow-700 hover:to-amber-700 transition-all duration-200 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="hidden sm:inline">Refresh</span>
                    </button>
                </div>
            </div>
        </template>

        <!-- Full Page Content -->
        <div class="h-full bg-gradient-to-br from-gray-50 via-white to-gray-50">
            <div class="h-full overflow-y-auto">
                <div class="max-w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                        <!-- Total Revenue -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-green-100 text-xs sm:text-sm font-medium truncate">Total Revenue</p>
                                        <p class="text-xl sm:text-3xl font-bold text-white mt-1 sm:mt-2 truncate">{{ formatCurrency(totalRevenue) }}</p>
                                    </div>
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white bg-opacity-20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 bg-green-50">
                                <p class="text-xs text-green-600">From {{ totalTransactions }} transactions</p>
                                <p class="text-xs text-green-500 mt-1">Updated: {{ formatDateTime(new Date()) }}</p>
                            </div>
                        </div>

                        <!-- Total Profit -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-blue-100 text-xs sm:text-sm font-medium truncate">Total Profit</p>
                                        <p class="text-xl sm:text-3xl font-bold text-white mt-1 sm:mt-2 truncate">{{ formatCurrency(totalProfit) }}</p>
                                    </div>
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white bg-opacity-20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 bg-blue-50">
                                <p class="text-xs text-blue-600">Profit Margin: {{ formatPercentage(profitMargin) }}</p>
                                <p class="text-xs text-blue-500 mt-1">Range: {{ startDate }} - {{ endDate }}</p>
                            </div>
                        </div>

                        <!-- Total Cost -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="bg-gradient-to-r from-orange-500 to-red-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-orange-100 text-xs sm:text-sm font-medium truncate">Total Cost</p>
                                        <p class="text-xl sm:text-3xl font-bold text-white mt-1 sm:mt-2 truncate">{{ formatCurrency(totalCost) }}</p>
                                    </div>
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white bg-opacity-20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 bg-orange-50">
                                <p class="text-xs text-orange-600">Cost of goods sold</p>
                            </div>
                        </div>

                        <!-- Average Transaction -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-purple-100 text-xs sm:text-sm font-medium truncate">Avg Transaction</p>
                                        <p class="text-xl sm:text-3xl font-bold text-white mt-1 sm:mt-2 truncate">{{ formatCurrency(averageTransactionValue) }}</p>
                                    </div>
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white bg-opacity-20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 bg-purple-50">
                                <p class="text-xs text-purple-600">Per transaction value</p>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Performance by Period -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-4 sm:px-8 py-4 sm:py-6">
                            <h2 class="text-lg sm:text-xl font-bold text-white">Financial Performance by {{ period.charAt(0).toUpperCase() + period.slice(1) }}</h2>
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-indigo-100">Revenue, profit, and cost trends over time</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period & Time</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Transactions</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Profit</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Cost</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Margin</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in financialByPeriod" :key="item.period" class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                            <div class="truncate max-w-[120px] sm:max-w-none" v-html="getPeriodLabel(period, item.period)"></div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                            {{ item.transaction_count }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 font-semibold">
                                            {{ formatCurrency(item.revenue) }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-green-600 font-medium hidden md:table-cell">
                                            {{ formatCurrency(item.profit) }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-red-600 hidden lg:table-cell">
                                            {{ formatCurrency(item.cost) }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm hidden xl:table-cell">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="item.revenue > 0 ? (item.profit / item.revenue * 100) > 20 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'">
                                                {{ formatPercentage(item.revenue > 0 ? (item.profit / item.revenue * 100) : 0) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Payment Methods -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-4 sm:px-8 py-4 sm:py-6">
                                <h2 class="text-lg sm:text-xl font-bold text-white">Payment Method Analysis</h2>
                                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-teal-100">Revenue breakdown by payment methods</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Transactions</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Average</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="method in paymentMethods" :key="method.payment_method" class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900 capitalize">
                                                {{ method.payment_method }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                                {{ method.transaction_count }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 font-semibold">
                                                {{ formatCurrency(method.revenue) }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden md:table-cell">
                                                {{ formatCurrency(method.average_transaction) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Top Profitable Products -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-green-600 px-4 sm:px-8 py-4 sm:py-6">
                                <h2 class="text-lg sm:text-xl font-bold text-white">Top Profitable Products</h2>
                                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-emerald-100">Products generating highest profit</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Sold</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Revenue</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="product in topProfitableProducts" :key="product.id" class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                                <div class="truncate max-w-[120px] sm:max-w-none">{{ product.name }}</div>
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                                {{ product.total_sold }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden md:table-cell">
                                                {{ formatCurrency(product.revenue) }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-green-600 font-semibold">
                                                {{ formatCurrency(product.profit) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Cashier Performance -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-4 sm:px-8 py-4 sm:py-6">
                            <h2 class="text-lg sm:text-xl font-bold text-white">Cashier Performance</h2>
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-pink-100">Revenue and profit performance by cashier</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cashier</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Transactions</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Profit</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Average</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="cashier in cashierPerformance" :key="cashier.id" class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                            <div class="truncate max-w-[120px] sm:max-w-none">{{ cashier.name }}</div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                            {{ cashier.transaction_count }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 font-semibold">
                                            {{ formatCurrency(cashier.revenue) }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-green-600 font-medium hidden md:table-cell">
                                            {{ formatCurrency(cashier.profit) }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                                            {{ formatCurrency(cashier.average_transaction) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>