<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Referralcode extends Model
{
    public $timestamps = false;
    protected $table = 'referral_codes';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
      'title',
      'description',
      'referCode',
      'numberOfparson',
      'percentage',
      'isActive'
    ];
}
