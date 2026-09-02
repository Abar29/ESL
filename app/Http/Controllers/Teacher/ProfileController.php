<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->teacherProfile;

        return Inertia::render('Teacher/Profile/Edit', [
            'profile' => $profile,
        ]);
    }

    public function data()
    {
        $user = Auth::user();
        $profile = $user->teacherProfile;

        return response()->json([
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'bio' => 'nullable|string|max:1000',
            'gcash_number' => 'nullable|string|max:20',
            'gotyme_number' => 'nullable|string|max:20',
            'maya_number' => 'nullable|string|max:20',
            'zoom_link' => 'nullable|url|max:255',
        ]);

        $profile = $user->teacherProfile;

        if (!$profile) {
            $profile = TeacherProfile::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                ...$validated,
            ]);
        } else {
            $profile->update($validated);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $profile = $user->teacherProfile;

        if ($request->hasFile('profile_pic')) {
            // Delete old picture
            if ($profile->profile_pic) {
                \Storage::disk('public')->delete($profile->profile_pic);
            }

            $path = $request->file('profile_pic')->store('profile-pics', 'public');
            $profile->update(['profile_pic' => $path]);
        }

        return back()->with('success', 'Profile picture updated.');
    }

    public function storeCertificate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issued_by' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $profile = $user->teacherProfile;

        $path = $request->file('file')->store('certificates', 'public');

        Certificate::create([
            'id' => Str::uuid(),
            'teacher_id' => $profile->id,
            'title' => $validated['title'],
            'issued_by' => $validated['issued_by'] ?? null,
            'file_path' => $path,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Certificate uploaded.');
    }

    public function destroyCertificate(Certificate $certificate)
    {
        $user = Auth::user();

        if ($certificate->teacher_id !== $user->teacherProfile->id) {
            abort(403);
        }

        \Storage::disk('public')->delete($certificate->file_path);
        $certificate->delete();

        return back()->with('success', 'Certificate deleted.');
    }
}
