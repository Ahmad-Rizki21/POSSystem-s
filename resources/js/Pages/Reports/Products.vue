<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    productPerformance: Array,
    categoryPerformance: Array,
    topSellingProducts: Array,
    lowStockProducts: Array,
    outOfStockProducts: Array,
    categories: Array,
    startDate: String,
    endDate: String,
    selectedCategory: String
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

const calculateProfitMargin = (revenue, profit) => {
    return revenue > 0 ? (profit / revenue) * 100 : 0;
};

const getStockStatus = (stock) => {
    if (stock <= 0) return { text: 'Out of Stock', color: 'red' };
    if (stock <= 10) return { text: 'Low Stock', color: 'yellow' };
    return { text: 'In Stock', color: 'green' };
};
</script>

<template>
    <Head title="Product Report" />

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
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            Product Report
                        </h1>
                        <p class="mt-1 text-xs sm:text-sm text-gray-600">Analyze product performance, inventory, and sales trends</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button class="inline-flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                    <button class="inline-flex items-center px-3 sm:px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
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
                        <!-- Total Products -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-blue-100 text-xs sm:text-sm font-medium truncate">Total Products</p>
                                        <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">{{ productPerformance.length }}</p>
                                    </div>
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white bg-opacity-20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 bg-blue-50">
                                <p class="text-xs text-blue-600">Active in catalog</p>
                            </div>
                        </div>

                        <!-- Low Stock -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="bg-gradient-to-r from-yellow-500 to-orange-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-yellow-100 text-xs sm:text-sm font-medium truncate">Low Stock</p>
                                        <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">{{ lowStockProducts.length }}</p>
                                    </div>
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white bg-opacity-20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 bg-yellow-50">
                                <p class="text-xs text-yellow-600">Need restocking</p>
                            </div>
                        </div>

                        <!-- Out of Stock -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="bg-gradient-to-r from-red-500 to-pink-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-red-100 text-xs sm:text-sm font-medium truncate">Out of Stock</p>
                                        <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">{{ outOfStockProducts.length }}</p>
                                    </div>
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white bg-opacity-20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 bg-red-50">
                                <p class="text-xs text-red-600">Unavailable</p>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-green-100 text-xs sm:text-sm font-medium truncate">Categories</p>
                                        <p class="text-2xl sm:text-3xl font-bold text-white mt-1 sm:mt-2">{{ categoryPerformance.length }}</p>
                                    </div>
                                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white bg-opacity-20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0 ml-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 bg-green-50">
                                <p class="text-xs text-green-600">Product categories</p>
                            </div>
                        </div>
                    </div>

                    <!-- Top Selling Products -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-4 sm:px-8 py-4 sm:py-6">
                            <h2 class="text-lg sm:text-xl font-bold text-white">Top Selling Products</h2>
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-indigo-100">Best performing products by quantity sold</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Category</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sold</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Revenue</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Profit</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Margin</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="product in topSellingProducts" :key="product.id" class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                            <div class="truncate max-w-[150px] sm:max-w-none">{{ product.name }}</div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden md:table-cell">
                                            {{ product.category_name || 'N/A' }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 font-semibold">
                                            {{ product.total_sold }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden lg:table-cell">
                                            {{ formatCurrency(product.total_revenue) }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-green-600 font-medium hidden xl:table-cell">
                                            {{ formatCurrency(product.total_profit) }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm hidden xl:table-cell">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="calculateProfitMargin(product.total_revenue, product.total_profit) > 20 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                                {{ formatPercentage(calculateProfitMargin(product.total_revenue, product.total_profit)) }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                                            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getStockStatus(product.stock).color === 'red' ? 'bg-red-100 text-red-800' : getStockStatus(product.stock).color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'">
                                                {{ product.stock }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Low Stock Alert -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-500 to-red-600 px-4 sm:px-8 py-4 sm:py-6">
                                <h2 class="text-lg sm:text-xl font-bold text-white">Low Stock Alert</h2>
                                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-orange-100">Products that need restocking</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="product in lowStockProducts" :key="product.id" class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                                <div class="truncate max-w-[150px] sm:max-w-none">{{ product.name }}</div>
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 font-semibold">
                                                {{ product.stock }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm hidden sm:table-cell">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Low Stock
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Category Performance -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-4 sm:px-8 py-4 sm:py-6">
                                <h2 class="text-lg sm:text-xl font-bold text-white">Category Performance</h2>
                                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-teal-100">Revenue and profit by category</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Products</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sold</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="category in categoryPerformance" :key="category.id" class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                                <div class="truncate max-w-[120px] sm:max-w-none">{{ category.name }}</div>
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                                {{ category.product_count }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 font-semibold">
                                                {{ category.total_sold }}
                                            </td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden md:table-cell">
                                                {{ formatCurrency(category.total_revenue) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Out of Stock Products -->
                    <div v-if="outOfStockProducts.length > 0" class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-500 to-pink-600 px-4 sm:px-8 py-4 sm:py-6">
                            <h2 class="text-lg sm:text-xl font-bold text-white">Out of Stock Products</h2>
                            <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-red-100">Products currently unavailable</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Category</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Last Updated</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="product in outOfStockProducts" :key="product.id" class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                            <div class="truncate max-w-[150px] sm:max-w-none">{{ product.name }}</div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden md:table-cell">
                                            {{ product.category?.name || 'N/A' }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                                            {{ new Date(product.updated_at).toLocaleDateString() }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                                            <Link :href="route('products.edit', product.id)" class="inline-flex items-center px-2 sm:px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-md hover:bg-blue-200 transition-colors duration-200">
                                                Restock
                                            </Link>
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