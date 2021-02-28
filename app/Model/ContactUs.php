<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    public $timestamps = false;
    protected $table = 'contact_us';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
            'userId',
            'subject',
            'message',
            'reply_message',
            'isReply'
    ];
}
