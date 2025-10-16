<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    role: Object
});

const formatPermissions = (permissions) => {
    const grouped = {};
    permissions.forEach(permission => {
        if (!grouped[permission.group]) {
            grouped[permission.group] = [];
        }
        grouped[permission.group].push(permission);
    });
    return grouped;
};
</script>

<template>
    <Head title="Role Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        Role Details
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">View detailed information about this role</p>
                </div>
                <div>
                    <Link
                        :href="route('roles.index')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Roles
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8 px-6 sm:px-8 lg:px-12">
            <div class="w-full max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Role Header -->
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">{{ role.display_name }}</h2>
                                    <p class="text-purple-100">{{ role.name }}</p>
                                </div>
                            </div>
                            <div class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <span class="text-sm font-medium text-white">ID: {{ role.id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Role Information -->
                    <div class="p-8 space-y-6">
                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Display Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-medium">{{ role.display_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">System Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-medium">{{ role.name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <span v-if="role.description">{{ role.description }}</span>
                                            <span v-else class="text-gray-400 italic">No description provided</span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ new Date(role.created_at).toLocaleDateString() }}</dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-purple-50 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-purple-900">Permissions</span>
                                        </div>
                                        <div class="text-2xl font-bold text-purple-900">{{ role.permissions.length }}</div>
                                    </div>
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-900">Users</span>
                                        </div>
                                        <div class="text-2xl font-bold text-blue-900">{{ role.users_count || 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Permissions</h3>
                            <div v-if="role.permissions.length > 0" class="space-y-4">
                                <div v-for="(groupPermissions, groupName) in formatPermissions(role.permissions)" :key="groupName" class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                                        <div class="h-6 w-6 rounded bg-purple-100 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </div>
                                        {{ groupName }}
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div v-for="permission in groupPermissions" :key="permission.id" class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ permission.display_name }}</div>
                                                <div class="text-xs text-gray-500">{{ permission.name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="bg-gray-50 rounded-lg p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No permissions assigned</h3>
                                <p class="mt-1 text-sm text-gray-500">This role doesn't have any permissions assigned.</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-6 border-t border-gray-200">
                            <Link
                                :href="route('roles.edit', role.id)"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Role
                            </Link>
                            <Link
                                :href="route('roles.index')"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to List
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>