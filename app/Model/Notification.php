<?php

namespace App\Model;

class Notification extends CustomModel
{
    
    protected $fillable = [
        'userId',
        'notificationTemplateId',
        'gameTripId',
        'messageId',
        'notificationType',
        'userGameHistoryId',
        'module',
        'moduleId',
        'title',
        'media',
        'description',
        'action',
        'data',
        'createdAt'
    ];

}
