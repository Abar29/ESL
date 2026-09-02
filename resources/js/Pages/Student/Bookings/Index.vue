<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    bookings: Object,
});

const statusColor = (status) => {
    const colors = {
        pending_payment: 'bg-yellow-100 text-yellow-800',
        pending_verification: 'bg-orange-100 text-orange-800',
        confirmed: 'bg-green-100 text-green-800',
        declined: 'bg-red-100 text-red-800',
        cancelled: 'bg-gray-100 text-gray-800',
        completed: 'bg-blue-100 text-blue-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="My Bookings" />

    <AuthenticatedLayout>
        <template #header>My Bookings</template>

        <div>
            <!-- Empty State -->
            <div v-if="!bookings.data || bookings.data.length === 0" class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 font-medium mb-2">No bookings yet</p>
                <a href="/student/teachers" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Browse Teachers to get started</a>
            </div>

            <!-- Bookings List -->
            <div v-else class="space-y-3">
                <div v-for="booking in bookings.data" :key="booking.id" class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-indigo-600">{{ booking.teacher?.user?.name?.charAt(0)?.toUpperCase() }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ booking.teacher?.user?.name }}</p>
                                <p class="text-sm text-gray-500">{{ booking.slot?.slot_date }} · {{ booking.slot?.start_time }} - {{ booking.slot?.end_time }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span :class="[statusColor(booking.status), 'px-2.5 py-1 text-xs font-medium rounded-full']">
                                {{ booking.status.replace('_', ' ') }}
                            </span>
                            <a :href="'/student/bookings/' + booking.id" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="bookings.links && bookings.links.length > 3" class="mt-6 flex justify-center gap-2">
                <template v-for="link in bookings.links" :key="link.label">
                    <a
                        v-if="link.url"
                        :href="link.url"
                        class="px-3 py-2 text-sm rounded-lg"
                        :class="link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'"
                        v-html="link.label"
                    ></a>
                    <span v-else class="px-3 py-2 text-sm rounded-lg text-gray-400" v-html="link.label"></span>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
