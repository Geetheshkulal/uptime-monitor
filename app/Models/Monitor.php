<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Monitor extends Model
{
    use HasFactory;

    protected $fillable = ['name','user_id', 'url', 'type', 'port', 'retries', 'interval','email'];

    public function responses()
    {
        return $this->hasMany(PortResponse::class);
    }

    public function latestPortResponse()
    {
    
        return $this->hasOne(PortResponse::class)->latest();
    }

    public function latestResponseBar()
    {
        return $this->hasMany(PortResponse::class, 'monitor_id')->orderBy('created_at', 'desc')->take(10);
    }


}
