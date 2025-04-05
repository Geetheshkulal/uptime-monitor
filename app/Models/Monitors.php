<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class Monitors extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //     ->logOnly(['name', 'user_id','url','type','email']);
    //     // Chain fluent methods for configuration options
    // }

    public function pingResponses()
    {
        return $this->hasMany(PingResponse::class, 'monitor_id');
    }

    public function latestPortResponse()
    {
    
        return $this->hasOne(PortResponse::class,'monitor_id')->latest();
    }

    
}
