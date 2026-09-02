<script setup>
import { ref, computed, onMounted } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ProfileModal from '@/Components/ProfileModal.vue';
import SettingsModal from '@/Components/SettingsModal.vue';
import Toast from '@/Components/Toast.vue';
import { Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';

const sidebarOpen = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value?.role);
const currentPath = computed(() => page.url);

const showProfileModal = ref(false);
const showSettingsModal = ref(false);
const teacherProfile = ref(null);
const profilePic = ref(null);

const goTo = (href) => {
    sidebarOpen.value = false;
    window.location.href = href;
};

const openProfileModal = async () => {
    sidebarOpen.value = false;
    if (userRole.value === 'teacher') {
        await fetchTeacherProfile();
        showProfileModal.value = true;
    } else {
        showSettingsModal.value = true;
    }
};

const fetchTeacherProfile = async () => {
    try {
        const response = await axios.get('/teacher/profile/data');
        teacherProfile.value = response.data.profile;
        if (response.data.profile?.profile_pic) {
            profilePic.value = response.data.profile.profile_pic;
        }
    } catch (e) {
        teacherProfile.value = null;
    }
};

const fetchProfilePic = async () => {
    try {
        const response = await axios.get('/profile/pic');
        if (response.data.profile_pic) {
            profilePic.value = response.data.profile_pic;
        }
    } catch (e) {
        // ignore
    }
};

const updateProfilePic = () => {
    if (userRole.value === 'teacher') {
        fetchTeacherProfile();
    } else {
        fetchProfilePic();
    }
};

const openSettingsModal = () => {
    sidebarOpen.value = false;
    showSettingsModal.value = true;
};

const navigation = computed(() => {
    const role = userRole.value;
    const items = [
        { name: 'Dashboard', href: role === 'admin' ? '/admin/dashboard' : role === 'teacher' ? '/teacher/dashboard' : '/student/dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    ];

    if (role === 'student') {
        items.push(
            { name: 'Browse Teachers', href: '/student/teachers', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z' },
            { name: 'My Bookings', href: '/student/bookings', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
            { name: 'My Profile', action: 'profile', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
        );
    } else if (role === 'teacher') {
        items.push(
            { name: 'Manage Schedule', href: '/teacher/availability', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
            { name: 'Bookings', href: '/teacher/bookings', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' },
            { name: 'History & Ratings', href: '/teacher/history', icon: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z' },
            { name: 'My Profile', action: 'profile', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
        );
    } else if (role === 'admin') {
        items.push(
            { name: 'Users', href: '/admin/users', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
            { name: 'Teacher Approvals', href: '/admin/teacher-approvals', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
            { name: 'All Bookings', href: '/admin/bookings', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
            { name: 'Reports', href: '/admin/reports', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
        );
    }

    return items;
});

const isActive = (href) => {
    return currentPath.value === href || currentPath.value.startsWith(href + '/');
};

onMounted(() => {
    if (userRole.value === 'teacher') {
        fetchTeacherProfile();
    } else {
        fetchProfilePic();
    }
});
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Mobile sidebar overlay -->
        <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:shadow-md"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold text-gray-900">ESL Scheduler</span>
                        <div class="text-xs text-gray-500 capitalize">{{ userRole }} Portal</div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <div
                        v-for="item in navigation"
                        :key="item.name"
                        @click="item.action === 'profile' ? openProfileModal() : goTo(item.href)"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium cursor-pointer select-none"
                        :class="item.action === 'profile' ? 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' : (isActive(item.href) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900')"
                    >
                        <svg
                            class="w-5 h-5 flex-shrink-0"
                            :class="item.action === 'profile' ? 'text-gray-400' : (isActive(item.href) ? 'text-indigo-600' : 'text-gray-400')"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="item.icon" />
                        </svg>
                        {{ item.name }}
                    </div>
                </nav>

                <!-- User section -->
                <div class="border-t border-gray-100 px-3 py-4">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <img v-if="profilePic" :src="'/storage/' + profilePic" class="w-9 h-9 rounded-full object-cover" />
                            <span v-else class="text-sm font-semibold text-indigo-700">{{ user?.name?.charAt(0)?.toUpperCase() }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ user?.name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ user?.email }}</p>
                        </div>
                    </div>
                    <div class="mt-2 space-y-1">
                        <div
                            @click="openSettingsModal"
                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 cursor-pointer select-none"
                        >
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </div>
                        <form method="post" action="/logout">
                            <input type="hidden" name="_token" :value="page.props.csrf_token" />
                            <button
                                type="submit"
                                class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 text-left"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center justify-between px-4 py-3 lg:px-6">
                    <button
                        @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1 lg:flex-none">
                        <h1 v-if="$slots.header" class="text-lg font-semibold text-gray-900">
                            <slot name="header" />
                        </h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center overflow-hidden">
                                        <img v-if="profilePic" :src="'/storage/' + profilePic" class="w-8 h-8 rounded-full object-cover" />
                                        <span v-else class="text-sm font-semibold text-indigo-700">{{ user?.name?.charAt(0)?.toUpperCase() }}</span>
                                    </div>
                                    <span class="hidden sm:block text-sm font-medium text-gray-700">{{ user?.name }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink as="button" method="post" href="/logout">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 lg:p-6">
                <slot />
            </main>
        </div>

        <!-- Modals -->
        <ProfileModal
            :show="showProfileModal"
            :profile="teacherProfile"
            @close="showProfileModal = false"
            @refresh="fetchTeacherProfile"
            @updatePic="updateProfilePic"
        />
        <SettingsModal
            :show="showSettingsModal"
            @close="showSettingsModal = false"
            @updatePic="updateProfilePic"
        />
    </div>
</template>
