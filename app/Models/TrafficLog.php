<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip','email','status','reason','isp','country','browser','platform' ,'user_agent','url','method' ,
    ];
}
