<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    product: Object
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

const getStockStatus = (stock) => {
    if (stock > 10) return { text: 'In Stock', class: 'bg-green-100 text-green-800' };
    return { text: 'Out of Stock', class: 'bg-red-100 text-red-800' };
};
</script>

<template>
    <Head title="Product Detail" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Product Detail</h1>
                </div>
                <div class="mt-2 sm:mt-0">
                    <Link 
                        :href="route('products.index')" 
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Back to Products
                    </Link>
                </div>
            </div>
        </template>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ product.name }}</h2>
                        <div class="mt-4 space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">SKU</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ product.sku }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Category</h3>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ product.category ? product.category.name : 'N/A' }}
                                    </span>
                                </p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Stock</h3>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span 
                                        :class="[
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            getStockStatus(product.stock).class
                                        ]"
                                    >
                                        {{ product.stock }} - {{ getStockStatus(product.stock).text }}
                                    </span>
                                </p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Harga Beli</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(product.buying_price) }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Harga Jual</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(product.selling_price) }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Margin Keuntungan</h3>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ formatCurrency(product.selling_price - product.buying_price) }}
                                    ({{ ((product.selling_price - product.buying_price) / product.selling_price * 100).toFixed(2) }}%)
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Description</h3>
                        <div class="mt-1 text-sm text-gray-900">
                            <p v-if="product.description">{{ product.description }}</p>
                            <p v-else class="text-gray-500 italic">No description provided</p>
                        </div>
                        
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500">Actions</h3>
                            <div class="mt-2 flex space-x-3">
                                <Link 
                                    :href="route('products.edit', product.id)" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Edit
                                </Link>
                                
                                <button
                                    @click="confirmDelete"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>