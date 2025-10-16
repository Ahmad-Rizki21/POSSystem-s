<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Reports" />

    <AuthenticatedLayout :fullPage="true">
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        Reports
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-gray-600">Generate and view business reports and analytics</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button class="inline-flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <span class="hidden sm:inline">Filter</span>
                    </button>
                </div>
            </div>
        </template>

        <!-- Full Page Content -->
        <div class="h-full bg-gradient-to-br from-gray-50 via-white to-indigo-50">
            <div class="h-full overflow-y-auto">
                <div class="max-w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">
                    
                    <!-- Report Types Grid - Full Width -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Product Report Card -->
                        <Link :href="route('reports.products')" class="group">
                            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-full">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 sm:p-8">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="h-14 w-14 sm:h-16 sm:w-16 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <h2 class="text-xl sm:text-2xl font-bold text-white mb-2">Product Report</h2>
                                            <p class="text-sm sm:text-base text-blue-100">Track product performance and inventory</p>
                                        </div>
                                        <div class="text-white opacity-80 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200 ml-4">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-indigo-50">
                                    <div class="grid grid-cols-3 gap-3 sm:gap-4">
                                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                            <div class="text-xl sm:text-2xl font-bold text-gray-900">Top</div>
                                            <div class="text-xs sm:text-sm text-gray-500 mt-1">Selling</div>
                                        </div>
                                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                            <div class="text-xl sm:text-2xl font-bold text-gray-900">Stock</div>
                                            <div class="text-xs sm:text-sm text-gray-500 mt-1">Levels</div>
                                        </div>
                                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                            <div class="text-xl sm:text-2xl font-bold text-gray-900">Category</div>
                                            <div class="text-xs sm:text-sm text-gray-500 mt-1">Analysis</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Link>

                        <!-- Financial Report Card -->
                        <Link :href="route('reports.financial')" class="group">
                            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-full">
                                <div class="bg-gradient-to-r from-yellow-500 to-amber-600 p-6 sm:p-8">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="h-14 w-14 sm:h-16 sm:w-16 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4">
                                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <h2 class="text-xl sm:text-2xl font-bold text-white mb-2">Financial Report</h2>
                                            <p class="text-sm sm:text-base text-yellow-100">Track revenue, profit, costs & margins</p>
                                        </div>
                                        <div class="text-white opacity-80 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200 ml-4">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 sm:p-6 bg-gradient-to-br from-yellow-50 to-amber-50">
                                    <div class="grid grid-cols-3 gap-3 sm:gap-4">
                                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                            <div class="text-xl sm:text-2xl font-bold text-gray-900">Revenue</div>
                                            <div class="text-xs sm:text-sm text-gray-500 mt-1">Analysis</div>
                                        </div>
                                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                            <div class="text-xl sm:text-2xl font-bold text-gray-900">Profit</div>
                                            <div class="text-xs sm:text-sm text-gray-500 mt-1">Margins</div>
                                        </div>
                                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                            <div class="text-xl sm:text-2xl font-bold text-gray-900">Costs</div>
                                            <div class="text-xs sm:text-sm text-gray-500 mt-1">Tracking</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Report Features Section -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 sm:px-8 py-5 sm:py-6">
                            <h3 class="text-lg sm:text-xl font-bold text-white flex items-center gap-3">
                                <div class="h-10 w-10 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                Report Features
                            </h3>
                            <p class="mt-2 text-xs sm:text-sm text-purple-100">Comprehensive analytics for your business</p>
                        </div>

                        <div class="p-6 sm:p-8">
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                                <!-- Feature 1 -->
                                <div class="text-center group hover:scale-105 transition-transform duration-200">
                                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:shadow-lg transition-shadow">
                                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-sm sm:text-base text-gray-900 mb-1 sm:mb-2">Real-time Data</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Live insights from your transactions</p>
                                </div>

                                <!-- Feature 2 -->
                                <div class="text-center group hover:scale-105 transition-transform duration-200">
                                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:shadow-lg transition-shadow">
                                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-sm sm:text-base text-gray-900 mb-1 sm:mb-2">Custom Periods</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Filter by date ranges and periods</p>
                                </div>

                                <!-- Feature 3 -->
                                <div class="text-center group hover:scale-105 transition-transform duration-200">
                                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:shadow-lg transition-shadow">
                                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-sm sm:text-base text-gray-900 mb-1 sm:mb-2">Trend Analysis</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Track performance over time</p>
                                </div>

                                <!-- Feature 4 -->
                                <div class="text-center group hover:scale-105 transition-transform duration-200">
                                    <div class="h-14 w-14 sm:h-16 sm:w-16 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:shadow-lg transition-shadow">
                                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-sm sm:text-base text-gray-900 mb-1 sm:mb-2">Export Data</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Download reports in various formats</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Getting Started Section -->
                    <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 rounded-xl sm:rounded-2xl p-6 sm:p-8 border border-indigo-200">
                        <div class="text-center max-w-4xl mx-auto">
                            <div class="h-14 w-14 sm:h-16 sm:w-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Getting Started with Reports</h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-6 max-w-2xl mx-auto">
                                Choose a report type above to start analyzing your business data. Reports are updated in real-time
                                and can be filtered by date ranges, categories, and more.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                                <Link :href="route('reports.products')" class="inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm sm:text-base font-semibold rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    View Product Report
                                </Link>
                                <Link :href="route('reports.financial')" class="inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-yellow-600 to-amber-600 text-white text-sm sm:text-base font-semibold rounded-lg sm:rounded-xl hover:from-yellow-700 hover:to-amber-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    View Financial Report
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Reports</h4>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">2</p>
                        </div>

                        <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Last Updated</h4>
                            <p class="text-base sm:text-lg font-bold text-gray-900">Just now</p>
                        </div>

                        <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Data Points</h4>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">1000+</p>
                        </div>

                        <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active Users</h4>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">24/7</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>