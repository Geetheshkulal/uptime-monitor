<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    //Display permission page
    public function DisplayPermissions()
    {
        $permissions = Permission::latest()->get();
        return view('pages.admin.DisplayPermissions', compact('permissions'));
    }

    //Add permission page
    public function AddPermission()
    {
        return view('pages.admin.AddPermission');
    }

    //Function to store permission
    public function StorePermission(Request $request)
    {
        //validation
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'group_name' => 'required|string|in:user,role,permission,monitor,activity'
        ]);

        //Create the permissions

        $permission=Permission::create($validated);

        //log activity
        activity()
        ->causedBy(auth()->user())
        ->performedOn($permission)
        ->inLog('permission_management')
        ->event('created')
        ->withProperties([
            'permission_id' => $permission->id,
            'name' => $permission->name,
            'group' => $permission->group_name,
            'created_by' => auth()->user()->name,
            'created_user_email' => auth()->user()->email
        ])
        ->log('Created a new permission');

        return redirect()->route('display.permissions')
               ->with('success', 'Permission added successfully!');
    }

    //Delete Permission
    public function DeletePermission($id)
    {
        try {

            $permission = Permission::findOrFail($id);

            $deletedData = $permission->toArray();

            $permission->delete(); //delete the permission

            ///Log the activity
            activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->inLog('permission_management')
            ->event('deleted')
            ->withProperties([
                'deleted_permission' => $deletedData,
                'deleted_by' => auth()->user()->name,
                'deleted_user_email' => auth()->user()->email
            ])
            ->log('Deleted a permission');

            return redirect()->route('display.permissions')
                   ->with('success', 'Permission deleted successfully!');
                   
        } catch (\Exception $e) {
            return redirect()->route('display.permissions')
                   ->with('error', 'Failed to delete permission: ' . $e->getMessage());
        }
    }


    //Edit permission page.
    public function EditPermission($id)
    {
        $permission = Permission::findOrFail($id);
        return view('pages.admin.EditPermission', compact('permission'));
    }

    //Update the permission
    public function UpdatePermission(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$id,
            'group_name' => 'required|string|max:255'
        ]);

        try {
            $permission = Permission::findOrFail($id); //Find a permission.

            $oldValues = $permission->getOriginal();

            $permission->update($validated);

            //Log activity
            activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
            ->inLog('permission_management')
            ->event('updated')
            ->withProperties([
                'old_values' => $oldValues,
                'new_values' => $validated,
                'updated_by' => auth()->user()->name,
                'user_email' => auth()->user()->email
            ])
            ->log('Updated a permission');

            return redirect()->route('display.permissions')
                ->with('success', 'Permission updated successfully!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating permission: '.$e->getMessage());
        }
    }
}
