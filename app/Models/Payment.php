<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status','user_id', 'payment_method', 'amount', 'payment_status', 'transaction_id', 'payment_type',
        'start_date', 'end_date'
    ];
    protected $table = 'payment';
}
