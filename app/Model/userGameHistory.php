<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class userGameHistory extends Model
{
    public $timestamps = false;
    protected $table = 'user_game_history';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
		'userId',
		'subscriptionHistoriesId',
		'gameId',
    'isTriel'
  ];
}
