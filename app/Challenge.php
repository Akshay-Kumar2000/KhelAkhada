<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class Challenge extends Authenticatable
{
    use HasApiTokens,Notifiable,HasRoles,SoftDeletes;

    protected $appends = [
        
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'c_id','cname','rcode','status','ip','o_id','oname','amount','type'
    ];
   
    public function userresult()
    {
        return $this->hasOne(UserResult::class,'ch_id','id')->where('user_id',auth()->user()->id);
    }

    public function creator()
    {
        return $this->hasOne(User::class,'id','c_id');
    }

    public function opponent()
    {
        return $this->hasOne(User::class,'id','o_id');
    }

    public function result()
    {
        return $this->hasOne(ChallengeResult::class,'ch_id','id')->with('winner');
    }

    public function usersresult()
    {
        return $this->hasMany(UserResult::class,'ch_id','id')->with('user');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class,'source_id','id')->with('playername');
    }
    
}
