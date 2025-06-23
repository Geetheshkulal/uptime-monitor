<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Subscriptions;

class CouponController extends Controller
{
    
public function apply(Request $request)
    {
        $code = $request->input('code');
        $user = auth()->user();

        $coupon = CouponCode::where('code', $code)
            ->where('is_active', true)
            ->where(function ($q) {
                $now = now();
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
            })
            ->where(function ($q) {
                $now = now();
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
            })
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon.']);
        }

        if ($coupon->max_uses && $coupon->uses >= $coupon->max_uses) {
            return response()->json(['success' => false, 'message' => 'Coupon usage limit reached.']);
        }

        // Check if this user has already used the coupon
        if ($coupon->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'You already used this coupon.']);
        }

        
        // Store in pivot table
        $coupon->users()->attach($user->id);

        // Increment uses
        $coupon->increment('uses');

        session([
            'applied_coupon' => [
                'code' => $coupon->code,
                'discount' => $coupon->value,
                'subscription' => $coupon->subscription_id,
                'discount_type' => $coupon->discount_type
            ]
        ]);


        activity()
        ->performedOn($coupon)
        ->causedBy($user)
        ->inLog("Coupon Management") 
        ->event('edited')
        ->withProperties([
            'user_name' => $user->name,
            'coupon_code' => $coupon->code,
        ])
        ->log("Applied Coupon`");
        
        return response()->json([
            'success' => true, 
            'discount' => $coupon->value,
            'subscription_id' => $coupon->subscription_id,
            'code' => $coupon->code,
            'message' => 'Coupon applied successfully!',
            'discount_type' => $coupon->discount_type
        ]);
    }

     public function UserSearch(Request $request){
        $search = $request->get('q');
        $query = User::select('id', 'name', 'email', 'phone')->role('user');

        if (!auth()->user()->hasRole('superadmin')) {
            $superadminIds = User::role('superadmin')->pluck('id');
            $query->whereNotIn('id', $superadminIds);
        }

        if (auth()->user()->hasRole('user')) {
            $subUserIds = User::role('subuser')
                ->where('parent_user_id', auth()->id())
                ->pluck('id');
            $query->whereIn('id', $subUserIds);
        }

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });

        $query->orderByRaw("CASE 
            WHEN name LIKE ? THEN 1
            WHEN name LIKE ? THEN 2
            ELSE 3
            END", ["{$search}%", "%{$search}%"]);

        $users = $query->limit(10)->get();

        return response()->json($users->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => "{$user->name} | {$user->email} | {$user->phone}",
            ];
        }));
    }

    public function remove(Request $request)
    {
        if (session()->has('applied_coupon')) {
            $code = session('applied_coupon')['code'];
            $user = auth()->user();

            $coupon = CouponCode::where('code', $code)->first();

            if($coupon){
                // Remove from coupon_user table
                DB::table('coupon_user')
                    ->where('user_id', $user->id)
                    ->where('coupon_code_id', $coupon->id)
                    ->delete();
                
                // Decrement uses
                $coupon->decrement('uses');
            }

            session()->forget('applied_coupon');

            activity()
            ->performedOn($coupon)
            ->causedBy($user)
            ->inLog("Coupon Management") 
            ->event('removed coupon')
            ->withProperties([
                'user_name' => $user->name,
                'coupon_code' => $coupon->code,
            ])
            ->log("User {$user->name} Removed Coupon`");

            return response()->json(['success' => true, 'message' => 'Coupon removed successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'No coupon applied.'], 400);
    }

    public function DisplayCoupons()
    {
        $coupons = CouponCode::all();
        $users = User::Role('user')->get();

        $subscriptions = Subscriptions::all();

        return view('pages.coupons.DisplayCoupons', compact('coupons','users','subscriptions'));
    }

    public function CouponStore(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupon_codes,code',
            'discount_type' => 'required|in:flat,percentage',
            'value' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percentage' && $value > 100) {
                        $fail('The percentage discount cannot be more than 100%.');
                    }

                    if ($request->discount_type === 'flat') {
                        // Ensure subscription exists
                        $subscription = \App\Models\Subscriptions::find($request->subscription_id);
                        if ($subscription && $value > $subscription->amount) {
                            $fail("The flat discount cannot be more than the subscription amount ({$subscription->amount}).");
                        }
                    }
                },
            ],
            'max_uses' => 'nullable|integer',
            'valid_from' => 'nullable|date',
            'valid_until' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('valid_from') && $value < $request->valid_from) {
                        $fail('The valid until date must be on or after the valid from date.');
                    }
                },
            ],
            'is_active' => 'boolean',
            'subscription_id'=>'required',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $data = $request->except('user_ids');

        if($request->filled('user_ids')){

            $data['user_ids'] = json_encode($request->user_ids);
        
        }
            
        // CouponCode::create($request->all());
        CouponCode::create($data);

        activity()
            ->performedOn(new CouponCode())
            ->causedBy(auth()->user())
            ->inLog('Coupon Management') 
            ->event('created')
            ->withProperties([
                'user_name' => auth()->user()->name,
                'coupon_code' => $request->code,
            ])
            ->log("Created Coupon: {$request->code}");

        return back()->with('success', 'Coupon created successfully.');
    }

    public function CouponUpdate(Request $request, $id)
    {
        $coupon = CouponCode::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:coupon_codes,code,' . $coupon->id,
            'value' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percentage' && $value > 100) {
                        $fail('The percentage discount cannot be more than 100%.');
                    }

                    if ($request->discount_type === 'flat') {
                        // Ensure subscription exists
                        $subscription = \App\Models\Subscriptions::find($request->subscription_id);
                        if ($subscription && $value > $subscription->amount) {
                            $fail("The flat discount cannot be more than the subscription amount ({$subscription->amount}).");
                        }
                    }
                },
            ],
            'max_uses' => 'nullable|integer',
            'valid_from' => 'nullable|date',
            'valid_until' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('valid_from') && $value < $request->valid_from) {
                        $fail('The valid until date must be on or after the valid from date.');
                    }
                },
            ],
            'is_active' => 'boolean'
        ]);

        $coupon->update($request->all());

        activity()
            ->performedOn($coupon)
            ->causedBy(auth()->user())
            ->inLog('Coupon Management') 
            ->event('updated')
            ->withProperties([
                'user_name' => auth()->user()->name,
                'coupon_code' => $request->code,
            ])
            ->log("Updated Coupon: {$request->code}");

        return redirect()->back()->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = CouponCode::findOrFail($id);
        $coupon->delete();

        activity()
            ->performedOn($coupon)
            ->causedBy(auth()->user())
            ->inLog('Coupon Management') 
            ->event('deleted')
            ->withProperties([
                'user_name' => auth()->user()->name,
                'coupon_code' => $coupon->code,
            ])
            ->log("Deleted Coupon: {$coupon->code}");

        return back()->with('success', 'Coupon deleted successfully.');
    }

    public function showClaimedUsers($coupon_id)
    {
        $coupon = CouponCode::findOrFail($coupon_id);
        $claimedUsers = $coupon->claimedUsers;

        return view('pages.coupons.claimed_users', compact('claimedUsers', 'coupon'));
    }
}
        
