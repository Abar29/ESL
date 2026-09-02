<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({ password: '' });

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete('/profile', {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div>
        <p class="text-sm text-gray-600 mb-4">
            Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data you wish to retain.
        </p>

        <button
            @click="confirmUserDeletion"
            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700"
        >
            Delete Account
        </button>

        <!-- Modal -->
        <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeModal">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-900">Delete your account?</h3>
                <p class="mt-2 text-sm text-gray-600">
                    This action is permanent. Enter your password to confirm.
                </p>

                <div class="mt-4">
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="Enter your password"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" class="mt-1" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button
                        @click="deleteUser"
                        :disabled="form.processing"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50"
                    >
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
