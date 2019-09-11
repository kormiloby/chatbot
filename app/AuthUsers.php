<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthUsers extends Model
{
    protected $table = 'auth_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_id',
        'cms_user_id',
    ];
}
