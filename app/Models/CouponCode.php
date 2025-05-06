<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user');
    }

}
