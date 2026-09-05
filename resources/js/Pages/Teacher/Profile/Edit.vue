<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    profile: Object,
});

const form = useForm({
    bio: props.profile?.bio || '',
    gcash_number: props.profile?.gcash_number || '',
    gcash_name: props.profile?.gcash_name || '',
    gotyme_number: props.profile?.gotyme_number || '',
    gotyme_name: props.profile?.gotyme_name || '',
    maya_number: props.profile?.maya_number || '',
    maya_name: props.profile?.maya_name || '',
    zoom_link: props.profile?.zoom_link || '',
});

const certForm = useForm({ title: '', issued_by: '', file: null });
const showCertForm = ref(false);
const saved = ref(false);

const submit = () => {
    form.put('/teacher/profile', {
        onSuccess: () => { saved.value = true; setTimeout(() => saved.value = false, 2000); },
    });
};

const handlePicture = (e) => {
    const file = e.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('profile_pic', file);
        router.post('/teacher/profile/picture', formData);
    }
};

const handleCertFile = (e) => { certForm.file = e.target.files[0]; };

const submitCert = () => {
    certForm.post('/teacher/profile/certificates', {
        onSuccess: () => { showCertForm.value = false; certForm.reset(); },
        forceFormData: true,
    });
};

const deleteCert = (cert) => {
    if (confirm('Delete this certificate?')) {
        router.delete(`/teacher/profile/certificates/${cert.id}`);
    }
};
</script>

<template>
    <Head title="My Profile" />

    <AuthenticatedLayout>
        <template #header>My Profile</template>

        <div class="max-w-2xl space-y-6">
            <!-- Profile Picture -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Profile Picture</h3>
                </div>
                <div class="p-6 flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        <img v-if="profile?.profile_pic" :src="profile.profile_pic.startsWith('http') || profile.profile_pic.startsWith('data:') ? profile.profile_pic : '/storage/' + profile.profile_pic" class="w-20 h-20 rounded-full object-cover" />
                        <span v-else class="text-2xl font-bold text-indigo-600">{{ $page.props.auth.user?.name?.charAt(0)?.toUpperCase() }}</span>
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Upload Picture
                            <input type="file" @change="handlePicture" accept="image/jpeg,image/png" class="hidden" />
                        </label>
                        <p class="mt-1 text-xs text-gray-500">JPG or PNG, max 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Profile Information</h3>
                </div>
                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <textarea id="bio" v-model="form.bio" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" rows="4" placeholder="Tell students about yourself..."></textarea>
                        <InputError :message="form.errors.bio" class="mt-1" />
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Payment Details</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="gcash_number" class="block text-sm font-medium text-gray-700 mb-1">GCash Number</label>
                                <input id="gcash_number" v-model="form.gcash_number" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="09XX XXX XXXX" />
                            </div>
                            <div>
                                <label for="gcash_name" class="block text-sm font-medium text-gray-700 mb-1">GCash Account Name</label>
                                <input id="gcash_name" v-model="form.gcash_name" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Account holder name" />
                            </div>
                            <div>
                                <label for="gotyme_number" class="block text-sm font-medium text-gray-700 mb-1">GoTyme Number</label>
                                <input id="gotyme_number" v-model="form.gotyme_number" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="09XX XXX XXXX" />
                            </div>
                            <div>
                                <label for="gotyme_name" class="block text-sm font-medium text-gray-700 mb-1">GoTyme Account Name</label>
                                <input id="gotyme_name" v-model="form.gotyme_name" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Account holder name" />
                            </div>
                            <div>
                                <label for="maya_number" class="block text-sm font-medium text-gray-700 mb-1">Maya Number</label>
                                <input id="maya_number" v-model="form.maya_number" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="09XX XXX XXXX" />
                            </div>
                            <div>
                                <label for="maya_name" class="block text-sm font-medium text-gray-700 mb-1">Maya Account Name</label>
                                <input id="maya_name" v-model="form.maya_name" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Account holder name" />
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Zoom Link</h4>
                        <label for="zoom_link" class="block text-sm font-medium text-gray-700 mb-1">Permanent Zoom Meeting Link</label>
                        <input id="zoom_link" v-model="form.zoom_link" type="url" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="https://zoom.us/j/..." />
                        <p class="mt-1 text-xs text-gray-500">Shown to students when they book a session.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                            Save Profile
                        </button>
                        <span v-if="saved" class="text-sm text-green-600 font-medium">Saved!</span>
                    </div>
                </form>
            </div>

            <!-- Certificates -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Certificates</h3>
                    <button @click="showCertForm = !showCertForm" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                        {{ showCertForm ? 'Cancel' : '+ Add Certificate' }}
                    </button>
                </div>

                <div class="p-6">
                    <!-- Upload Form -->
                    <div v-if="showCertForm" class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <form @submit.prevent="submitCert" class="space-y-4">
                            <div>
                                <label for="cert_title" class="block text-sm font-medium text-gray-700 mb-1">Certificate Title</label>
                                <input id="cert_title" v-model="certForm.title" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required />
                                <InputError :message="certForm.errors.title" class="mt-1" />
                            </div>
                            <div>
                                <label for="issued_by" class="block text-sm font-medium text-gray-700 mb-1">Issued By (optional)</label>
                                <input id="issued_by" v-model="certForm.issued_by" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                            </div>
                            <div>
                                <label for="cert_file" class="block text-sm font-medium text-gray-700 mb-1">Certificate File</label>
                                <input type="file" id="cert_file" @change="handleCertFile" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                            </div>
                            <button type="submit" :disabled="certForm.processing" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                                Upload
                            </button>
                        </form>
                    </div>

                    <!-- List -->
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
        </div>
    </AuthenticatedLayout>
</template>
