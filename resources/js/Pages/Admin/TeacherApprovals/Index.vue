<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    teachers: Object,
});

const approve = (teacher) => {
    router.post(route('admin.teacher-approvals.approve', teacher.id));
};

const reject = (teacher) => {
    if (confirm('Are you sure you want to reject this teacher?')) {
        router.post(route('admin.teacher-approvals.reject', teacher.id));
    }
};
</script>

<template>
    <Head title="Teacher Approvals" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Teacher Approvals
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="teachers.data.length === 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500">No pending teacher approvals.</p>
                </div>
                <div v-else class="space-y-4">
                    <div v-for="teacher in teachers.data" :key="teacher.id" class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ teacher.user?.name }}</h3>
                                <p class="text-sm text-gray-500">{{ teacher.user?.email }}</p>
                                <p v-if="teacher.bio" class="mt-2 text-sm text-gray-600">{{ teacher.bio }}</p>
                                <div v-if="teacher.certificates?.length" class="mt-3">
                                    <p class="text-sm font-medium text-gray-700">Certificates:</p>
                                    <ul class="mt-1 space-y-1">
                                        <li v-for="cert in teacher.certificates" :key="cert.id" class="text-sm text-gray-600">
                                            {{ cert.title }} <span v-if="cert.issued_by" class="text-gray-400">({{ cert.issued_by }})</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button @click="approve(teacher)" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                    Approve
                                </button>
                                <button @click="reject(teacher)" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
