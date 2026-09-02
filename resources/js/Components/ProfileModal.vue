<script setup>
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import Toast from '@/Components/Toast.vue';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    profile: Object,
});

const emit = defineEmits(['close', 'refresh', 'updatePic']);

const form = ref({
    bio: props.profile?.bio || '',
    gcash_number: props.profile?.gcash_number || '',
    gotyme_number: props.profile?.gotyme_number || '',
    maya_number: props.profile?.maya_number || '',
    zoom_link: props.profile?.zoom_link || '',
});

const errors = ref({});
const certForm = ref({ title: '', issued_by: '', file: null });
const showCertForm = ref(false);
const saved = ref(false);
const processing = ref(false);
const toastShow = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

const showToast = (message, type = 'success') => {
    toastMessage.value = message;
    toastType.value = type;
    toastShow.value = true;
};

watch(() => props.profile, (newProfile) => {
    if (newProfile) {
        form.value.bio = newProfile.bio || '';
        form.value.gcash_number = newProfile.gcash_number || '';
        form.value.gotyme_number = newProfile.gotyme_number || '';
        form.value.maya_number = newProfile.maya_number || '';
        form.value.zoom_link = newProfile.zoom_link || '';
    }
});

const submit = async () => {
    processing.value = true;
    errors.value = {};
    try {
        await axios.put('/teacher/profile', form.value);
        showToast('Profile saved successfully!');
        emit('refresh');
    } catch (e) {
        if (e.response?.data?.errors) {
            errors.value = e.response.data.errors;
            showToast('Failed to save profile.', 'error');
        }
    } finally {
        processing.value = false;
    }
};

const handlePicture = (e) => {
    const file = e.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('profile_pic', file);
        router.post('/teacher/profile/picture', formData, {
            onFinish: () => {
                showToast('Profile picture uploaded!');
                emit('refresh');
                emit('updatePic');
            },
        });
    }
};

const handleCertFile = (e) => { certForm.value.file = e.target.files[0]; };

const submitCert = async () => {
    const formData = new FormData();
    formData.append('title', certForm.value.title);
    formData.append('issued_by', certForm.value.issued_by || '');
    if (certForm.value.file) {
        formData.append('file', certForm.value.file);
    }
    try {
        await axios.post('/teacher/profile/certificates', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        showCertForm.value = false;
        certForm.value = { title: '', issued_by: '', file: null };
        showToast('Certificate uploaded successfully!');
        emit('refresh');
    } catch (e) {
        if (e.response?.data?.errors) {
            errors.value = e.response.data.errors;
            showToast('Failed to upload certificate.', 'error');
        }
    }
};

const deleteCert = async (cert) => {
    if (confirm('Delete this certificate?')) {
        try {
            await axios.delete(`/teacher/profile/certificates/${cert.id}`);
            showToast('Certificate deleted.');
            emit('refresh');
        } catch (e) {
            showToast('Failed to delete certificate.', 'error');
        }
    }
};
</script>

<template>
    <Modal :show="show" @close="emit('close')" max-width="4xl">
        <div class="p-6 max-h-[80vh] overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">My Profile</h3>

            <!-- Profile Picture -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Profile Picture</h4>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        <img v-if="profile?.profile_pic" :src="profile.profile_pic.startsWith('http') ? profile.profile_pic : '/storage/' + profile.profile_pic" class="w-16 h-16 rounded-full object-cover" />
                        <span v-else class="text-xl font-bold text-indigo-600">{{ $page.props.auth.user?.name?.charAt(0)?.toUpperCase() }}</span>
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

            <!-- Profile Form -->
            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                    <textarea id="bio" v-model="form.bio" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" rows="3" placeholder="Tell students about yourself..."></textarea>
                    <InputError :message="errors.bio?.[0]" class="mt-1" />
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Payment Details</h4>
                    <div class="space-y-3">
                        <div>
                            <label for="gcash_number" class="block text-sm font-medium text-gray-700 mb-1">GCash Number</label>
                            <input id="gcash_number" v-model="form.gcash_number" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="09XX XXX XXXX" />
                        </div>
                        <div>
                            <label for="gotyme_number" class="block text-sm font-medium text-gray-700 mb-1">GoTyme Number</label>
                            <input id="gotyme_number" v-model="form.gotyme_number" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="09XX XXX XXXX" />
                        </div>
                        <div>
                            <label for="maya_number" class="block text-sm font-medium text-gray-700 mb-1">Maya Number</label>
                            <input id="maya_number" v-model="form.maya_number" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="09XX XXX XXXX" />
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Zoom Link</h4>
                    <label for="zoom_link" class="block text-sm font-medium text-gray-700 mb-1">Permanent Zoom Meeting Link</label>
                    <input id="zoom_link" v-model="form.zoom_link" type="url" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="https://zoom.us/j/..." />
                    <p class="mt-1 text-xs text-gray-500">Shown to students when they book a session.</p>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" :disabled="processing" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                        Save Profile
                    </button>
                </div>
            </form>

            <hr class="border-gray-100 my-6" />

            <!-- Certificates -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-900">Certificates</h4>
                    <button @click="showCertForm = !showCertForm" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        {{ showCertForm ? 'Cancel' : '+ Add Certificate' }}
                    </button>
                </div>

                <div v-if="showCertForm" class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <form @submit.prevent="submitCert" class="space-y-3">
                        <div>
                            <label for="cert_title" class="block text-sm font-medium text-gray-700 mb-1">Certificate Title</label>
                            <input id="cert_title" v-model="certForm.title" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required />
                            <InputError :message="errors.title?.[0]" class="mt-1" />
                        </div>
                        <div>
                            <label for="issued_by" class="block text-sm font-medium text-gray-700 mb-1">Issued By (optional)</label>
                            <input id="issued_by" v-model="certForm.issued_by" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                        </div>
                        <div>
                            <label for="cert_file" class="block text-sm font-medium text-gray-700 mb-1">Certificate File</label>
                            <input type="file" id="cert_file" @change="handleCertFile" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                            Upload
                        </button>
                    </form>
                </div>

                <div v-if="profile?.certificates?.length" class="space-y-2">
                    <div v-for="cert in profile.certificates" :key="cert.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ cert.title }}</p>
                                <p v-if="cert.issued_by" class="text-xs text-gray-500">{{ cert.issued_by }}</p>
                            </div>
                        </div>
                        <button @click="deleteCert(cert)" class="text-xs font-medium text-red-600 hover:text-red-700">Delete</button>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-500">No certificates uploaded yet.</p>
            </div>
        </div>
    </Modal>
    <Toast :show="toastShow" :message="toastMessage" :type="toastType" @close="toastShow = false" />
</template>
