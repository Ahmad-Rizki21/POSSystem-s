<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: Object
});

const getRoleBadgeClass = (role) => {
    if (!role) return 'bg-gray-100 text-gray-800';
    return role.name === 'admin'
        ? 'bg-purple-100 text-purple-800'
        : role.name === 'cashier'
        ? 'bg-green-100 text-green-800'
        : 'bg-blue-100 text-blue-800';
};

const confirmDelete = (userId) => {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        axios.delete(`/api/users/${userId}`)
            .then(() => {
                window.location.href = route('users.index');
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                alert('Error deleting user');
            });
    }
};
</script>

<template>
    <Head title="User Detail" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">User Detail</h1>
                    <p class="mt-1 text-sm text-gray-600">View and manage user information</p>
                </div>
                <div>
                    <Link 
                        :href="route('users.index')" 
                        class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Users
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Profile Header -->
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8 text-center">
                            <div class="inline-flex h-24 w-24 rounded-full bg-white bg-opacity-20 backdrop-blur-sm items-center justify-center text-white font-bold text-3xl shadow-lg mb-4">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <h2 class="text-xl font-bold text-white">{{ user.name }}</h2>
                            <p class="text-indigo-100 text-sm mt-1">{{ user.email }}</p>
                        </div>

                        <!-- Profile Info -->
                        <div class="p-6 space-y-4">
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Role</h3>
                                <span 
                                    v-if="user.role" 
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold" 
                                    :class="getRoleBadgeClass(user.role)"
                                >
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    {{ user.role.display_name }}
                                </span>
                                <span v-else class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                    No Role Assigned
                                </span>
                            </div>

                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Member Since</h3>
                                <div class="flex items-center text-sm text-gray-900">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ new Date(user.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Actions & Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <Link 
                                :href="route('users.edit', user.id)" 
                                class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow"
                            >
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit User
                            </Link>
                            
                            <button
                                @click="confirmDelete(user.id)"
                                class="flex items-center justify-center px-4 py-3 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-all duration-200 shadow-sm hover:shadow"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete User
                            </button>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
                        </div>
                        
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-6">
                                <div class="flex items-start">
                                    <dt class="flex items-center text-sm font-medium text-gray-500 w-32">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Name
                                    </dt>
                                    <dd class="text-sm text-gray-900 font-medium">{{ user.name }}</dd>
                                </div>

                                <div class="flex items-start">
                                    <dt class="flex items-center text-sm font-medium text-gray-500 w-32">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Email
                                    </dt>
                                    <dd class="text-sm text-gray-900 font-medium">{{ user.email }}</dd>
                                </div>

                                <div class="flex items-start">
                                    <dt class="flex items-center text-sm font-medium text-gray-500 w-32">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        Role
                                    </dt>
                                    <dd class="text-sm text-gray-900 font-medium">
                                        {{ user.role ? user.role.display_name : 'No Role Assigned' }}
                                    </dd>
                                </div>

                                <div class="flex items-start">
                                    <dt class="flex items-center text-sm font-medium text-gray-500 w-32">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Joined
                                    </dt>
                                    <dd class="text-sm text-gray-900 font-medium">
                                        {{ new Date(user.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Activity Stats (Optional Enhancement) -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Overview</h3>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                                <div class="text-2xl font-bold text-blue-600">0</div>
                                <div class="text-xs text-blue-600 font-medium mt-1">Total Sales</div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                                <div class="text-2xl font-bold text-green-600">0</div>
                                <div class="text-xs text-green-600 font-medium mt-1">Transactions</div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                                <div class="text-2xl font-bold text-purple-600">Active</div>
                                <div class="text-xs text-purple-600 font-medium mt-1">Status</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>