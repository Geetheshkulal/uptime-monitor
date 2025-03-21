<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HttpResponse extends Model
{
    use HasFactory;

    protected $table = 'http_response';
    protected $guarded =  [];

    public function monitor()
    {
        return $this->belongsTo(Monitors::class);
    }
}
