<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    users: Object,
});

const deleteUser = (user) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('admin.users.destroy', user.id));
    }
};

const restoreUser = (user) => {
    router.post(route('admin.users.restore', user.id));
};
</script>

<template>
    <Head title="Manage Users" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Manage Users
                </h2>
                <Link :href="route('admin.users.create')" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                    Add User
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="user in users.data" :key="user.id" :class="{ 'bg-red-50': user.deleted_at }">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full" :class="{
                                        'bg-purple-100 text-purple-800': user.role === 'admin',
                                        'bg-blue-100 text-blue-800': user.role === 'teacher',
                                        'bg-green-100 text-green-800': user.role === 'student',
                                    }">{{ user.role }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span v-if="user.deleted_at" class="text-red-600">Deleted</span>
                                    <span v-else-if="user.status === 'suspended'" class="text-orange-600">Suspended</span>
                                    <span v-else class="text-green-600">Active</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link v-if="!user.deleted_at" :href="route('admin.users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                                    <button v-if="!user.deleted_at" @click="deleteUser(user)" class="text-red-600 hover:text-red-900">Delete</button>
                                    <button v-else @click="restoreUser(user)" class="text-green-600 hover:text-green-900">Restore</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
