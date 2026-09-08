<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Cookie Policy" />

    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg aria-hidden="true" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">ESL Scheduler</span>
                </Link>
                <Link href="/" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">Back to Home</Link>
            </div>
        </nav>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Cookie Policy</h1>
            <p class="text-sm text-gray-500 mb-8">Last updated: September 8, 2026</p>

            <div class="prose prose-gray max-w-none space-y-8">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">1. What Are Cookies</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Cookies are small text files placed on your device when you visit a website. They help websites function properly and provide information to the site operators.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Cookies We Use</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        We use only <strong>essential session cookies</strong> required for the Platform to function. These are:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li><strong>Session Cookie (laravel-session):</strong> Required for user authentication and to maintain your logged-in session. This cookie is essential for the Platform to work.</li>
                        <li><strong>CSRF Token Cookie (XSRF-TOKEN):</strong> Required for security — protects against cross-site request forgery attacks. This cookie is essential for the Platform to work.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Cookies We Do NOT Use</h2>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>No analytics cookies (no Google Analytics, Facebook Pixel, or similar)</li>
                        <li>No advertising or marketing cookies</li>
                        <li>No third-party tracking cookies</li>
                        <li>No social media cookies</li>
                        <li>No preference or functionality cookies beyond essential session management</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Managing Cookies</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Since we only use essential session cookies, disabling them will prevent you from logging in and using the Platform. You can manage cookies through your browser settings. However, disabling our session cookies will make the Platform unusable.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Third-Party Services</h2>
                    <p class="text-gray-700 leading-relaxed">
                        While we do not use third-party cookies, our Platform integrates with external services that have their own cookie policies:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li><strong>Zoom:</strong> When you join a Zoom session via our Platform, Zoom may set its own cookies. Please refer to <a href="https://zoom.us/privacy" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-500">Zoom's Privacy Policy</a>.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Changes to This Policy</h2>
                    <p class="text-gray-700 leading-relaxed">
                        We may update this Cookie Policy from time to time. Any changes will be posted on this page with an updated "Last updated" date.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">7. Contact Us</h2>
                    <p class="text-gray-700 leading-relaxed">
                        If you have questions about our Cookie Policy, please contact us:
                    </p>
                    <div class="mt-3 p-4 bg-gray-50 rounded-lg text-gray-700">
                        <p><strong>Individual ESL</strong></p>
                        <p>Email: ESLindiv@gmail.com</p>
                    </div>
                </section>
            </div>
        </main>
    </div>
</template>
