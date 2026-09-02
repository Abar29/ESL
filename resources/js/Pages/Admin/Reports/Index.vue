<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    monthlyBookings: Array,
    topTeachers: Array,
    recentBookings: Array,
});

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
</script>

<template>
    <Head title="Reports" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Reports & Analytics
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500">Total Bookings</div>
                        <div class="text-2xl font-bold">{{ stats.total_bookings }}</div>
                    </div>
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500">Completed Sessions</div>
                        <div class="text-2xl font-bold text-green-600">{{ stats.completed_bookings }}</div>
                    </div>
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500">Active Teachers</div>
                        <div class="text-2xl font-bold">{{ stats.active_teachers }}</div>
                    </div>
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm text-gray-500">Total Revenue</div>
                        <div class="text-2xl font-bold text-indigo-600">₱{{ Number(stats.total_revenue).toLocaleString() }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Top Teachers -->
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Teachers</h3>
                        <div v-if="topTeachers.length === 0" class="text-gray-500 text-sm">No data yet.</div>
                        <div v-else class="space-y-3">
                            <div v-for="(teacher, index) in topTeachers" :key="teacher.id" class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-bold text-gray-400">{{ index + 1 }}</span>
                                    <div>
                                        <p class="font-medium">{{ teacher.user?.name }}</p>
                                        <p class="text-sm text-gray-500">{{ teacher.bookings_count }} completed sessions</p>
                                    </div>
                                </div>
                                <span class="text-yellow-500">★ {{ teacher.rating_avg || 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Bookings -->
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Bookings ({{ new Date().getFullYear() }})</h3>
                        <div class="space-y-2">
                            <div v-for="month in 12" :key="month" class="flex items-center gap-3">
                                <span class="text-sm text-gray-500 w-8">{{ months[month - 1] }}</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-4">
                                    <div class="bg-indigo-500 h-4 rounded-full" :style="{ width: ((monthlyBookings.find(m => m.month === month)?.count || 0) / Math.max(...monthlyBookings.map(m => m.count), 1) * 100) + '%' }"></div>
                                </div>
                                <span class="text-sm font-medium w-8 text-right">{{ monthlyBookings.find(m => m.month === month)?.count || 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="mt-6 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Bookings</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Student</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Teacher</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="booking in recentBookings" :key="booking.id">
                                    <td class="px-4 py-2 text-sm">{{ booking.student?.name }}</td>
                                    <td class="px-4 py-2 text-sm">{{ booking.teacher?.user?.name }}</td>
                                    <td class="px-4 py-2 text-sm">{{ booking.slot?.slot_date ? new Date(booking.slot.slot_date.includes('T') ? booking.slot.slot_date : booking.slot.slot_date + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full" :class="{
                                            'bg-green-100 text-green-800': booking.status === 'confirmed',
                                            'bg-blue-100 text-blue-800': booking.status === 'completed',
                                            'bg-red-100 text-red-800': booking.status === 'declined',
                                            'bg-gray-100 text-gray-800': booking.status === 'cancelled',
                                            'bg-orange-100 text-orange-800': booking.status === 'pending_verification',
                                        }">{{ booking.status }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
