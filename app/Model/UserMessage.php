<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMessage extends Authenticatable
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'user_messages';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

  protected $fillable = [
        'userId',
        'gameId',
        'sportPackageId',
        'title',
        'content',
        'createdAt',
        'updatedAt',
		    'isActive'
  ];

}
