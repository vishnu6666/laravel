<?php
namespace App\Helpers;
use App\Model\PackagesAsignToGame;
use App\Model\userGameHistory;
use App\Model\SubscriptionPlan;
use App\Model\SubscriptionHistories;
use App\Model\Notification;
use App\Model\UserPackagesSubscriptionHistories;
use App\Model\UserReferralCodes;
use App\Model\User;
use App\Model\Package;
use Illuminate\Support\Carbon;


class ApiHelper
{
    /*
    |--------------------------------------------------------------------------
    |  API Helper
    |--------------------------------------------------------------------------
    |
    | This helper is used for API related controllers.
    | In this helper common methods written that is used at multiple locations.
    |
    */

    /**
     * Gets user by id.
     *
     * @param int $id
     *
     * @return query result
    */
    public static function getUserById($id)
    {
        $profilePath = url(config('constant.PROFILES'));
    
        return User::where('id',$id)->selectRaw('id,name,deviceType,stripeId,password,email,mobileNumber,isActive,isEmailVerified,isMobileVerified,otp,CONCAT("' . $profilePath . '","/",ifnull(profilePic,"default.jpg")) as profilePic,otp,referralCode,socialType');
    }

    /**
     * add isPayment flag in userDetail
     *
     * @param array $userDetail
     *
     * @return userDetail result
    */
    public static function getUserWithIsPayment($userDetail)
    {
        if(!empty($userDetail)){
            if($userDetail['deviceType'] === 'Ios'){
                $userDetail['isPayment'] = 0;
            }else if($userDetail['deviceType'] === 'Android'){
                $userDetail['isPayment'] = 0;
            }
        }
        return $userDetail;
    }

    /**
     * Gets user by email or mobile no.
     *
     * @param string $data
     * @param string $column
     *
     * @return query result
    */
 
    public static function getUserByEmail($column, $data)
    {
        // check Email or Mobile No unique
        $where = $column . ' = "' . $data['email'] . '"';

        $profilePath = url(config('constant.AVATARIMAGE'));

        return User::whereRaw($where)->selectRaw('id,name,deviceType,stripeId,email,mobileNumber,password,isActive,isEmailVerified,otp,isMobileVerified,CONCAT("' . $profilePath . '","/",ifnull(profilePic,"default.jpg")) as profilePic,referralCode,socialType');
    }

    /**
     * Gets user by socialId and email .
     *
     * @param string $data
     * @param string $column
     *
     * @return query result
    */
 
    public static function getUserBysocialId($column, $data)
    {
        // check Email or Mobile No unique
        $where = $column . ' = "' . $data['socialId'] . '"';

        $profilePath = url(config('constant.AVATARIMAGE'));

        return User::whereRaw($where)->selectRaw('id,name,deviceType,stripeId,email,socialId,mobileNumber,password,isActive,isEmailVerified,otp,isMobileVerified,CONCAT("' . $profilePath . '","/",ifnull(profilePic,"default.jpg")) as profilePic,referralCode,socialType');
    }

    /**
     * Gets user by ReferralCode.
     *
     * @param string $referralCodeBy
     *
     * @return query result
    */
 
    public static function getUserByReferralCode($referralCodeBy)
    {
        return User::selectRaw('id,referralCode,userType')
                            ->where('userType', 'User')
                            ->where('referralCode', $referralCodeBy)
                            ->first();
    }
    
    /**
     * Gets sport Package pluck 
     *
     *
     * @return query result
    */
 
    public static function getsportPackageId()
    {
        return Package::selectRaw('id')
                                ->where('isActive', 1)
                                ->pluck('id');
    }
    
    public static function getUserByEmailOrMobile($column, $data)
    {
        // check Email or Mobile No unique
        $where = $column . ' = "' . $data['email'] . '" OR mobileNumber = "'.$data['email'].'"';

        $profilePath = url(config('constant.AVATARIMAGE'));

        return User::whereRaw($where)->selectRaw('id,name,deviceType,stripeId,email,mobileNumber,password,isActive,isEmailVerified,otp,isMobileVerified,CONCAT("' . $profilePath . '","/",ifnull(profilePic,"default.jpg")) as profilePic,referralCode,socialType');
    }

     /**
     * Gets Subscription plan by plan Full Packages 
     *
     * @param number $numberOfPackages
     *
     * @return query result
    */
 
