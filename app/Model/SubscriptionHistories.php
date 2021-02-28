<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistories extends Model
{
    public $timestamps = false;
    protected $table = 'subscription_histories';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

  protected $fillable = [
		'userId',
		'planName',
		'planType',
    'IsTriel',
    'subscriptionValidity',
    'promocodeId',
    'subscriptionExpiryDate',
    'paymentType',
    'isRefundRequest',
    'paymentStatus',
    'transactionId',
    'refundTransactionId',
    'paymentResponse',
    'refundPaymentResponse',
    'amount'
  ];
}
