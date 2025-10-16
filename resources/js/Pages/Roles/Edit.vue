<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    role: Object,
    permissions: Object
});

const form = ref({
    name: props.role.name,
    display_name: props.role.display_name,
    description: props.role.description,
    permissions: props.role.permissions.map(p => p.id)
});

const isSubmitting = ref(false);

const submitForm = () => {
    isSubmitting.value = true;

    router.put(`/roles/${props.role.id}`, form.value, {
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

const togglePermission = (permissionId) => {
    const index = form.value.permissions.indexOf(permissionId);
    if (index > -1) {
        form.value.permissions.splice(index, 1);
    } else {
        form.value.permissions.push(permissionId);
    }
};

const toggleGroup = (groupPermissions) => {
    const allPermissionIds = groupPermissions.flat().map(p => p.id);

    const allSelected = allPermissionIds.every(id => form.value.permissions.includes(id));

    if (allSelected) {
        form.value.permissions = form.value.permissions.filter(id => !allPermissionIds.includes(id));
    } else {
        allPermissionIds.forEach(id => {
            if (!form.value.permissions.includes(id)) {
                form.value.permissions.push(id);
            }
        });
    }
};

const isGroupSelected = (groupPermissions) => {
    const allPermissionIds = groupPermissions.flat().map(p => p.id);
    return allPermissionIds.every(id => form.value.permissions.includes(id));
};

const isGroupPartiallySelected = (groupPermissions) => {
    const allPermissionIds = groupPermissions.flat().map(p => p.id);
    const selectedCount = allPermissionIds.filter(id => form.value.permissions.includes(id)).length;
    return selectedCount > 0 && selectedCount < allPermissionIds.length;
};
</script>

<template>
    <Head title="Edit Role" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        Edit Role
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">Update role information and permissions</p>
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
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                    <div class="h-10 w-10 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    Role Information
                                </h2>
                                <p class="mt-2 text-sm text-blue-100">Update the details for this role</p>
                            </div>
                            <div class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <span class="text-xs font-medium text-white">ID: {{ role.id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <form @submit="submitForm" class="p-8 space-y-6">
                        <!-- Role Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Role Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        id="name"
                                        required
                                        class="block w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="e.g., admin, cashier, manager"
                                    >
                                </div>
                                <p class="mt-2 text-xs text-gray-500">System identifier for the role</p>
                            </div>

                            <div>
                                <label for="display_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Display Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input
                                        v-model="form.display_name"
                                        type="text"
                                        id="display_name"
                                        required
                                        class="block w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="e.g., Administrator, Cashier, Manager"
                                    >
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Human-readable name for the role</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>
                            <div class="relative">
                                <textarea
                                    v-model="form.description"
                                    id="description"
                                    rows="3"
                                    class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                    placeholder="Describe what this role can do..."
                                ></textarea>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Optional: Add details about this role's purpose</p>
                        </div>

                        <!-- Permissions -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Permissions</h3>
                                <div class="text-sm text-gray-500">
                                    Selected: {{ form.permissions.length }} permissions
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div v-for="(groupPermissions, groupName) in permissions" :key="groupName" class="border border-gray-200 rounded-xl overflow-hidden">
                                    <!-- Group Header -->
                                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                    </svg>
                                                </div>
                                                <h4 class="font-semibold text-gray-900">{{ groupName }}</h4>
                                            </div>
                                            <button
                                                type="button"
                                                @click="toggleGroup(groupPermissions)"
                                                :class="[
                                                    'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                                    isGroupSelected(groupPermissions)
                                                        ? 'bg-blue-600 text-white hover:bg-blue-700'
                                                        : isGroupPartiallySelected(groupPermissions)
                                                        ? 'bg-blue-100 text-blue-700 hover:bg-blue-200'
                                                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                                                ]"
                                            >
                                                {{ isGroupSelected(groupPermissions) ? 'Deselect All' : 'Select All' }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Permissions List -->
                                    <div class="p-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div v-for="permission in groupPermissions" :key="permission.id" class="flex items-start gap-3">
                                                <input
                                                    type="checkbox"
                                                    :id="`permission-${permission.id}`"
                                                    :value="permission.id"
                                                    v-model="form.permissions"
                                                    class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                >
                                                <div class="flex-1">
                                                    <label :for="`permission-${permission.id}`" class="font-medium text-gray-900 cursor-pointer">
                                                        {{ permission.display_name }}
                                                    </label>
                                                    <p class="text-sm text-gray-500">{{ permission.description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <Link
                                :href="route('roles.index')"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:transform-none disabled:cursor-not-allowed"
                            >
                                <svg v-if="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg v-else class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isSubmitting ? 'Updating...' : 'Update Role' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Info Section -->
                <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-amber-900 mb-2">Important Note</h3>
                            <p class="text-sm text-amber-800">
                                Updating this role will affect {{ role.users_count || 0 }} user(s).
                                Make sure the changes are appropriate for all users in this role.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>