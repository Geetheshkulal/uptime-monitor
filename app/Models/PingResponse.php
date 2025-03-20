<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PingResponse extends Model
{
    use HasFactory;

    // Explicitly define the table name
    protected $table = 'ping_response'; // Correct table name

    // Allow mass assignment for these columns
    protected $fillable = [
        'monitor_id',
        'status',
        'response_time',
    ];

    // Define the inverse relationship with Monitor (Belongs-to)
    public function monitor()
    {
        return $this->belongsTo(Monitors::class, 'monitor_id');
    }
}