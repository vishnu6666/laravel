<?php

namespace App\Model;

class UserCard extends CustomModel
{

    protected $table = 'user_cards';
    protected $fillable = [
        'userId',
        'cardHolderName',
        'last4',
        'cardType',
        'expiryDate',
        'cardToken',
        'isDefault',
        'cardImage',
    ];
}
