<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    bookings: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

const filterBookings = () => {
    router.get(route('admin.bookings.index'), {
        search: search.value,
        status: status.value,
    }, { preserveState: true });
};

const cancelBooking = (booking) => {
    if (confirm('Cancel this booking?')) {
        router.post(route('admin.bookings.cancel', booking.id));
    }
};

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
    <Head title="All Bookings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                All Bookings
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="bg-white shadow-sm sm:rounded-lg p-4 mb-6">
                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <input v-model="search" type="text" placeholder="Student/Teacher name or ref #" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select v-model="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">All</option>
                                <option value="pending_payment">Pending Payment</option>
                                <option value="pending_verification">Pending Verification</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="declined">Declined</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <button @click="filterBookings" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                            Filter
                        </button>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Teacher</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Date/Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Payment</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="booking in bookings.data" :key="booking.id">
                                <td class="px-4 py-3 text-sm">{{ booking.student?.name }}</td>
                                <td class="px-4 py-3 text-sm">{{ booking.teacher?.user?.name }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <div>{{ booking.slot?.slot_date }}</div>
                                    <div class="text-gray-500">{{ booking.slot?.start_time }} - {{ booking.slot?.end_time }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span :class="[statusColor(booking.status), 'px-2 py-1 text-xs font-medium rounded-full']">
                                        {{ booking.status.replace('_', ' ') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="uppercase text-xs">{{ booking.payment_method }}</div>
                                    <div class="text-gray-500">{{ booking.payment_reference }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <Link :href="route('admin.bookings.show', booking.id)" class="text-indigo-600 hover:text-indigo-900 text-sm mr-2">View</Link>
                                    <button v-if="['pending_payment', 'pending_verification', 'confirmed'].includes(booking.status)" @click="cancelBooking(booking)" class="text-red-600 hover:text-red-900 text-sm">Cancel</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
