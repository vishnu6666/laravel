<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class GroupPromocode extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'groups_promocodes';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $fillable = [
      'groupName',
      'description',
      'promoCode',
      'discountType',
      'discountAmount',
      'startDate',
      'endDate',
      'planName',
      'planId',
      'isActive',
      'isApplyMultiTime'
    ];
}

