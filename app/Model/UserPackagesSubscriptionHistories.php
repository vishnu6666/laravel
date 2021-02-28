<?php

namespace App\model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class UserPackagesSubscriptionHistories extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'user_packages_subscription_histories';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'userId',
        'subscriptionHistoriesId',
        'sportPackageId',
        'isTriel'
    ];

  // public function packages()
  // {
  //     return $this->hasMany('App\Model\Package', 'sportPackageId', 'id');
  // }
}
