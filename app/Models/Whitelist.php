<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whitelist extends Model
{
    use HasFactory;
    
    protected $table = 'whitelist';
    protected $guarded = [];

    protected $casts = [
    'whitelist' => 'array',
    ];
}
