<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class PaymentOrder extends Authenticatable
{
    use HasApiTokens,Notifiable,HasRoles;

    protected $appends = [
        
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id','order_id','amount','status','gateway','ip'
    ];

    public function playername()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    
}
