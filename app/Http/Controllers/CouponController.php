<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponCode;
use Illuminate\Support\Facades\DB;

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

        session()->forget('applied_coupon');

        return response()->json(['success' => true,  'message' => 'Coupon removed successfully!']);
    }

    return response()->json(['success' => false, 'message' => 'No coupon applied.'], 400);
}


}
