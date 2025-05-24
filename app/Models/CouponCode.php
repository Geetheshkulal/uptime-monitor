<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscriptions;

class CouponCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','user_ids','discount_type','value','max_uses', 'valid_from', 'valid_until', 'is_active', 
    ];
    protected $casts = [
        'user_ids' => 'array',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user');
    }

    public function claimedUsers()
    {
        return $this->belongsToMany(User::class, 'coupon_user', 'coupon_code_id', 'user_id')->withTimestamps();;
    }

    public function subscription()
    {
        return $this->belongsTo(Subscriptions::class);
    }


}
