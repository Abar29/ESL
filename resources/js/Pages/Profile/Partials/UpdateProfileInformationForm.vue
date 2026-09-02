<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const saved = ref(false);

const submit = () => {
    form.patch('/profile', {
        onSuccess: () => { saved.value = true; setTimeout(() => saved.value = false, 2000); },
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
                id="name"
                type="text"
                v-model="form.name"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                required
            />
            <InputError class="mt-1" :message="form.errors.name" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
                id="email"
                type="email"
                v-model="form.email"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                required
            />
            <InputError class="mt-1" :message="form.errors.email" />
        </div>

        <div v-if="mustVerifyEmail && user.email_verified_at === null" class="p-3 bg-yellow-50 rounded-lg">
            <p class="text-sm text-yellow-800">
                Your email is unverified.
                <Link :href="'/email/verification-notification'" method="post" as="button" class="font-medium underline hover:text-yellow-900">
                    Resend verification email.
                </Link>
            </p>
            <p v-if="status === 'verification-link-sent'" class="mt-1 text-sm font-medium text-green-600">
                Verification link sent!
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50"
            >
                Save Changes
            </button>
            <span v-if="saved" class="text-sm text-green-600 font-medium">Saved!</span>
        </div>
    </form>
</template>
