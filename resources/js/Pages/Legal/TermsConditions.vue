<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Terms and Conditions" />

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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Terms and Conditions</h1>
            <p class="text-sm text-gray-500 mb-8">Last updated: September 8, 2026</p>

            <div class="prose prose-gray max-w-none space-y-8">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Acceptance of Terms</h2>
                    <p class="text-gray-700 leading-relaxed">
                        By accessing and using the ESL Scheduler platform ("Platform"), you agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use the Platform. These terms constitute a legally binding agreement between you and Individual ESL.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Description of Service</h2>
                    <p class="text-gray-700 leading-relaxed">
                        ESL Scheduler is an online platform that connects students seeking English as a Second Language (ESL) instruction with qualified teachers. The Platform facilitates teacher discovery, session booking, payment processing, and video session delivery via Zoom.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">3. User Accounts</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">You must create an account to use the Platform. When creating an account:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>You must provide accurate and complete information</li>
                        <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                        <li>You are responsible for all activities that occur under your account</li>
                        <li>You must notify us immediately of any unauthorized use of your account</li>
                        <li>You must be at least 18 years old to create an account</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Roles and Responsibilities</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Students</h3>
                            <ul class="list-disc pl-6 space-y-2 text-gray-700">
                                <li>Browse teacher profiles and availability</li>
                                <li>Book sessions and make payments</li>
                                <li>Attend scheduled Zoom sessions on time</li>
                                <li>Leave honest reviews after completed sessions</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Teachers</h3>
                            <ul class="list-disc pl-6 space-y-2 text-gray-700">
                                <li>Create and maintain an accurate profile</li>
                                <li>Set and manage availability</li>
                                <li>Accept or decline booking requests promptly</li>
                                <li>Conduct sessions professionally via Zoom</li>
                                <li>Provide proof of qualifications upon request</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Payment Terms</h2>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Each session costs <strong>₱750.00 PHP</strong></li>
                        <li>Payments are made via GCash, GoTyme, or Maya</li>
                        <li>Students must upload a payment screenshot and reference number upon booking</li>
                        <li>Bookings are confirmed after the teacher verifies the payment</li>
                        <li>All prices are in Philippine Pesos (PHP)</li>
                        <li>We do not store payment card details — all payment processing is handled by third-party providers</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Cancellation and Refund Policy</h2>
                    <p class="text-gray-700 leading-relaxed">
                        For details on cancellations and refunds, please refer to our <Link href="/refund-policy" class="text-indigo-600 hover:text-indigo-500">Refund Policy</Link>.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">7. Session Conduct</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">All users agree to:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Be respectful and professional during sessions</li>
                        <li>Not engage in harassment, discrimination, or inappropriate behavior</li>
                        <li>Not record sessions without the other party's explicit consent</li>
                        <li>Not share session links with unauthorized persons</li>
                        <li>Use Zoom in compliance with Zoom's Terms of Service</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">8. Intellectual Property</h2>
                    <p class="text-gray-700 leading-relaxed">
                        The Platform and its original content, features, and functionality are owned by Individual ESL and are protected by copyright, trademark, and other intellectual property laws. You may not reproduce, distribute, or create derivative works from our content without our express written permission.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">9. Limitation of Liability</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Individual ESL acts as an intermediary between students and teachers. We are not responsible for the quality of instruction, the conduct of any user during sessions, or disputes between students and teachers. Our liability is limited to the maximum extent permitted by Philippine law.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">10. Account Termination</h2>
                    <p class="text-gray-700 leading-relaxed">
                        We reserve the right to suspend or terminate your account at our discretion if you violate these Terms, engage in fraudulent activity, or behave inappropriately. You may also request account deletion by contacting us at ESLindiv@gmail.com.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">11. Changes to Terms</h2>
                    <p class="text-gray-700 leading-relaxed">
                        We reserve the right to modify these Terms at any time. We will notify you of material changes by posting the updated Terms on this page. Continued use of the Platform after changes constitutes acceptance of the new Terms.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">12. Governing Law</h2>
                    <p class="text-gray-700 leading-relaxed">
                        These Terms are governed by and construed in accordance with the laws of the Republic of the Philippines. Any disputes arising from these Terms shall be resolved in the appropriate courts of the Philippines.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">13. Contact Us</h2>
                    <p class="text-gray-700 leading-relaxed">
                        If you have any questions about these Terms and Conditions, please contact us:
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
