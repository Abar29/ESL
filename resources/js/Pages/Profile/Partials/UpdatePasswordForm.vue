<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const saved = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put('/password', {
        preserveScroll: true,
        onSuccess: () => { form.reset(); saved.value = true; setTimeout(() => saved.value = false, 2000); },
        onError: () => {
            if (form.errors.password) { form.reset('password', 'password_confirmation'); passwordInput.value.focus(); }
            if (form.errors.current_password) { form.reset('current_password'); currentPasswordInput.value.focus(); }
        },
    });
};
</script>

<template>
    <form @submit.prevent="updatePassword" class="space-y-5">
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input
                id="current_password"
                ref="currentPasswordInput"
                v-model="form.current_password"
                type="password"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                autocomplete="current-password"
            />
            <InputError :message="form.errors.current_password" class="mt-1" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input
                id="password"
                ref="passwordInput"
                v-model="form.password"
                type="password"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                autocomplete="new-password"
            />
            <InputError :message="form.errors.password" class="mt-1" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                autocomplete="new-password"
            />
            <InputError :message="form.errors.password_confirmation" class="mt-1" />
        </div>

        <div class="flex items-center gap-3">
            <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50"
            >
                Update Password
            </button>
            <span v-if="saved" class="text-sm text-green-600 font-medium">Updated!</span>
        </div>
    </form>
</template>
