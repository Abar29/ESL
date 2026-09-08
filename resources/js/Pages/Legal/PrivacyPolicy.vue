<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Privacy Policy" />

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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Privacy Policy</h1>
            <p class="text-sm text-gray-500 mb-8">Last updated: September 8, 2026</p>

            <div class="prose prose-gray max-w-none space-y-8">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Introduction</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Individual ESL ("we", "us", or "our") operates the ESL Scheduler platform. We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.
                    </p>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        This policy complies with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong> and its Implementing Rules and Regulations.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Information We Collect</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">We collect the following types of personal information:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li><strong>Account Information:</strong> Full name, email address, password (encrypted), and role (student, teacher, or admin)</li>
                        <li><strong>Profile Information:</strong> Profile picture, bio, address (optional)</li>
                        <li><strong>Payment Information:</strong> Payment method (GCash, GoTyme, Maya), payment reference numbers, and payment screenshots</li>
                        <li><strong>Teacher-Specific Information:</strong> Payment account numbers and account names, Zoom meeting links, certificates, and qualifications</li>
                        <li><strong>Booking Data:</strong> Session dates, times, status, and booking history</li>
                        <li><strong>Reviews:</strong> Ratings and comments left by students after completed sessions</li>
                        <li><strong>Technical Data:</strong> IP address, browser type, device information, and session cookies</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">3. How We Use Your Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">We use your personal information for the following purposes:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>To provide and maintain the ESL Scheduler platform</li>
                        <li>To process bookings and payments between students and teachers</li>
                        <li>To facilitate Zoom sessions for booked classes</li>
                        <li>To manage teacher profiles, qualifications, and approval status</li>
                        <li>To communicate with you regarding your bookings and account</li>
                        <li>To display teacher ratings and reviews to help students make informed choices</li>
                        <li>To ensure platform security and prevent fraud</li>
                        <li>To comply with legal obligations under Philippine law</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">4. How We Share Your Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">We may share your information with the following third parties:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li><strong>Teachers/Students:</strong> Booking details (name, email) are shared between the student and teacher for the booked session</li>
                        <li><strong>Zoom Video Communications:</strong> Session links are provided by teachers; we do not share data with Zoom directly</li>
                        <li><strong>Payment Processors (GCash, GoTyme, Maya):</strong> Payment transactions are processed externally; we only store reference numbers and screenshots</li>
                        <li><strong>Cloudinary:</strong> Profile pictures and payment screenshots are stored on Cloudinary's cloud storage</li>
                        <li><strong>Neon Database:</strong> All platform data is stored on Neon PostgreSQL cloud database</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        We do <strong>not</strong> sell, trade, or rent your personal information to third parties for marketing purposes.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Data Storage and Security</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Your data is stored on secure cloud infrastructure (Neon PostgreSQL for database, Cloudinary for files). We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of electronic transmission or storage is 100% secure.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Data Retention</h2>
                    <p class="text-gray-700 leading-relaxed">
                        We retain your personal information for as long as your account is active or as needed to provide services. If you request account deletion, we will remove your personal data within 30 days, except where retention is required by law or for legitimate business purposes (e.g., financial records).
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">7. Your Rights Under the Data Privacy Act of 2012</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">Under RA 10173, you have the following rights:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li><strong>Right to be informed</strong> — to know how your personal data is collected and processed</li>
                        <li><strong>Right to access</strong> — to obtain a copy of your personal data</li>
                        <li><strong>Right to object</strong> — to object to the processing of your personal data</li>
                        <li><strong>Right to erasure or blocking</strong> — to request deletion or blocking of your personal data</li>
                        <li><strong>Right to rectification</strong> — to correct any inaccurate personal data</li>
                        <li><strong>Right to data portability</strong> — to obtain your data in a structured, commonly used format</li>
                        <li><strong>Right to file a complaint</strong> — to file a complaint with the National Privacy Commission (NPC) if you believe your rights have been violated</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">8. Cookies</h2>
                    <p class="text-gray-700 leading-relaxed">
                        We use only essential session cookies required for authentication and platform functionality. We do not use tracking cookies, advertising cookies, or third-party analytics cookies. For more details, see our <Link href="/cookie-policy" class="text-indigo-600 hover:text-indigo-500">Cookie Policy</Link>.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">9. Children's Privacy</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Our platform is not intended for users under the age of 18. We do not knowingly collect personal information from children. If you are a parent or guardian and believe your child has provided us with personal information, please contact us immediately.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">10. Changes to This Policy</h2>
                    <p class="text-gray-700 leading-relaxed">
                        We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new policy on this page and updating the "Last updated" date.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">11. Contact Us</h2>
                    <p class="text-gray-700 leading-relaxed">
                        If you have questions about this Privacy Policy or wish to exercise your data rights, contact us at:
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
