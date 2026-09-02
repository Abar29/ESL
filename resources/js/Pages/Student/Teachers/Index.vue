<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    teachers: Object,
});
</script>

<template>
    <Head title="Browse Teachers" />

    <AuthenticatedLayout>
        <template #header>Browse Teachers</template>

        <div>
            <!-- Empty State -->
            <div v-if="!teachers.data || teachers.data.length === 0" class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-gray-500 font-medium">No approved teachers available yet.</p>
            </div>

            <!-- Teacher Grid -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div v-for="teacher in teachers.data" :key="teacher.id" class="bg-white rounded-xl border border-gray-200 hover:shadow-md transition-shadow overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                <img v-if="teacher.profile_pic" :src="teacher.profile_pic.startsWith('http') ? teacher.profile_pic : '/storage/' + teacher.profile_pic" class="w-14 h-14 rounded-full object-cover" />
                                <span v-else class="text-xl font-bold text-indigo-600">{{ teacher.user?.name?.charAt(0)?.toUpperCase() }}</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ teacher.user?.name }}</h3>
                                <div class="flex items-center gap-1 text-sm">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-gray-600">{{ teacher.rating_avg || 'New' }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ teacher.bio || 'No bio available.' }}</p>
                        <a :href="'/student/teachers/' + teacher.id" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700">
                            View Profile & Book
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
