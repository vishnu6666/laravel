<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Authenticatable
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'subscription_plans';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

  protected $fillable = [
		'planName',
		'planSubTitle',
		'planDuration',
    'planType',
    'planWeeklyPrice',
    'planMonthlyPrice',
    'IsActive',
    'isTrial'
  ];

  public function packageData()
  {
      return $this->hasMany('App\Model\UserPackagesSubscriptionHistories', 'sportPackageId', 'id');
  }
}
