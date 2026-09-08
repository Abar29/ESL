<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Refund Policy" />

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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Refund Policy</h1>
            <p class="text-sm text-gray-500 mb-8">Last updated: September 8, 2026</p>

            <div class="prose prose-gray max-w-none space-y-8">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">1. General Policy</h2>
                    <p class="text-gray-700 leading-relaxed">
                        All ESL sessions are priced at <strong>₱750.00 PHP per session</strong>. Refunds are handled on a case-by-case basis depending on the circumstances of the booking.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Eligible for Refund</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">You are eligible for a full refund if:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li><strong>Teacher no-show:</strong> The teacher did not join the Zoom session within 15 minutes of the scheduled start time</li>
                        <li><strong>Teacher cancellation:</strong> The teacher cancelled the booking after payment was confirmed</li>
                        <li><strong>Technical issues:</strong> A verified technical problem on the Platform prevented the session from taking place (e.g., Zoom integration failure)</li>
                        <li><strong>Duplicate payment:</strong> You were charged twice for the same session</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Not Eligible for Refund</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">Refunds will <strong>not</strong> be issued in the following cases:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li><strong>Student no-show:</strong> You did not attend the scheduled session without prior cancellation</li>
                        <li><strong>Completed sessions:</strong> The session was completed successfully</li>
                        <li><strong>Late arrival:</strong> You joined the session late but the session still took place</li>
                        <li><strong>Dissatisfaction with teaching style:</strong> Subjective dissatisfaction does not qualify for a refund (leave a review instead)</li>
                        <li><strong>Change of mind:</strong> You no longer want the session after it has been confirmed</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">4. How to Request a Refund</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">To request a refund:</p>
                    <ol class="list-decimal pl-6 space-y-2 text-gray-700">
                        <li>Contact us via email at <strong>ESLindiv@gmail.com</strong></li>
                        <li>Include your booking reference number</li>
                        <li>Provide a reason for the refund request</li>
                        <li>Include any supporting evidence (screenshots, chat logs, etc.)</li>
                    </ol>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Refund Processing</h2>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Refund requests will be reviewed within <strong>3-5 business days</strong></li>
                        <li>If approved, refunds will be processed via the same payment method used for the original payment (GCash, GoTyme, or Maya)</li>
                        <li>Refund processing time depends on your payment provider (typically 1-7 business days after approval)</li>
                        <li>You will be notified via email once the refund has been processed</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Disputes</h2>
                    <p class="text-gray-700 leading-relaxed">
                        If you disagree with our refund decision, you may appeal by emailing us with additional information. We will review your appeal within 5 business days. You also have the right to file a complaint with the <strong>Department of Trade and Industry (DTI)</strong> or the <strong>National Privacy Commission (NPC)</strong> if you believe your consumer rights under Philippine law have been violated.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">7. Changes to This Policy</h2>
                    <p class="text-gray-700 leading-relaxed">
                        We reserve the right to update this Refund Policy at any time. Changes will be posted on this page with an updated "Last updated" date.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">8. Contact Us</h2>
                    <p class="text-gray-700 leading-relaxed">
                        For refund requests or questions, please contact us:
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
