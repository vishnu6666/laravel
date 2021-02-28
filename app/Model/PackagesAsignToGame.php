<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PackagesAsignToGame extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'packages_asign_to_game';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
      'packageId',
      'gameId'
    ];
}
