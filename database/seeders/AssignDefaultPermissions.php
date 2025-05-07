<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignDefaultPermissions extends Seeder
{
    public function run()
    {
        // 1. First we get the 'user' role from the database
        // firstOrFail() will throw an exception if the role doesn't exist
        $userRole = Role::where('name', 'user')->firstOrFail();
        
        // 2. Define which permissions should be given to regular users by default
        // These are the permissions you want all normal users to have
        $defaultPermissions = [
            'see.incidents',      // Can view incidents
            'see.monitors',       // Can view monitors list
            'see.monitor.details',// Can view monitor details
            'edit.monitor',      // Can edit monitors
            'delete.monitor',    // Can delete monitors
            'see.statuspage',     // Can view status page
            
        ];
        
        // 3. Loop through each permission and assign it to the role
        foreach ($defaultPermissions as $permissionName) {
            // Find the permission in database
            $permission = Permission::where('name', $permissionName)->first();
            
            // If permission exists and isn't already assigned to the role
            if ($permission && !$userRole->hasPermissionTo($permission)) {
                // Assign the permission to the role
                $userRole->givePermissionTo($permission);
            }
        }
        
        // Output success message to console
        $this->command->info('Default permissions assigned to user role.');
    }
}