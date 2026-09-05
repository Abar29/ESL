<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\TeacherProfile;
use App\Services\CloudinaryService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();
        $profile = $user->teacherProfile;
        $cloudinary = app(CloudinaryService::class);

        // Upload to Cloudinary or base64
        $uploadedUrl = null;

        if ($cloudinary->isConfigured()) {
            try {
                if ($profile && $profile->profile_pic && str_starts_with($profile->profile_pic, 'http')) {
                    $cloudinary->delete($profile->profile_pic);
                }
                if ($user->profile_pic && str_starts_with($user->profile_pic, 'http')) {
                    $cloudinary->delete($user->profile_pic);
                }
                $uploadedUrl = $cloudinary->upload($request->file('profile_pic'), 'profile-pics');
            } catch (\Exception $e) {
                \Log::error('Cloudinary upload exception', ['error' => $e->getMessage()]);
            }
        }

        if (!$uploadedUrl) {
            $file = $request->file('profile_pic');
            $uploadedUrl = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        // Teachers: save to teacher_profiles
        if ($profile) {
            $profile->update(['profile_pic' => $uploadedUrl]);
        } else {
            // Students & admin: save to users table
            $user->update(['profile_pic' => $uploadedUrl]);
        }

        return response()->json(['success' => true, 'profile_pic' => $uploadedUrl, 'message' => 'Profile picture updated.']);
    }

    public function getPicture(Request $request)
    {
        $user = $request->user();
        $profile = $user->teacherProfile;

        return response()->json([
            'profile_pic' => $profile?->profile_pic ?? $user->profile_pic,
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
