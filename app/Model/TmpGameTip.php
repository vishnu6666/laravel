<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TmpGameTip extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'tmp_games_tips';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $fillable = [
      'date',
      'spreadsheetsId',
      'userId',
      'gameId',
      'tips',
      'odds',
      'units',
      'IsWin',
      'profitLosForTips',
      'profitLosForDay',
      'dailyPot',
      'profitLossCumulative',
      'pot',
      'profitOneUnit',
      'profitTwoUnit',
      'profitThreeUnit',
      'isResultUpdate'
    ];
}
