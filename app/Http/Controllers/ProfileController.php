<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\TeacherProfile;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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

        if ($profile) {
            if ($profile->profile_pic) {
                Storage::disk('public')->delete($profile->profile_pic);
            }
            $path = $request->file('profile_pic')->store('profile-pics', 'public');
            $profile->update(['profile_pic' => $path]);
        } else {
            $profile = TeacherProfile::create([
                'user_id' => $user->id,
                'profile_pic' => $request->file('profile_pic')->store('profile-pics', 'public'),
            ]);
        }

        return back()->with('success', 'Profile picture updated.');
    }

    public function getPicture(Request $request)
    {
        $user = $request->user();
        $profile = $user->teacherProfile;

        return response()->json([
            'profile_pic' => $profile?->profile_pic,
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
