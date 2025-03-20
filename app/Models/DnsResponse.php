<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnsResponse extends Model
{
    use HasFactory;
    protected $guarded = [ ];
    protected $table = "dns_response";
}
