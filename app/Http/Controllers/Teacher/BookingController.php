<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\BookingDeclinedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('teacher_id', $user->teacherProfile->id)
            ->with(['student', 'slot'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Teacher/Bookings/Index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);

        return Inertia::render('Teacher/Bookings/Show', [
            'booking' => $booking->load(['student', 'slot']),
        ]);
    }

    public function accept(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status !== BookingStatus::PendingVerification) {
            return back()->withErrors(['booking' => 'This booking cannot be accepted.']);
        }

        $booking->update(['status' => BookingStatus::Confirmed]);
        $booking->slot->update(['status' => \App\Enums\SlotStatus::Booked]);

        $booking->student->notify(new BookingConfirmedNotification($booking));

        return back()->with('success', 'Booking confirmed.');
    }

    public function decline(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status !== BookingStatus::PendingVerification) {
            return back()->withErrors(['booking' => 'This booking cannot be declined.']);
        }

        $booking->update(['status' => BookingStatus::Declined]);
        $booking->slot->update(['status' => \App\Enums\SlotStatus::Available]);

        $booking->student->notify(new BookingDeclinedNotification($booking));

        return back()->with('success', 'Booking declined.');
    }

    public function complete(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status !== BookingStatus::Confirmed) {
            return back()->withErrors(['booking' => 'This booking cannot be completed.']);
        }

        $booking->update(['status' => BookingStatus::Completed]);

        return back()->with('success', 'Session marked as completed.');
    }

    private function authorizeBooking(Booking $booking): void
    {
        $user = Auth::user();
        if ($booking->teacher_id !== $user->teacherProfile->id) {
            abort(403);
        }
    }
}
