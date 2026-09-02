<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()
            ->with('teacherProfile')
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:student,teacher,admin',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'id' => Str::uuid(),
            ...$validated,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        if ($validated['role'] === 'teacher') {
            TeacherProfile::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user->load('teacherProfile'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:student,teacher,admin',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,suspended',
        ]);

        $oldRole = $user->role;
        $user->update($validated);

        if ($validated['role'] === 'teacher' && $oldRole !== 'teacher') {
            if (!$user->teacherProfile) {
                TeacherProfile::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'approval_status' => 'pending',
                ]);
            } else {
                $user->teacherProfile->update(['approval_status' => 'pending']);
            }
        }

        return back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return back()->with('success', 'User restored.');
    }
}
