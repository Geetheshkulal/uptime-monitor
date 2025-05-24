<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CouponCode;

class CouponUser extends Model
{
    protected $table = 'coupon_user'; // Laravel assumes snake_case
    public $incrementing = false; // Composite primary key
    protected $primaryKey = null; // No single primary key
    public $timestamps = true;

    protected $fillable = [
        'coupon_code_id',
        'user_id',
    ];

    public function coupon()
    {
        return $this->belongsTo(CouponCode::class, 'coupon_code_id');
    }
}