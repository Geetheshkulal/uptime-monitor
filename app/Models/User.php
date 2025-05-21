<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Notifications\CustomVerifyEmail;

// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;
class User extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable;


    use HasRoles;
    use SoftDeletes;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_ip',
        'phone', 
        'premium_end_date',
        'status',
        'role',
        'email_verified_at',
        'parent_user_id',
        'status_page_hash',
        'enable_public_status',       
        'address',        
        'city', 
        'state', 
        'pincode', 
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    
    public function monitors()
    {
        return $this->hasMany(Monitors::class);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id')->withTrashed();
    }

    // Sub-users created by this parent user
    public function subUsers()
    {
        return $this->hasMany(User::class, 'parent_user_id');
    }
    public function coupons()

    {
        return $this->belongsToMany(CouponCode::class, 'coupon_user');
    }


  }
