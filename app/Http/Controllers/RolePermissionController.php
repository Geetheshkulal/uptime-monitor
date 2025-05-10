<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

//Controller for permissions in role
class RolePermissionController extends Controller
{
    //Edit permissions in role
    public function EditRolePermissions($id)
    {
        $superadminIds = User::role('superadmin')->pluck('id');

        //Cant edit superadmin permissions
        if($superadminIds->contains($id)) {
            abort(404);
        }
        
        $role = Role::findOrFail($id); //Find role to edit permissions
        $permission_groups = Permission::select('group_name')->groupBy('group_name')->get();
        
        // Get all permissions grouped by group_name
        $groupedPermissions = [];
        foreach ($permission_groups as $group) {
            $groupedPermissions[$group->group_name] = Permission::where('group_name', $group->group_name)->get();
        }    
        
        return view('pages.admin.EditRolePermissions', compact('role', 'permission_groups', 'groupedPermissions'));
    }

    //Update permissions in role
    public function UpdateRolePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id); //Find role to update

        //Validate request
        $request->validate([
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,id'
        ]);

        try {
            //get old role permission names
            $oldPermissions = $role->permissions->pluck('name')->toArray();

            //get role permissions
            $permissions = $request->permission ? Permission::whereIn('id', $request->permission)->get() : [];
            $role->syncPermissions($permissions); //assign new permissions to role

            $newPermissions = $role->permissions->pluck('name')->toArray();

            //Log activity
            activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->inLog('permission_role_management')
            ->withProperties([
                'updated_by' => auth()->user()->name,
                'updated_by_email' => auth()->user()->email,
                'role' => $role->name,
                'old_permissions' => $oldPermissions,
                'new_permissions' => $newPermissions
            ])
            ->log('Updated role permissions');

            return redirect()->route('roles.index')
                   ->with('success', 'Permissions updated successfully!');
                   
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating permissions: '.$e->getMessage());
        }
    }
}
