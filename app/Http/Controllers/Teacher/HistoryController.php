<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->teacherProfile;

        $completedBookings = Booking::where('teacher_id', $profile->id)
            ->where('status', 'completed')
            ->with(['student', 'slot', 'review'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total_completed' => Booking::where('teacher_id', $profile->id)->where('status', 'completed')->count(),
            'total_cancelled' => Booking::where('teacher_id', $profile->id)->where('status', 'cancelled')->count(),
            'total_declined' => Booking::where('teacher_id', $profile->id)->where('status', 'declined')->count(),
            'average_rating' => $profile->rating_avg,
        ];

        return Inertia::render('Teacher/History/Index', [
            'bookings' => $completedBookings,
            'stats' => $stats,
        ]);
    }
}
