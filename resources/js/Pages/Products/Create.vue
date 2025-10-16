<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const name = ref('');
const sku = ref('');
const description = ref('');
const purchase_price = ref('');
const selling_price = ref('');
const stock = ref('');
const unit = ref('pcs');
const category_id = ref('');
const categories = ref([]);
const loading = ref(false);
const errors = ref({});

// Image handling
const selectedImages = ref([]);
const imagePreview = ref([]);
const dragOver = ref(false);

// Fetch categories for dropdown
const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

onMounted(() => {
    fetchCategories();
});

// Image upload functions
const handleImageSelect = (event) => {
    const files = Array.from(event.target.files);
    processImages(files);
};

const handleDrop = (event) => {
    event.preventDefault();
    dragOver.value = false;
    const files = Array.from(event.dataTransfer.files);
    processImages(files);
};

const processImages = (files) => {
    files.forEach(file => {
        if (file.type.startsWith('image/')) {
            selectedImages.value.push(file);
            
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.value.push({
                    url: e.target.result,
                    name: file.name,
                    size: file.size
                });
            };
            reader.readAsDataURL(file);
        }
    });
};

const removeImage = (index) => {
    selectedImages.value.splice(index, 1);
    imagePreview.value.splice(index, 1);
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Currency formatting functions
const formatCurrencyInput = (value) => {
    // Remove all non-numeric characters
    const numericValue = value.replace(/[^\d]/g, '');

    // Convert to number and format with thousand separators
    if (numericValue === '') return '';

    return parseInt(numericValue).toLocaleString('id-ID');
};

const parseCurrencyValue = (formattedValue) => {
    // Remove all non-numeric characters to get the actual numeric value
    return parseInt(formattedValue.replace(/[^\d]/g, '')) || 0;
};

// Watchers for currency formatting
const handlePurchasePriceInput = (event) => {
    const value = event.target.value;
    const formatted = formatCurrencyInput(value);
    purchase_price.value = formatted;
};

const handleSellingPriceInput = (event) => {
    const value = event.target.value;
    const formatted = formatCurrencyInput(value);
    selling_price.value = formatted;
};

const submitForm = async (e) => {
    e.preventDefault();
    loading.value = true;
    errors.value = {};
    
    try {
        const formData = new FormData();
        formData.append('name', name.value);
        formData.append('sku', sku.value);
        formData.append('description', description.value);
        formData.append('purchase_price', parseCurrencyValue(purchase_price.value));
        formData.append('selling_price', parseCurrencyValue(selling_price.value));
        formData.append('stock', parseInt(stock.value) || 0);
        formData.append('unit', unit.value);
        formData.append('category_id', parseInt(category_id.value) || null);
        
        // Add images to form data
        selectedImages.value.forEach((image, index) => {
            formData.append(`images[${index}]`, image);
        });
        
        router.post('/products', formData, {
            forceFormData: true,
            onSuccess: () => {
                // Success handled by Inertia
            },
            onError: (errors) => {
                errors.value = errors;
            },
            onFinish: () => {
                loading.value = false;
            }
        });
    } catch (error) {
        console.error('Error creating product:', error);
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Create Product" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Create New Product</h1>
                        <p class="text-sm text-gray-500">Add a new product to your inventory</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <Link 
                        :href="route('products.index')" 
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Products
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <!-- Progress indicator -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-sm text-indigo-600">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-white">1</span>
                        <span class="ml-2 font-medium">Product Information</span>
                    </div>
                    <div class="ml-4 flex-1 h-px bg-gray-200"></div>
                </div>
            </div>

            <form @submit="submitForm" class="space-y-8">
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Product Details -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Basic Information Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">Product Details</h3>
                                <p class="mt-1 text-sm text-gray-600">Fill in the basic information about your product</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Product Name -->
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Product Name
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="name"
                                        type="text"
                                        id="name"
                                        required
                                        :class="[
                                            'block w-full px-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                            errors.name ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                        ]"
                                        placeholder="Enter product name"
                                    >
                                    <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                                </div>

                                <!-- SKU and Category Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="sku" class="block text-sm font-semibold text-gray-700 mb-2">
                                            SKU (Stock Keeping Unit)
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model="sku"
                                            type="text"
                                            id="sku"
                                            required
                                            :class="[
                                                'block w-full px-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                errors.sku ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                            ]"
                                            placeholder="e.g., PRD-001"
                                        >
                                        <p v-if="errors.sku" class="mt-1 text-sm text-red-600">{{ errors.sku }}</p>
                                    </div>

                                    <div>
                                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Category
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            v-model="category_id"
                                            id="category_id"
                                            required
                                            :class="[
                                                'block w-full px-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                errors.category_id ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                            ]"
                                        >
                                            <option value="">Select a category</option>
                                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                                {{ category.name }}
                                            </option>
                                        </select>
                                        <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id }}</p>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Product Description
                                    </label>
                                    <textarea
                                        v-model="description"
                                        id="description"
                                        rows="4"
                                        :class="[
                                            'block w-full px-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none',
                                            errors.description ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                        ]"
                                        placeholder="Enter detailed product description..."
                                    ></textarea>
                                    <p class="mt-1 text-sm text-gray-500">Provide a detailed description of the product (optional)</p>
                                    <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Inventory Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">Pricing & Inventory</h3>
                                <p class="mt-1 text-sm text-gray-600">Set pricing and stock information</p>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <!-- Stock -->
                                    <div>
                                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Initial Stock
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            v-model="stock"
                                            type="number"
                                            id="stock"
                                            required
                                            min="0"
                                            :class="[
                                                'block w-full px-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                errors.stock ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                            ]"
                                            placeholder="0"
                                        >
                                        <p v-if="errors.stock" class="mt-1 text-sm text-red-600">{{ errors.stock }}</p>
                                    </div>

                                    <!-- Unit -->
                                    <div>
                                        <label for="unit" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Unit
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            v-model="unit"
                                            id="unit"
                                            required
                                            :class="[
                                                'block w-full px-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                errors.unit ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                            ]"
                                        >
                                            <option value="pcs">Pieces (pcs)</option>
                                            <option value="kg">Kilogram (kg)</option>
                                            <option value="ltr">Liter (ltr)</option>
                                            <option value="box">Box</option>
                                            <option value="pack">Pack</option>
                                            <option value="dozen">Dozen</option>
                                            <option value="meter">Meter</option>
                                        </select>
                                        <p v-if="errors.unit" class="mt-1 text-sm text-red-600">{{ errors.unit }}</p>
                                    </div>

                                    <!-- Purchase Price -->
                                    <div>
                                        <label for="purchase_price" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Harga Beli
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative rounded-lg shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 text-lg font-medium">Rp</span>
                                            </div>
                                            <input
                                                :value="purchase_price"
                                                @input="handlePurchasePriceInput"
                                                type="text"
                                                id="purchase_price"
                                                required
                                                :class="[
                                                    'block w-full pl-12 pr-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                    errors.purchase_price ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                                ]"
                                                placeholder="0"
                                            >
                                        </div>
                                        <p v-if="errors.purchase_price" class="mt-1 text-sm text-red-600">{{ errors.purchase_price }}</p>
                                    </div>

                                    <!-- Selling Price -->
                                    <div>
                                        <label for="selling_price" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Harga Jual
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative rounded-lg shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 text-lg font-medium">Rp</span>
                                            </div>
                                            <input
                                                :value="selling_price"
                                                @input="handleSellingPriceInput"
                                                type="text"
                                                id="selling_price"
                                                required
                                                :class="[
                                                    'block w-full pl-12 pr-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                    errors.selling_price ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                                ]"
                                                placeholder="0"
                                            >
                                        </div>
                                        <p v-if="errors.selling_price" class="mt-1 text-sm text-red-600">{{ errors.selling_price }}</p>
                                    </div>
                                </div>

                                <!-- Profit calculation display -->
                                <div v-if="parseCurrencyValue(purchase_price) > 0 && parseCurrencyValue(selling_price) > 0" class="mt-6 bg-green-50 rounded-lg p-4 border border-green-200">
                                    <h4 class="text-sm font-semibold text-green-800 mb-2">Analisis Keuntungan</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-green-600">Keuntungan per unit:</span>
                                            <div class="font-semibold text-green-800">
                                                Rp {{ (parseCurrencyValue(selling_price) - parseCurrencyValue(purchase_price)).toLocaleString('id-ID') }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-green-600">Margin keuntungan:</span>
                                            <div class="font-semibold text-green-800">
                                                {{ parseCurrencyValue(selling_price) > 0 ? (((parseCurrencyValue(selling_price) - parseCurrencyValue(purchase_price)) / parseCurrencyValue(selling_price)) * 100).toFixed(1) : 0 }}%
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-green-600">Total nilai inventori:</span>
                                            <div class="font-semibold text-green-800">
                                                Rp {{ (parseCurrencyValue(purchase_price) * (stock || 0)).toLocaleString('id-ID') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Product Images -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-6">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">Product Images</h3>
                                <p class="mt-1 text-sm text-gray-600">Upload product photos</p>
                            </div>
                            <div class="p-6">
                                <!-- Image Upload Area -->
                                <div
                                    @drop="handleDrop"
                                    @dragover.prevent="dragOver = true"
                                    @dragleave="dragOver = false"
                                    :class="[
                                        'relative border-2 border-dashed rounded-lg p-6 text-center transition-colors duration-200',
                                        dragOver ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 hover:border-gray-400'
                                    ]"
                                >
                                    <input
                                        type="file"
                                        multiple
                                        accept="image/*"
                                        @change="handleImageSelect"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    >
                                    <div class="space-y-2">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium text-indigo-600">Click to upload</span> or drag and drop
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div v-if="imagePreview.length > 0" class="mt-6 space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-700">Selected Images ({{ imagePreview.length }})</h4>
                                    <div class="space-y-3">
                                        <div
                                            v-for="(image, index) in imagePreview"
                                            :key="index"
                                            class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg"
                                        >
                                            <img :src="image.url" :alt="image.name" class="h-12 w-12 object-cover rounded-lg">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ image.name }}</p>
                                                <p class="text-xs text-gray-500">{{ formatFileSize(image.size) }}</p>
                                            </div>
                                            <button
                                                type="button"
                                                @click="removeImage(index)"
                                                class="text-red-500 hover:text-red-700 transition-colors duration-200"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload Tips -->
                                <div class="mt-6 bg-blue-50 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">Image Tips</h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Use high-quality images (at least 800x800px)</li>
                                                    <li>Show product from multiple angles</li>
                                                    <li>Use good lighting and clean background</li>
                                                    <li>First image will be the main product image</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <Link 
                            :href="route('products.index')" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="loading"
                            :class="[
                                'inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg font-medium text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm',
                                loading 
                                    ? 'bg-indigo-400 cursor-not-allowed' 
                                    : 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800'
                            ]"
                        >
                            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ loading ? 'Creating Product...' : 'Create Product' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>