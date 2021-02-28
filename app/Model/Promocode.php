<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Promocode extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'promocodes';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
      'title',
      'description',
      'promoCode',
      'discountAmount',
      'minTotalAmount',
      'maxDiscountAmount',
      'startDate',
      'endDate',
      'planName',
      'planType',
      'isActive'
    ];
}
