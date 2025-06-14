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

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'monitor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'monitor_id');
    }

    public function latestIncident()
    {
        return $this->incidents()->orderByDesc('start_timestamp')->first();
    }
    
}
