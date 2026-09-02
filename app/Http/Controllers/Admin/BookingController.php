<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['student', 'teacher.user', 'slot']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('teacher.user', function ($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%");
                })
                ->orWhere('payment_reference', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderByDesc('created_at')->paginate(20);

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    public function show(Booking $booking)
    {
        $booking->load(['student', 'teacher.user', 'slot', 'review']);

        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking,
        ]);
    }

    public function cancel(Booking $booking)
    {
        if (!in_array($booking->status, ['pending_payment', 'pending_verification', 'confirmed'])) {
            return back()->withErrors(['booking' => 'This booking cannot be cancelled.']);
        }

        $booking->update(['status' => 'cancelled']);
        if ($booking->slot) {
            $booking->slot->update(['status' => 'available']);
        }

        return back()->with('success', 'Booking cancelled by admin.');
    }
}
