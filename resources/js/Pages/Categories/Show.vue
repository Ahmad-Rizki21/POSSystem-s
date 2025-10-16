<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    category: Object,
    products: Array
});

const viewMode = ref('grid');

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};

const confirmDelete = () => {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        router.delete(route('categories.destroy', props.category.id));
    }
};
</script>

<template>
    <Head title="Category Detail" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        Category Details
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">View category information and associated products</p>
                </div>
                <div>
                    <Link 
                        :href="route('categories.index')" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Categories
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6 space-y-6">
            <!-- Category Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-8 py-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center gap-6">
                            <div class="h-20 w-20 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h2 class="text-3xl font-bold text-white">{{ category.name }}</h2>
                                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-semibold text-white">
                                        ID: {{ category.id }}
                                    </span>
                                </div>
                                <p class="text-purple-100">
                                    {{ category.description || 'No description provided' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <Link 
                                :href="route('categories.edit', category.id)" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-gray-50 text-purple-600 font-semibold rounded-xl transition-all duration-200 shadow-lg"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </Link>
                            <button
                                @click="confirmDelete"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                    <div class="px-8 py-6 text-center">
                        <div class="text-3xl font-bold text-gray-900">{{ products.length }}</div>
                        <div class="mt-1 text-sm text-gray-500">Total Products</div>
                    </div>
                    <div class="px-8 py-6 text-center">
                        <div class="text-3xl font-bold text-gray-900">
                            {{ products.reduce((sum, p) => sum + (p.stock || 0), 0) }}
                        </div>
                        <div class="mt-1 text-sm text-gray-500">Total Stock</div>
                    </div>
                    <div class="px-8 py-6 text-center">
                        <div class="text-3xl font-bold text-gray-900">
                            {{ formatCurrency(products.reduce((sum, p) => sum + ((p.selling_price || 0) * (p.stock || 0)), 0)) }}
                        </div>
                        <div class="mt-1 text-sm text-gray-500">Total Value</div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                Products in this Category
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">{{ products.length }} product(s) found</p>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-lg">
                            <button
                                @click="viewMode = 'grid'"
                                :class="[
                                    'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                    viewMode === 'grid' 
                                        ? 'bg-white text-indigo-600 shadow-sm' 
                                        : 'text-gray-600 hover:text-gray-900'
                                ]"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button
                                @click="viewMode = 'table'"
                                :class="[
                                    'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                                    viewMode === 'table' 
                                        ? 'bg-white text-indigo-600 shadow-sm' 
                                        : 'text-gray-600 hover:text-gray-900'
                                ]"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grid View -->
                <div v-if="viewMode === 'grid' && products.length > 0" class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="product in products"
                            :key="product.id"
                            class="bg-white border-2 border-gray-200 rounded-xl p-5 hover:border-indigo-300 hover:shadow-lg transition-all duration-200"
                        >
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="text-lg font-bold text-gray-900 flex-1 mr-2">{{ product.name }}</h4>
                                <span :class="[
                                    'px-2 py-1 text-xs font-semibold rounded-full',
                                    product.stock > 10 ? 'bg-green-100 text-green-700' : 
                                    product.stock > 0 ? 'bg-yellow-100 text-yellow-700' : 
                                    'bg-red-100 text-red-700'
                                ]">
                                    {{ product.stock }} in stock
                                </span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Price:</span>
                                    <span class="font-semibold text-gray-900">{{ formatCurrency(product.selling_price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">SKU:</span>
                                    <span class="font-mono text-xs text-gray-700">{{ product.sku || '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table View -->
                <div v-if="viewMode === 'table' && products.length > 0" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product Name</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SKU</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ product.name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-600">{{ product.sku || '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[
                                        'px-3 py-1 text-xs font-semibold rounded-full',
                                        product.stock > 10 ? 'bg-green-100 text-green-700' : 
                                        product.stock > 0 ? 'bg-yellow-100 text-yellow-700' : 
                                        'bg-red-100 text-red-700'
                                    ]">
                                        {{ product.stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ formatCurrency(product.selling_price) }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="products.length === 0" class="p-12">
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="h-24 w-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No products in this category</h3>
                        <p class="text-gray-500 mb-6">Start adding products to this category to see them here</p>
                        <Link 
                            :href="route('products.create')" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Product
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>