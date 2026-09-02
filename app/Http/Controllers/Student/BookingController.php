<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Notifications\NewBookingNotification;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('student_id', $user->id)
            ->with(['teacher.user', 'slot', 'review'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Student/Bookings/Index', [
            'bookings' => $bookings,
        ]);
    }

    public function create(AvailabilitySlot $slot)
    {
        if (!$slot->isAvailable()) {
            return back()->withErrors(['slot' => 'This slot is no longer available.']);
        }

        $slot->load('teacher.user');

        return Inertia::render('Student/Bookings/Create', [
            'slot' => $slot,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slot_id' => 'required|exists:availability_slots,id',
            'payment_method' => 'required|in:gcash,gotyme,maya',
            'payment_reference' => 'required|string|max:100',
            'screenshot' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $slot = AvailabilitySlot::findOrFail($validated['slot_id']);

        if (!$slot->isAvailable()) {
            return back()->withErrors(['slot' => 'This slot is no longer available.']);
        }

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $cloudinary = app(CloudinaryService::class);
            if ($cloudinary->isConfigured()) {
                $screenshotPath = $cloudinary->upload($request->file('screenshot'), 'payment-screenshots');
            } else {
                $screenshotPath = $request->file('screenshot')->store('payment-screenshots', 'public');
            }
        }

        $booking = Booking::create([
            'id' => Str::uuid(),
            'student_id' => Auth::id(),
            'teacher_id' => $slot->teacher_id,
            'slot_id' => $slot->id,
            'status' => 'pending_verification',
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'],
            'screenshot_path' => $screenshotPath,
            'amount' => 750,
        ]);

        $slot->update(['status' => 'held']);

        $booking->teacher->user->notify(new NewBookingNotification($booking));

        return redirect()->route('student.bookings.show', $booking)
            ->with('success', 'Booking submitted. Waiting for teacher verification.');
    }

    public function show(Booking $booking)
    {
        if ($booking->student_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Student/Bookings/Show', [
            'booking' => $booking->load(['teacher.user', 'slot', 'review']),
        ]);
    }

    public function cancel(Booking $booking)
    {
        if ($booking->student_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending_payment', 'pending_verification'])) {
            return back()->withErrors(['booking' => 'This booking cannot be cancelled.']);
        }

        $booking->update(['status' => 'cancelled']);
        $booking->slot->update(['status' => 'available']);

        return back()->with('success', 'Booking cancelled.');
    }
}
