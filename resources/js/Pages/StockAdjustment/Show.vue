<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    transaction: Object,
    auth: Object
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

const getTransactionTypeColor = (type) => {
    const colors = {
        'in': 'bg-green-100 text-green-800 border-green-200',
        'out': 'bg-red-100 text-red-800 border-red-200',
        'adjustment': 'bg-yellow-100 text-yellow-800 border-yellow-200'
    };
    return colors[type] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getTransactionTypeText = (type) => {
    const texts = {
        'in': 'Stock In',
        'out': 'Stock Out',
        'adjustment': 'Adjustment'
    };
    return texts[type] || type;
};

const getStockChangeText = (transaction) => {
    if (transaction.type === 'in') {
        return `+${transaction.quantity} ${transaction.product?.unit || 'pcs'}`;
    } else if (transaction.type === 'out') {
        return `-${transaction.quantity} ${transaction.product?.unit || 'pcs'}`;
    } else {
        return `${transaction.quantity} ${transaction.product?.unit || 'pcs'}`;
    }
};
</script>

<template>
    <Head title="Stock Adjustment Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Stock Adjustment Details</h1>
                        <p class="text-sm text-gray-600 mt-0.5">View stock adjustment information</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('stock-adjustment.index')"
                        class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </Link>
                    <Link
                        :href="route('stock-adjustment.create')"
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Adjustment
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div v-if="transaction" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Transaction Header -->
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Transaction #{{ transaction.id }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ getTransactionTypeText(transaction.type) }} • {{ formatDate(transaction.created_at) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div class="p-6 space-y-6">
                    <!-- Product Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">Product Information</h3>
                        <div class="flex items-start gap-4">
                            <div v-if="transaction.product?.image_url" class="flex-shrink-0 h-20 w-20 rounded-lg overflow-hidden shadow-md">
                                <img
                                    class="h-full w-full object-cover"
                                    :src="transaction.product.image_url"
                                    :alt="transaction.product.name"
                                >
                            </div>
                            <div v-else class="flex-shrink-0 h-20 w-20 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center shadow-md">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900">{{ transaction.product?.name || 'N/A' }}</h4>
                                <p class="text-sm text-gray-500">{{ transaction.product?.category?.name || 'Uncategorized' }}</p>
                                <p class="text-xs text-gray-400 mt-1">SKU: {{ transaction.product?.sku || 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">Stock Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Current Stock</p>
                                <p class="text-xl font-bold text-gray-900">{{ transaction.stock_before }} {{ transaction.product?.unit || 'pcs' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">New Stock</p>
                                <p class="text-xl font-bold" :class="{
                                    'text-green-600': transaction.type === 'in',
                                    'text-red-600': transaction.type === 'out',
                                    'text-yellow-600': transaction.type === 'adjustment'
                                }">
                                    {{ transaction.stock_after }} {{ transaction.product?.unit || 'pcs' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Adjustment Details -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">Adjustment Details</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Adjustment Type</p>
                                <span :class="[
                                    'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border',
                                    getTransactionTypeColor(transaction.type)
                                ]">
                                    {{ getTransactionTypeText(transaction.type) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Quantity</p>
                                <p :class="{
                                    'text-green-600 font-bold': transaction.type === 'in',
                                    'text-red-600 font-bold': transaction.type === 'out',
                                    'text-yellow-600 font-bold': transaction.type === 'adjustment'
                                }">
                                    {{ getStockChangeText(transaction) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Reference</p>
                                <p v-if="transaction.reference" class="text-sm text-gray-900 font-mono bg-white px-2 py-1 rounded">{{ transaction.reference }}</p>
                                <p v-else class="text-sm text-gray-400">No reference</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Cost Price</p>
                                <span v-if="transaction.cost_price" class="text-sm font-medium text-gray-900">{{ formatCurrency(transaction.cost_price) }}</span>
                                <span v-else class="text-sm text-gray-400">No cost price</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div v-if="transaction.notes" class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">Notes</h3>
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ transaction.notes }}</p>
                    </div>

                    <!-- User Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">User Information</h3>
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-medium">
                                {{ transaction.user?.name?.charAt(0).toUpperCase() || 'U' }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ transaction.user?.name || 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ formatDate(transaction.user?.created_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timestamp -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <p class="text-xs text-gray-500 text-center">
                        Created at: {{ formatDate(transaction.created_at) }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>