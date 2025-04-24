<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $user = Role::create(['name' => 'user']);

        // Define permissions
        $allPermissions = [
            'create tasks',
            'edit tasks',
            'delete tasks',
            'assign tasks',
            'change task status',
            'view all tasks',
            'manage users',
            'manage roles',
            'view own tasks'
        ];

        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo($allPermissions);
        $manager->givePermissionTo(['assign tasks', 'change task status', 'view all tasks']);
        $user->givePermissionTo(['view own tasks']);

        // Assign roles to users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('manager123'),
        ]);
        $user = User::create([
            'name' => 'Regular User 1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('user1234'),
        ]);
        
        $admin->assignRole('admin');
        $manager->assignRole('manager');
        $user->assignRole('user');
        $user = User::create([
            'name' => 'Regular User 2',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('user1234'),
        ]);
        $user->assignRole('user');
        $user = User::create([
            'name' => 'Regular User 3',
            'email' => 'user3@gmail.com',
            'password' => Hash::make('user1234'),
        ]);
        $user->assignRole('user');
    }
}
