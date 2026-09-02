<script setup>
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import Toast from '@/Components/Toast.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close', 'updatePic', 'toast']);

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
const profileProcessing = ref(false);
const passwordProcessing = ref(false);

const showToast = (message, type = 'success') => {
    emit('toast', { message, type });
};

const handlePicture = (e) => {
    const file = e.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('profile_pic', file);
        axios.post('/profile/picture', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        }).then(() => {
            showToast('Profile picture uploaded!');
            emit('updatePic');
        }).catch(() => {
            showToast('Failed to upload picture.', 'error');
        });
    }
};

const submitProfile = async () => {
    profileProcessing.value = true;
    profileErrors.value = {};
    try {
        await axios.patch('/profile', profileForm.value);
        showToast('Profile updated successfully!');
    } catch (e) {
        if (e.response?.data?.errors) {
            profileErrors.value = e.response.data.errors;
            showToast('Failed to update profile.', 'error');
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
        showToast('Password updated successfully!');
    } catch (e) {
        if (e.response?.data?.errors) {
            passwordErrors.value = e.response.data.errors;
            showToast('Failed to update password.', 'error');
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

            <!-- Profile Picture -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Profile Picture</h4>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        <span class="text-xl font-bold text-indigo-600">{{ user?.name?.charAt(0)?.toUpperCase() }}</span>
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Upload
                            <input type="file" @change="handlePicture" accept="image/jpeg,image/png" class="hidden" />
                        </label>
                        <p class="mt-1 text-xs text-gray-500">JPG or PNG, max 2MB</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100 mb-6" />

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
                    </div>
                </form>
            </div>
        </div>
    </Modal>
</template>
