<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id',
        'status',
        'token',
    ];

    public function monitor()
    {
        return $this->belongsTo(Monitors::class, 'monitor_id');
    }
}
