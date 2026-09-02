<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    booking: Object,
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

const cancelBooking = () => {
    if (confirm('Cancel this booking?')) {
        router.post(route('admin.bookings.cancel', props.booking.id));
    }
};
</script>

<template>
    <Head title="Booking Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Booking Details
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium">Booking #{{ booking.id?.substring(0, 8) }}</h3>
                        <span :class="[statusColor(booking.status), 'px-3 py-1 text-sm font-medium rounded-full']">
                            {{ booking.status.replace('_', ' ') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Student Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Student</h4>
                            <p class="font-medium">{{ booking.student?.name }}</p>
                            <p class="text-sm text-gray-500">{{ booking.student?.email }}</p>
                        </div>

                        <!-- Teacher Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Teacher</h4>
                            <p class="font-medium">{{ booking.teacher?.user?.name }}</p>
                            <p class="text-sm text-gray-500">{{ booking.teacher?.user?.email }}</p>
                        </div>

                        <!-- Session Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Session</h4>
                            <p class="font-medium">{{ booking.slot?.slot_date ? new Date(booking.slot.slot_date.includes('T') ? booking.slot.slot_date : booking.slot.slot_date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }) : '' }}</p>
                            <p class="text-sm text-gray-500">{{ booking.slot?.start_time }} - {{ booking.slot?.end_time }}</p>
                        </div>

                        <!-- Payment Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Payment</h4>
                            <p class="font-bold text-indigo-600 text-lg">₱{{ booking.amount || '750.00' }}</p>
                            <p class="font-medium uppercase">{{ booking.payment_method }}</p>
                            <p class="text-sm text-gray-500">Ref: {{ booking.payment_reference }}</p>
                        </div>
                    </div>

                    <!-- Payment Screenshot -->
                    <div v-if="booking.screenshot_path" class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Screenshot</h4>
                        <img :src="'/storage/' + booking.screenshot_path" class="max-w-sm rounded-lg border" />
                    </div>

                    <!-- Review -->
                    <div v-if="booking.review" class="mt-6 p-4 bg-yellow-50 rounded-lg">
                        <h4 class="text-sm font-medium text-yellow-900 mb-2">Student Review</h4>
                        <div class="flex items-center gap-1">
                            <span v-for="star in 5" :key="star" class="text-lg" :class="star <= booking.review.rating ? 'text-yellow-400' : 'text-gray-300'">★</span>
                        </div>
                        <p v-if="booking.review.comment" class="mt-2 text-sm text-gray-700">{{ booking.review.comment }}</p>
                    </div>

                    <!-- Actions -->
                    <div v-if="['pending_payment', 'pending_verification', 'confirmed'].includes(booking.status)" class="mt-6">
                        <button @click="cancelBooking" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            Cancel Booking (Admin)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
