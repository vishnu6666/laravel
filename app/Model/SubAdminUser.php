<?php

namespace App\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Middleware\Authenticate;
use App\Notifications\MyResetPassword;
class SubAdminUser  extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $table = 'users';
     /** 
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
		'password', 'remember_token',
  ];
  
  protected $fillable = [
		'name',
		'email',
		'password',
		'passwordShow',
        'isActive',
        'userType',
  ];

  /**
     * Scope method of check admin
     *
     * @param $query
     * @return mixed
     */
    public function scopeIsAdmin($query){
        return $query->where('userType','Admin');
    }
}
