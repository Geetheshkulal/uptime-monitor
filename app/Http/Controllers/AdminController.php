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
            $user->delete();

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

        Role::create(['name' => $request->name]);

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
        $role->update(['name' => $request->name]);

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

        Permission::create($validated);

        return redirect()->route('display.permissions')
               ->with('success', 'Permission added successfully!');
    }

    public function DeletePermission($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

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
            $permission->update($validated);

            return redirect()->route('display.permissions')
                ->with('success', 'Permission updated successfully!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating permission: '.$e->getMessage());
        }
    }
    public function DisplayActivity()
    {
        // Fetch all activity logs
        $logs = Activity::latest()->get();
        
        // Fetch all users with only id and name
        $users = User::select('id', 'name')->get();
        
        return view('pages.admin.DisplayActivity', compact('logs', 'users'));
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
            $permissions = $request->permission ? Permission::whereIn('id', $request->permission)->get() : [];
            $role->syncPermissions($permissions);

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