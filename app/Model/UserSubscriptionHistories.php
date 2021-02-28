<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserSubscriptionHistories extends Model
{
    public $timestamps = false;
    protected $table = 'user_subscription_histories';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

}
