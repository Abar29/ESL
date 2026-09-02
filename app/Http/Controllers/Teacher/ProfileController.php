<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\TeacherProfile;
use App\Services\CloudinaryService;
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
        $profile = $user->teacherProfile ? $user->teacherProfile->load('certificates') : null;

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
        $cloudinary = app(CloudinaryService::class);

        if ($cloudinary->isConfigured()) {
            if ($profile && $profile->profile_pic && str_starts_with($profile->profile_pic, 'http')) {
                $cloudinary->delete($profile->profile_pic);
            }
            $url = $cloudinary->upload($request->file('profile_pic'), 'profile-pics');
            if ($url) {
                $profile->update(['profile_pic' => $url]);
                return back()->with('success', 'Profile picture uploaded.');
            }
            return back()->withErrors(['profile_pic' => 'Failed to upload.']);
        }

        // Fallback to local
        if ($profile->profile_pic && !str_starts_with($profile->profile_pic, 'http')) {
            \Storage::disk('public')->delete($profile->profile_pic);
        }
        $path = $request->file('profile_pic')->store('profile-pics', 'public');
        $profile->update(['profile_pic' => $path]);

        return back()->with('success', 'Profile picture uploaded.');
    }

    public function storeCertificate(Request $request)
    {
        $user = Auth::user();
        $profile = $user->teacherProfile;

        if (!$profile) {
            return back()->withErrors(['file' => 'Please complete your profile first.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issued_by' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $cloudinary = app(CloudinaryService::class);

        if ($cloudinary->isConfigured()) {
            $url = $cloudinary->upload($request->file('file'), 'certificates');
            if ($url) {
                Certificate::create([
                    'id' => Str::uuid(),
                    'teacher_id' => $profile->id,
                    'title' => $validated['title'],
                    'issued_by' => $validated['issued_by'] ?? null,
                    'file_path' => $url,
                    'created_at' => now(),
                ]);
                return back()->with('success', 'Certificate uploaded.');
            }
            return back()->withErrors(['file' => 'Failed to upload.']);
        }

        // Fallback to local
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

        $cloudinary = app(CloudinaryService::class);
        if ($cloudinary->isConfigured() && str_starts_with($certificate->file_path, 'http')) {
            $cloudinary->delete($certificate->file_path);
        } elseif (!str_starts_with($certificate->file_path, 'http')) {
            \Storage::disk('public')->delete($certificate->file_path);
        }

        $certificate->delete();

        return back()->with('success', 'Certificate deleted.');
    }
}
