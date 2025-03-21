<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortResponse extends Model
{
    use HasFactory;

    protected $table = 'port_response';
    protected $fillable = ['monitor_id', 'response_time', 'status'];

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
