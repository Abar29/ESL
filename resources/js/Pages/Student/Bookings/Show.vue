<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    booking: Object,
});

const canJoin = ref(false);
let interval = null;

const checkCanJoin = () => {
    if (props.booking.status !== 'confirmed' || !props.booking.slot) {
        canJoin.value = false;
        return;
    }
    const sessionStart = new Date(`${props.booking.slot.slot_date}T${props.booking.slot.start_time}`);
    const diffMins = (sessionStart - new Date()) / 1000 / 60;
    canJoin.value = diffMins <= 10 && diffMins >= -60;
};

onMounted(() => { checkCanJoin(); interval = setInterval(checkCanJoin, 30000); });
onUnmounted(() => { if (interval) clearInterval(interval); });

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

const canCancel = computed(() => ['pending_payment', 'pending_verification'].includes(props.booking.status));
const canReview = computed(() => props.booking.status === 'completed' && !props.booking.review);

const cancel = () => {
    if (confirm('Are you sure you want to cancel this booking?')) {
        router.post(`/student/bookings/${props.booking.id}/cancel`);
    }
};
</script>

<template>
    <Head title="Booking Details" />

    <AuthenticatedLayout>
        <template #header>Booking Details</template>

        <div class="max-w-2xl">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Session with {{ booking.teacher?.user?.name }}</h3>
                    <span :class="[statusColor(booking.status), 'px-3 py-1 text-xs font-medium rounded-full']">
                        {{ booking.status.replace('_', ' ') }}
                    </span>
                </div>

                <!-- Details -->
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</p>
                            <p class="mt-0.5 font-medium text-gray-900">{{ booking.slot?.slot_date }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</p>
                            <p class="mt-0.5 font-medium text-gray-900">{{ booking.slot?.start_time }} - {{ booking.slot?.end_time }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment</p>
                            <p class="mt-0.5 font-medium text-gray-900 uppercase">{{ booking.payment_method }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference</p>
                            <p class="mt-0.5 font-medium text-gray-900">{{ booking.payment_reference }}</p>
                        </div>
                    </div>

                    <!-- Payment Screenshot -->
                    <div v-if="booking.screenshot_path">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Payment Screenshot</p>
                        <img :src="'/storage/' + booking.screenshot_path" class="max-w-full h-auto rounded-lg border" />
                    </div>

                    <!-- Join Meeting -->
                    <div v-if="booking.status === 'confirmed'">
                        <div v-if="canJoin" class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm font-medium text-green-900 mb-2">Session is starting soon!</p>
                            <a :href="booking.teacher?.zoom_link" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Join Meeting
                            </a>
                        </div>
                        <div v-else class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Join button will appear 10 minutes before the session starts.</p>
                        </div>
                    </div>

                    <!-- Review -->
                    <div v-if="booking.review" class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm font-medium text-yellow-900 mb-1">Your Review</p>
                        <div class="flex items-center gap-0.5">
                            <span v-for="star in 5" :key="star" class="text-lg" :class="star <= booking.review.rating ? 'text-yellow-400' : 'text-gray-300'">★</span>
                        </div>
                        <p v-if="booking.review.comment" class="mt-2 text-sm text-gray-700">{{ booking.review.comment }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-2">
                        <a v-if="canReview" :href="'/student/bookings/' + booking.id + '/review'" class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">
                            Leave Review
                        </a>
                        <button v-if="canCancel" @click="cancel" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Cancel Booking
                        </button>
                        <a href="/student/bookings" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
