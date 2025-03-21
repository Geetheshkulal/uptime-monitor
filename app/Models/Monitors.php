<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitors extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pingResponses()
    {
        return $this->hasMany(PingResponse::class, 'monitor_id');
    }

    public function latestPortResponse()
    {
    
        return $this->hasOne(PortResponse::class,'monitor_id')->latest();
    }

    
}
