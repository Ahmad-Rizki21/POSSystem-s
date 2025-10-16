<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    auth: Object,
    stats: Object,
    recentTransactions: Array,
    topProducts: Array
});

const stats = ref(props.stats || {
    totalProducts: 0,
    totalCategories: 0,
    totalTransactions: 0,
    todayRevenue: 0
});

// Stats are already provided via Inertia props, no need to fetch via API

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Welcome back, {{ auth.user.name }}! Here's what's happening with your store today.
                    </p>
                </div>
                <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ new Date().toLocaleDateString() }}
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <!-- Total Products Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                <dd class="text-2xl font-bold text-gray-900 mt-1">{{ stats.totalProducts }}</dd>
                                <div class="flex items-center mt-2 text-xs">
                                    <span class="text-green-600 font-medium">+12%</span>
                                    <span class="text-gray-500 ml-1">from last month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
                                <dd class="text-2xl font-bold text-gray-900 mt-1">{{ stats.totalCategories }}</dd>
                                <div class="flex items-center mt-2 text-xs">
                                    <span class="text-blue-600 font-medium">+5%</span>
                                    <span class="text-gray-500 ml-1">from last month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Transactions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="text-sm font-medium text-gray-500 truncate">Today's Transactions</dt>
                                <dd class="text-2xl font-bold text-gray-900 mt-1">{{ stats.totalTransactions }}</dd>
                                <div class="flex items-center mt-2 text-xs">
                                    <span class="text-green-600 font-medium">+8%</span>
                                    <span class="text-gray-500 ml-1">from yesterday</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Revenue Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <dt class="text-sm font-medium text-gray-500 truncate">Today's Revenue</dt>
                                <dd class="text-2xl font-bold text-gray-900 mt-1">{{ formatCurrency(stats.todayRevenue) }}</dd>
                                <div class="flex items-center mt-2 text-xs">
                                    <span class="text-green-600 font-medium">+15%</span>
                                    <span class="text-gray-500 ml-1">from yesterday</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <Link v-if="auth.user.role && (auth.user.role.name === 'cashier' || auth.user.role.name === 'admin')" :href="route('pos.create')" class="group flex items-center justify-center px-6 py-4 border border-gray-200 rounded-xl bg-white text-sm font-medium text-gray-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 shadow-sm transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span>New Sale</span>
                        </div>
                    </Link>

                    <Link v-if="auth.user.role && auth.user.role.name === 'admin'" :href="route('products.index')" class="group flex items-center justify-center px-6 py-4 border border-gray-200 rounded-xl bg-white text-sm font-medium text-gray-700 hover:bg-green-50 hover:border-green-300 hover:text-green-700 shadow-sm transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span>Add Product</span>
                        </div>
                    </Link>

                    <Link v-if="auth.user.role && auth.user.role.name === 'admin'" :href="route('reports.index')" class="group flex items-center justify-center px-6 py-4 border border-gray-200 rounded-xl bg-white text-sm font-medium text-gray-700 hover:bg-purple-50 hover:border-purple-300 hover:text-purple-700 shadow-sm transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3 group-hover:bg-purple-200 transition-colors">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <span>View Reports</span>
                        </div>
                    </Link>

                    <Link v-if="auth.user.role && auth.user.role.name === 'admin'" :href="route('users.index')" class="group flex items-center justify-center px-6 py-4 border border-gray-200 rounded-xl bg-white text-sm font-medium text-gray-700 hover:bg-yellow-50 hover:border-yellow-300 hover:text-yellow-700 shadow-sm transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg mr-3 group-hover:bg-yellow-200 transition-colors">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <span>Manage Users</span>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Recent Transactions and Top Products -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Recent Transactions (Admin only) -->
                <div v-if="auth.user.role && auth.user.role.name === 'admin' && recentTransactions && recentTransactions.length > 0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Recent Transactions
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cashier</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="transaction in recentTransactions" :key="transaction.id" class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ transaction.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ transaction.user ? transaction.user.name : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ formatCurrency(transaction.total_price) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ new Date(transaction.created_at).toLocaleString() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Top Products (Admin only) -->
                <div v-if="auth.user.role && auth.user.role.name === 'admin' && topProducts && topProducts.length > 0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Top Products
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sold</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="product in topProducts" :key="product.id" class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ product.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ product.category ? product.category.name : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-600">{{ product.total_sold || 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span 
                                            :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                product.stock > 10 ? 'bg-green-100 text-green-800' : 
                                                product.stock > 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                'bg-red-100 text-red-800'
                                            ]"
                                        >
                                            {{ product.stock }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Empty state if no data -->
                <div v-if="auth.user.role && auth.user.role.name === 'admin' && (!recentTransactions || recentTransactions.length === 0) && (!topProducts || topProducts.length === 0)" class="col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No data available</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by making some sales or adding products.</p>
                    <div class="mt-6">
                        <Link :href="route('products.index')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Your First Product
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>