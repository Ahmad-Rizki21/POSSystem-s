<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    product: Object,
    categories: Array
});

const name = ref(props.product.name);
const sku = ref(props.product.sku);
const description = ref(props.product.description || '');
const buying_price = ref(props.product.buying_price);
const selling_price = ref(props.product.selling_price);
const stock = ref(props.product.stock);
const category_id = ref(props.product.category_id);
const categories = ref(props.categories);
const loading = ref(false);
const errors = ref({});

// Image handling
const existingImages = ref(props.product.images || []);
const selectedImages = ref([]);
const imagePreview = ref([]);
const imagesToDelete = ref([]);
const dragOver = ref(false);

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
                    size: file.size,
                    isNew: true
                });
            };
            reader.readAsDataURL(file);
        }
    });
};

const removeNewImage = (index) => {
    selectedImages.value.splice(index, 1);
    imagePreview.value.splice(index, 1);
};

const removeExistingImage = (imageId) => {
    imagesToDelete.value.push(imageId);
    existingImages.value = existingImages.value.filter(img => img.id !== imageId);
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const submitForm = async (e) => {
    e.preventDefault();
    loading.value = true;
    errors.value = {};
    
    try {
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', name.value);
        formData.append('sku', sku.value);
        formData.append('description', description.value);
        formData.append('buying_price', parseFloat(buying_price.value) || 0);
        formData.append('selling_price', parseFloat(selling_price.value) || 0);
        formData.append('stock', parseInt(stock.value) || 0);
        formData.append('category_id', parseInt(category_id.value) || null);
        
        // Add new images to form data
        selectedImages.value.forEach((image, index) => {
            formData.append(`new_images[${index}]`, image);
        });
        
        // Add images to delete
        imagesToDelete.value.forEach((imageId, index) => {
            formData.append(`delete_images[${index}]`, imageId);
        });
        
        router.post(`/products/${props.product.id}`, formData, {
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
        console.error('Error updating product:', error);
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Edit Product" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
                        <p class="text-sm text-gray-500">Update product information</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <Link 
                        :href="route('products.show', product.id)" 
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Product
                    </Link>
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
            <!-- Product info banner -->
            <div class="mb-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-4 border border-indigo-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-indigo-900">{{ product.name }}</h3>
                        <p class="text-sm text-indigo-700">SKU: {{ product.sku }}</p>
                    </div>
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
                                <p class="mt-1 text-sm text-gray-600">Update the basic information about your product</p>
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
                                <p class="mt-1 text-sm text-gray-600">Update pricing and stock information</p>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Stock -->
                                    <div>
                                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Current Stock
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

                                    <!-- Buying Price -->
                                    <div>
                                        <label for="buying_price" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Harga Beli
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative rounded-lg shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 text-lg font-medium">Rp</span>
                                            </div>
                                            <input
                                                v-model="buying_price"
                                                type="number"
                                                step="1000"
                                                id="buying_price"
                                                required
                                                min="0"
                                                :class="[
                                                    'block w-full pl-12 pr-4 py-3 rounded-lg border shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500',
                                                    errors.buying_price ? 'border-red-300 focus:border-red-500' : 'border-gray-300 focus:border-indigo-500'
                                                ]"
                                                placeholder="0"
                                            >
                                        </div>
                                        <p v-if="errors.buying_price" class="mt-1 text-sm text-red-600">{{ errors.buying_price }}</p>
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
                                                v-model="selling_price"
                                                type="number"
                                                step="1000"
                                                id="selling_price"
                                                required
                                                min="0"
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
                                <div v-if="buying_price && selling_price" class="mt-6 bg-green-50 rounded-lg p-4 border border-green-200">
                                    <h4 class="text-sm font-semibold text-green-800 mb-2">Analisis Keuntungan</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-green-600">Keuntungan per unit:</span>
                                            <div class="font-semibold text-green-800">
                                                Rp {{ ((selling_price || 0) - (buying_price || 0)).toLocaleString('id-ID') }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-green-600">Margin keuntungan:</span>
                                            <div class="font-semibold text-green-800">
                                                {{ selling_price > 0 ? (((selling_price - buying_price) / selling_price) * 100).toFixed(1) : 0 }}%
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-green-600">Total nilai inventori:</span>
                                            <div class="font-semibold text-green-800">
                                                Rp {{ ((buying_price || 0) * (stock || 0)).toLocaleString('id-ID') }}
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
                                <p class="mt-1 text-sm text-gray-600">Manage product photos</p>
                            </div>
                            <div class="p-6">
                                <!-- Existing Images -->
                                <div v-if="existingImages.length > 0" class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Current Images ({{ existingImages.length }})</h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div
                                            v-for="image in existingImages"
                                            :key="image.id"
                                            class="relative group"
                                        >
                                            <img 
                                                :src="image.url" 
                                                :alt="image.alt || 'Product image'"
                                                class="w-full h-24 object-cover rounded-lg border border-gray-200"
                                            >
                                            <button
                                                type="button"
                                                @click="removeExistingImage(image.id)"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

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

                                <!-- New Images Preview -->
                                <div v-if="imagePreview.length > 0" class="mt-6 space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-700">New Images ({{ imagePreview.length }})</h4>
                                    <div class="space-y-3">
                                        <div
                                            v-for="(image, index) in imagePreview"
                                            :key="index"
                                            class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg border border-green-200"
                                        >
                                            <img :src="image.url" :alt="image.name" class="h-12 w-12 object-cover rounded-lg">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ image.name }}</p>
                                                <p class="text-xs text-gray-500">{{ formatFileSize(image.size) }}</p>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    New
                                                </span>
                                            </div>
                                            <button
                                                type="button"
                                                @click="removeNewImage(index)"
                                                class="text-red-500 hover:text-red-700 transition-colors duration-200"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Management Tips -->
                                <div class="mt-6 bg-yellow-50 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Image Management</h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Deleted images will be removed permanently</li>
                                                    <li>New images will be added after saving</li>
                                                    <li>First image will be the main product image</li>
                                                    <li>Changes are applied when you update the product</li>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ loading ? 'Updating Product...' : 'Update Product' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>