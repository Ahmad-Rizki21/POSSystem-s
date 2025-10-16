<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    products: Object,
    recentTransactions: Array,
    auth: Object
});

const searchQuery = ref('');
const selectedProduct = ref('');
const selectedType = ref('');
const dateFrom = ref('');
const dateTo = ref('');
const transactions = ref(props.recentTransactions || []);
const pagination = ref({});
const isLoading = ref(true);

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getTransactionTypeColor = (type) => {
    const colors = {
        in: 'bg-green-100 text-green-800 border-green-200',
        out: 'bg-red-100 text-red-800 border-red-200',
        adjustment: 'bg-yellow-100 text-yellow-800 border-yellow-200'
    };
    return colors[type] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getTransactionTypeText = (type) => {
    const texts = {
        in: 'Stock In',
        out: 'Stock Out', 
        adjustment: 'Adjustment'
    };
    return texts[type] || type;
};

const loadTransactions = async () => {
    try {
        isLoading.value = true;
        const params = new URLSearchParams();
        if (searchQuery.value) params.append('search', searchQuery.value);
        if (selectedProduct.value) params.append('product_id', selectedProduct.value);
        if (selectedType.value) params.append('type', selectedType.value);
        if (dateFrom.value) params.append('start_date', dateFrom.value);
        if (dateTo.value) params.append('end_date', dateTo.value);

        const response = await fetch(route('stock-adjustment.history-data') + (params.toString() ? '?' + params.toString() : ''), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        transactions.value = data.data || [];
        pagination.value = {
            current_page: data.current_page || 1,
            last_page: data.last_page || 1,
            per_page: data.per_page || 20,
            total: data.total || 0,
            from: data.from || 1,
            to: data.to || 0
        };
    } catch (error) {
        console.error('Error loading transactions:', error);
    } finally {
        isLoading.value = false;
    }
};

const filterTransactions = async () => {
    await loadTransactions();
};

const resetFilters = () => {
    searchQuery.value = '';
    selectedProduct.value = '';
    selectedType.value = '';
    dateFrom.value = '';
    dateTo.value = '';
    loadTransactions();
};

const goToPage = async (page) => {
    try {
        isLoading.value = true;
        const params = new URLSearchParams();
        if (searchQuery.value) params.append('search', searchQuery.value);
        if (selectedProduct.value) params.append('product_id', selectedProduct.value);
        if (selectedType.value) params.append('type', selectedType.value);
        if (dateFrom.value) params.append('start_date', dateFrom.value);
        if (dateTo.value) params.append('end_date', dateTo.value);
        params.append('page', page);

        const response = await fetch(route('stock-adjustment.history-data') + (params.toString() ? '?' + params.toString() : ''), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        transactions.value = data.data || [];
        pagination.value = {
            current_page: data.current_page || 1,
            last_page: data.last_page || 1,
            per_page: data.per_page || 20,
            total: data.total || 0,
            from: data.from || 1,
            to: data.to || 0
        };
    } catch (error) {
        console.error('Error loading transactions:', error);
    } finally {
        isLoading.value = false;
    }
};

const getPageNumbers = () => {
    const pages = [];
    const start = Math.max(1, pagination.value.current_page - 2);
    const end = Math.min(pagination.value.last_page, pagination.value.current_page + 2);
    
    for (let i = start; i <= end; i++) {
        pages.push(i);
    }
    
    return pages;
};

onMounted(() => {
    loadTransactions();
});
</script>

<template>
    <Head title="Stock Adjustment History" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Stock Adjustment History</h1>
                        <p class="text-sm text-gray-600 mt-0.5">Track all stock movements and adjustments</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <Link :href="route('stock-adjustment.index')" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </Link>
                    <Link :href="route('stock-adjustment.create')" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Adjustment
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6 space-y-6">
            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                        </div>
                        <button @click="resetFilters" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            Reset All
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input v-model="searchQuery"
                                       type="text"
                                       class="block w-full pl-10 pr-3 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                       placeholder="Search products...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                            <select v-model="selectedProduct"
                                    class="block w-full px-3 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">All Products</option>
                                <option v-for="product in products" :key="product.id" :value="product.id">
                                    {{ product.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select v-model="selectedType"
                                    class="block w-full px-3 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">All Types</option>
                                <option value="in">Stock In</option>
                                <option value="out">Stock Out</option>
                                <option value="adjustment">Adjustment</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                            <input v-model="dateFrom"
                                   type="date"
                                   class="block w-full px-3 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                            <input v-model="dateTo"
                                   type="date"
                                   class="block w-full px-3 py-2.5 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button @click="filterTransactions"
                                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white text-sm font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Stock Adjustment Records</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <span v-if="!isLoading">{{ pagination.total || 0 }} total records found</span>
                                <span v-else>Loading records...</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Loading State -->
                            <template v-if="isLoading">
                                <tr v-for="i in 5" :key="'loading-' + i" class="animate-pulse">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="bg-gray-200 rounded-lg w-16 h-16"></div>
                                            <div class="ml-4 space-y-2 flex-1">
                                                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4"><div class="h-6 bg-gray-200 rounded w-20"></div></td>
                                    <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded w-12"></div></td>
                                    <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded w-24"></div></td>
                                    <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded w-32"></div></td>
                                    <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded w-16"></div></td>
                                    <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded w-20"></div></td>
                                    <td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded w-20"></div></td>
                                </tr>
                            </template>

                            <!-- Data Rows -->
                            <tr v-else v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16">
                                            <div v-if="transaction.product?.image_url" class="h-16 w-16 rounded-lg overflow-hidden">
                                                <img class="h-full w-full object-cover" :src="transaction.product.image_url" :alt="transaction.product.name">
                                            </div>
                                            <div v-else class="h-16 w-16 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ transaction.product?.name || 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ transaction.product?.sku || 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border',
                                        getTransactionTypeColor(transaction.type)
                                    ]">
                                        {{ getTransactionTypeText(transaction.type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'text-green-600 font-bold': transaction.type === 'in',
                                        'text-red-600 font-bold': transaction.type === 'out',
                                        'text-yellow-600 font-bold': transaction.type === 'adjustment'
                                    }" class="text-sm">
                                        {{ transaction.type === 'out' ? '-' : '+' }}{{ transaction.quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-medium flex-shrink-0">
                                            {{ transaction.user?.name?.charAt(0).toUpperCase() || 'U' }}
                                        </div>
                                        <span class="ml-2 text-sm text-gray-900">{{ transaction.user?.name || 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(transaction.created_at) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="transaction.reference" class="text-sm text-gray-900 font-mono">{{ transaction.reference }}</span>
                                    <span v-else class="text-sm text-gray-400">-</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="transaction.notes" class="text-sm text-gray-900 line-clamp-2">{{ transaction.notes }}</span>
                                    <span v-else class="text-sm text-gray-400">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="transaction.cost_price" class="text-sm font-medium text-gray-900">{{ formatCurrency(transaction.cost_price) }}</span>
                                    <span v-else class="text-sm text-gray-400">-</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="lg:hidden divide-y divide-gray-200">
                    <!-- Loading State -->
                    <template v-if="isLoading">
                        <div v-for="i in 5" :key="'loading-mobile-' + i" class="p-4 animate-pulse">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="bg-gray-200 rounded-lg w-12 h-12 flex-shrink-0"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="h-4 bg-gray-200 rounded"></div>
                                <div class="h-4 bg-gray-200 rounded"></div>
                            </div>
                        </div>
                    </template>

                    <!-- Data Cards -->
                    <div v-else v-for="transaction in transactions" :key="transaction.id" class="p-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div v-if="transaction.product?.image_url" class="h-12 w-12 rounded-lg overflow-hidden">
                                        <img class="h-full w-full object-cover" :src="transaction.product.image_url" :alt="transaction.product.name">
                                    </div>
                                    <div v-else class="h-12 w-12 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ transaction.product?.name || 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ transaction.product?.sku || 'N/A' }}</p>
                                </div>
                            </div>
                            <span :class="[
                                'inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold border flex-shrink-0',
                                getTransactionTypeColor(transaction.type)
                            ]">
                                {{ getTransactionTypeText(transaction.type) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Quantity</p>
                                <p :class="{
                                    'text-green-600 font-bold': transaction.type === 'in',
                                    'text-red-600 font-bold': transaction.type === 'out',
                                    'text-yellow-600 font-bold': transaction.type === 'adjustment'
                                }" class="text-sm">
                                    {{ transaction.type === 'out' ? '-' : '+' }}{{ transaction.quantity }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Cost</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ transaction.cost_price ? formatCurrency(transaction.cost_price) : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-medium">
                                    {{ transaction.user?.name?.charAt(0).toUpperCase() || 'U' }}
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-900">{{ transaction.user?.name || 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ formatDate(transaction.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!isLoading && transactions.length === 0" class="text-center py-12 px-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No stock adjustments found</h3>
                    <p class="text-sm text-gray-600 mb-6">Try adjusting your filters or create a new stock adjustment.</p>
                    <Link :href="route('stock-adjustment.create')"
                          class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white text-sm font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Adjustment
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="!isLoading && transactions.length > 0" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-700 order-2 sm:order-1">
                            Showing <span class="font-medium">{{ pagination.from }}</span> to <span class="font-medium">{{ pagination.to }}</span> of <span class="font-medium">{{ pagination.total }}</span> results
                        </div>
                        <div class="flex items-center gap-2 order-1 sm:order-2">
                            <button 
                                v-if="pagination.current_page > 1"
                                @click="goToPage(pagination.current_page - 1)"
                                class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                Previous
                            </button>
                            <button 
                                v-for="page in getPageNumbers()" 
                                :key="page"
                                @click="goToPage(page)"
                                :class="{
                                    'px-3 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg shadow-lg': page === pagination.current_page,
                                    'px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-200': page !== pagination.current_page
                                }">
                                {{ page }}
                            </button>
                            <button 
                                v-if="pagination.current_page < pagination.last_page"
                                @click="goToPage(pagination.current_page + 1)"
                                class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>