    public static function getSubscriptionPlanByfullPackages()
    {
        // check plan is available 
        return  SubscriptionPlan::where(['isActive'=> 1,'isTrial' => 1])->orderBy('planFullPackages','desc')->first();
        // return SubscriptionPlan::selectRaw('id,planName,planDuration,planFullPackages,planWeeklyPrice')
        //                         ->where('isActive', 1)
        //                         ->where('planFullPackages', $numberOfPackages)
        //                         ->first();
    }

    /**
     * This method to sue for get name of packages
     *
     * @return string
    */
    public static function getPackagesName($packagesId)
    {
        $package =  Package::selectRaw('packageName')->whereIn('id', $packagesId)->pluck('packageName');
        return str_replace(",",", ",$package->implode(','));
    }

    /**
     * This method create subscription history on user registration time
     *
     * @param array  $sportPackageId
     * @param string $referralCodeBy
     * @param int   $referralCodeUsersId
     * @param array $user
     * @param array $planData
     *
     * @return void
    */
    public static function createsubscriptionhistories($sportPackageId,$referralCodeUsersId,$referralCodeBy,$user,$planData)
    {
        $subscriptionhistories = new SubscriptionHistories();
        $subscriptionhistories->userId    = $user->id;
        $subscriptionhistories->planName  = $planData->planName;
        $subscriptionhistories->planId    = $planData->id;
        $subscriptionhistories->planAmount= $planData->planWeeklyPrice; 
        $subscriptionhistories->amount    = 0.00;
        $subscriptionhistories->discountAmount   = 0.00;
        $subscriptionhistories->planType  = 'Weekly';
        $subscriptionhistories->isTrial   = 1;
        $subscriptionhistories->isCancel  = 1;
        $subscriptionhistories->subscriptionExpiryDate  = Carbon::now()->addDays($planData->planDuration);
        $subscriptionhistories->discountType = null;
        $subscriptionhistories->paymentType  = 'free';
        $subscriptionhistories->isRefundRequest  = 0;
        $subscriptionhistories->paymentStatus  = 'success';
        $subscriptionhistories->subscriptionValidity  = $planData->planDuration;
        $subscriptionhistories->packageName =  Self::getPackagesName($sportPackageId);

        $subscriptionhistories->save();
         // start user_packages_subscription_histories 
         $packagesData = [];
         foreach ($sportPackageId as $packageKey => $package) {
             $packagesData[] = [
                 'userId'                    => $user->id,
                 'subscriptionHistoriesId'   => $subscriptionhistories->id,
                 'sportPackageId'            => $package,
                 'isTrial'                   => 1
             ];
         }

         UserPackagesSubscriptionHistories::insert($packagesData);

         // end user_packages_subscription_histories

         // start packages wise user game history

        $packageByGameData = PackagesAsignToGame::whereIn('packageId', $sportPackageId)
                            ->pluck('gameId')->toArray();
        $gameData = [];
        foreach ($packageByGameData as $gameKey => $game) {
            $gameData[] = [
                'userId'                    => $user->id,
                'subscriptionHistoriesId'   => $subscriptionhistories->id,
                'gameId'                    => $game,
                'isTrial'                   => 1,
                'isSubscribed'              => 1
            ];
        }

        userGameHistory::insert($gameData);

        // End packages wise user game history

        //  start referral code 
         if(!empty($referralCodeUsersId)){
            $referralCodeData = [
                'referralFrom'  => $user->id,
                'referralTo'    => $referralCodeUsersId->id,
                'referralCode'  => $referralCodeBy,
                'isApplied'     => 0
            ];
            UserReferralCodes::insert($referralCodeData);
        }
        // end referral code 

        $cmd = 'cd ' . base_path() . ' && php artisan mail:SendInvoiceCommand "' . $subscriptionhistories->id . '' . $user->id . '"';
            exec($cmd . ' > /dev/null &');

        return $subscriptionhistories;
    }

