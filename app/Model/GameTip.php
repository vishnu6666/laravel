<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameTip extends Authenticatable
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'games_tips';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $fillable = [
      'date',
      'packageId',
      'gameId',
      'track',
      'tips',
      'odds',
      'units',
      'IsWin',
      'profitLosForTips',
      'weeklyProfitLos',
      'weeklyPot',
      'monthlyProfitLos',
      'monthlyPot',
      'allTimeProfitLos',
      'allTimePot',
      'tipsImage',
      'text',
    ];
}
