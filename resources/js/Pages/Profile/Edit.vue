<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <template #header>Settings</template>

        <div class="max-w-3xl space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Profile Information</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Update your account's profile information and email address.</p>
                </div>
                <div class="p-6">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                </div>
            </div>

            <!-- Password Card -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Update Password</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Ensure your account is using a long, random password to stay secure.</p>
                </div>
                <div class="p-6">
                    <UpdatePasswordForm />
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="bg-white rounded-xl border border-red-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-red-100 bg-red-50">
                    <h3 class="text-base font-semibold text-red-900">Delete Account</h3>
                    <p class="text-sm text-red-600 mt-0.5">Permanently delete your account and all of its data.</p>
                </div>
                <div class="p-6">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
