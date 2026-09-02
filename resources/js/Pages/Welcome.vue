<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="ESL Scheduler - Book Your English Session" />

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">ESL Scheduler</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <Link v-if="canLogin" :href="route('login')" class="text-gray-600 hover:text-gray-900 font-medium">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">
                            Get Started
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                        Now accepting bookings
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                        Learn English with
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                            Expert Teachers
                        </span>
                    </h1>
                    <p class="mt-6 text-xl text-gray-600 max-w-2xl mx-auto">
                        Book personalized ESL sessions with qualified teachers. Flexible scheduling, affordable rates, and proven results.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                        <Link :href="route('register')" class="px-8 py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold text-lg transition shadow-lg shadow-indigo-200">
                            Start Learning Today
                        </Link>
                        <Link :href="route('login')" class="px-8 py-4 bg-white text-gray-700 rounded-xl hover:bg-gray-50 font-semibold text-lg transition border border-gray-200">
                            Browse Teachers
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Decorative blobs -->
            <div class="absolute top-0 left-0 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-0 right-0 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        </div>

        <!-- Features Section -->
        <div class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900">How It Works</h2>
                    <p class="mt-4 text-lg text-gray-600">Start your English learning journey in 3 simple steps</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center p-8 rounded-2xl bg-gray-50 hover:bg-indigo-50 transition">
                        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Find a Teacher</h3>
                        <p class="text-gray-600">Browse our qualified ESL teachers, check their profiles, ratings, and availability.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center p-8 rounded-2xl bg-gray-50 hover:bg-indigo-50 transition">
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Book a Session</h3>
                        <p class="text-gray-600">Choose your preferred time slot and pay via GCash, GoTyme, or Maya.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center p-8 rounded-2xl bg-gray-50 hover:bg-indigo-50 transition">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Start Learning</h3>
                        <p class="text-gray-600">Join your Zoom session at the scheduled time and improve your English skills.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- For Teachers Section -->
        <div class="py-24 bg-gradient-to-br from-indigo-600 to-purple-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6">Teach English on Your Schedule</h2>
                        <p class="text-indigo-100 text-lg mb-8">Join our platform as an ESL teacher and connect with students worldwide. Set your own hours, rates, and availability.</p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-white">
                                <svg class="w-6 h-6 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Set your own schedule
                            </li>
                            <li class="flex items-center gap-3 text-white">
                                <svg class="w-6 h-6 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Receive payments via GCash, GoTyme, or Maya
                            </li>
                            <li class="flex items-center gap-3 text-white">
                                <svg class="w-6 h-6 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Build your teaching profile and ratings
                            </li>
                        </ul>
                        <Link :href="route('register')" class="mt-8 inline-block px-8 py-4 bg-white text-indigo-700 rounded-xl hover:bg-indigo-50 font-semibold text-lg transition">
                            Apply as Teacher
                        </Link>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-full"></div>
                                    <div>
                                        <div class="h-4 w-32 bg-white/30 rounded"></div>
                                        <div class="h-3 w-24 bg-white/20 rounded mt-2"></div>
                                    </div>
                                </div>
                                <div class="flex gap-1">
                                    <span class="text-yellow-300 text-xl">★★★★★</span>
                                </div>
                                <div class="h-20 bg-white/10 rounded-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-indigo-600">50+</div>
                        <div class="mt-2 text-gray-600">Qualified Teachers</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600">1000+</div>
                        <div class="mt-2 text-gray-600">Sessions Completed</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600">4.8</div>
                        <div class="mt-2 text-gray-600">Average Rating</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600">24/7</div>
                        <div class="mt-2 text-gray-600">Flexible Schedule</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-24 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Ready to Start Learning?</h2>
                <p class="text-lg text-gray-600 mb-10">Join thousands of students improving their English skills with our expert teachers.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('register')" class="px-10 py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold text-lg transition shadow-lg shadow-indigo-200">
                        Create Free Account
                    </Link>
                    <Link :href="route('login')" class="px-10 py-4 bg-white text-gray-700 rounded-xl hover:bg-gray-50 font-semibold text-lg transition border border-gray-200">
                        Sign In
                    </Link>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center gap-2 mb-4 md:mb-0">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-white font-semibold">ESL Scheduler</span>
                    </div>
                    <div class="text-sm">
                        © 2026 ESL Scheduler. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}
</style>
