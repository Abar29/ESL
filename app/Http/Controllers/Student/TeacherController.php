<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = TeacherProfile::where('approval_status', 'approved')
            ->with(['user', 'certificates'])
            ->withCount('availabilitySlots')
            ->paginate(12);

        return Inertia::render('Student/Teachers/Index', [
            'teachers' => $teachers,
        ]);
    }

    public function show(TeacherProfile $teacher)
    {
        if (!$teacher->isApproved()) {
            abort(404);
        }

        $slots = $teacher->availabilitySlots()
            ->where('slot_date', '>=', now()->toDateString())
            ->where('status', 'available')
            ->with('teacher')
            ->orderBy('slot_date')
            ->orderBy('start_time')
            ->get();

        return Inertia::render('Student/Teachers/Show', [
            'teacher' => $teacher->load(['user', 'certificates']),
            'slots' => $slots,
        ]);
    }
}
