<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage-own-profile',
            'manage-availability',
            'verify-payments',
            'browse-teachers',
            'book-sessions',
            'manage-users',
            'approve-teachers',
            'view-all-bookings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->givePermissionTo(['browse-teachers', 'book-sessions', 'manage-own-profile']);

        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->givePermissionTo(['manage-own-profile', 'manage-availability', 'verify-payments']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $admin = User::firstOrCreate(
            ['email' => 'admin@eslscheduler.com'],
            [
                'id' => Str::uuid(),
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');
    }
}
