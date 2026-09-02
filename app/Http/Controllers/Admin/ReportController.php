<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'declined_bookings' => Booking::where('status', 'declined')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'active_teachers' => TeacherProfile::where('approval_status', 'approved')->count(),
            'pending_approvals' => TeacherProfile::where('approval_status', 'pending')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_revenue' => Booking::where('status', 'completed')->count() * 100, // Assuming 100 per session
        ];

        $monthlyBookings = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $topTeachers = TeacherProfile::with('user')
            ->withCount(['bookings' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        $recentBookings = Booking::with(['student', 'teacher.user', 'slot'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return Inertia::render('Admin/Reports/Index', [
            'stats' => $stats,
            'monthlyBookings' => $monthlyBookings,
            'topTeachers' => $topTeachers,
            'recentBookings' => $recentBookings,
        ]);
    }
}
