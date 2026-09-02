<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherApprovalController extends Controller
{
    public function index()
    {
        $pendingTeachers = TeacherProfile::where('approval_status', 'pending')
            ->with(['user', 'certificates'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('Admin/TeacherApprovals/Index', [
            'teachers' => $pendingTeachers,
        ]);
    }

    public function approve(TeacherProfile $teacher)
    {
        $teacher->update(['approval_status' => 'approved']);

        return back()->with('success', 'Teacher approved.');
    }

    public function reject(Request $request, TeacherProfile $teacher)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $teacher->update(['approval_status' => 'rejected']);

        return back()->with('success', 'Teacher rejected.');
    }
}
