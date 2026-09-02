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
    <Head title="Bookings" />

    <AuthenticatedLayout>
        <template #header>Bookings</template>

        <div>
            <div v-if="!bookings.data || bookings.data.length === 0" class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <p class="text-gray-500">No bookings yet.</p>
            </div>

            <div v-else class="space-y-3">
                <div v-for="booking in bookings.data" :key="booking.id" class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ booking.student?.name }}</p>
                            <p class="text-sm text-gray-500">{{ booking.slot?.slot_date }} | {{ booking.slot?.start_time }} - {{ booking.slot?.end_time }}</p>
                            <p v-if="booking.payment_reference" class="text-sm text-gray-400">Ref: {{ booking.payment_reference }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span :class="[statusColor(booking.status), 'px-2 py-1 text-xs font-medium rounded-full']">
                                {{ booking.status.replace('_', ' ') }}
                            </span>
                            <a :href="'/teacher/bookings/' + booking.id" class="text-indigo-600 hover:underline text-sm font-medium">
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
                    <span
                        v-else
                        class="px-3 py-2 text-sm rounded-lg text-gray-400"
                        v-html="link.label"
                    ></span>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
