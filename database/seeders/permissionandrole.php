<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class permissionandrole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions with their groups
        $permissions = [
            // User permissions
            'see.users' => 'user',
            'edit.user' => 'user',
            'delete.user' => 'user',
            'add.user' => 'user',
            
            // Role permissions
            'see.roles' => 'role',
            'edit.role' => 'role',
            'edit.role.permissions' => 'role',
            'delete.role' => 'role',
            'add.role' => 'role',
            
            // Permission permissions
            // 'see.permissions' => 'permission',
            // 'edit.permission' => 'permission',
            // 'delete.permission' => 'permission',
            // 'add.permission' => 'permission',
            
            // Monitor permissions
            'see.monitors' => 'monitor',
            'see.monitor.details' => 'monitor',
            'edit.monitor' => 'monitor',
            'delete.monitor' => 'monitor',

            
            
            // Activity permissions
            'see.activity' => 'activity'
        ];

        // Create permissions
        foreach ($permissions as $name => $group) {
            Permission::create([
                'name' => $name,
                'group_name' => $group
            ]);
        }

        // Create superadmin role and assign all permissions       
    }
}
