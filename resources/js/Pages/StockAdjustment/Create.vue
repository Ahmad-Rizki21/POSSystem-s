<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    products: Array,
    auth: Object
});

const form = ref({
    product_id: '',
    type: 'in',
    quantity: '',
    reference: '',
    notes: '',
    cost_price: ''
});

const errors = ref({});
const loading = ref(false);

const selectedProduct = computed(() => {
    return props.products.find(p => p.id == form.value.product_id);
});

const currentStock = computed(() => {
    return selectedProduct.value?.stock || 0;
});

const newStock = computed(() => {
    if (!form.value.quantity || !selectedProduct.value) return currentStock.value;
    
    const qty = parseInt(form.value.quantity);
    if (form.value.type === 'in') {
        return currentStock.value + qty;
    } else if (form.value.type === 'out') {
        return currentStock.value - qty;
    } else {
        return qty;
    }
});

const submitForm = () => {
    loading.value = true;
    errors.value = {};

    router.post('/stock-adjustment', form.value, {
        onSuccess: () => {
            // Success handled by Inertia
        },
        onError: (errs) => {
            errors.value = errs;
        },
        onFinish: () => {
            loading.value = false;
        }
    });
};

const resetForm = () => {
    form.value = {
        product_id: '',
        type: 'in',
        quantity: '',
        reference: '',
        notes: '',
        cost_price: ''
    };
    errors.value = {};
};
</script>

