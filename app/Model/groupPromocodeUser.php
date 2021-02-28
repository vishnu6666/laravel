<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class groupPromocodeUser extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'groups_promocodes_users';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $fillable = [
      'groupId',
      'userId',
      'isSend'
    ];
}
