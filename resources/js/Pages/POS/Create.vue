<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    products: Array
});

const searchQuery = ref('');
const cart = ref([]);
const paymentMethod = ref('cash');
const paidAmount = ref('');
const processing = ref(false);
const selectedCategory = ref('all');

const filteredProducts = computed(() => {
    let filtered = props.products;

    // Filter by search query
    if (searchQuery.value) {
        filtered = filtered.filter(product =>
            product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            product.sku.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    // Filter by category
    if (selectedCategory.value !== 'all') {
        filtered = filtered.filter(product => 
            product.category?.id === selectedCategory.value
        );
    }

    return filtered;
});

const categories = computed(() => {
    const cats = new Map();
    props.products.forEach(product => {
        if (product.category) {
            cats.set(product.category.id, product.category);
        }
    });
    return Array.from(cats.values());
});

const cartTotal = computed(() => {
    return cart.value.reduce((sum, item) => sum + (item.quantity * item.price), 0);
});

const cartItemCount = computed(() => {
    return cart.value.reduce((sum, item) => sum + item.quantity, 0);
});

const changeAmount = computed(() => {
    const paid = parseFloat(paidAmount.value) || 0;
    return paid - cartTotal.value;
});

const addToCart = (product) => {
    if (product.stock <= 0) {
        alert('Produk ini sedang habis!');
        return;
    }

    const existingItem = cart.value.find(item => item.id === product.id);

    if (existingItem) {
        if (existingItem.quantity >= product.stock) {
            alert('Stok tidak mencukupi!');
            return;
        }
        existingItem.quantity++;
    } else {
        cart.value.push({
            id: product.id,
            name: product.name,
            sku: product.sku,
            price: parseFloat(product.selling_price),
            quantity: 1,
            stock: product.stock,
            category: product.category?.name || 'N/A'
        });
    }
};

const removeFromCart = (item) => {
    cart.value = cart.value.filter(cartItem => cartItem.id !== item.id);
};

const updateQuantity = (item, newQuantity) => {
    if (newQuantity < 1) {
        removeFromCart(item);
        return;
    }

    if (newQuantity > item.stock) {
        alert('Stok tidak mencukupi!');
        return;
    }

    item.quantity = newQuantity;
};

const clearCart = () => {
    if (cart.value.length > 0) {
        if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
            cart.value = [];
            paidAmount.value = '';
        }
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
};

const quickPayAmount = (multiplier) => {
    const total = cartTotal.value;
    const rounded = Math.ceil(total / multiplier) * multiplier;
    paidAmount.value = rounded.toString();
};

const completeTransaction = () => {
    if (cart.value.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    const paid = parseFloat(paidAmount.value) || 0;
    if (paid < cartTotal.value) {
        alert('Jumlah pembayaran kurang!');
        return;
    }

    processing.value = true;

    const transactionData = {
        items: cart.value.map(item => ({
            product_id: item.id,
            quantity: item.quantity,
            price: item.price,
            subtotal: item.quantity * item.price
        })),
        payment_method: paymentMethod.value,
        paid_amount: paid
    };

    useForm(transactionData).post(route('pos.store'), {
        onSuccess: (page) => {
            clearCart();
            processing.value = false;
            alert('Transaksi berhasil!');
        },
        onError: (errors) => {
            processing.value = false;
            let errorMessage = 'Transaksi gagal!';
            if (errors.message) {
                errorMessage = errors.message;
            }
            alert(errorMessage);
        }
    });
};

// Keyboard shortcuts
const handleKeyPress = (event) => {
    if (event.ctrlKey || event.metaKey) {
        switch (event.key) {
            case 'Enter':
                event.preventDefault();
                if (cart.value.length > 0) {
                    completeTransaction();
                }
                break;
            case 'Escape':
                event.preventDefault();
                clearCart();
                break;
        }
    }
};

onMounted(() => {
    document.addEventListener('keydown', handleKeyPress);
});
</script>

<template>
    <Head title="Point of Sale" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Link :href="route('dashboard')" 
                          class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </Link>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            Point of Sale
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">Proses transaksi dengan cepat dan efisien</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <Link :href="route('pos.index')" 
                          class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Riwayat</span>
                    </Link>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="py-6">
            <div class="max-w-full mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Products Section - 2 columns on large screens -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Search & Filter Bar -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <!-- Search Input -->
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input
                                        v-model="searchQuery"
                                        type="text"
                                        class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm transition-all duration-200"
                                        placeholder="Cari produk berdasarkan nama atau SKU..."
                                    >
                                </div>
                                
                                <!-- Category Filter -->
                                <div class="sm:w-48">
                                    <select v-model="selectedCategory"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent sm:text-sm transition-all duration-200">
                                        <option value="all">Semua Kategori</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Produk Tersedia 
                                    <span class="ml-2 text-gray-500 font-normal">({{ filteredProducts.length }})</span>
                                </h3>
                            </div>
                            
                            <div class="p-4 max-h-[calc(100vh-350px)] overflow-y-auto custom-scrollbar">
                                <div v-if="filteredProducts.length" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                                    <div v-for="product in filteredProducts" :key="product.id"
                                         class="group relative bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-4 hover:shadow-lg hover:shadow-green-500/10 hover:border-green-300 transition-all duration-300 cursor-pointer transform hover:-translate-y-1"
                                         @click="addToCart(product)">
                                        <!-- Stock Badge -->
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold shadow-sm"
                                                  :class="product.stock > 10 ? 'bg-green-100 text-green-700 border border-green-200' : 
                                                          product.stock > 0 ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 
                                                          'bg-red-100 text-red-700 border border-red-200'">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                                </svg>
                                                {{ product.stock }}
                                            </span>
                                        </div>

                                        <div class="mb-3 pr-16">
                                            <h4 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-green-600 transition-colors duration-200 mb-1">
                                                {{ product.name }}
                                            </h4>
                                            <p class="text-xs text-gray-500 font-mono">{{ product.sku }}</p>
                                            <span class="inline-block mt-1.5 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md font-medium">
                                                {{ product.category?.name || 'Tanpa Kategori' }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                            <div class="text-lg font-bold text-gray-900">
                                                {{ formatCurrency(product.selling_price) }}
                                            </div>
                                            <button :disabled="product.stock <= 0"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-semibold rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-md hover:shadow-lg disabled:shadow-none">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Empty State -->
                                <div v-else class="text-center py-16">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Produk tidak ditemukan</h3>
                                    <p class="text-sm text-gray-500">Coba sesuaikan pencarian atau filter Anda</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Section - 1 column on large screens -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 sticky top-6 overflow-hidden">
                            <!-- Cart Header -->
                            <div class="px-5 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-xl bg-white bg-opacity-20 flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white">Keranjang</h3>
                                            <p class="text-xs text-green-100">{{ cartItemCount }} item</p>
                                        </div>
                                    </div>
                                    <button v-if="cart.length > 0" @click="clearCart"
                                            class="text-sm text-white hover:text-red-200 font-semibold px-3 py-1.5 rounded-lg hover:bg-white hover:bg-opacity-10 transition-all duration-200">
                                        Kosongkan
                                    </button>
                                </div>
                            </div>

                            <!-- Cart Items -->
                            <div class="max-h-[calc(100vh-550px)] overflow-y-auto custom-scrollbar">
                                <div v-if="cart.length" class="p-4 space-y-3">
                                    <div v-for="item in cart" :key="item.id"
                                         class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-3 border border-gray-200 hover:border-green-300 transition-all duration-200 shadow-sm">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1 min-w-0 pr-2">
                                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-1 mb-1">{{ item.name }}</h4>
                                                <p class="text-xs text-gray-500 mb-1">{{ formatCurrency(item.price) }} / pcs</p>
                                                <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">
                                                    {{ item.category }}
                                                </span>
                                            </div>
                                            <button @click="removeFromCart(item)"
                                                    class="flex-shrink-0 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg p-1.5 transition-all duration-200">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                            <div class="flex items-center space-x-2">
                                                <button @click="updateQuantity(item, item.quantity - 1)"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-gray-300 text-gray-700 hover:bg-gray-100 hover:border-gray-400 font-semibold transition-all duration-200">
                                                    −
                                                </button>
                                                <input :value="item.quantity"
                                                       @input="updateQuantity(item, parseInt($event.target.value) || 1)"
                                                       type="number"
                                                       min="1"
                                                       :max="item.stock"
                                                       class="w-14 text-center border-2 border-gray-300 rounded-lg text-sm font-semibold py-1.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                <button @click="updateQuantity(item, item.quantity + 1)"
                                                        :disabled="item.quantity >= item.stock"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg border-2 border-gray-300 text-gray-700 hover:bg-gray-100 hover:border-gray-400 font-semibold disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200">
                                                    +
                                                </button>
                                            </div>
                                            <div class="text-base font-bold text-gray-900">
                                                {{ formatCurrency(item.quantity * item.price) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Empty Cart State -->
                                <div v-else class="text-center py-12 px-4">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Keranjang Kosong</h3>
                                    <p class="text-sm text-gray-500">Tambahkan produk untuk memulai</p>
                                </div>
                            </div>

                            <!-- Payment Section -->
                            <div v-if="cart.length > 0" class="border-t-2 border-gray-200 bg-gray-50">
                                <!-- Total -->
                                <div class="px-5 py-4 bg-gradient-to-r from-gray-100 to-gray-50">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-semibold text-gray-600">Total Belanja:</span>
                                        <span class="text-2xl font-bold text-gray-900">{{ formatCurrency(cartTotal) }}</span>
                                    </div>
                                </div>

                                <div class="px-5 py-4 space-y-4">
                                    <!-- Payment Method -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                                        <select v-model="paymentMethod"
                                                class="block w-full px-4 py-3 rounded-xl border-2 border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 sm:text-sm font-medium transition-all duration-200">
                                            <option value="cash">💵 Tunai</option>
                                            <option value="qris">📱 QRIS</option>
                                            <option value="transfer">🏦 Transfer Bank</option>
                                            <option value="debit_card">💳 Kartu Debit</option>
                                            <option value="credit_card">💳 Kartu Kredit</option>
                                        </select>
                                    </div>

                                    <!-- Quick Pay Buttons -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Cepat</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <button @click="paidAmount = cartTotal.toString()"
                                                    class="px-3 py-2 bg-white border-2 border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 hover:border-green-500 transition-all duration-200">
                                                Pas
                                            </button>
                                            <button @click="quickPayAmount(10000)"
                                                    class="px-3 py-2 bg-white border-2 border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 hover:border-green-500 transition-all duration-200">
                                                10rb
                                            </button>
                                            <button @click="quickPayAmount(50000)"
                                                    class="px-3 py-2 bg-white border-2 border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 hover:border-green-500 transition-all duration-200">
                                                50rb
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Paid Amount -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Bayar</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                                            <input v-model="paidAmount"
                                                   type="number"
                                                   step="1000"
                                                   min="0"
                                                   class="block w-full pl-12 pr-4 py-3 rounded-xl border-2 border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 sm:text-sm font-semibold transition-all duration-200"
                                                   placeholder="0">
                                        </div>
                                    </div>

                                    <!-- Change Amount -->
                                    <div v-if="paidAmount && parseFloat(paidAmount) >= cartTotal" 
                                         class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-semibold text-green-800">Kembalian:</span>
                                            <span class="text-xl font-bold text-green-600">{{ formatCurrency(changeAmount) }}</span>
                                        </div>
                                    </div>

                                    <!-- Warning for insufficient payment -->
                                    <div v-if="paidAmount && parseFloat(paidAmount) < cartTotal" 
                                         class="p-3 bg-red-50 rounded-xl border-2 border-red-200">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm font-semibold text-red-800">Pembayaran kurang!</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-3 pt-2">
                                        <button @click="clearCart"
                                                class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 font-semibold text-sm transition-all duration-200">
                                            Batal
                                        </button>
                                        <button @click="completeTransaction"
                                                :disabled="processing || !paidAmount || parseFloat(paidAmount) < cartTotal"
                                                class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-bold text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg hover:shadow-xl disabled:shadow-none">
                                            <span v-if="processing" class="flex items-center justify-center">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Proses...
                                            </span>
                                            <span v-else class="flex items-center justify-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Bayar Sekarang
                                            </span>
                                        </button>
                                    </div>

                                    <!-- Keyboard Shortcuts Info -->
                                    <div class="pt-3 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 text-center">
                                            <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">Ctrl+Enter</kbd> untuk bayar • 
                                            <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">Ctrl+Esc</kbd> untuk batal
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Line clamp utility - Fixed vendor prefix warning */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1; /* Standard property */
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2; /* Standard property */
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animation for cart items */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.space-y-3 > * {
    animation: slideIn 0.3s ease-out;
}

/* Keyboard shortcut styling */
kbd {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
}

/* Smooth transitions for all interactive elements */
button,
input,
select {
    transition: all 0.2s ease-in-out;
}

/* Focus styles for accessibility */
button:focus-visible,
input:focus-visible,
select:focus-visible {
    outline: 2px solid #10b981;
    outline-offset: 2px;
}

/* Prevent text selection on buttons */
button {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

/* Number input - hide spinner */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
    appearance: textfield;
}

/* Gradient text effect for headings */
.gradient-text {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Card hover effect */
.hover-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-card:hover {
    transform: translateY(-4px);
}

/* Pulse animation for notifications */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Loading spinner */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Fade in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
}

/* Badge pulse effect */
.badge-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
}

/* Dark mode support (optional) */
@media (prefers-color-scheme: dark) {
    /* Add dark mode styles if needed */
}

/* Reduce motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>