<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\CouponCode;

class CouponHelper
{
    public static function getAvailableCouponsForUser()
    {
        $user = Auth::user();
        $now = now();

        return CouponCode::whereNull('user_ids')
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
            })
            ->whereDoesntHave('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->get();
    }
}
