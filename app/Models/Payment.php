<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status','user_id', 'payment_method', 'amount', 'payment_status', 'transaction_id', 'payment_type',
        'start_date', 'end_date'
    ];
    protected $table = 'payment';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['status', 'user_id','payment_method','amount','transaction_id']);
        // Chain fluent methods for configuration options
    }

}
