<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HttpResponse extends Model
{
    use HasFactory;

    protected $table = 'http_response';
    protected $fillable = [
        'monitor_id', 'status', 'status_code', 'response_time'
    ];

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
