<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    slots: Array,
});

const showForm = ref(false);

const form = useForm({
    slot_date: '',
    start_time: '09:00',
    end_time: '10:00',
});

const groupedSlots = computed(() => {
    const groups = {};
    (props.slots || []).forEach(slot => {
        if (!groups[slot.slot_date]) {
            groups[slot.slot_date] = [];
        }
        groups[slot.slot_date].push(slot);
    });
    return Object.entries(groups).sort(([a], [b]) => a.localeCompare(b));
});

const statusColor = (status) => {
    const colors = {
        available: 'bg-green-100 text-green-800',
        booked: 'bg-blue-100 text-blue-800',
        unavailable: 'bg-gray-100 text-gray-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const submit = () => {
    form.post('/teacher/availability', {
        onSuccess: () => {
            showForm.value = false;
            form.reset();
        },
    });
};

const deleteSlot = (id) => {
    if (confirm('Delete this slot?')) {
        router.delete(`/teacher/availability/${id}`);
    }
};

const formatDate = (dateStr) => {
    if (!dateStr) return 'No Date';
    const date = new Date(dateStr.includes('T') ? dateStr : dateStr + 'T00:00:00');
    if (isNaN(date.getTime())) return 'Invalid Date';
    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
};
</script>

<template>
    <Head title="Manage Availability" />

    <AuthenticatedLayout>
        <template #header>Manage Availability</template>

        <div class="space-y-6">
            <!-- Add Slot Button -->
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">Manage your available time slots for students to book.</p>
                <button
                    @click="showForm = true"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Slot
                </button>
            </div>

            <!-- Slots List -->
            <div v-if="groupedSlots.length === 0" class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 font-medium">No availability slots yet</p>
                <p class="text-sm text-gray-400 mt-1">Click "Add Slot" to set your available time.</p>
            </div>

            <div v-else class="space-y-4">
                <div v-for="[date, daySlots] in groupedSlots" :key="date" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-900">{{ formatDate(date) }}</h3>
                    </div>
                    <div class="divide-y">
                        <div v-for="slot in daySlots" :key="slot.id" class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ slot.start_time }} - {{ slot.end_time }}
                                </div>
                                <span :class="[statusColor(slot.status), 'px-2 py-0.5 text-xs font-medium rounded-full']">
                                    {{ slot.status }}
                                </span>
                            </div>
                            <button
                                v-if="slot.status === 'available'"
                                @click="deleteSlot(slot.id)"
                                class="text-red-500 hover:text-red-700 text-sm font-medium"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Slot Modal -->
            <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showForm = false">
                <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Availability Slot</h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" v-model="form.slot_date" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                <input type="time" v-model="form.start_time" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                <input type="time" v-model="form.end_time" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required />
                            </div>
                        </div>
                        <p v-if="form.errors.slot_date" class="text-sm text-red-600">{{ form.errors.slot_date }}</p>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showForm = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50">Add Slot</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
