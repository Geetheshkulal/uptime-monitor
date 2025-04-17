<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    //
    public function EditRolePermissions($id)
    {
        $superadminIds = User::role('superadmin')->pluck('id');

        if($superadminIds->contains($id)) {
            abort(404);
        }
        $role = Role::findOrFail($id);
        $permission_groups = Permission::select('group_name')->groupBy('group_name')->get();
        
        // Get all permissions grouped by group_name
        $groupedPermissions = [];
        foreach ($permission_groups as $group) {
            $groupedPermissions[$group->group_name] = Permission::where('group_name', $group->group_name)->get();
        }    
        
        return view('pages.admin.EditRolePermissions', compact('role', 'permission_groups', 'groupedPermissions'));
    }

    
    public function UpdateRolePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $request->validate([
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,id'
        ]);

        try {
            $oldPermissions = $role->permissions->pluck('name')->toArray();

            $permissions = $request->permission ? Permission::whereIn('id', $request->permission)->get() : [];
            $role->syncPermissions($permissions);

            $newPermissions = $role->permissions->pluck('name')->toArray();

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
