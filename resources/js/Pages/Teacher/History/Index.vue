<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    bookings: Object,
    stats: Object,
});
</script>

<template>
    <Head title="History & Ratings" />

    <AuthenticatedLayout>
        <template #header>History & Ratings</template>

        <div class="space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Completed</p>
                    <p class="mt-1 text-2xl font-bold text-green-600">{{ stats.total_completed }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Avg Rating</p>
                    <p class="mt-1 text-2xl font-bold text-yellow-500">★ {{ stats.average_rating || 'N/A' }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Cancelled</p>
                    <p class="mt-1 text-2xl font-bold text-gray-600">{{ stats.total_cancelled }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Declined</p>
                    <p class="mt-1 text-2xl font-bold text-red-600">{{ stats.total_declined }}</p>
                </div>
            </div>

            <!-- Completed Sessions -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Completed Sessions</h3>
                </div>

                <div v-if="!bookings.data || bookings.data.length === 0" class="p-12 text-center">
                    <p class="text-gray-500">No completed sessions yet.</p>
                </div>

                <div v-else class="divide-y">
                    <div v-for="booking in bookings.data" :key="booking.id" class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ booking.student?.name }}</p>
                                <p class="text-sm text-gray-500">{{ booking.slot?.slot_date }} · {{ booking.slot?.start_time }} - {{ booking.slot?.end_time }}</p>
                            </div>
                            <div v-if="booking.review" class="text-right">
                                <div class="flex items-center gap-0.5">
                                    <span v-for="star in 5" :key="star" class="text-lg" :class="star <= booking.review.rating ? 'text-yellow-400' : 'text-gray-300'">★</span>
                                </div>
                                <p v-if="booking.review.comment" class="mt-1 text-sm text-gray-600 max-w-xs">{{ booking.review.comment }}</p>
                            </div>
                            <div v-else class="text-sm text-gray-400">No review</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
