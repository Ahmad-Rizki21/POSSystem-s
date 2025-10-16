<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);

const sidebarOpen = ref(false);

const navigation = computed(() => {
    const items = [
        {
            name: 'Dashboard',
            href: route('dashboard'),
            current: route().current('dashboard'),
            icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
        }
    ];

    // Admin menu items with submenus
    if (user.value.role && user.value.role.name === 'admin') {
        items.push(
            {
                name: 'Products',
                href: route('products.index'),
                current: route().current('products*'),
                icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                subItems: [
                    { name: 'All Products', href: route('products.index') },
                    { name: 'Add Product', href: route('products.create') },
                ]
            },
            {
                name: 'Categories',
                href: route('categories.index'),
                current: route().current('categories*'),
                icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                subItems: [
                    { name: 'All Categories', href: route('categories.index') },
                    { name: 'Add Category', href: route('categories.create') }
                ]
            },
            {
                name: 'Users',
                href: route('users.index'),
                current: route().current('users*'),
                icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                subItems: [
                    { name: 'All Users', href: route('users.index') },
                    { name: 'Add User', href: route('users.create') },
                    { name: 'Roles & Permissions', href: route('roles.index') }
                ]
            },
            {
                name: 'Reports',
                href: route('reports.index'),
                current: route().current('reports*'),
                icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                subItems: [
                    { name: 'Product Report', href: route('reports.products') },
                    { name: 'Financial Report', href: route('reports.financial') }
                ]
            }
        );
    }

    // POS menu items for both admin and cashier
    if (user.value.role && (user.value.role.name === 'cashier' || user.value.role.name === 'admin')) {
        items.push({
            name: 'Point of Sale',
            href: route('pos.create'),
            current: route().current('pos*'),
            icon: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
            subItems: [
                { name: 'New Sale', href: route('pos.create') },
                { name: 'Transaction History', href: route('pos.index') }
            ]
        });

        // Add Stock Management for both admin and cashier
        items.push({
            name: 'Stock Management',
            href: route('stock-adjustment.index'),
            current: route().current('stock-adjustment*'),
            icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            subItems: [
                { name: 'Stock Adjustments', href: route('stock-adjustment.index') },
                { name: 'Stock History', href: route('stock.history') },
                { name: 'New Adjustment', href: route('stock-adjustment.create') }
            ]
        });
    }

    return items;
});

const expandedItems = ref([]);

