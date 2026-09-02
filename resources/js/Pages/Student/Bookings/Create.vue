<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    slot: Object,
});

const form = useForm({
    slot_id: props.slot.id,
    payment_method: 'gcash',
    payment_reference: '',
    screenshot: null,
});

const handleFileChange = (e) => {
    form.screenshot = e.target.files[0];
};

const submit = () => {
    form.post('/student/bookings', { forceFormData: true });
};

const paymentDetails = {
    gcash: props.slot.teacher?.gcash_number,
    gotyme: props.slot.teacher?.gotyme_number,
    maya: props.slot.teacher?.maya_number,
};
</script>

<template>
    <Head title="Book Session" />

    <AuthenticatedLayout>
        <template #header>Book Session</template>

        <div class="max-w-2xl">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <!-- Slot Info -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <p class="font-medium text-gray-900">{{ slot.teacher?.user?.name }}</p>
                    <p class="text-sm text-gray-500">{{ slot.slot_date ? new Date(slot.slot_date.includes('T') ? slot.slot_date : slot.slot_date + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '' }} · {{ slot.start_time }} - {{ slot.end_time }}</p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
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
                        <p class="mt-1 text-xl font-bold text-blue-900">{{ paymentDetails[form.payment_method] || 'Not available' }}</p>
                    </div>

                    <!-- Reference Number -->
                    <div>
                        <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Payment Reference Number</label>
                        <input
                            id="payment_reference"
                            v-model="form.payment_reference"
                            type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        />
                        <InputError :message="form.errors.payment_reference" class="mt-1" />
                    </div>

                    <!-- Screenshot -->
                    <div>
                        <label for="screenshot" class="block text-sm font-medium text-gray-700 mb-1">Payment Screenshot</label>
                        <input
                            type="file"
                            id="screenshot"
                            @change="handleFileChange"
                            accept="image/jpeg,image/png"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            required
                        />
                        <InputError :message="form.errors.screenshot" class="mt-1" />
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <a :href="'/student/teachers/' + slot.teacher_id" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </a>
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
