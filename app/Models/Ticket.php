<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'user_id', 'title', 'message', 'status',
        'priority', 'assigned_user_id','is_read','attachments','created_by'
    ];
    protected $casts = [
        'attachments' => 'array',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function assignedUser(){
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
    
    public function comments(){
        return $this->hasMany(Comment::class);
    }  
    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by');
    }
}
