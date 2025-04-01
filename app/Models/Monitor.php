<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','name','status', 'url', 'type', 'port', 'retries', 'interval','paused',
        'email', 'telegram_id', 'telegram_bot_token'
    ];
    protected $table = 'monitors';
}
