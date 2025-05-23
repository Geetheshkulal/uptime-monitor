<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignDefaultPermissions extends Seeder
{
    public function run()
    {
        // firstOrFail() will throw an exception if the role doesn't exist
        $userRole = Role::where('name', 'user')->firstOrFail();
        
        // 2. Define which permissions should be given to regular users by default
        $defaultPermissions = [
            'see.activity',      
            'see.incidents',      
            'see.monitors',       
            'see.monitor.details',
            'edit.monitor',      
            'delete.monitor',    
            'add.monitor',      
            'see.statuspage',     
            'delete.user',      
            'raise.issue'
        ];
        
        // 3. Loop through each permission and assign it to the role
        foreach ($defaultPermissions as $permissionName) {
        
            $permission = Permission::where('name', $permissionName)->first();
            
            // If permission exists and isn't already assigned to the role
            if ($permission && !$userRole->hasPermissionTo($permission)) {
                // Assign the permission to the role
                $userRole->givePermissionTo($permission);
            }
        }
        $this->command->info('Default permissions assigned to user role.');

        $supportRole = Role::where('name', 'support')->firstOrFail();

        $supportPermissions = [
            'manage.coupons',
        ];
    }
}