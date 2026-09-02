<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    booking: Object,
});

const form = useForm({
    rating: 5,
    comment: '',
});

const hoverRating = ref(0);

const submit = () => {
    form.post(`/student/bookings/${props.booking.id}/review`);
};
</script>

<template>
    <Head title="Leave Review" />

    <AuthenticatedLayout>
        <template #header>Leave Review</template>

        <div class="max-w-2xl">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <!-- Session Info -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <p class="font-medium text-gray-900">Session with {{ booking.teacher?.user?.name }}</p>
                    <p class="text-sm text-gray-500">{{ booking.slot?.slot_date ? new Date(booking.slot.slot_date.includes('T') ? booking.slot.slot_date : booking.slot.slot_date + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '' }} · {{ booking.slot?.start_time }} - {{ booking.slot?.end_time }}</p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Star Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex gap-1">
                            <button
                                v-for="star in 5"
                                :key="star"
                                type="button"
                                @click="form.rating = star"
                                @mouseenter="hoverRating = star"
                                @mouseleave="hoverRating = 0"
                                class="text-4xl focus:outline-none transition-transform hover:scale-110"
                                :class="(hoverRating || form.rating) >= star ? 'text-yellow-400' : 'text-gray-300'"
                            >
                                ★
                            </button>
                        </div>
                        <InputError :message="form.errors.rating" class="mt-1" />
                    </div>

                    <!-- Comment -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Comment (optional)</label>
                        <textarea
                            id="comment"
                            v-model="form.comment"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            rows="4"
                            placeholder="Share your experience with this teacher..."
                        ></textarea>
                        <InputError :message="form.errors.comment" class="mt-1" />
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <a :href="'/student/bookings/' + booking.id" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
