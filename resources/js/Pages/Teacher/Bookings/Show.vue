<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Toast from '@/Components/Toast.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    booking: Object,
});

const toastShow = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

const showToast = (message, type = 'success') => {
    toastMessage.value = message;
    toastType.value = type;
    toastShow.value = true;
};

const statusColor = (status) => {
    const colors = {
        pending_verification: 'bg-orange-100 text-orange-800',
        confirmed: 'bg-green-100 text-green-800',
        declined: 'bg-red-100 text-red-800',
        completed: 'bg-blue-100 text-blue-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const canVerify = computed(() => {
    return props.booking.status === 'pending_verification';
});

const canComplete = computed(() => {
    return props.booking.status === 'confirmed';
});

const accept = () => {
    router.post(`/teacher/bookings/${props.booking.id}/accept`, {}, {
        onSuccess: () => showToast('Booking accepted!'),
        onError: () => showToast('Failed to accept booking.', 'error'),
    });
};

const decline = () => {
    if (confirm('Are you sure you want to decline this booking?')) {
        router.post(`/teacher/bookings/${props.booking.id}/decline`, {}, {
            onSuccess: () => showToast('Booking declined.'),
            onError: () => showToast('Failed to decline booking.', 'error'),
        });
    }
};

const complete = () => {
    if (confirm('Mark this session as completed?')) {
        router.post(`/teacher/bookings/${props.booking.id}/complete`, {}, {
            onSuccess: () => showToast('Session marked as completed!'),
            onError: () => showToast('Failed to complete session.', 'error'),
        });
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
                    <h3 class="text-lg font-semibold text-gray-900">Booking from {{ booking.student?.name }}</h3>
                    <span :class="[statusColor(booking.status), 'px-3 py-1 text-xs font-medium rounded-full']">
                        {{ booking.status.replace('_', ' ') }}
                    </span>
                </div>

                <!-- Details -->
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="font-medium text-gray-900">{{ booking.slot?.slot_date ? new Date(booking.slot.slot_date.includes('T') ? booking.slot.slot_date : booking.slot.slot_date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }) : '' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Time</p>
                            <p class="font-medium text-gray-900">{{ booking.slot?.start_time }} - {{ booking.slot?.end_time }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="font-medium text-gray-900 uppercase">{{ booking.payment_method }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Amount</p>
                            <p class="font-bold text-indigo-600">₱{{ booking.amount || '750.00' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Reference Number</p>
                            <p class="font-medium text-gray-900">{{ booking.payment_reference }}</p>
                        </div>
                    </div>

                    <!-- Payment Screenshot -->
                    <div v-if="booking.screenshot_path">
                        <p class="text-sm text-gray-500 mb-2">Payment Screenshot</p>
                        <img :src="'/storage/' + booking.screenshot_path" class="max-w-full h-auto rounded-lg border" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex gap-3">
                        <button v-if="canVerify" @click="accept" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            Accept Booking
                        </button>
                        <button v-if="canVerify" @click="decline" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Decline Booking
                        </button>
                        <button v-if="canComplete" @click="complete" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Mark as Completed
                        </button>
                        <a href="/teacher/bookings" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Back to Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <Toast :show="toastShow" :message="toastMessage" :type="toastType" @close="toastShow = false" />
</template>