<template>
    <Head title="Create Stock Adjustment" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Create Stock Adjustment</h1>
                        <p class="text-sm text-gray-600 mt-0.5">Record inventory stock movements</p>
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
                        Back to Adjustments
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <!-- Stock Information Card -->
            <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
            >
                <div v-if="selectedProduct" class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl shadow-sm border border-indigo-200 p-6 mb-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div v-if="selectedProduct.image_url" class="flex-shrink-0 h-16 w-16 rounded-lg overflow-hidden shadow-md">
                                <img class="h-full w-full object-cover" :src="selectedProduct.image_url" :alt="selectedProduct.name">
                            </div>
                            <div v-else class="flex-shrink-0 h-16 w-16 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center shadow-md">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ selectedProduct.name }}</h3>
                                <p class="text-sm text-gray-600">{{ selectedProduct.category?.name }} • SKU: {{ selectedProduct.sku }}</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-sm font-medium text-gray-600 mb-1">Current Stock</p>
                            <p class="text-3xl font-bold" :class="currentStock > 10 ? 'text-green-600' : currentStock > 0 ? 'text-yellow-600' : 'text-red-600'">
                                {{ currentStock }} <span class="text-lg">{{ selectedProduct.unit }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </transition>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Form Fields -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                                <h3 class="text-lg font-semibold text-gray-900">Adjustment Details</h3>
                                <p class="mt-1 text-sm text-gray-600">Fill in the stock movement information</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Product Selection -->
                                <div>
                                    <label for="product_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Product
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.product_id"
                                        id="product_id"
                                        required
                                        :class="[
                                            'block w-full px-4 py-3 rounded-lg border shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                            errors.product_id ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                        ]"
                                    >
                                        <option value="">Select a product</option>
                                        <option v-for="product in products" :key="product.id" :value="product.id">
                                            {{ product.name }} ({{ product.stock }} {{ product.unit }})
                                        </option>
                                    </select>
                                    <p v-if="errors.product_id" class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ errors.product_id }}
                                    </p>
                                </div>

                                <!-- Transaction Type and Quantity Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Transaction Type
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            v-model="form.type"
                                            id="type"
                                            required
                                            :class="[
                                                'block w-full px-4 py-3 rounded-lg border shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                errors.type ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                            ]"
                                        >
                                            <option value="in">Stock In (Delivery/Purchase)</option>
                                            <option value="out">Stock Out (Usage/Damage)</option>
                                            <option value="adjustment">Stock Adjustment (Manual Fix)</option>
                                        </select>
                                        <p v-if="errors.type" class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ errors.type }}
                                        </p>
                                    </div>

                                    <div>
                                        <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Quantity
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.quantity"
                                            type="number"
                                            id="quantity"
                                            required
                                            min="1"
                                            :class="[
                                                'block w-full px-4 py-3 rounded-lg border shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                errors.quantity ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                            ]"
                                            placeholder="Enter quantity"
                                        >
                                        <p v-if="errors.quantity" class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ errors.quantity }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Reference and Cost Price -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="reference" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Reference Number
                                        </label>
                                        <input
                                            v-model="form.reference"
                                            type="text"
                                            id="reference"
                                            :class="[
                                                'block w-full px-4 py-3 rounded-lg border shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                errors.reference ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                            ]"
                                            placeholder="Invoice #, Delivery Note #, etc."
                                        >
                                        <p v-if="errors.reference" class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ errors.reference }}
                                        </p>
                                    </div>

                                    <div v-if="form.type === 'in'">
                                        <label for="cost_price" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Cost Price (optional)
                                        </label>
                                        <div class="relative rounded-lg shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 text-lg font-medium">Rp</span>
                                            </div>
                                            <input
                                                v-model="form.cost_price"
                                                type="number"
                                                step="0.01"
                                                id="cost_price"
                                                min="0"
                                                :class="[
                                                    'block w-full pl-12 pr-4 py-3 rounded-lg border shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                    errors.cost_price ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                                ]"
                                                placeholder="0.00"
                                            >
                                        </div>
                                        <p v-if="errors.cost_price" class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ errors.cost_price }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Notes
                                    </label>
                                    <textarea
                                        v-model="form.notes"
                                        id="notes"
                                        rows="4"
                                        :class="[
                                            'block w-full px-4 py-3 rounded-lg border shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none',
                                            errors.notes ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                        ]"
                                        placeholder="Add any additional notes about this adjustment..."
                                    ></textarea>
                                    <p v-if="errors.notes" class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ errors.notes }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Preview -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-6">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                                <h3 class="text-lg font-semibold text-gray-900">Adjustment Preview</h3>
                                <p class="mt-1 text-sm text-gray-600">Review the stock change</p>
                            </div>
                            <div class="p-6">
                                <div v-if="selectedProduct && form.quantity" class="space-y-4">
                                    <div class="border-b border-gray-200 pb-4">
                                        <p class="text-sm font-medium text-gray-900">{{ selectedProduct.name }}</p>
                                        <p class="text-xs text-gray-500">{{ selectedProduct.category?.name }}</p>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-500">Current Stock:</span>
                                            <span class="font-medium text-gray-900">{{ currentStock }} {{ selectedProduct.unit }}</span>
                                        </div>

                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-500">Adjustment Type:</span>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border" :class="{
                                                'bg-green-100 text-green-800 border-green-200': form.type === 'in',
                                                'bg-red-100 text-red-800 border-red-200': form.type === 'out',
                                                'bg-yellow-100 text-yellow-800 border-yellow-200': form.type === 'adjustment'
                                            }">
                                                {{ form.type === 'in' ? 'Stock In' : form.type === 'out' ? 'Stock Out' : 'Adjustment' }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-500">Quantity:</span>
                                            <span class="font-medium" :class="{
                                                'text-green-600': form.type === 'in',
                                                'text-red-600': form.type === 'out',
                                                'text-yellow-600': form.type === 'adjustment'
                                            }">
                                                {{ form.type === 'out' ? '-' : '+' }}{{ form.quantity }} {{ selectedProduct.unit }}
                                            </span>
                                        </div>

                                        <div class="border-t border-gray-200 pt-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-900">New Stock:</span>
                                                <span class="text-2xl font-bold" :class="{
                                                    'text-green-600': form.type === 'in',
                                                    'text-red-600': form.type === 'out',
                                                    'text-yellow-600': form.type === 'adjustment'
                                                }">
                                                    {{ newStock }} <span class="text-sm">{{ selectedProduct.unit }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Warning for insufficient stock -->
                                    <transition
                                        enter-active-class="transition duration-300 ease-out"
                                        enter-from-class="transform scale-95 opacity-0"
                                        enter-to-class="transform scale-100 opacity-100"
                                    >
                                        <div v-if="form.type === 'out' && currentStock < parseInt(form.quantity)" class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                                            <div class="flex">
                                                <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-red-800">Insufficient Stock</h3>
                                                    <p class="text-sm text-red-700 mt-1">
                                                        You only have {{ currentStock }} {{ selectedProduct.unit }} available.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </transition>
                                </div>

                                <div v-else class="text-center text-gray-500 py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm">Select a product and quantity to see the preview</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                        <button
                            type="button"
                            @click="resetForm"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset Form
                        </button>
                        <button
                            type="submit"
                            :disabled="loading || !selectedProduct || !form.quantity || (form.type === 'out' && currentStock < parseInt(form.quantity))"
                            :class="[
                                'inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg font-medium text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl',
                                loading || !selectedProduct || !form.quantity || (form.type === 'out' && currentStock < parseInt(form.quantity))
                                    ? 'bg-gray-400 cursor-not-allowed'
                                    : 'bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800'
                            ]"
                        >
                            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ loading ? 'Processing...' : 'Create Adjustment' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>