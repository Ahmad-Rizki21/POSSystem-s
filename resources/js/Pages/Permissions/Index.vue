<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    permissions: Object
});

const search = ref('');
const isDeleting = ref(false);

const allPermissions = computed(() => {
    const all = [];
    Object.entries(props.permissions).forEach(([group, permissions]) => {
        permissions.forEach(permission => {
            all.push({ ...permission, group });
        });
    });
    return all;
});

const filteredPermissions = computed(() => {
    if (!search.value) return allPermissions.value;

    return allPermissions.value.filter(permission =>
        permission.name.toLowerCase().includes(search.value.toLowerCase()) ||
        permission.display_name.toLowerCase().includes(search.value.toLowerCase()) ||
        permission.group.toLowerCase().includes(search.value.toLowerCase())
    );
});

const deletePermission = (permission) => {
    if (confirm(`Are you sure you want to delete the permission "${permission.display_name}"?`)) {
        isDeleting.value = true;
        router.delete(`/permissions/${permission.id}`, {
            onFinish: () => {
                isDeleting.value = false;
            }
        });
    }
};
</script>

<template>
    <Head title="Permissions" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        Permissions
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">Manage system permissions and access control</p>
                </div>
                <div class="flex gap-3">
                    <Link
                        :href="route('roles.index')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Roles
                    </Link>
                    <Link
                        :href="route('permissions.create')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-semibold hover:from-indigo-700 hover:to-blue-700 transition-all duration-200 shadow-lg"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Permission
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8 px-6 sm:px-8 lg:px-12">
            <div class="w-full max-w-full">
                <!-- Search and Stats -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Total Permissions</h3>
                                <p class="text-sm text-gray-500">{{ allPermissions.length }} permissions configured</p>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input
                                v-model="search"
                                type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Search permissions..."
                            >
                        </div>
                    </div>
                </div>

                <!-- Permissions by Groups -->
                <div v-for="(groupPermissions, groupName) in permissions" :key="groupName" class="mb-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Group Header -->
                        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-8 py-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ groupName }}</h3>
                                        <p class="text-sm text-indigo-100">{{ groupPermissions.length }} permissions</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions List -->
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-900">Permission</th>
                                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-900">Description</th>
                                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-900">Used By</th>
                                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-900">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="permission in groupPermissions" :key="permission.id" class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-4 px-4">
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ permission.display_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ permission.name }}</div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-sm text-gray-600">{{ permission.description }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div v-if="permission.roles && permission.roles.length > 0" class="flex flex-wrap gap-1">
                                                    <span
                                                        v-for="role in permission.roles"
                                                        :key="role.id"
                                                        class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full"
                                                    >
                                                        {{ role.display_name }}
                                                    </span>
                                                </div>
                                                <div v-else class="text-sm text-gray-400 italic">Not used</div>
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <Link
                                                        :href="route('permissions.edit', permission.id)"
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </Link>
                                                    <button
                                                        @click="deletePermission(permission)"
                                                        :disabled="isDeleting"
                                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="Object.keys(permissions).length === 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No permissions found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first permission.</p>
                    <div class="mt-6">
                        <Link
                            :href="route('permissions.create')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Permission
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>