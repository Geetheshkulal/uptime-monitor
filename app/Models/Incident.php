<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents'; 

    protected $fillable = [
        'status', // "ongoing" or "resolved"
        'root_cause',
        'start_timestamp',
        'end_timestamp',
        'monitor_id',
        'updated_at',
    ];

    protected $casts = [
        'start_timestamp' => 'datetime',
        'end_timestamp' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = false;

    public function monitor()
    {
        return $this->belongsTo(Monitor::class, 'monitor_id');
    }

    public function isOngoing()
    {
        return $this->status === 'ongoing';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }
}