const toggleSubmenu = (itemName) => {
    const index = expandedItems.value.indexOf(itemName);
    if (index > -1) {
        expandedItems.value.splice(index, 1);
    } else {
        expandedItems.value.push(itemName);
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Mobile sidebar backdrop -->
        <transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" @click="sidebarOpen = false">
                <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
            </div>
        </transition>

        <!-- Mobile Sidebar Panel -->
        <transition
            enter-active-class="transition ease-in-out duration-300 transform"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition ease-in-out duration-300 transform"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <div v-if="sidebarOpen" class="fixed inset-y-0 left-0 z-50 w-80 lg:hidden">
                <div class="flex h-full flex-col bg-gradient-to-b from-indigo-600 to-indigo-700 shadow-2xl">
                    <!-- Sidebar Header with Close Button -->
                    <div class="flex h-20 items-center justify-between px-6 bg-indigo-800">
                        <Link :href="route('dashboard')" class="flex items-center" @click="sidebarOpen = false">
                            <ApplicationLogo class="block h-9 w-auto fill-current text-white" />
                            <span class="ml-3 text-xl font-bold text-white">Store POS</span>
                        </Link>
                        <button
                            @click="sidebarOpen = false"
                            class="rounded-lg p-2 text-indigo-200 hover:bg-indigo-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white transition-colors"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- User Info Card -->
                    <div class="mx-4 my-4 rounded-xl bg-white/10 backdrop-blur-sm p-4 border border-white/20">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-white/30 to-white/10 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ user.name }}</p>
                                <p class="text-xs text-indigo-200 truncate">{{ user.email }}</p>
                                <span v-if="user.role" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-white/20 text-white mt-1">
                                    {{ user.role.display_name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Navigation -->
                    <div class="flex-1 overflow-y-auto px-3 pb-4 mobile-scrollbar">
                        <nav class="space-y-1">
                            <template v-for="item in navigation" :key="item.name">
                                <!-- Main menu item without submenu -->
                                <Link
                                    v-if="!item.subItems"
                                    :href="item.href"
                                    @click="sidebarOpen = false"
                                    :class="[
                                        item.current
                                            ? 'bg-white/20 text-white shadow-lg'
                                            : 'text-indigo-100 hover:bg-white/10 hover:text-white',
                                        'group flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200'
                                    ]"
                                >
                                    <svg class="mr-3 h-5 w-5 flex-shrink-0" :class="[item.current ? 'text-white' : 'text-indigo-200 group-hover:text-white']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
                                    </svg>
                                    {{ item.name }}
                                </Link>

                                <!-- Menu item with submenu -->
                                <div v-else class="space-y-1">
                                    <button
                                        @click="toggleSubmenu(item.name)"
                                        :class="[
                                            item.current
                                                ? 'bg-white/20 text-white'
                                                : 'text-indigo-100 hover:bg-white/10 hover:text-white',
                                            'group flex items-center w-full px-4 py-3.5 text-sm font-medium rounded-xl transition-all duration-200'
                                        ]"
                                    >
                                        <svg class="mr-3 h-5 w-5 flex-shrink-0" :class="[item.current ? 'text-white' : 'text-indigo-200 group-hover:text-white']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
                                        </svg>
                                        <span class="flex-1 text-left">{{ item.name }}</span>
                                        <svg 
                                            :class="[
                                                'ml-3 h-4 w-4 transition-transform duration-200',
                                                expandedItems.includes(item.name) ? 'rotate-90' : ''
                                            ]" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>

                                    <!-- Submenu with smooth transition -->
                                    <transition
                                        enter-active-class="transition duration-200 ease-out"
                                        enter-from-class="transform scale-95 opacity-0 -translate-y-2"
                                        enter-to-class="transform scale-100 opacity-100 translate-y-0"
                                        leave-active-class="transition duration-150 ease-in"
                                        leave-from-class="transform scale-100 opacity-100 translate-y-0"
                                        leave-to-class="transform scale-95 opacity-0 -translate-y-2"
                                    >
                                        <div v-if="expandedItems.includes(item.name)" class="ml-4 mt-1 space-y-1 border-l-2 border-indigo-400/30 pl-4">
                                            <Link
                                                v-for="subItem in item.subItems"
                                                :key="subItem.name"
                                                :href="subItem.href"
                                                @click="sidebarOpen = false"
                                                class="group flex items-center px-3 py-2.5 text-sm text-indigo-100 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200"
                                            >
                                                <span class="w-1.5 h-1.5 bg-indigo-300 rounded-full mr-3 group-hover:bg-white group-hover:scale-125 transition-all"></span>
                                                {{ subItem.name }}
                                            </Link>
                                        </div>
                                    </transition>
                                </div>
                            </template>
                        </nav>
                    </div>

                    <!-- Sidebar Footer -->
                    <div class="border-t border-white/10 p-4">
                        <button
                            @click="$inertia.post(route('logout')); sidebarOpen = false"
                            class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium text-white bg-red-500/20 hover:bg-red-500/30 rounded-xl transition-colors duration-200 border border-red-400/30"
                        >
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Sign Out
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Desktop sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex flex-grow flex-col overflow-y-auto bg-gradient-to-b from-indigo-600 to-indigo-700 shadow-xl">
                <!-- Sidebar header -->
                <div class="flex h-20 flex-shrink-0 items-center px-6 bg-indigo-800">
                    <Link :href="route('dashboard')" class="flex items-center">
                        <ApplicationLogo class="block h-8 w-auto fill-current text-white" />
                        <span class="ml-3 text-xl font-bold text-white">Store POS</span>
                    </Link>
                </div>

                <!-- Sidebar navigation -->
                <div class="mt-5 flex flex-1 flex-col pb-4">
                    <nav class="flex-1 space-y-2 px-3">
                        <template v-for="item in navigation" :key="item.name">
                            <!-- Main menu item -->
                            <div>
                                <Link
                                    v-if="!item.subItems"
                                    :href="item.href"
                                    :class="[
                                        item.current
                                            ? 'bg-white bg-opacity-20 text-white border-r-4 border-white'
                                            : 'text-indigo-100 hover:bg-white hover:bg-opacity-10 hover:text-white',
                                        'group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200'
                                    ]"
                                >
                                    <svg class="mr-3 h-5 w-5 flex-shrink-0" :class="[item.current ? 'text-white' : 'text-indigo-200 group-hover:text-white']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
                                    </svg>
                                    {{ item.name }}
                                </Link>

                                <!-- Menu item with submenu -->
                                <div v-else>
                                    <button
                                        @click="toggleSubmenu(item.name)"
                                        :class="[
                                            item.current
                                                ? 'bg-white bg-opacity-20 text-white'
                                                : 'text-indigo-100 hover:bg-white hover:bg-opacity-10 hover:text-white',
                                            'group flex items-center w-full px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200'
                                        ]"
                                    >
                                        <svg class="mr-3 h-5 w-5 flex-shrink-0" :class="[item.current ? 'text-white' : 'text-indigo-200 group-hover:text-white']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
                                        </svg>
                                        <span class="flex-1 text-left">{{ item.name }}</span>
                                        <svg 
                                            :class="[
                                                'ml-3 h-4 w-4 transition-transform duration-200',
                                                expandedItems.includes(item.name) ? 'rotate-90' : ''
                                            ]" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>

                                    <!-- Submenu -->
                                    <transition
                                        enter-active-class="transition duration-200 ease-out"
                                        enter-from-class="transform scale-95 opacity-0"
                                        enter-to-class="transform scale-100 opacity-100"
                                        leave-active-class="transition duration-75 ease-in"
                                        leave-from-class="transform scale-100 opacity-100"
                                        leave-to-class="transform scale-95 opacity-0"
                                    >
                                        <div v-if="expandedItems.includes(item.name)" class="mt-2 space-y-1">
                                            <Link
                                                v-for="subItem in item.subItems"
                                                :key="subItem.name"
                                                :href="subItem.href"
                                                class="group flex items-center pl-12 pr-4 py-2 text-sm text-indigo-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-200"
                                            >
                                                <span class="w-2 h-2 bg-indigo-300 rounded-full mr-3 group-hover:bg-white"></span>
                                                {{ subItem.name }}
                                            </Link>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </template>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col lg:pl-72">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 flex-shrink-0 items-center justify-between border-b border-gray-200 bg-white shadow-sm backdrop-blur-sm bg-opacity-95">
                <!-- Mobile menu button -->
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="ml-4 inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden transition-colors duration-200"
                >
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Breadcrumb for larger screens -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-2 text-sm font-medium text-gray-700 capitalize">{{ $page.component.replace(/([A-Z])/g, ' $1').trim() }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- User dropdown and notifications -->
                <div class="ml-4 flex flex-1 items-center justify-end pr-4 space-x-4">
                    <!-- User dropdown -->
                    <Dropdown align="right" width="64">
                        <template #trigger>
                            <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-medium shadow-lg">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </div>
                                <span class="ml-2 text-gray-700 hidden sm:block font-medium">{{ user.name }}</span>
                                <svg class="ml-2 h-4 w-4 text-gray-400 hidden sm:block" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ user.email }}</p>
                                <p class="text-xs text-gray-500 mt-1" v-if="user.role">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="user.role.name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'">
                                        {{ user.role.display_name }}
                                    </span>
                                </p>
                            </div>
                            <div class="py-1">
                                <DropdownLink :href="route('profile.edit')" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile Settings
                                </DropdownLink>
                                <DropdownLink :href="route('profile.edit')" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Account Settings
                                </DropdownLink>
                                <div class="border-t border-gray-100"></div>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200"
                                >
                                    <svg class="mr-3 h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Sign Out
                                </DropdownLink>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow-sm border-b border-gray-200">
                <div class="mx-auto px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 pb-8 min-h-[calc(100vh-20rem)]">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>

        <!-- Mobile bottom navigation (for mobile devices) -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 lg:hidden z-50 safe-area-bottom">
            <div class="grid grid-cols-4 gap-1">
                <Link
                    v-for="item in navigation.slice(0, 4)"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        item.current
                            ? 'text-indigo-600 bg-indigo-50'
                            : 'text-gray-600 hover:text-gray-900',
                        'flex flex-col items-center justify-center px-3 py-2 text-xs font-medium transition-colors duration-200'
                    ]"
                >
                    <svg class="h-5 w-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
                    </svg>
                    <span class="truncate">{{ item.name }}</span>
                </Link>
            </div>
        </div>

        <!-- Add padding bottom for mobile bottom navigation -->
        <div class="pb-16 lg:pb-0"></div>

        <!-- Copyright Footer -->
        <footer class="bg-white border-t border-gray-200 py-4 mt-auto lg:pl-72">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600">
                    <div class="flex items-center mb-2 sm:mb-0">
                        <span class="text-gray-900 font-medium">Store POS System</span>
                        <span class="mx-2 text-gray-400">•</span>
                        <span>Point of Sale & Inventory Management</span>
                    </div>
                    <div class="flex items-center">
                        <span>&copy; {{ new Date().getFullYear() }} Store POS. All rights reserved.</span>
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-indigo-600 font-medium">Powered by Artacomindo</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Custom Scrollbar untuk Mobile Sidebar */
.mobile-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.mobile-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.mobile-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.mobile-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Safe area untuk notch di mobile */
.safe-area-bottom {
    padding-bottom: env(safe-area-inset-bottom);
}

/* Smooth scrolling */
@media (max-width: 1024px) {
    .mobile-scrollbar {
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }
}
</style>