<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    transaction: Object
});

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
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getPaymentMethodColor = (method) => {
    const colors = {
        cash: 'bg-green-100 text-green-800',
        qris: 'bg-blue-100 text-blue-800',
        transfer: 'bg-purple-100 text-purple-800',
        debit_card: 'bg-orange-100 text-orange-800',
        credit_card: 'bg-red-100 text-red-800'
    };
    return colors[method] || 'bg-gray-100 text-gray-800';
};

const printInvoice = () => {
    window.print();
};
</script>

<template>
    <Head :title="`Invoice ${transaction.invoice_number}`" />

    <AuthenticatedLayout :fullPage="true">
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('pos.index')" class="inline-flex items-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            Invoice {{ transaction.invoice_number }}
                        </h1>
                        <p class="mt-1 text-xs sm:text-sm text-gray-600">Transaction details and receipt</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button @click="printInvoice"
                            class="inline-flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span class="hidden sm:inline">Print</span>
                    </button>
                </div>
            </div>
        </template>

        <!-- Printable Invoice -->
        <div class="h-full bg-gradient-to-br from-gray-50 via-white to-gray-50">
            <div class="h-full overflow-y-auto">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Invoice Header -->
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                            <div class="flex justify-between items-start">
                                <div class="text-white">
                                    <h2 class="text-2xl font-bold mb-2">SALES RECEIPT</h2>
                                    <p class="text-indigo-100">Invoice #: {{ transaction.invoice_number }}</p>
                                    <p class="text-indigo-100 text-sm mt-1">{{ formatDate(transaction.created_at) }}</p>
                                </div>
                                <div class="text-right text-white">
                                    <p class="text-sm text-indigo-100 mb-1">Cashier:</p>
                                    <p class="font-semibold">{{ transaction.user?.name || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="p-8 border-b border-gray-200">
                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">STORE INFORMATION</h3>
                                    <p class="text-gray-900 font-semibold">Store POS System</p>
                                    <p class="text-gray-600 text-sm">Your Business Address</p>
                                    <p class="text-gray-600 text-sm">Phone: (021) 123-4567</p>
                                    <p class="text-gray-600 text-sm">Email: info@storepos.com</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">PAYMENT METHOD</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium capitalize" :class="getPaymentMethodColor(transaction.payment_method)">
                                        {{ transaction.payment_method.replace('_', ' ') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="p-8 border-b border-gray-200">
                            <h3 class="text-sm font-medium text-gray-500 mb-4">ORDERED ITEMS</h3>
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-2 text-sm font-medium text-gray-700">Product</th>
                                        <th class="text-center py-2 text-sm font-medium text-gray-700">Qty</th>
                                        <th class="text-right py-2 text-sm font-medium text-gray-700">Price</th>
                                        <th class="text-right py-2 text-sm font-medium text-gray-700">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in transaction.items" :key="item.id" class="border-b border-gray-100">
                                        <td class="py-3">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ item.product?.name || 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">SKU: {{ item.product?.sku || 'N/A' }}</p>
                                            </div>
                                        </td>
                                        <td class="text-center py-3 text-sm text-gray-600">{{ item.quantity }}</td>
                                        <td class="text-right py-3 text-sm text-gray-600">{{ formatCurrency(item.price) }}</td>
                                        <td class="text-right py-3 text-sm font-medium text-gray-900">{{ formatCurrency(item.subtotal) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="p-8">
                            <div class="max-w-sm ml-auto">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-medium text-gray-900">{{ formatCurrency(transaction.total_price) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tax (0%):</span>
                                        <span class="font-medium text-gray-900">{{ formatCurrency(0) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Paid:</span>
                                        <span class="font-medium text-gray-900">{{ formatCurrency(transaction.paid_amount) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm pt-2 border-t border-gray-200">
                                        <span class="text-gray-900 font-semibold">Total:</span>
                                        <span class="font-bold text-lg text-gray-900">{{ formatCurrency(transaction.total_price) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Change:</span>
                                        <span class="font-medium text-green-600">{{ formatCurrency(transaction.change_amount) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Thank You Message -->
                            <div class="mt-8 text-center">
                                <p class="text-lg font-semibold text-gray-900">Thank you for your purchase!</p>
                                <p class="text-sm text-gray-600 mt-1">Please come again</p>
                            </div>

                            <!-- Footer -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">
                                    This is a computer-generated receipt and does not require a signature.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons (Not Printable) -->
                    <div class="mt-6 flex justify-center space-x-4 print:hidden">
                        <Link :href="route('pos.create')"
                              class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Sale
                        </Link>
                        <Link :href="route('pos.index')"
                              class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to History
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style media="print">
@media print {
    .print\:hidden {
        display: none !important;
    }

    body {
        background: white !important;
    }

    .bg-gradient-to-r {
        background: #4f46e5 !important;
    }
}
</style>