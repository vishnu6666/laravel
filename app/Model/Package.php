<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Authenticatable
{
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'packages';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

  protected $fillable = [
		'packageName',
		'isActive'
  ];

  public function gameMessage()
  {
      return $this->hasMany('App\Model\UserMessage', 'gameId', 'id');
  }

  public function games()
  {
      return $this->hasMany('App\Model\PackagesAsignToGame', 'packageId');
  }

  public function packageData()
  {
      return $this->hasMany('App\Model\UserPackagesSubscriptionHistories', 'sportPackageId', 'id');
  }
}
