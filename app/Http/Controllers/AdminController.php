<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;


class AdminController extends Controller
{
    /**
     * Display all users in the admin panel with pagination
     */
    public function DisplayUsers(Request $request)
    {
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

        return view('pages.admin.DisplayUsers', compact('users'));
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
            $roles = Role::orderBy('name')->paginate(10);
            
            return view('pages.admin.DisplayRoles', compact('roles'));
            
        } catch (\Exception $e) {
            // Log error and show friendly message
            \Log::error('Error displaying roles: ' . $e->getMessage());
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

    public function DisplayActivity()
    {
        $logs = Activity::latest()->get(); // Fetch all activity logs
        return view('pages.admin.DisplayActivity', compact('logs'));
    }

}