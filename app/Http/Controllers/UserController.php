<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

//Controller to manage users
class UserController extends Controller
{
    //Store a user
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

            //Record activity
            activity()
                ->causedBy(auth()->user()) // the super admin
                ->performedOn($user)
                ->inLog('user_management')
                ->event('user-created')
                ->withProperties([
                    'created_user_name' => $user->name,
                    'created_user_email' => $user->email,
                    'created_by' => auth()->user()->name,
                    'role_assigned' => $role ? $role->name : 'None',
                    'status' => $user->status,
                ])
                ->log(" {$user->name} created a new user");

            Log::info("User created successfully: ", $user->toArray()); //Log the activity

            return redirect()->route('display.users')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            Log::error("User creation error: " . $e->getMessage());

            return back()->with('error', 'User creation failed. Please try again.');
        }
    }

        //Display all users  (for superadmin)
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

        //Show details of a particular user

        public function ShowUser($id)
        {
            $user = User::with('roles')->findOrFail($id);

            activity()
            ->causedBy(auth()->user()) // super admin
            ->performedOn($user)       // the user being viewed
            ->inLog('user_management')
            ->event('viewed')
            ->withProperties([
                'viewed_by' => auth()->user()->name,
                'viewed_by_email' => auth()->user()->email,
                'viewed_user_id' => $user->id,
                'viewed_user_name' => $user->name,
                'viewed_user_email' => $user->email,
            ])
            ->log("viewed user details");

            return view('pages.admin.ViewUserDetails', compact('user'));
        }

         //Edit user data page
        public function EditUsers($id)
        {
            $user = User::findOrFail($id);
            $roles = Role::whereNot('name','superadmin')->get();

            if($user->hasRole('user')||$user->hasRole('superadmin')){
                abort(404, 'Page not found.');
            }
            
            return view('pages.admin.EditUsers', compact('user', 'roles'));
        }

        //Update User
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
                ->inLog('user_update')
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

        //Delete a particular user
        public function DeleteUser($id)
        {
            try {
                // Prevent deleting yourself
                if ($id == auth()->id()) {
                    return redirect()->back()->with('error', 'You cannot delete your own account!');
                }

                $user = User::findOrFail($id);

                //cannot delete superadmin
                if($user->hasRole('superadmin')){
                    return redirect()->back()->with('error', 'Superadmin cannot be deleted.');

                }

                $deletedUserInfo = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];

                $user->delete();

                //Record activity
                activity()
                ->causedBy(auth()->user())       // who deleted
                ->performedOn($user)             // which user was deleted
                ->inLog('user_update')
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

        public function DisplaySubUsers()
        {
            $user = auth()->user();

            if ($user->is_sub_user) {
                abort(403, 'Sub-users cannot view other sub-users.');
            }

            $subUsers = User::where('parent_user_id', $user->id)->get();

            return view('pages.DisplaySubUsers', compact('subUsers'));
        }

        public function StoreSubUser(Request $request)
        {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'phone' => 'nullable|string',
            ]);

            $parentUser = auth()->user();
            

            // Optional: Ensure only main users can create sub-users
            if ($parentUser->is_sub_user) {
                return redirect()->back()->with('error', 'Sub-users cannot create other users.');
            }

            $subUser = User::create([
                'name'            => $request->name,
                'email'           => $request->email,
                'password'        => Hash::make($request->password),
                'status'          => 'subuser',
                'phone'           => $request->phone,
                'parent_user_id'  => $parentUser->id,
                'email_verified_at' => now(),
            ]);

            // Assign role using Spatie
            $subUser->assignRole('subuser');

            return redirect()->back()->with('success', 'Sub-user added successfully.');
        }

        public function EditSubUserPermissions($id)
        {
            $user = User::findOrFail($id);

            $targetGroups = ['monitor', 'status_page', 'incident'];

            // Filter permissions by allowed groups
            $permissions = Permission::whereIn('group_name', $targetGroups)->get();

            $groupedPermissions = $permissions->groupBy('group_name');

            $permission_groups = DB::table('permissions')
                ->select('group_name')
                ->whereIn('group_name', $targetGroups)
                ->groupBy('group_name')
                ->get();

            return view('pages.EditSubUserPermissions', compact('user', 'groupedPermissions', 'permission_groups'));
        }

        public function UpdateSubUserPermissions(Request $request, $id)
        {
            $user = User::findOrFail($id);

            $permissions = $request->input('permission', []);
            

            $user->syncPermissions($permissions);

            return redirect()->route('display.sub.users')->with('success', 'Permissions updated successfully.');
        }

}
