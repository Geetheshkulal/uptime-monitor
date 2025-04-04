<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{
    /**
     * Display all users in the admin panel with pagination
     */
public function storeUser(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:3',
        'phone' => 'nullable|string',
        'role' => 'required|exists:roles,id',  // Ensure role exists
        'status' => 'required|in:free,paid',
        'premium_end_date' => 'nullable|date'
    ]);

    try {
        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
            'premium_end_date' => $validated['premium_end_date'] ?? null,
            'last_login_ip' => $request->ip()
        ]);

        // Find role and attach to user
        $role = Role::find($validated['role']);
        if ($role) {
            $user->roles()->attach($role->id);
        } else {
            Log::warning("Role not found: " . $validated['role']);
        }

        activity()
            ->causedBy(auth()->user()) // the super admin
            ->performedOn($user)
            ->event('user-created')
            ->withProperties([
                'created_user_name' => $user->name,
                'created_user_email' => $user->email,
                'created_by' => auth()->user()->name,
                'role_assigned' => $role ? $role->name : 'None',
                'status' => $user->status,
            ])
            ->log('Super Admin created a new user');

        Log::info("User created successfully: ", $user->toArray());

        return redirect()->route('display.users')->with('success', 'User created successfully');
    } catch (\Exception $e) {
        Log::error("User creation error: " . $e->getMessage());

        return back()->with('error', 'User creation failed. Please try again.');
    }
}

    public function DisplayUsers(Request $request)
    {
        $roles = Role::whereNot('name','superadmin')->get();

        // Basic search functionality
        $search = $request->input('search');
        
        $users = User::with('roles')
                    ->when($search, function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orderBy('name')
                    ->paginate(10); // 10 users per page

        return view('pages.admin.DisplayUsers', compact('users','roles'));
    }

    public function ShowUser($id)
    {
        $user = User::with('roles')->findOrFail($id);

        activity()
        ->causedBy(auth()->user()) // super admin
        ->performedOn($user)       // the user being viewed
        ->event('viewed')
        ->withProperties([
            'viewed_by' => auth()->user()->name,
            'viewed_by_email' => auth()->user()->email,
            'viewed_user_id' => $user->id,
            'viewed_user_name' => $user->name,
            'viewed_user_email' => $user->email,
        ])
        ->log('Super Admin viewed user details');

        return view('pages.admin.ViewUserDetails', compact('user'));
    }

    public function EditUsers($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        
        return view('pages.admin.EditUsers', compact('user', 'roles'));
    }


    public function UpdateUsers(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->roles->pluck('name')->first() ?? 'none',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id'
        ]);
    
        try {
            // Update basic user info
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone']
            ]);
    
            // Update role
            $role = Role::findById($validated['role']);
            $user->syncRoles([$role->name]);

            $newValues = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $role->name,
            ];

            activity()
            ->causedBy(auth()->user()) // Who made the change
            ->performedOn($user)       // Which user was updated
            ->event('user updated')
            ->withProperties([
                'edited_by' => auth()->user()->name,
                'edited_by_email'=>auth()->user()->email,
                'old' => $oldValues,
                'new' => $newValues,
            ])
            ->log('User details updated');
    
            return redirect()->route('display.users', $user->id)
                   ->with('success', 'User updated successfully!');
                   
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating user: '.$e->getMessage());
        }
    }


    public function DeleteUser($id)
    {
        try {
            // Prevent deleting yourself
            if ($id == auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account!');
            }

            $user = User::findOrFail($id);

            $deletedUserInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];

            $user->delete();

            activity()
            ->causedBy(auth()->user())       // who deleted
            ->performedOn($user)             // which user was deleted
            ->event('user deleted')
            ->withProperties([
                'deleted_by' => auth()->user()->name,
                'deleted_by_email' => auth()->user()->email,
                'deleted_user' => $deletedUserInfo,
            ])
            ->log('A user account was deleted');


            return redirect()->route('display.users')
                   ->with('success', 'User deleted successfully!');
                   
        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function DisplayRoles()
    {
        try {
            // Get paginated roles (10 per page)
            $roles = Role::whereNot('name', 'superadmin')->orderBy('name')->get();

            activity()
            ->causedBy(auth()->user())       // who deleted         
            ->event('viewed')
            ->withProperties([
                'page' => 'Roles List',
                'user_name' => auth()->user()->name,
                'user_email' => auth()->user()->email,
            ])
            ->log('visited roles page');
            
            return view('pages.admin.DisplayRoles', compact('roles'));
            
        } catch (\Exception $e) {
            // Log error and show friendly message
            Log::error('Error displaying roles: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load roles. Please try again.');
        }
    }


    public function AddRole()
    {
        return view('pages.admin.AddRoles');
    }
    public function StoreRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name'
        ]);

        $role=Role::create(['name' => $request->name]);

        activity()
        ->causedBy(auth()->user())
        ->performedOn($role)
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


    public function DeleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Prevent deletion of admin role (optional safeguard)
            if ($role->name === 'admin') {
                return redirect()->back()
                       ->with('error', 'Cannot delete Admin role!');
            }

        $roleName = $role->name; // Store role name before deletion
        $roleId = $role->id;

        // Log activity before deletion
        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->event('deleted')
            ->withProperties([
                'role_id' => $roleId,
                'role_name' => $roleName,
                'deleted_by' => auth()->user()->name,
                'deleted_user_email' => auth()->user()->email,
            ])
            ->log("Role deleted");

            $role->delete();
            
            return redirect()->route('display.roles')
                   ->with('success', 'Role deleted successfully!');
                   
        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }
    

    public function EditRole($id)
    {
        $role = Role::findOrFail($id);
        return view('pages.admin.EditRole', compact('role'));
    }

    /**
     * Update role
     */
    public function UpdateRole(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id
        ]);

        $role = Role::findOrFail($id);

        $oldName = $role->name; 
        $newName = $request->name;

        $role->update(['name' => $request->name]);

        activity()
        ->causedBy(auth()->user())
        ->performedOn($role)
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


    public function DisplayPermissions()
    {
        $permissions = Permission::latest()->get();
        return view('pages.admin.DisplayPermissions', compact('permissions'));
    }

    public function AddPermission()
    {
        return view('pages.admin.AddPermission');
    }

    public function StorePermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'group_name' => 'required|string|in:user,role,permission,monitor,activity'
        ]);

        $permission=Permission::create($validated);

        activity()
        ->causedBy(auth()->user())
        ->performedOn($permission)
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

    public function DeletePermission($id)
    {
        try {

            $permission = Permission::findOrFail($id);

            $deletedData = $permission->toArray();

            $permission->delete();

            activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
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


    public function EditPermission($id)
    {
        $permission = Permission::findOrFail($id);
        return view('pages.admin.EditPermission', compact('permission'));
    }

    public function UpdatePermission(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$id,
            'group_name' => 'required|string|max:255'
        ]);

        try {
            $permission = Permission::findOrFail($id);

            $oldValues = $permission->getOriginal();

            $permission->update($validated);

            activity()
            ->causedBy(auth()->user())
            ->performedOn($permission)
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
    public function DisplayActivity()
    {
        $logs = Activity::latest()->get(); // Fetch all activity logs
        return view('pages.admin.DisplayActivity', compact('logs'));
    }


    public function EditRolePermissions($id)
    {
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

    public function AdminDashboard(){
        return view('pages.admin.AdminDashboard');
    }
}