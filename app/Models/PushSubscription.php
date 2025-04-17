<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;

    // Define the table name if it does not follow the Laravel naming convention
    protected $table = 'push_subscriptions';

    // Allow mass assignment for these columns
    protected $fillable = [
        'user_id',
        'endpoint',
        'auth',
        'p256dh',
    ];

    // If you don't want timestamps (created_at, updated_at)

}
