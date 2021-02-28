<?php

/*
 |--------------------------------------------------------------------------
 | Message Language Lines
 |--------------------------------------------------------------------------
 |
 | The following language lines contain the Custom error messages used by
 | the . Some of these rules have multiple versions such
 | as the size rules. Feel free to tweak each of these messages here.
 |
 */

return [

    /*
         |--------------------------------------------------------------------------
         | Flash Messages Language Lines
         |--------------------------------------------------------------------------
         */

    /*
         * Common Messages
         */


    //'adminreport' => 'As per your requirement data not found',    

    'profile' => [
        'update' => [
            'success' => 'Profile has been updated successfully.',
            'error' => 'There is some problem to update profile.',
        ],
        'delete' => [
            'success' => 'Profile has been delete successfully.',
            'error' => 'There is some problem to delete profile image',
        ],
    ],

    'changePassword'=> [
        'success' => 'Your Password has been  changed',
        'error'=> 'Entered current password is wrong'
    ], 

    'otpVerify'=> [
        'success' => 'Your OTP has been verify success',
        'error'=> 'Entered current OTP is wrong',
        'verifyAccount' => 'Verify your account',
    ],

    'users' => [
        'create' => [
            'success'  => 'User information has been created successfully',
            'error' => 'There is some problem to create user',
        ],
        'update' => [
            'success' => 'User information has been updated successfully',
            'error' => 'There is some problem to update user',
        ],
        'status' => [
            'success' => 'User has been :status successfully',
            'error' => 'There is some problem to update status',
        ],
        'delete' => [
            'success' => 'User information has been deleted successfully',
            'error' => 'There is some problem to delete user',
        ],
        'import' => [
            'success' =>  'Users list imported successfully',
            'error' => 'There is some problem to import excel',
            'duplicate' => 'Users List in already exists this :email.'
        ],
        'not_found' => 'User not found'
    ],

    'admins' => [
        'create' => [
            'success'  => 'Admin has been created successfully',
            'error' => 'There is some problem to create admin',
        ],
        'update' => [
            'success' => 'Admin has been updated successfully',
            'error' => 'There is some problem to update admin',
        ],
        'status' => [
            'success' => 'Admin has been :status successfully',
            'error' => 'There is some problem to update status',
        ],
        'delete' => [
            'success' => 'Admin has been deleted successfully',
            'error' => 'There is some problem to delete admin',
        ],
        'import' => [
            'success' =>  'Admins list imported successfully',
            'error' => 'There is some problem to import excel',
            'duplicate' => 'Admins List in already exists this :email.'
        ],
        'not_found' => 'Admin not found'
    ],

    'games' => [
        'create' => [
            'success'  => 'Game has been created successfully',
            'error' => 'There is some problem to create game',
        ],
        'update' => [
            'success' => 'Game has been updated successfully',
            'error' => 'There is some problem to update game',
        ],
        'status' => [
            'success' => 'Game status change successfully',
            'error' => 'There is some problem to change status',
        ],
        'delete' => [
            'success' => 'Game has been deleted successfully',
            'error' => 'There is some problem to delete game',
        ],
        'not_found' => 'Game not found'
    ],

    'subscription_plans' => [
        'create' => [
            'success'  => 'Subscription Plan has been created successfully',
            'error' => 'There is some problem to create Subscription Plan',
        ],
        'update' => [
            'success' => 'Subscription Plan has been updated successfully',
            'error' => 'There is some problem to update Subscription Plan',
        ],
        'status' => [
            'success' => 'Subscription Plan status change successfully',
            'error' => 'There is some problem to change status',
        ],
        'delete' => [
            'success' => 'Subscription Plan has been deleted successfully',
            'error' => 'There is some problem to delete Subscription Plan',
        ],
        'not_found' => 'Subscription Plan not found'
    ],

    'packages' => [
        'create' => [
            'success'  => 'Package has been created successfully',
            'error' => 'There is some problem to create Package',
        ],
        'update' => [
            'success' => 'Package has been updated successfully',
            'error' => 'There is some problem to update Package',
        ],
        'status' => [
            'success' => 'Package status change successfully',
            'error' => 'There is some problem to change status',
        ],
        'delete' => [
            'success' => 'Package has been deleted successfully',
            'error' => 'There is some problem to delete Package',
        ],
        'not_found' => 'Package not found'
    ],

    'promocode' => [
        'create' => [
            'success'  => 'Promocode has been created successfully',
            'error' => 'There is some problem to create Promocode',
        ],
        'update' => [
            'success' => 'Promocode has been updated successfully',
            'error' => 'There is some problem to update Promocode',
        ],
        'status' => [
            'success' => 'Promocode status change successfully',
            'error' => 'There is some problem to change status',
        ],
        'delete' => [
            'success' => 'Promocode has been deleted successfully',
            'error' => 'There is some problem to delete Promocode',
        ],
        'not_found' => 'Promocode not found'
    ],

    'groups' => [
        'create' => [
            'success'  => 'Group discount created successfully',
            'error' => 'There is some problem to create group discount',
        ],
        'update' => [
            'success' => 'Group discount  updated  successfully',
            'error' => 'There is some problem to update group discount',
        ],
        'status' => [
            'success' => 'Group discount status change successfully',
            'error' => 'There is some problem to change status',
        ],
        'delete' => [
            'success' => 'Group discount deleted successfully',
            'error' => 'There is some problem to delete group discount',
        ],
        'not_found' => 'Group not found'
    ],

    'referCode' => [
        'create' => [
            'success'  => 'ReferCode has been created successfully',
            'error' => 'There is some problem to create ReferCode',
        ],
        'update' => [
            'success' => 'ReferCode has been updated successfully',
            'error' => 'There is some problem to update ReferCode',
        ],
        'status' => [
            'success' => 'ReferCode status change successfully',
            'error' => 'There is some problem to change status',
        ],
        'delete' => [
            'success' => 'ReferCode has been deleted successfully',
            'error' => 'There is some problem to delete ReferCode',
        ],
        'not_found' => 'ReferCode not found'
    ],

    'faqs' => [
        'create' => [
            'success'  => 'Faq has been created successfully',
            'error' => 'There is some problem to create faq',
        ],
        'update' => [
            'success' => 'Faq has been updated successfully',
            'error' => 'There is some problem to update faq',
        ],
        'status' => [
            'success' => 'Faq status change successfully',
            'error' => 'There is some problem to change status',
        ],
        'delete' => [
            'success' => 'Faq has been deleted successfully',
            'error' => 'There is some problem to delete faq',
        ],
        'not_found' => 'Faq not found'
    ],
    'inquiry' => [
        'update' => [
            'success' => 'Inquiry reply has been sent successfully',
            'error' => 'There is some problem to update inquiry reply',
        ],
        'delete' => [
            'success' => 'Inquiry has been deleted successfully',
            'error' => 'There is some problem to delete inquiry reply',
        ],
        'not_found' => 'Inquiry not found'
    ],

    'manage_transaction' => [
        'delete' => [
            'success' => 'Tansaction has been deleted successfully',
            'error' => 'There is some problem to delete Tansaction',
        ],
        'not_found' => 'Tansaction not found'
    ],

    'manage_tips' => [
        'delete' => [
            'success' => 'Tips has been deleted successfully',
            'error' => 'There is some problem to delete Tips',
        ],
        'updateGoogleSheets' =>[
            'success' => 'Tips has been update successfully by google sheets',
            'error' => 'There is some problem to update tips by google sheets',
        ],

        'create' => [
            'success'  => 'Game Tips has been created successfully',
            'error' => 'There is some problem to create game',
        ],
        'update' => [
            'success' => 'Game Tips has been updated successfully',
            'error' => 'There is some problem to update game',
        ],
        'status' => [
            'success' => 'Game Tips status change successfully',
            'error' => 'There is some problem to change status',
        ],

        'not_found' => 'Tips not found'
    ],

    'message' => [
        'create' => [
            'success'  => 'Message has been created successfully',
            'error' => 'User not found for this package.',
        ],
        'not_found' => 'message not found'
    ],
    
    'attendees' => [
        'create' => [
            'success'  => 'attendee information has been created successfully',
            'error' => 'There is some problem to create attendee',
        ],
        'update' => [
            'success' => 'Attendee information has been updated successfully',
            'error' => 'There is some problem to update attendee',
        ],
        'delete' => [
            'success' => 'Attendee information has been deleted successfully',
            'error' => 'There is some problem to delete attendee',
        ],
        'not_found' => 'Attendee not found'
    ],
    
    'scheduledays' => [
        'create' => [
            'success'  => 'Schedule days information has been created successfully',
            'error' => 'There is some problem to create scheduledays',
        ],
        'update' => [
            'success' => 'Schedule days information has been updated successfully',
            'error' => 'There is some problem to update schedule days',
        ],
        'delete' => [
            'success' => 'Schedule days information has been deleted successfully',
            'error' => 'There is some problem to delete schedule days',
        ],
    ],

    'schedules' => [
        'create' => [
            'success'  => 'Schedules information has been created successfully',
            'error' => 'There is some problem to create schedules',
        ],
        'update' => [
            'success' => 'Schedules information has been updated successfully',
            'error' => 'There is some problem to update schedules',
        ],
        'delete' => [
            'success' => 'Schedules information has been deleted successfully',
            'error' => 'There is some problem to delete schedules',
        ],
        'updateRank' => [
            'success' => 'Schedules information rank has been updated.',
            'error' => 'There is some problem to update schedule rank.',
        ],
    ],

    'notifications' =>[

        'clearallnotification' => [

            'success' => 'All notifications has been cleared successfully',
            'error' => 'You have no notifications'
        ],
        'status' => [
            'success' => 'Status has been :status successfully',
            'error' => 'There is some problem to update status',
        ],
        'delete' => [
            'success' => 'Notification info has been deleted successfully',
            'error' => 'There is some problem to delete notification',
        ],
    ],

    'locations' => [
        'create' => [
            'success'  => 'Location information has been created successfully',
            'error' => 'There is some problem to create location',
        ],
        'update' => [
            'success' => 'Location information has been updated successfully',
            'error' => 'There is some problem to update location',
        ],
        'delete' => [
            'success' => 'Location information has been deleted successfully',
            'error' => 'There is some problem to delete location',
        ],
        'imageNumber' => 'You can upload maximum 5 images.',
    ],

    'speakerdays' => [
        'create' => [
            'success'  => 'Speaker days information has been created successfully',
            'error' => 'There is some problem to create speakerdays',
        ],
        'update' => [
            'success' => 'Speaker days information has been updated successfully',
            'error' => 'There is some problem to update speaker days',
        ],
        'delete' => [
            'success' => 'Speaker days information has been deleted successfully',
            'error' => 'There is some problem to delete speaker days',
        ],
    ],

    'speakers' => [
        'create' => [
            'success'  => 'Sepeaker information has been created successfully',
            'error' => 'There is some problem to create speaker',
        ],
        'update' => [
            'success' => 'Speaker information has been updated successfully',
            'error' => 'There is some problem to update speaker',
        ],
        'delete' => [
            'success' => 'Speaker information has been deleted successfully',
            'error' => 'There is some problem to delete Speaker',
        ],
        'updateRank' => [
            'success' => 'Speaker information rank has been updated.',
            'error' => 'There is some problem to update speaker rank.',
        ],
    ],

    'impact' => [
        'create' => [
            'success'  => 'Impact information has been created successfully',
            'error' => 'There is some problem to create impact',
        ],
        'update' => [
            'success' => 'Impact information has been updated successfully',
            'error' => 'There is some problem to update impact',
        ],
    ],

    'question_and_answers' => [

        'update' => [
            'success' => 'Reply has been sent successfully',
            'error' => 'There is some problem to sent reply',
        ],
    ],

    'page_content' => [
        'create' => [
            'success'  => 'Impact information has been created successfully',
            'error' => 'There is some problem to create impact',
        ],
        'update' => [
            'success' => 'Page content information has been updated successfully',
            'error' => 'There is some problem to update page content',
        ],
    ],

    'cities' => [
        'create' => [
            'success'  => 'City information has been created successfully',
            'error' => 'There is some problem to create city',
        ],
        'update' => [
            'success' => 'City information has been updated successfully',
            'error' => 'There is some problem to update city',
        ],
        'status' => [
            'success' => 'City has been :status successfully',
            'error' => 'There is some problem to update status',
        ],
        'city_flight_update' => [
            'success' => 'City flights has been updated successfully',
            'error' => 'There is some problem to update city flights',
        ]
    ],

    'flights' => [
        'create' => [
            'success'  => 'Flight information has been created successfully',
            'error' => 'There is some problem to create flight',
        ],
        'update' => [
            'success' => 'Flight information has been updated successfully',
            'error' => 'There is some problem to update flight',
        ],
        'status' => [
            'success' => 'Flight has been :status successfully',
            'error' => 'There is some problem to update status',
        ],
        
    ],

    'socials' => [
        'create' => [
            'success'  => 'Social information has been created successfully',
            'error' => 'There is some problem to create social',
        ],
        'update' => [
            'success' => 'Social information has been updated successfully',
            'error' => 'There is some problem to update social',
        ],
        'delete' => [
            'success' => 'Social information has been deleted successfully',
            'error' => 'There is some problem to delete social',
        ],
        'updateRank' => [
            'success' => 'Social information rank has been updated.',
            'error' => 'There is some problem to update social rank.',
        ],
    ],

    'coachedays' => [
        'create' => [
            'success'  => 'Coach days information has been created successfully',
            'error' => 'There is some problem to create coach days',
        ],
        'update' => [
            'success' => 'Coach days information has been updated successfully',
            'error' => 'There is some problem to update coach days',
        ],
        'delete' => [
            'success' => 'Coach days information has been deleted successfully',
            'error' => 'There is some problem to delete coach days',
        ],
    ],

    'dressCode' => [
        'create' => [
            'success'  => 'Dress code information has been created successfully',
            'error' => 'There is some problem to create dress code',
        ],
        'update' => [
            'success' => 'Dress code information has been updated successfully',
            'error' => 'There is some problem to update dress code',
        ],
        'delete' => [
            'success' => 'Dress code information has been deleted successfully',
            'error' => 'There is some problem to delete dress code',
        ],
    ],

    'coaches' => [
        'create' => [
            'success'  => 'Coach information has been created successfully',
            'error' => 'There is some problem to create Coach',
        ],
        'update' => [
            'success' => 'Coach information has been updated successfully',
            'error' => 'There is some problem to update coach',
        ],
        'delete' => [
            'success' => 'Coach information has been deleted successfully',
            'error' => 'There is some problem to delete coach',
        ],
    ],

    'adminreport' => [
        'error' => 'Report not found.',
    ],
    'notification_template'  => [
        'create' => [
            'success' => 'Notification template has been created successfully',
            'error' => 'There is some problem to create notification template'
        ],
        'update' => [
            'success' => 'Notification template has been updated successfully',
            'error' => 'There is some problem to update notification template',
        ],
        'status' => [
            'success' => 'Status has been :status successfully',
            'error' => 'There is some problem to update status',
        ],
        'delete' => [
            'success' => 'Notification template has been deleted successfully',
            'error' => 'There is some problem to delete notification template',
        ],
        'send_notification'=>[
            'success' => 'Notification has been sent to all admin user',
            'error' => 'There is some problem to send notification',

        ],
         'admin_send_notification'=>[
            'success' => 'Notification has been sent to all customers',
            'error' => 'There is some problem to send notification',

        ],
    ],

];