<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Authenticatable
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'games';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $fillable = [
      'gameName',
      'gameFullName',
      'gameImage',
      'isActive',
    ];

    public function gameTips()
    {
      return $this->hasMany('App\Model\GameTip', 'gameId', 'id');
    }

    public function gameData()
    {
        return $this->hasMany('App\Model\GameTip', 'gameId', 'id');
    }

}
