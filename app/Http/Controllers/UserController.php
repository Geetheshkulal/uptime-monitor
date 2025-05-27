<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Models\Whitelist;
use Illuminate\Support\Carbon;

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
            'phone' => 'nullable|string|digits:10',
            'role' => 'required|exists:roles,id',  // Ensure role exists
            'premium_end_date' => 'nullable|date',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => strtolower($validated['email']),
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'status' => 'free',
                'premium_end_date' => null,
                'last_login_ip' => $request->ip(),
                'status_page_hash' => Str::random(32),
                'email_verified_at'=>now()
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
        public function DisplayCustomers(Request $request)
        {
            $roles = Role::whereNot('name','superadmin')->whereNot('name','subuser')->whereNot('name','user')->get();

            // Basic search functionality
            $search = $request->input('search');
            
            $users = User::withTrashed()->with('roles')
                        ->when($search, function($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orderBy('name')
                        ->paginate(10); // 10 users per page

            $customerCount = User::withTrashed()->role('user')->count();

            $userCount =    User::whereDoesntHave('roles', function ($q) {
                                $q->where('name', 'user');
                            })->count();

            return view('pages.admin.DisplayUsers', compact('users','roles','customerCount','userCount'));
        }

        //Show details of a particular user

        public function ShowUser($id)
        {
            $user = User::withTrashed()->with('roles')->findOrFail($id);

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
            $user = User::withTrashed()->findOrFail($id);
            $roles = Role::whereNot('name','superadmin')->get();

            if($user->hasRole('user')||$user->hasRole('superadmin')){
                abort(404, 'Page not found.');
            }
            
            return view('pages.admin.EditUsers', compact('user', 'roles'));
        }

        //Update User
        public function UpdateUsers(Request $request, $id)
        {
            $user = User::withTrashed()->findOrFail($id);

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
                    'email' => strtolower($request->email),
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

        //Disable (soft delete) a particular user
        public function DeleteUser($id)
        {
            try {
                // Prevent deleting yourself
                if ($id === auth()->id()) {
                    return redirect()->route('display.users')->with('error', 'You cannot delete your own account!');
                }

                $user = User::findOrFail($id);

                Log::info('User to be deleted: ', $user->toArray());


                //cannot delete superadmin
                if($user->hasRole('superadmin')){
                    return redirect()->route('display.users')->with('error', 'Superadmin cannot be deleted.');

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
                    ->with('success', 'User disabled successfully!');
                    
            } catch (\Exception $e) {
                return redirect()->route('display.users')
                    ->with('error', 'Error disabling user: ' . $e->getMessage());
            }
        }

        public function DisplaySubUsers()
        {
            $user = auth()->user();

            if ($user->is_sub_user){
                abort(403, 'Sub-users cannot view other sub-users.');
            }

            $subUsers = User::withTrashed()->where('parent_user_id', $user->id)->get();

            return view('pages.DisplaySubUsers', compact('subUsers'));
        }

        public function StoreSubUser(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:3',
                'phone' => 'required|nullable|string|digits:10',
            ]);

            $parentUser = auth()->user();
            

            // Optional: Ensure only main users can create sub-users
            if ($parentUser->is_sub_user) {
                return redirect()->back()->with('error', 'Sub-users cannot create other users.');
            }

            $subUser = User::create([
                'name'            => $request->name,
                'email'           => strtolower($request->email),
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


        public function statusPageSettings()
        {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            if($user->hasRole('subuser')){
                $user = $user->parentUser;
            }

            $whitelistedIPs = Whitelist::where('user_id',$user->id)->first();

        
            // Auto-generate unique hash if doesn't exist

            
            if (!$user->status_page_hash) {
                $user->status_page_hash = Str::random(32);
                $user->save(); 
            }
        
            return view('user.status-settings', [
                'user' => $user,
                'publicUrl' => route('public.status', $user->status_page_hash),
                'iframeCode' => '<iframe src="'.route('public.status', $user->status_page_hash).'" width="100%" height="600" style="border:none;"></iframe>',
                'whitelist' => $whitelistedIPs
            ]);
        }

    /**
     * Update status page settings
     */
    public function updateStatusPageSettings(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if($user->hasRole('subuser')){
            $user = $user->parentUser;
        }

        $whitelist = Whitelist::firstOrCreate(
            ['user_id' => $user->id],
            ['whitelist' => []] // default if creating
        );

        $whitelist->whitelist = json_decode($request->whitelist, true);
        $whitelist->save();

        $user->update([
            'enable_public_status' => $request->has('enable_public_status')
        ]);

        return back()->with('success', 'Status page settings updated successfully');
    }

    public function UpdateBillingInfo(Request $request)
    {
        $request->validate([
            // Billing info validation
            'address' => [ 'string', 'max:255'],
            'city' => [ 'string', 'max:100'],
            'state' => [ 'string', 'max:100'],
            'pincode' => [ 'string', 'max:20'],
            'country' => [ 'string', 'max:100'],
        ]);

        $user = auth()->user();

        $user->update([
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => $request->country,
        ]);

        return redirect()->back()->with('success', 'Billing info updated successfully!');
    }

    public function RestoreUser($id){
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
        }

        return redirect()->route('display.users')
                    ->with('success', 'User restored successfully!');
    }

    public function DeleteSubUser($id){
        $user = User::findOrFail($id);
        $user->forceDelete();
        return redirect()->route('display.sub.users')
                    ->with('success', 'User deleted successfully!');
    }

    public function CompletelyDeleteUser($id){
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('display.users')
                    ->with('success', 'User deleted successfully!');
    }
}
