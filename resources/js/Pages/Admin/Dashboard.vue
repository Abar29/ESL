<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>Admin Dashboard</template>

        <div class="space-y-6">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-6 text-white">
                <h2 class="text-2xl font-bold">Admin Control Panel</h2>
                <p class="mt-2 text-gray-300">Manage users, approve teachers, and oversee all bookings.</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <div class="text-2xl font-bold text-gray-900">{{ stats.total_users }}</div>
                    <div class="text-sm text-gray-500">Total Users</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <div class="text-2xl font-bold text-blue-600">{{ stats.total_teachers }}</div>
                    <div class="text-sm text-gray-500">Teachers</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <div class="text-2xl font-bold text-green-600">{{ stats.total_students }}</div>
                    <div class="text-sm text-gray-500">Students</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <div class="text-2xl font-bold text-orange-600">{{ stats.pending_approvals }}</div>
                    <div class="text-sm text-gray-500">Pending</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <div class="text-2xl font-bold text-purple-600">{{ stats.total_bookings }}</div>
                    <div class="text-sm text-gray-500">Bookings</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <div class="text-2xl font-bold text-emerald-600">{{ stats.active_bookings }}</div>
                    <div class="text-sm text-gray-500">Active</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link :href="route('admin.users.index')" class="bg-white rounded-xl p-6 border border-gray-200 hover:border-indigo-300 hover:shadow-md transition group">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Manage Users</h3>
                    <p class="text-sm text-gray-500 mt-1">Create, edit, delete accounts</p>
                </Link>

                <Link :href="route('admin.teacher-approvals.index')" class="bg-white rounded-xl p-6 border border-gray-200 hover:border-yellow-300 hover:shadow-md transition group">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-200 transition">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Teacher Approvals</h3>
                    <p class="text-sm text-gray-500 mt-1">Review registrations</p>
                </Link>

                <Link :href="route('admin.bookings.index')" class="bg-white rounded-xl p-6 border border-gray-200 hover:border-green-300 hover:shadow-md transition group">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-200 transition">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">All Bookings</h3>
                    <p class="text-sm text-gray-500 mt-1">View & manage bookings</p>
                </Link>

                <Link :href="route('admin.reports.index')" class="bg-white rounded-xl p-6 border border-gray-200 hover:border-purple-300 hover:shadow-md transition group">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-200 transition">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Reports</h3>
                    <p class="text-sm text-gray-500 mt-1">Analytics & insights</p>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
