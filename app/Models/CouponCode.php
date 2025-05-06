<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','value','max_uses', 'valid_from', 'valid_until', 'is_active', 
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user');
    }

}
