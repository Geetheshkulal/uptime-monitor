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
    
    $planAmount=$coupon->subscription()->amount;
 

    // Store in pivot table
    $coupon->users()->attach($user->id);

    // Increment uses
    $coupon->increment('uses');

    session([
        'applied_coupon' => [
            'code' => $coupon->code,
            'discount' => $coupon->value
        ]
    ]);
    

    return response()->json(['success' => true, 'discount' => $coupon->value,'message' => 'Coupon applied successfully!']);
}

public function remove(Request $request)
{
    if (session()->has('applied_coupon')) {
        // Optional: remove from coupon_user table if needed
        $code = session('applied_coupon')['code'];
        $user = auth()->user();

        $coupon = CouponCode::where('code', $code)->first();

        if($coupon){
        // Assuming you have a pivot or tracking table
        DB::table('coupon_user')
                ->where('user_id', $user->id)
                ->where('coupon_code_id', $coupon->id)
                ->delete();
        }
        $coupon->decrement('uses');

        session()->forget('applied_coupon');

        return response()->json(['success' => true,  'message' => 'Coupon removed successfully!']);
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
        'value' => 'required|numeric',
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

    return back()->with('success', 'Coupon created successfully.');
}

public function CouponUpdate(Request $request, $id)
{
    $coupon = CouponCode::findOrFail($id);

    $request->validate([
        'code' => 'required|unique:coupon_codes,code,' . $coupon->id,
        'value' => 'required|numeric',
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

    return redirect()->back()->with('success', 'Coupon updated successfully.');
}

public function destroy($id)
{
    $coupon = CouponCode::findOrFail($id);
    $coupon->delete();

    return back()->with('success', 'Coupon deleted successfully.');
}

public function showClaimedUsers($coupon_id)
{
    $coupon = CouponCode::findOrFail($coupon_id);
    $claimedUsers = $coupon->claimedUsers;

    return view('pages.coupons.claimed_users', compact('claimedUsers', 'coupon'));
}


}
