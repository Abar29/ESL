<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    teacher: Object,
    slots: Array,
});

const showBookingModal = ref(false);
const selectedSlot = ref(null);

const form = useForm({
    slot_id: '',
    payment_method: 'gcash',
    payment_reference: '',
    screenshot: null,
});

const paymentDetails = computed(() => {
    if (!selectedSlot.value) return {};
    return {
        gcash: { number: selectedSlot.value.teacher?.gcash_number, name: selectedSlot.value.teacher?.gcash_name },
        gotyme: { number: selectedSlot.value.teacher?.gotyme_number, name: selectedSlot.value.teacher?.gotyme_name },
        maya: { number: selectedSlot.value.teacher?.maya_number, name: selectedSlot.value.teacher?.maya_name },
    };
});

const formatDate = (date) => {
    if (!date) return 'No Date';
    const d = new Date(date.includes('T') ? date : date + 'T00:00:00');
    if (isNaN(d.getTime())) return 'Invalid Date';
    return d.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
};

const formatSlotDate = (date) => {
    if (!date) return 'No Date';
    const d = new Date(date.includes('T') ? date : date + 'T00:00:00');
    if (isNaN(d.getTime())) return 'Invalid Date';
    return d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
};

const openBooking = (slot) => {
    selectedSlot.value = slot;
    form.slot_id = slot.id;
    form.payment_method = 'gcash';
    form.payment_reference = '';
    form.screenshot = null;
    form.clearErrors();
    showBookingModal.value = true;
};

const closeBooking = () => {
    showBookingModal.value = false;
    selectedSlot.value = null;
    form.reset();
    form.clearErrors();
};

const handleFileChange = (e) => {
    form.screenshot = e.target.files[0];
};

const submit = () => {
    form.post('/student/bookings', {
        forceFormData: true,
        onSuccess: () => closeBooking(),
    });
};
</script>

<template>
    <Head :title="teacher.user?.name" />

    <AuthenticatedLayout>
        <template #header>{{ teacher.user?.name }}</template>

        <div class="max-w-4xl">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="p-6 text-center">
                            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-4 overflow-hidden">
                                <img v-if="teacher.profile_pic" :src="teacher.profile_pic.startsWith('http') || teacher.profile_pic.startsWith('data:') ? teacher.profile_pic : '/storage/' + teacher.profile_pic" class="w-20 h-20 rounded-full object-cover" />
                                <span v-else class="text-3xl font-bold text-indigo-600">{{ teacher.user?.name?.charAt(0)?.toUpperCase() }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ teacher.user?.name }}</h3>
                            <p v-if="teacher.user?.address" class="text-sm text-gray-500 mt-1">📍 {{ teacher.user.address }}</p>
                            <div class="flex items-center justify-center gap-1 mt-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-sm text-gray-600">{{ teacher.rating_avg || 'New teacher' }}</span>
                            </div>
                        </div>
                        <div class="px-6 pb-6 space-y-4">
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">About</h4>
                                <p class="text-sm text-gray-600">{{ teacher.bio || 'No bio available.' }}</p>
                            </div>
                            <div v-if="teacher.certificates?.length">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Certificates</h4>
                                <ul class="space-y-1">
                                    <li v-for="cert in teacher.certificates" :key="cert.id" class="text-sm text-gray-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                        </svg>
                                        {{ cert.title }}<span v-if="cert.issued_by" class="text-gray-400"> — {{ cert.issued_by }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Slots -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Available Slots</h3>
                        </div>

                        <div v-if="!slots || slots.length === 0" class="p-12 text-center">
                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500">No available slots at the moment.</p>
                        </div>

                        <div v-else class="divide-y">
                            <div v-for="slot in slots" :key="slot.id" class="flex items-center justify-between px-6 py-4 hover:bg-gray-50">
                                <div>
                                    <p class="font-medium text-gray-900">{{ formatDate(slot.slot_date) }}</p>
                                    <p class="text-sm text-gray-500">{{ slot.start_time }} - {{ slot.end_time }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-lg font-bold text-indigo-600">₱750</span>
                                    <button @click="openBooking(slot)" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Modal -->
        <div v-if="showBookingModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeBooking">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Book Session</h3>
                    <button @click="closeBooking" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <!-- Session Info -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="font-medium text-gray-900">{{ teacher.user?.name }}</p>
                        <p v-if="teacher.user?.address" class="text-sm text-gray-500">📍 {{ teacher.user.address }}</p>
                        <p class="text-sm text-gray-500">{{ selectedSlot ? formatSlotDate(selectedSlot.slot_date) : '' }} · {{ selectedSlot?.start_time }} - {{ selectedSlot?.end_time }}</p>
                        <p class="mt-2 text-lg font-bold text-indigo-600">₱750.00</p>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label
                                v-for="method in ['gcash', 'gotyme', 'maya']"
                                :key="method"
                                class="relative flex cursor-pointer rounded-lg border-2 p-3 text-center transition-colors"
                                :class="form.payment_method === method ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                            >
                                <input type="radio" v-model="form.payment_method" :value="method" class="sr-only" />
                                <span class="block text-sm font-semibold text-gray-900 uppercase w-full">{{ method }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Send payment to</p>
                        <p class="mt-1 text-xl font-bold text-blue-900">{{ paymentDetails[form.payment_method]?.number || 'Not available' }}</p>
                        <p v-if="paymentDetails[form.payment_method]?.name" class="text-sm text-blue-700 mt-1">Account Name: {{ paymentDetails[form.payment_method].name }}</p>
                    </div>

                    <!-- Reference Number -->
                    <div>
                        <label for="modal_payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Payment Reference Number</label>
                        <input
                            id="modal_payment_reference"
                            v-model="form.payment_reference"
                            type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        />
                        <InputError :message="form.errors.payment_reference" class="mt-1" />
                    </div>

                    <!-- Screenshot -->
                    <div>
                        <label for="modal_screenshot" class="block text-sm font-medium text-gray-700 mb-1">Payment Screenshot</label>
                        <input
                            type="file"
                            id="modal_screenshot"
                            @change="handleFileChange"
                            accept="image/jpeg,image/png"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            required
                        />
                        <InputError :message="form.errors.screenshot" class="mt-1" />
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="closeBooking" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Submit Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
