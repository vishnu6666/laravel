<?php
return [

    'DATE_FORMAT_FRONT' => '%D %b.%y',
    'DATE_FORMAT' => '%d %b %Y',
    'DATE_FORMAT_SCHEDULE' => '%a %d/%m/%y',
    'DATE_FORMAT_SOCIAL' => '%W %d %M %Y',
    'DATE_FORMAT_TRAVELCOACH' => '%W %D %M',
    'TIME_FORMAT' => '%h:%i %p',
    'DATE_TIME_FORMAT' => '%d %b %Y %H:%i %p',
    'FULLDAY_DATE_TIME_FORMAT' => '%W %h %p',
    'DAY_MONTH_DATE_FORMAT' => '%a,%d %b',

    'MIN_QTY_MESSAGE_LIMIT' => 5,

    
    'LOCATIONS' => 'images/locations',
    'AVATARIMAGE' => 'images/AvatarImage',
    'IMPACTS' => 'images/Impacts',
    'SOCIAL' => 'images/socials',
    'AVATARIMAGE' => 'images/AvatarImage',
    'PROFILES' => 'images/profiles',
    'GAMEIMAGES' => 'images/gameimage',
    'SPEAKER' => 'images/speakers',
    'DRESSCODE' => 'images/DressCode',
    
    'CURRENCY' => '$',

    'discount_type' => [
        'Percentage' => 'Percentage',
        'Flat' => 'Flat',
      ],

    'BRAINTREE_CREDENTIALS' => [
        'merchantId' => 'merchantId',
        'publicKey' => 'publicKey',
        'privateKey' => 'privateKey',
    ],


    'push_notification' => [
        'url' => 'https://fcm.googleapis.com/fcm/send',
        'token' => 'Token',
        'project_id' => 'project-name'
    ],

    'OCR_URL' => 'https://vision.googleapis.com/v1/images:annotate?alt=json&key=key',

    'notifications' => [
        'url' => 'https://fcm.googleapis.com/fcm/send',
        'token' => env('PUSH_NOTIFICATION_KEY'),
        'project_id' => env('PUSH_NOTIFICATION_PROJECT_ID'),
    ],
    
    'stripeDetails' => [
        'secret_key' => 'sk_test_key',       // sendbox account
        'publishable_key' => 'pk_test_key',  // sendbox account
    ],
];
