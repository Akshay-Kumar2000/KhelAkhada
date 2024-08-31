<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserData extends Model
{
	
	 use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'user_id', 'vplay_id', 'DOCUMENT_NAME', 'DOCUMENT_NUMBER', 'DOCUMENT_FIRST_NAME', 'DOCUMENT_LAST_NAME', 'DOCUMENT_DOB', 'DOCUMENT_STATE', 'DOCUMENT_FRONT_IMAGE', 'DOCUMENT_BACK_IMAGE', 'verify_status',
    ];

  
}
