<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CustomModel extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    public static $snakeAttributes = false;
}
