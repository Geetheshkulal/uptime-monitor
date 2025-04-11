<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    // Link to the payment (if needed)
    protected $fillable=[
        'name',
        'amount'
    ];
    public function payment()
    {
        
        return $this->hasOne(Payment::class, 'subscription_id');
    }
}