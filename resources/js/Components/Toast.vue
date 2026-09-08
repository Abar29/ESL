<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    message: String,
    type: {
        type: String,
        default: 'success',
    },
});

const emit = defineEmits(['close']);

const visible = ref(false);

watch(() => props.show, (val) => {
    if (val) {
        visible.value = true;
        setTimeout(() => {
            visible.value = false;
            emit('close');
        }, 3000);
    }
});
</script>

<template>
    <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div v-if="visible" class="fixed top-4 right-4 z-[100] flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-sm font-medium"
            role="status"
            aria-live="polite"
            :class="{
                'bg-green-50 text-green-800 border border-green-200': type === 'success',
                'bg-red-50 text-red-800 border border-red-200': type === 'error',
                'bg-blue-50 text-blue-800 border border-blue-200': type === 'info',
            }"
        >
            <svg aria-hidden="true" v-if="type === 'success'" class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg aria-hidden="true" v-else-if="type === 'error'" class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ message }}</span>
            <button @click="visible = false; emit('close')" class="ml-2 text-current opacity-50 hover:opacity-100 focus:ring-2 focus:ring-current focus:ring-offset-2 rounded" aria-label="Dismiss notification">
                <svg aria-hidden="true" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </Transition>
</template>
