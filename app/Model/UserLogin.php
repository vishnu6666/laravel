<?php

namespace App\Model;

class UserLogin extends CustomModel
{

    protected $table = 'user_logins';
    protected $fillable = [
        'userId',
        'isLogin',
        'deviceId',
        'platform'
    ];
}
