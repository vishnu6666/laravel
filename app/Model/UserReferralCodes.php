<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserReferralCodes extends Model
{
    public $timestamps = false;
    protected $table = 'user_referral_codes';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

  protected $fillable = [
		'referralFrom',
		'referralTo',
		'referralCode',
    'isApplied',
    'isSubscribed'
  ];
}
