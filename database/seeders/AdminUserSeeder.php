<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create permissions if they don't exist
        $permissions = [
            'view users',
            'edit users',
            'delete users',
            'view videos',
            'create videos',
            'edit videos',
            'delete videos',
            'view withdrawals',
            'approve withdrawals',
            'reject withdrawals',
            'view earnings',
            'view deposits',
            'manage system'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to admin role
        $adminRole->syncPermissions($permissions);

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@videoearn.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@videoearn.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'balance' => 0.00,
                'points' => 0,
                'has_deposited' => true,
                'is_active' => true,
                'phone' => '+1234567890',
                'payment_method' => 'admin',
                'payment_details' => 'Admin Account',
            ]
        );

        // Assign admin role to the user
        $adminUser->assignRole('admin');

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@videoearn.com');
        $this->command->info('Password: admin123');
    }
}