<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_code','coupon_value','payment_amount','status','user_id', 'payment_method', 'amount', 'payment_status', 'transaction_id', 'payment_type',
        'start_date', 'end_date','subscription_id','address','city','state','pincode','country','discount_type'
    ];
    protected $table = 'payment';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['status', 'user_id','payment_method','amount','transaction_id']);
        // Chain fluent methods for configuration options
    }

    public function subscription()
    {
        return $this->belongsTo(Subscriptions::class,'subscription_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }


}