    /**
     * This method create subscription history on user create payment time
     *
     * @param array  $sportPackageId
     * @param string $referralCodeBy
     * @param int   $referralCodeUsersId
     * @param array $user
     * @param array $planData
     *
     * @return void
    */
    public static function createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType)
    {
        if($request->deviceType == 'Web')
        {
            $user = \Auth::guard('web')->user();
        }
        else
        {
            $user = \Auth::guard('api')->user();
        }

        if($request->planType == 'Monthly'){
            $month = Carbon::now()->addMonth();  
            $planDuration = 30; 
        }else if($request->planType == 'Weekly'){
            $month = Carbon::now()->addDays(7); 
            $planDuration = 7;
        }

        $encode_response = json_encode($paymentData);
                
        $subscriptionhistories = new SubscriptionHistories();
        $subscriptionhistories->userId                  = $user->id;
        if(!empty($paymentData)){
            // commited code used for braintree payment

            // $subscriptionhistories->amount              = $paymentData->transaction->amount;
            // $subscriptionhistories->transactionId       = $paymentData->transaction->id;
            // $subscriptionhistories->paymentType         = $paymentData->transaction->paymentInstrumentType;

            $subscriptionhistories->amount              = number_format($paymentData['amount'],2,'.', '')/100;
            $subscriptionhistories->transactionId       = $paymentData['id'];
            $subscriptionhistories->paymentType         = $paymentData['status'];
        }else{
            $subscriptionhistories->amount              = 0.00;
            $subscriptionhistories->transactionId       = null;
            $subscriptionhistories->paymentType         = 'referral'; 
        }

        if($paymentType == 'onlyPlan'){
            $subscriptionhistories->paymentType         = null;  
            $subscriptionhistories->discountType        = null;
        } else if($paymentType == 'applyPromoCode'){
            $subscriptionhistories->discountType        = 'promocode';
            $subscriptionhistories->promocodeId         = $planAmountData['promocodeId']; 
            $subscriptionhistories->appliedPromocode    = $planAmountData['appliedPromocode']; 
        } else if($paymentType == 'applyReferralCode'){
            $subscriptionhistories->discountType        = 'referral';
            $subscriptionhistories->referralcodeId      = $planAmountData['referralcodeId'];
            $subscriptionhistories->appliedPromocode    = $planAmountData['referralcode']; 
        }
        $subscriptionhistories->paymentResponse     = $encode_response;
        $subscriptionhistories->planName            = $planData->planName;
        $subscriptionhistories->planAmount          = $planAmountData['planAmount']; 
        $subscriptionhistories->planId              = $request->planId;
        $subscriptionhistories->planType            = $request->planType;
        
        $subscriptionhistories->discountAmount      = $planAmountData['discountAmount'];  
        $subscriptionhistories->isTrial             = 0;
        $subscriptionhistories->subscriptionExpiryDate  = $month->toDateString();
        $subscriptionhistories->isRefundRequest  = 0;
        $subscriptionhistories->paymentStatus    = 'success';
        $subscriptionhistories->subscriptionValidity  = $planDuration;
        $subscriptionhistories->packageName =  Self::getPackagesName($request->sportPackageId);

        \DB::beginTransaction();
        if($subscriptionhistories->save()){
            // start user_packages_subscription_histories 
            $packagesData = [];
            foreach ($request->sportPackageId as $packageKey => $package) {
                $packagesData[] = [
                    'userId'                    => $user->id,
                    'subscriptionHistoriesId'   => $subscriptionhistories->id,
                    'sportPackageId'            => $package,
                    'isTrial'                   => 0
                ];
            }

            UserPackagesSubscriptionHistories::insert($packagesData);

            // start packages wise user game history

            $packageByGameData = PackagesAsignToGame::whereIn('packageId', $request->sportPackageId)
                                ->pluck('gameId')->toArray();
            $gameData = [];
            foreach ($packageByGameData as $gameKey => $game) {
                $gameData[] = [
                    'userId'                    => $user->id,
                    'subscriptionHistoriesId'   => $subscriptionhistories->id,
                    'gameId'                    => $game,
                    'isTrial'                   => 0,
                    'isSubscribed'              => 1
                ];
            }

            $userGameHistory = userGameHistory::insert($gameData);

            $userGameHistoryLastId = userGameHistory::orderBy('id', 'desc')->first();

            $cmd = 'cd ' . base_path() . ' && php artisan mail:SendInvoiceCommand "' . $subscriptionhistories->id . '' . $user->id . '"';
            exec($cmd . ' > /dev/null &');

            $resp['status'] = 1;

            $notificationType = 7;


            $image = url('admin-assets/images/logo/full_logo_new.png');


            $notification[] = [
                'senderId' => 1,
                'userId'       => $user->id,
                'notificationType' => $notificationType,
                'module' => 'subscription',
                'title'      => 'You have successfully subscribed for the <b style="color: #03a9f3;">' . $planData->planName . '</b>  sports packages',
                'content'     => 'You have successfully subscribed for the <b style="color: #03a9f3;">' . $planData->planName . '</b>  sports packages',
                'userGameHistoryId' => $userGameHistoryLastId->id,
                'media' => $image,
                'createdAt' => Carbon::now(),
            ];

           if(!empty($notification))
           {
                Notification::insert($notification);
                $cmd = 'cd ' . base_path() . ' && php artisan SendNotificationType:send "' . $notificationType . '" "' . $userGameHistoryLastId->id . '" ';
                //\Log::debug($cmd);
                exec($cmd . '> /dev/null &');
           }
            
           


            return $resp;

        }else{
            $resp['status'] = 0;
            return $resp;
        }
    }

     /**
     * Gets all user message .
     *
     * @param int $id
     *
     * @return query result
    */
    public static function getDateFormate($filedName, $colunNmae)
    {
        $dateFormat = config('constant.DATE_TIME_FORMAT');

        return 'CASE
                    WHEN '.$filedName.' between date_sub(now(), INTERVAL 60 second) and now() 
                        THEN concat(second(TIMEDIFF(now(), '.$filedName.')), "sec")

                    WHEN '.$filedName.' between date_sub(now(), INTERVAL 60 minute) and now() 
                        THEN concat(minute(TIMEDIFF(now(), '.$filedName.')), " min")

                    WHEN '.$filedName.' between date_sub(now(), INTERVAL 24 hour) and now() 
                        THEN concat(hour(TIMEDIFF(NOW(), '.$filedName.')), "h")

                    WHEN datediff(now(), '.$filedName.') = 1 
                        THEN "Yesterday"
                    
                    WHEN datediff(now(), '.$filedName.') = 2 
                        THEN "2d"
                        
                    WHEN datediff(now(), '.$filedName.') = 3 
                        THEN "3d" 

                    WHEN datediff(now(), '.$filedName.') = 7 
                        THEN "1w"

                    WHEN datediff(now(), '.$filedName.') = 14 
                        THEN "2w"    

                    ELSE DATE_FORMAT('.$filedName.', "'.$dateFormat.'")
                END as '.$colunNmae.'';
    }

    /**
     * Gets Expiring Status for .
     *
     * @param int $id
     *
     * @return query result
    */
    public static function getExpiringStatus($filedName, $colunNmae)
    {
        $dateFormat = config('constant.DATE_TIME_FORMAT');
        //return 'CONCAT("Expiring in ", datediff(now(), '.$filedName.'), " days") '.$colunNmae.'';
        
        return 'CASE
                    WHEN ABS(datediff(now() , '.$filedName.')) < 30 
                        THEN CONCAT("Expiring in ", datediff('.$filedName.',now()), " days")
                    
                    WHEN ABS(datediff(now() , '.$filedName.')) <= 60 
                        THEN CONCAT("Valid till next"," 2 months")
                        
                    ELSE DATE_FORMAT('.$filedName.', "'.$dateFormat.'")
                END as '.$colunNmae.'';
    }

    /**
     * Gets tips date formate .
     *
     * @param int $id
     *
     * @return query result
    */
    public static function getTipsDateFormate($filedName, $colunNmae)
    {
        $dateFormat = config('constant.DAY_MONTH_DATE_FORMAT');

        return 'CASE
                    WHEN datediff(now(), '.$filedName.') = 0 
                        THEN "Today"

                    WHEN datediff(now(), '.$filedName.') = 1 
                        THEN "Yesterday"

                    ELSE DATE_FORMAT('.$filedName.', "'.$dateFormat.'")
                END as '.$colunNmae.'';
    }


     /**
     * Gets message date formate .
     *
     *
     * @return query result
    */
    public static function getMessageDateFormate($filedName, $colunNmae)
    {
        $dateFormat = config('constant.DATE_TIME_FORMAT');

        return 'CASE
                    WHEN datediff(now(), '.$filedName.') = 0 
                        THEN "Today"

                    ELSE DATE_FORMAT('.$filedName.', "'.$dateFormat.'")
                END as '.$colunNmae.'';
    }

 public static function getIstrialExpired($userId)
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $isTrialCheck = SubscriptionHistories:: where(['userId'=> $userId])->orderBy('id','desc')->first();


        if($isTrialCheck['isTrial'] == 1){


            if($isTrialCheck['subscriptionExpiryDate'] > $todayDate){
                return 0;
            }
            return 1;
        }
        return 0;
    }

}
