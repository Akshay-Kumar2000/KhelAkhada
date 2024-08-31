<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class Transaction extends Authenticatable
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
        'user_id','source_id','amount','a_amount','status','ip','remark','closing_balance'
    ];

    public function challenge()
    {
        return $this->hasOne(Challenge::class,'id','source_id');
    }

    public function challengeresult()
    {
        return $this->hasOne(ChallengeResult::class,'ch_id','source_id');
    }

    public function playername()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    
}
