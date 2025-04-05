<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ssl extends Model
{
    protected $table = 's_s_l';

    protected $fillable = [
        'url',
        'valid_from',
        'valid_to',
        'status',
        'issuer',
        'user_id',
        
    ];
} 