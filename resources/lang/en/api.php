<?php


    /*
    |--------------------------------------------------------------------------
    | API Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */


return [

    'auth' => [
        'invalid_key' => 'Authentication key is invalid',
        'invalid_user' => 'Please login for this feature',
        'inactive_user' => 'Your account is inactive, please contact to admin',
        'user_not_found' => 'User not found',
        'unauthorized' => 'Unauthorized user',
        'invalid_current_password' => 'Current Password does not match',
        'current_same_new' => 'Current Password and new password must be different',
        'change_password' => 'Password has been changed successfully',
        'reset_password_success' => 'Reset Password successfully',
        'reset_password_error' => 'There is some problem in reset password',
        'resend_otp_success' => 'Resend OTP send successfully',
        'resend_otp_error' => 'There is some problem to generate OTP',
        'invalid_league_token' => 'Invalid league token',
        'gamer_tag_updated' => 'Gamertags updated successfully',
        
    ],

    'register' => [
        'email_already_exist' => 'Email address is already registered',
        'mobileNo_already_exist' => 'Mobile no is already registered',
        'error' => 'There is some problem in signup',
        'invalid_referral_code_by_code' => 'Referral code is invalid',
        'plan_not_found' => 'Plan not found',
        'success' => 'You are sign up successfully.',
    ],

    'login' => [
        'success' => 'You are logged in successfully',
        'already' => 'We send you mail to offline from other devices',
        'inactive' => 'Your account is inactive',
        'invalid' => 'Email or password is invalid',
        'offline' => 'We send you mail to offline from other devices',
        'notfound' => 'Account not found'
    ],

    'logout' => [
        'success' => 'You are logged out successfully.'
    ],

    'social_login' => [
        'error' => 'Please allow permission to read your email address',
    ],

    'forgot_password' => [

        'email_success' => 'Reset Password OTP has been sent to your registered email address',
        'mobileNumber_success' => 'Reset Password OTP has been sent to your registered phone number',
        'email_error' => 'Email address is invalid. Please enter registered email address',
        'mobileNumber_error' => 'Phone Number is invalid. Please enter registered Phone Number'
    ],

    'otp_verify' => [
        'success' => 'OTP has been verified successfully',
        'invalid' => 'OTP is invalid',
        'error' => 'Invalid OTP, Please try again',
        'sent_otp' => 'OTP has been sent to your registered :type'
    ],

    'package' => [
        'detail' => 'Get packages details successfully',
        'error' => 'Package not found',
    ],

    'plan' => [
        'detail' => 'Get plan details successfully',
        'error' => 'Plan not found',
    ],

    'promocode' => [
        'not_found' => 'Promocode not found',
        'found' => 'Promocode get successfully',
        'expired' => 'Your promo code is expired',
        'already_applied' => 'Your Promo code is already applied',
        'invalid_plan' => 'Promo code not available for this plan',
        'minimum_order_error' => 'Minimum order amount must be :amount for promo code',
        'success' => 'Promo code has been applied successfully',
        'error' => 'Promo code is invalid'
    ],

    'referralcode' => [
        'not_found' => 'Referral code not found',
        'found' => 'Referral code get successfully'
    ],

    'discounts' => [
        'not_found' => 'Discount not found',
        'found' => 'Discount get successfully'
    ],

    'reset_password.success' => 'Password has been changed successfully',

    'termAndUse' => [
        'found' => 'Term And Use found successfully',
        'not_found'  => 'Term And Use not found'
    ],

    'privacyANDPolicy' => [
        'found' => 'Privacy and Policy found successfully',
        'not_found'  => 'Privacy and Policy not found'
    ],

    'legalPolicy' => [
        'found' => 'Legal Policy found successfully',
        'not_found'  => 'Legal Policy not found'
    ],

    'cancellationPolicy' => [
        'found' => 'Cancellation Policy found successfully',
        'not_found'  => 'Cancellation Policy not found'
    ],

    'refundPolicy' => [
        'found' => 'Refund Policy found successfully',
        'not_found'  => 'Refund Policy not found'
    ],

    'profile' => [
        'success' => 'User profile details found successfully',
        'error'  => 'User profile details not found',
        'profilePicDeleted' => 'User profile photo delete successfully',
        'profilePicDeleteError' => 'User profile photo not delete',
        'defouldImageNotDelete' => 'Default profile photo can not delete',
        'profilePicNotFound' => 'Profile photo not found.'
    ],

    'gametips' => [
        'homedetail' => 'Get game details successfully',
        'homeerror' => 'Game not found',
        'detail' => 'Get tips details successfully',
        'error' => 'Tips not found',
        'notsubscribederror' => 'You need to subscribe game first',
        'upcomingdetail' => 'Get upcoming game details successfully',
        'upcomingdetailerror' => 'Upcoming game detail not found',
        'keepNotificationupdate' => 'keep notification update successfully',
        'keepNotificationerror' => 'keep notification not update',
    ],

    'comparetips' => [
        'detail' => 'Get compare tips details successfully',
        'error' => 'Compare tips not found',
        'notsubscribederror' => 'You need to subscribe game first',
    ],

    'packagesmessages' => [
        'detail' => 'Get message details successfully',
        'error' => 'Message not found',
    ],

    'gametipsspecific' => [
        'detail' => 'Get spacific tips details successfully',
        'error' => 'spacific tips not found',
    ],

    'contactus' => [
        'sent' => 'ContactUs details sent successfully',
        'error' => 'ContactUs details sending fail ,please try again.',
    ],

    'faq' => [
        'faq_found' => 'FAQ found successfully',
        'faq_not_found' => 'FAQ not found.',
    ],

    'packagesMessage' => [
        'detail' => 'Get message details successfully',
        'error' => 'Message not found',
        'not_found' => 'Subscription not found',
    ],

    'getMysport' => [
        'detail' => 'Get mysport details successfully',
        'error' => 'Mysport not found',
    ],

    'compareSportDetails' => [
        'detail' => 'Get compare sport details successfully',
        'error' => 'Compare sport not found',
    ],

    'searchSport' => [
        'detail' => 'Get sport search details successfully',
        'error' => 'Sport search not found',
    ],

    //This API message for credit card added successfully at braintree.
    'card.add_success' => 'Your credit card added successfully',
    'card.add_failed' => 'Please provide valid card detail.',
    'card.availble' => 'This credit card is already save',
    'card.not_at_payment_gateway' => 'Sorry! your card is not available at payment gateway side',

    //This API message for get some error from .
    'card.add_error' => 'Sorry! something goes wrong from payment gateway side',
    'card.get_success' => 'Get card successfully',
    'address.delete.error' => 'Your address are not delete, please try again',
    'card.delete.success' => 'Your card deleted successfully',
    'card.delete.error' => 'Your card are not delete, please try again',
    'card.setAsDefault.success' => 'Your card are set as default',
    'card.setAsDefault.error' => 'Your card are not set as default, please try again',
    'card.delete.cardError' => 'Sorry, You can not delete the Default Card. Please make the another card as Default Card to delete this card',
    'card.get_default_success' => 'Get default card successfully',

    // Braintree messages for card not found for particular user
    'user.card_not_found' => 'Sorry! you do not have any card till now.',
    
    'user.braintree_customer_creation_problem' => 'Sorry! something goes wrong from payment gateway side.',

    // Share message
    'user.share_msg' => 'Enter the invite code :promoCode to earn one free search.
    Download the Best Ride app to compare rates of various taxi providers - http://bestridetestapp.app.link/Y0UCe3xRO7',

    //This message for user card list
    'user.card_list' => 'User all card list.',
    'card.user_not_found_at_payment_gateway' => 'Sorry! User not registered at payment gateway.',
    'payment.payment_error' => 'Sorry! something goes wrong your payment failed.',
    'payment.payment_done' => 'Your payment done successfully.',
    'payment.paymet_history' => 'User payment history.',
    'payment.not_found_payment' => 'Sorry! We are not able to find any payment done by your side.',

    'notifications' => [
        'not_found' => 'Notification not found',
        'found' => 'Notification found successfully',
        'change_done' => 'Notification action changed successfully',
        'change_league_accept' => 'League invitation accepted successfully',
        'change_league_decline' => 'League invitation decline successfully',
        'like_notification_success' => 'Notification :isLike successfully',
        'like_notification_error' => 'There is some problem to notification :isLike',
        'like_not_remove' => 'Liked notification can not be removed',
        'remove_success' => 'Notification removed successfully',
        'remove_error' => 'There is some problem to remove notification'
    ],

    'history' => [
        'history_found' => 'Subscription history found successfully',
        'history_not_found' => 'Subscription history not found.',
    ],













    


    




    'schedule' => [
        'detail' => 'Schedule Details',
        'error' => 'Schedule not found',
    ],

    'location' => [
        'detail' => 'Locations Details',
        'error' => 'Locations not found',
    ],

    'speaker' => [
        'detail' => 'Speaker Details',
        'error' => 'Speaker not found',
    ],

    'impacts' => [
        'detail' => 'Impacts Details',
        'error' => 'Impacts not found',
    ],

    'dressCode' => [
        'detail' => 'Dress Code Details',
        'error' => 'Dress Code not found',
    ],

    'attendee' => [
        'detail' => 'Attendee Details',
        'error' => 'Attendee not found',
    ],

    'coache' => [
        'detail' => 'Coach Details',
        'error' => 'Coach not found',
    ],

    'flight' => [
        'detail' => 'Flight Details',
        'error' => 'Something goes wrong. Flight not saved',
        'head' => 'Flight Head get successfully.',
        'headError' => 'Flight Head not get successfully.',
    ],

    'city' => [
        'list' => 'City list get successfully.',
        'error' => 'City not found',
    ],

    'flight' => [
        'list' => 'City wise flight list get successfully.',
        'error' => 'This city flight not found',
    ],

    'games' => [
        'not_found' => 'Games not found',
        'game_tags_not_found' => 'Game tags not found',
        'league_console_not_found' => 'League console not found'
    ],

    'otpMsg' => 'Your One Time Password for Verification with Vttips is :otp',

    'edit_profile' => [
        'success' => 'Profile edited successfully',
        'error' => 'There is some problem to edit profile',

    ],

    'inquiry' => [
        'send_success' => 'Your inquiry submitted to admin successfully',
        'send_error' => 'There is some problem to send inquiry' 
    ],

    'leagues' => [
        'not_found' => 'Leagues not found',
        'pass_not_found' => 'League pass not found',
        'not_join_league' => 'Leage closed, you can"t be join league',
        'pass_limit_over' => 'League pass limit over, please purchase befor join league',
        'already_joined' => 'You have already join this league',
        'join_league_success' => 'You have join league successfully',
        'join_league_error' => 'There is some problem to join league',
        'not_valid_player' => 'You can"t join league, you are not team member',
        'inactive_player' => 'Your account not Accepted',
        'not_team_member' => 'You are not team member',
        'register_success' => 'You have registered league successfully',
        'invalid_token' => 'Invalid token'
    ],

    'team' => [
        'not_found' => 'Teams not found',
        'team_already_created' => 'Same team is already exist',
        'detail_not_found' => 'Team detail not found',
        'member_not_found' => 'Team member not found',
        'save_team' => 'Team save successfully',
        'save_error' => 'There is some problem for save team',
        'invite_success' => 'Team member invites send successfully',
        'status_changed_success' => 'Team member status changed successfully',
        'not_valid_player' => 'You can"t change status, you are not team member',
        'team_request_success' => 'Team join request send successfully',
        'team_request_already' => 'You have already sent team request',
        'team_request_not_found' => 'Cancel team request is not found',
        'cancel_request_success' => 'Your cancel team request is successfully',
        'not_team_member' => 'You are not team member',
        'change_role_no_rights' => 'You have no rights to create admin and captain',
        'change_role_success' => 'Change role successfully',
        'change_role_error' => 'There is some problem for change role',
        'remove_member_success' => 'Team member has been remove successfully',
        'remove_member_error' => 'There is some problem for remove team member',
        'close_team_success' => 'Your team has been closed successfully',
        'game_result_not_found' => 'Game result not found',
        'leave_team_success' => 'You have leave team successfully',
        'leave_team_error' => 'There is some problem for leave team',
        'roster_not_found' => 'Team roster not found',
        'team_games_not_found' => 'Team games not found'
    ],

    'subscriptions' => [
        'not_found' => 'Subscription plan not found',
        'success' => 'You have subscribed plan successfully',
        'error' => 'There is some problem to subscribe plan',
        'cancel_subscription_plan_update' => 'Cancel Plan successfully',
        'cancel_subscription_plan_error' => 'There is some problem for cancel plan',
    ],

    'braintree' => [
        'token_error' => 'There is some problem for generating token, please try again',
        'transaction_success' => 'Your payment paid successfully',
        'transaction_failed' => 'There is some problem in payment, please try again',
        'transaction_failed_for_wrong_amount' => 'There is some problem in payment, amount is not match',
        'dicountcodeNotvalid' => 'There is some problem in payment, discount code is not valid',
        'referralcodeNotvalid' => 'There is some problem in payment, referral code is not valid',
        'transaction_fail' => 'There is some problem with payment, please check the card details'
    ],
   
    'aplydiscount' => [
        'dicountcodeNotvalid' => 'Discount code is not valid',
        'success' => 'Discount code has been applied successfully',
        'referralcodeNotvalid' => 'Referral code is not valid'
    ],

    'common' => [
        'upload_media_success' => 'Media uploaded successfully',
        'upload_media_error' => 'There is some problem in media uploaded, please try again',
        'upload_video_success' => 'Video uploaded successfully'
    ],

    'friend_request' => [
        'request_accept' => 'Friend request accept successfully',
        'request_decline' => 'Friend request decline successfully',
        'invalid' => 'Invalid request',
        'request_send' => 'Friend request send successfully',
        'request_block' => 'You blocked successfully',
        'not_found' => 'Friend not found',
        'friend_request_not_found' => 'Cancel Friend request is invalid',
        'friend_request_cancel_success' => 'Friend request cancel successfully'
    ],

    'league_stats' => [
        'schedule_not_found' => 'Schedule not found.',
        'highlights_not_found' => 'No videos have been added to this league yet.',
        'video_remove_success' => 'Video remove successfully',
        'change_role_success' => 'Change role successfully',
        'not_valid_match' => 'Not valid match for this action',
        'player_swap_success' => 'Player swap successfully',
        'something_wrong' => 'Something went wrong',
        'add_substitute' => 'Add substitute successfully',
        'mvp_not_available' => 'mvp vote not available',
        'mvp_vote_success' => 'mvp vote successfully',
        'mvp_already_vote' => 'Mvp already voted',
        'captain_move_not_valid' => 'Captain move not valid',
    ],

    'match_headlines' => [
        'not_found' => 'Match headlines not found'
    ],

    'matches' => [
        'not_found' => 'Match not found'
    ],

    'store' => [
        'filters' => [
            'not_found' => 'Filters not found'
        ],
        'products' => [
            'not_found' => 'Product not found',
            'combination_not_found' => 'Product combination not found'
        ],
        'product_detail' => [
            'not_available_variant' => 'Sorry ! This combination not available',
            'qty_not_available' => 'Expected quantity not available',
            'not_available' => 'Out of stock'
        ],
        'carts' => [
            'qty_not_available' => 'Product qty is not available',
            'add_success' => 'Product has been added in cart successfully',
            'add_error' => 'There is some problem in add to cart',
            'cart_empty' => 'Your cart is empty !!',
            'product_not_found' => 'Product not found in your cart',
            'product_remove_success' => 'Product remove from your cart successfully',
            'product_remove_error' => 'There is some problem to remove product from your cart',
            
        ],
    ],
     
    'videos' => [
        'not_found' => 'Video not found',
        'updated' => 'Video updated successfully',
        'delete_success' => 'Video removed successfully',
        'delete_error' => 'There is some problem to remove video',
    ],

    'change_league_invitation' => [
        'positive' => [
            'success' => 'Your league invitation has been accept successfully',
        ],
        'negative' => [
            'success' => 'Your league invitation has been decline successfully',
        ],
    ],

    'change_team_invitation' => [
        'positive' => [
            'success' => 'Your team invitation has been accept successfully',
        ],
        'negative' => [
            'success' => 'Your team invitation has been decline successfully',
        ],
    ],

    'change_friend_request' => [
        'positive' => [
            'success' => 'Your friend request has been accept successfully',
        ],
        'negative' => [
            'success' => 'Your friend request has been decline successfully',
        ],
    ],

    'rounds' => [
        'not_found' => 'Round not found'
    ],

    'uploads' => [
        'success' => 'Round image uploaded successfully',
        'errror' => 'There is some problem to upload round image'
    ],

    'country_not_found' => 'Country not found',
    'state_not_found' => 'State not found',
    'city_not_found' => 'City not found',
    'logo_collection_not_found' => 'Logo not found',
    'logo_collection_image_not_found' => 'Logo image not exists',
    'subscription_plan_not_found' => 'Subscription plan not found'
];
