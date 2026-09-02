<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->student_id !== Auth::id()) {
            abort(403);
        }

        if (!$booking->isCompleted()) {
            return back()->withErrors(['booking' => 'You can only review completed sessions.']);
        }

        if ($booking->review) {
            return back()->withErrors(['booking' => 'You have already reviewed this session.']);
        }

        return Inertia::render('Student/Reviews/Create', [
            'booking' => $booking->load(['teacher.user', 'slot']),
        ]);
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->student_id !== Auth::id()) {
            abort(403);
        }

        if (!$booking->isCompleted() || $booking->review) {
            return back()->withErrors(['booking' => 'Cannot review this session.']);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'id' => Str::uuid(),
            'booking_id' => $booking->id,
            ...$validated,
            'created_at' => now(),
        ]);

        // Update teacher's average rating
        $this->updateTeacherRating($booking->teacher_id);

        return redirect()->route('student.bookings.show', $booking)
            ->with('success', 'Review submitted successfully.');
    }

    private function updateTeacherRating(string $teacherId): void
    {
        $avgRating = Review::whereHas('booking', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->avg('rating');

        TeacherProfile::where('id', $teacherId)->update([
            'rating_avg' => round($avgRating, 2),
        ]);
    }
}
