<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    public function DisplayRoles()
    {
        try {
            // Get paginated roles (10 per page)
            $roles = Role::whereNot('name', 'superadmin')->orderBy('name')->get();

            activity()
            ->causedBy(auth()->user())       // who deleted    
            ->inLog('role_management')     
            ->event('viewed')
            ->withProperties([
                'page' => 'Roles List',
                'user_name' => auth()->user()->name,
                'user_email' => auth()->user()->email,
            ])->log('visited roles page');
            
            return view('pages.admin.DisplayRoles', compact('roles'));
            
        } catch (\Exception $e) {
            // Log error and show friendly message
            Log::error('Error displaying roles: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load roles. Please try again.');
        }
    }


    //Add Role page
    public function AddRole()
    {
        return view('pages.admin.AddRoles');
    }

    //Store the new role in Database.
    public function StoreRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name'
        ]);

        $role=Role::create(['name' => $request->name]);

        activity()
        ->causedBy(auth()->user())
        ->performedOn($role)
        ->inLog('role_management')
        ->event('created')
        ->withProperties([
            'role_name' => $role->name,
            'created_by' => auth()->user()->name,
            'created_user_email' => auth()->user()->email,
        ])
        ->log('A new role was created.');

        return redirect()->route('display.roles')
               ->with('success', 'Role added successfully!');
    }


    //Delete a role
    public function DeleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Prevent deletion of superadmin role 
            if ($role->name === 'superadmin') {
                return redirect()->back()
                       ->with('error', 'Cannot delete Admin role!');
            }

        $roleName = $role->name; // Store role name before deletion
        $roleId = $role->id;

        // Log activity before deletion
        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->inLog('role_management')
            ->event('deleted')
            ->withProperties([
                'role_id' => $roleId,
                'role_name' => $roleName,
                'deleted_by' => auth()->user()->name,
                'deleted_user_email' => auth()->user()->email,
            ])
            ->log("Role deleted");

            //Delete Role
            $role->delete();
            
            return redirect()->route('display.roles')
                   ->with('success', 'Role deleted successfully!');
                   
        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }
    

    //Edit Role Page
    public function EditRole($id)
    {
        $system_roles = ['user','subuser','support'];
        $role = Role::findOrFail($id);

        if(in_array($role->name,$system_roles)){
            abort(404,'System roles cannot be modified');
        }

        return view('pages.admin.EditRole', compact('role'));
    }

    /**
     * Update role
     */
    public function UpdateRole(Request $request, $id)
    {
        //Validate input
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id
        ]);
 
        $role = Role::findOrFail($id); //Find role with given id

        $oldName = $role->name; 
        $newName = $request->name;

        //Update role
        $role->update(['name' => $request->name]);

        //Log the updation
        activity()
        ->causedBy(auth()->user())
        ->performedOn($role)
        ->inLog('role_management')
        ->event('updated')
        ->withProperties([
            'role_id' => $role->id,
            'old_name' => $oldName,
            'new_name' => $newName,
            'updated_by' => auth()->user()->name,
            'updated_user_email' => auth()->user()->email,
        ])
        ->log('Role name updated');

        return redirect()->route('display.roles')
               ->with('success', 'Role updated successfully!');
    }
}
