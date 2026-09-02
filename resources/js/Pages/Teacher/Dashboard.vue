<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const teacherProfile = computed(() => user.value?.teacher_profile);
const isApproved = computed(() => teacherProfile.value?.approval_status === 'approved');
</script>

<template>
    <Head title="Teacher Dashboard" />

    <AuthenticatedLayout>
        <template #header>Teacher Dashboard</template>

        <div class="space-y-6">
            <!-- Pending Approval Banner -->
            <div v-if="!isApproved" class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-yellow-800">Account Pending Approval</h3>
                        <p class="mt-1 text-sm text-yellow-700">Your teacher account is being reviewed by admin. You'll be able to manage your schedule once approved.</p>
                    </div>
                </div>
            </div>

            <!-- Welcome Banner -->
            <div v-else class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold">Welcome back, {{ user?.name }}!</h2>
                        <p class="mt-1 text-purple-100">Ready to teach? Check your schedule and bookings.</p>
                    </div>
                    <Link
                        href="/teacher/availability"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 rounded-lg font-medium hover:bg-purple-50 transition-colors shadow-sm"
                    >
                        Manage Schedule
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link
                    href="/teacher/availability"
                    class="bg-white rounded-xl p-5 border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all group"
                    :class="{ 'opacity-50 pointer-events-none': !isApproved }"
                >
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Manage Schedule</h3>
                    <p class="text-sm text-gray-500 mt-1">Set your available time slots</p>
                </Link>

                <Link
                    href="/teacher/bookings"
                    class="bg-white rounded-xl p-5 border border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all group"
                    :class="{ 'opacity-50 pointer-events-none': !isApproved }"
                >
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Bookings</h3>
                    <p class="text-sm text-gray-500 mt-1">Review student bookings</p>
                </Link>

                <Link
                    href="/teacher/history"
                    class="bg-white rounded-xl p-5 border border-gray-200 hover:border-yellow-300 hover:shadow-md transition-all group"
                    :class="{ 'opacity-50 pointer-events-none': !isApproved }"
                >
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-200 transition-colors">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">History & Ratings</h3>
                    <p class="text-sm text-gray-500 mt-1">View completed sessions</p>
                </Link>

                <Link
                    href="/teacher/profile"
                    class="bg-white rounded-xl p-5 border border-gray-200 hover:border-green-300 hover:shadow-md transition-all group"
                >
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">My Profile</h3>
                    <p class="text-sm text-gray-500 mt-1">Update profile & payments</p>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
