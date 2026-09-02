<script setup>
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close']);

const page = usePage();
const user = computed(() => page.props.auth.user);

const profileForm = ref({
    name: user.value?.name || '',
    email: user.value?.email || '',
});

const passwordForm = ref({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const profileErrors = ref({});
const passwordErrors = ref({});
const profileSaved = ref(false);
const passwordSaved = ref(false);
const profileProcessing = ref(false);
const passwordProcessing = ref(false);

const submitProfile = async () => {
    profileProcessing.value = true;
    profileErrors.value = {};
    try {
        await axios.patch('/profile', profileForm.value);
        profileSaved.value = true;
        setTimeout(() => profileSaved.value = false, 2000);
    } catch (e) {
        if (e.response?.data?.errors) {
            profileErrors.value = e.response.data.errors;
        }
    } finally {
        profileProcessing.value = false;
    }
};

const submitPassword = async () => {
    passwordProcessing.value = true;
    passwordErrors.value = {};
    try {
        await axios.put('/password', passwordForm.value);
        passwordForm.value = { current_password: '', password: '', password_confirmation: '' };
        passwordSaved.value = true;
        setTimeout(() => passwordSaved.value = false, 2000);
    } catch (e) {
        if (e.response?.data?.errors) {
            passwordErrors.value = e.response.data.errors;
        }
    } finally {
        passwordProcessing.value = false;
    }
};
</script>

<template>
    <Modal :show="show" @close="emit('close')" max-width="2xl">
        <div class="p-6 max-h-[80vh] overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Settings</h3>

            <!-- Profile Information -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Profile Information</h4>
                <p class="text-xs text-gray-500 mb-4">Update your account's profile information and email address.</p>
                <form @submit.prevent="submitProfile" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input
                            id="name"
                            type="text"
                            v-model="profileForm.name"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        />
                        <InputError class="mt-1" :message="profileErrors.name?.[0]" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            id="email"
                            type="email"
                            v-model="profileForm.email"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        />
                        <InputError class="mt-1" :message="profileErrors.email?.[0]" />
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" :disabled="profileProcessing" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                            Save Changes
                        </button>
                        <span v-if="profileSaved" class="text-sm text-green-600 font-medium">Saved!</span>
                    </div>
                </form>
            </div>

            <hr class="border-gray-100 mb-6" />

            <!-- Update Password -->
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Update Password</h4>
                <p class="text-xs text-gray-500 mb-4">Ensure your account is using a long, random password to stay secure.</p>
                <form @submit.prevent="submitPassword" class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input
                            id="current_password"
                            v-model="passwordForm.current_password"
                            type="password"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            autocomplete="current-password"
                        />
                        <InputError :message="passwordErrors.current_password?.[0]" class="mt-1" />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input
                            id="password"
                            v-model="passwordForm.password"
                            type="password"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            autocomplete="new-password"
                        />
                        <InputError :message="passwordErrors.password?.[0]" class="mt-1" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input
                            id="password_confirmation"
                            v-model="passwordForm.password_confirmation"
                            type="password"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            autocomplete="new-password"
                        />
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" :disabled="passwordProcessing" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                            Update Password
                        </button>
                        <span v-if="passwordSaved" class="text-sm text-green-600 font-medium">Updated!</span>
                    </div>
                </form>
            </div>
        </div>
    </Modal>
</template>
