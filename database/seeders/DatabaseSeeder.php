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
        // Create permissions
        Permission::create(['name' => 'manage-own-profile']);
        Permission::create(['name' => 'manage-availability']);
        Permission::create(['name' => 'verify-payments']);
        Permission::create(['name' => 'browse-teachers']);
        Permission::create(['name' => 'book-sessions']);
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'approve-teachers']);
        Permission::create(['name' => 'view-all-bookings']);

        // Create roles and assign permissions
        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo(['browse-teachers', 'book-sessions', 'manage-own-profile']);

        $teacherRole = Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo(['manage-own-profile', 'manage-availability', 'verify-payments']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create admin user
        User::create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@eslscheduler.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ])->assignRole('admin');
    }
}
