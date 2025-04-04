<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdmin extends Seeder
{
    public function run()
    {
        // Check if superadmin role exists, create if not
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);

        // Create or update superadmin user
        $user = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('123'), // Using simple password for development
                'phone'=>'1234567890'
            ]
        );

        // Assign superadmin role
        if (!$user->hasRole('superadmin')) {
            $user->assignRole('superadmin');
        }

        $this->command->info('Super Admin created:');
        $this->command->info('Email: superadmin@example.com');
        $this->command->info('Password: 123');
    }
}