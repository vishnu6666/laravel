<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Helpers\BraintreeHelper;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\UserLogin;
use App\Model\UserReferralCodes;
use App\Model\UserPackagesSubscriptionHistories;
use App\Model\SubscriptionHistories;
use App\Model\SubscriptionPlan;
use App\Model\Notification;
use App\Model\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Auth Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles login,social login, registration and forgot password features.
     */

    public function refferal_Code($length)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
    }

    /**
     * Register user.
     *
     * @param Request $request
     *
     * @return json
     */
    public function signUp(Request $request)
    {
        $signUpValidion = [
            //'name' => 'required|max:60',
            'email' => 'required|unique:users',
           
            'mobileNumber' => 'nullable',
            'password' => 'required',
            'deviceType' => 'required|in:Ios,Android,Web',
            "deviceToken" => "required",
            // "sportPackageId" => "required",
            // "referralCodeBy" => "required"
            // "socialId" => "required",
            // "socialType" => "required|in:facebook,google,twitter",
        ];

        if(empty($request->socialId))
        {
            $signUpValidion['name'] =  'required|max:60';
        }

        if(!empty($request->mobileNumber))
        {
            $signUpValidion['mobileNumber']  = 'unique:users';
        }

        $this->validate($request,  $signUpValidion);

        $user = new User();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->userType = 'User';
        $user->referralCode = self::refferal_Code(12);
        $user->mobileNumber = $request->mobileNumber;

        $user->password = bcrypt($request->password);
        $user->showPassword = $request->password;
        
        if(!empty($request->socialId))
        {
            $generatePassword = substr(str_shuffle('1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz'), 0, 8);
            $user->password = bcrypt($generatePassword);
            $user->showPassword = $generatePassword;
        }
       
        $user->deviceType = $request->deviceType;
        $user->deviceToken = $request->deviceToken;
        $user->isActive = 1;
        $user->socialId = empty($request->socialType) ? null : $request->socialId;
        $user->socialType = empty($request->socialType) ? null : $request->socialType;
        $user->isEmailVerified = empty($request->socialType) ? 0 : 1;
        

        if(!empty($request->referralCodeBy)){
            $referralCodeUsersId = ApiHelper::getUserByReferralCode($request->referralCodeBy);
        }else{
            $referralCodeUsersId = null;
        }
        
        if(!empty($referralCodeUsersId) || empty($request->referralCodeBy)){
            $otp = rand(1000, 9999);
            $user->otp = empty($request->socialType) ? $otp : null;
            
            \DB::beginTransaction();
            if ($user->save()) {
                $sportPackageId = ApiHelper::getsportPackageId();
                
                $planData = ApiHelper::getSubscriptionPlanByfullPackages();

                if(!empty($planData)){
                    
                    $subscriptionhistories = ApiHelper::createsubscriptionhistories($sportPackageId,$referralCodeUsersId,$request->referralCodeBy,$user,$planData);
                    
                    if($subscriptionhistories){

                        $userDetail = ApiHelper::getUserById($user->id)->first();
                        $userDetail = ApiHelper::getUserWithIsPayment($userDetail);
                            if(empty($request->socialType)){
                                $cmd = 'cd ' . base_path() . ' && php artisan mail:sendsignupotpCommand ' . $userDetail->id . ' ';
                                exec($cmd . ' > /dev/null &');
                                \DB::commit();

                                if($request->deviceType =='Web'){
                                    $user = \Auth::loginUsingId($userDetail->id);
                                    return redirect('user/otp');
                                }
                                
                                return $this->toJson(['otp' => $userDetail->otp], trans('api.register.success'));
                            }

                            $user = \Auth::loginUsingId($userDetail->id);
                            $tokenResult = $user->createToken('Bttips')->accessToken;

                            if(!empty($request->socialId))
                            {
                                $cmd = 'cd ' . base_path() . ' && php artisan mail:sendFreeTrialInfoCommand ' . $userDetail->id . ' ';
                                exec($cmd . ' > /dev/null &');
                            }
                        \DB::commit();
                        return $this->toJson([
                            'userDetail' => $userDetail,
                            'accessToken' => $tokenResult,
                        ], trans('api.register.success'));
                    }

                }
                    DB::rollback();
                    if($request->deviceType =='Web'){
                        return redirect()->route('signup')->with('error',trans('api.register.plan_not_found'));
                    }
                return $this->toJson(null, trans('api.register.plan_not_found'), 0);

            }
            if($request->deviceType =='Web'){
                return redirect()->route('signup')->with('error',trans('api.register.error'));
            }
            return $this->toJson(null, trans('api.register.error'), 0);

        }
        if($request->deviceType =='Web'){
            return redirect()->route('signup')->with('error',trans('api.register.invalid_referral_code_by_code'));
        }
        return $this->toJson(null, trans('api.register.invalid_referral_code_by_code'), 0);
    }

    /**
     * Verify OTP Api
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function verifyOTP(Request $request)
    {
        $authUser = \Auth::guard('api')->user();

        $this->validate($request, [
            'email' => 'required',
            'otp' => 'required',
            'deviceType' => 'required|in:Ios,Android',
            'fcmToken' => 'required',
            'deviceToken' => 'required',
        ]);

        $param['email'] = $request->email;

        $userDetail = ApiHelper::getUserByEmailOrMobile('email', $param)->first();

        if (!empty($userDetail)) {
            \DB::beginTransaction();

            // Check otp is valid
            if ($userDetail->otp == $request->otp) {

                $userDetail->otp = null;
                $userDetail->isEmailVerified = 1;


                if ($userDetail->save()) {

                    //$customerData = BraintreeHelper::creatCustomer($userDetail);

                    $tokenResult = $request->header('Authorization');
                    if (empty($authUser)) {
                        $user = \Auth::loginUsingId($userDetail->id);
                        $tokenResult = $user->createToken('Bttips')->accessToken;
                        $this->userLogin($request, $userDetail);
                        $user->fcmToken = $request->fcmToken;
                        $user->save(); 
                        
                        $cmd = 'cd ' . base_path() . ' && php artisan mail:sendFreeTrialInfoCommand ' . $user->id . ' ';
                        exec($cmd . ' > /dev/null &');
                    }
                    \DB::commit();
                    $userDetail = ApiHelper::getUserWithIsPayment($userDetail);
                    return $this->toJson([
                        'userDetail' => $userDetail,
                        'accessToken' => $tokenResult,
                    ], trans('api.otp_verify.success'));

                }
                DB::rollback();
                return $this->toJson(null, trans('api.otp_verify.error'), 0);
            }
            return $this->toJson(null, trans('api.otp_verify.invalid'), 0);
        }
        return $this->toJson(null, trans('api.auth.user_not_found'), 0);
    }

    /**
     * Verify email Api
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function verifyEmail(Request $request)
    {
        $authUser = \Auth::guard('api')->user();

        $this->validate($request, [
            'email' => 'required|unique:users',
            'otp' => 'required'
        ]);

        if (!empty($authUser)) {
            \DB::beginTransaction();

            // Check otp is valid
            if ($authUser->otp == $request->otp) {

                $authUser->otp = null;
                $authUser->email =  $request->email;
                $authUser->isEmailVerified = 1;

                if ($authUser->save()) {

                   $param['email'] = $authUser->email;
                   $userDetail = ApiHelper::getUserByEmail('email', $param)->first();
                   $userDetail = ApiHelper::getUserWithIsPayment($userDetail);
                   \DB::commit();
                    return $this->toJson([
                        'userDetail' => $userDetail
                    ], trans('api.otp_verify.success'));

                }

                return $this->toJson(null, trans('api.otp_verify.error'), 0);
            }
            return $this->toJson(null, trans('api.otp_verify.invalid'), 0);
        }
        return $this->toJson(null, trans('api.auth.user_not_found'), 0);
    }

    /**
     * Login user in our system.
     *
     * @param object $request
     *
     * @return json
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'deviceType' => 'required|in:Ios,Android',
            'fcmToken' => 'required',
            'deviceToken' => 'required',
        ]);

        // check Email or Mobile No unique

        $param['email'] = $request->email;

        $userDetail = ApiHelper::getUserByEmailOrMobile('email', $param)->first();
        //$userDetail = ApiHelper::getUserWithIsPayment($userDetail);

        
        if (empty($userDetail)) {
            return $this->toJson(null, trans('api.login.invalid'), 0);
        }

        if ($userDetail->isActive != 1) {
            return $this->toJson(null, trans('api.login.inactive'), 0);
        }

        if (\Hash::check($request->password, $userDetail->password)) {
            $credentials = request(['email', 'password']);

            $user = \Auth::loginUsingId($userDetail->id);

            if ($userDetail->isEmailVerified != 1) {
                $otp = rand(1000, 9999);
                $userDetail->otp = $otp;
                $userDetail->save();

               $cmd = 'cd ' . base_path() . ' && php artisan mail:sendloginotpCommand ' . $userDetail->id . ' ';
                exec($cmd . ' > /dev/null &');
            }

            $isVerified = (int) ($userDetail->isEmailVerified == 1);
            $tokenResult = '';

            $userDetail = ApiHelper::getUserByEmailOrMobile('email', $param)->first();
            $userDetail = ApiHelper::getUserWithIsPayment($userDetail);

            if ($isVerified) {
                $tokenResult = $user->createToken('Bttips')->accessToken;

                $this->userLogin($request, $user);
                $user->fcmToken = $request->fcmToken;
                $user->save(); 

                return $this->toJson([
                    'userDetail' => $userDetail,
                    'accessToken' => $tokenResult,
                ], trans('api.login.success'), 1);
            }

            return $this->toJson([
                'userDetail' => $userDetail,
                'accessToken' => "",
            ], trans('api.login.success'), 1);
        }
    
        return $this->toJson(null, trans('api.login.invalid'), 0);

    }


    /**
     * Login user by social.
     *
     * @param object $request
     *
     * @return json
     */
  
    public function socialLogin(Request $request)
    {
        $this->validate($request, [
                "socialId" => "required",
                "socialType" => "required|in:facebook,google,twitter,apple",
                'deviceType' => 'required|in:Ios,Android',
        ]);

        $param['email'] = $request->email;
        $param['socialId'] = $request->socialId;
        $param['socialType'] = $request->socialType;

        $userDetail = ApiHelper::getUserBysocialId('socialId', $param)->first();
        
        if($userDetail){
            
            $isVerified = (int) ($userDetail->isEmailVerified == 1);
            $tokenResult = '';
            if ($isVerified) {
                $user = \Auth::loginUsingId($userDetail->id);

                $tokenResult = $user->createToken('Bttips')->accessToken;

                $user->fcmToken = $request->fcmToken;
                $user->deviceType = $request->deviceType;
               
                $user->save(); 

                $userDetail->deviceType = $request->deviceType;
                $userDetail = ApiHelper::getUserWithIsPayment($userDetail);
        

                return $this->toJson([
                    'userDetail' => $userDetail,
                    'isExist' => 1,
                    'accessToken' => $tokenResult,
                ], trans('api.login.success'), 1);
            }
        }
                return $this->toJson([
                    'userDetail' => [],
                    'isExist' => 0,
                ], trans('api.login.notfound'), 1);
    }

    /**
     * Resend OTP
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function resendOTP(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $param['email'] = $request->email;

        $userDetail = ApiHelper::getUserByEmailOrMobile('email', $param)->first();

        if (!empty($userDetail)) {
            $otp = rand(1000, 9999);
            $userDetail->otp = $otp;
            $userDetail->save();

            $cmd = 'cd ' . base_path() . ' && php artisan mail:sendforgotpassword ' . $userDetail->id . ' ';
            exec($cmd . ' > /dev/null &');

            return $this->toJson([
                'otp' => $userDetail->otp,
            ], trans('api.otp_verify.sent_otp', ['type' => 'email']), 1);
        }

        return $this->toJson(null, trans('api.auth.user_not_found'), 0);
    }

    /**
     * Resend OTP For Edit Email in profile
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function resendOTPForEditEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $user = Auth::guard('api')->user();

        $userDetail = ApiHelper::getUserById($user->id)->first();
        
        if (!empty($userDetail)) {
            $otp = rand(1000, 9999);
            $userDetail->otp = $otp;
            $userDetail->save();

            $cmd = 'cd ' . base_path() . ' && php artisan mail:resendotp ' . $request->email . ' ' . $userDetail->id . ' ';
            exec($cmd . ' > /dev/null &');

            return $this->toJson([
                'otp' => $userDetail->otp,
            ], trans('api.otp_verify.sent_otp', ['type' => 'email']), 1);
        }

        return $this->toJson(null, trans('api.auth.user_not_found'), 0);
    }

     /**
     * Reset Password
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
        ]);

        $param['email'] = $request->email;

        $userDetail = ApiHelper::getUserByEmailOrMobile('email', $param)->first();

        if (!empty($userDetail)) {
            $userDetail->password = bcrypt($request->password);
            $userDetail->showPassword = $request->password;
            $userDetail->save();

            return $this->toJson(null, trans('api.reset_password.success'), 1);
        }

        return $this->toJson(null, trans('api.auth.user_not_found'), 0);
    }

    /**
     * Logout user
     *
    */
    public function logout1()
    {
        $user = Auth::guard('api')->user();

        $userToken = Auth::user()->token();

        if (empty($user)) {
            return $this->toJson(null, trans('api.auth.user_not_found'), 0);
        }

        User::where([
            'id' => $user->id
        ])->update(['fcmToken' => null]);

        $userToken->revoke();

        \Session::flush();
        return $this->toJson(null, trans('api.logout.success'));
    }

    /**
     * Logout user
     *
    */
    public function logout()
    {
        $user = Auth::guard('api')->user();

        if (empty($user)) {
            return $this->toJson(null, trans('api.logout.success'));
        }


        $userToken = Auth::guard('api')->user();

        if(!empty($user))
        {
            $userToken = $user->token();

        User::where([
            'id' => $user->id
        ])->update(['fcmToken' => null]);

        $userToken->revoke();

        }

        \Session::flush();
        return $this->toJson(null, trans('api.logout.success'));
    }


    /**
     * Change Password
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        $user = Auth::guard('api')->user();

        if (empty($user)) {
            return $this->toJson(null, trans('api.auth.user_not_found'), 0);
        }

        if (\Hash::check($request->oldPassword, $user->password)) {
            $user->password = bcrypt($request->newPassword);
            $user->save();
            return $this->toJson(null, trans('api.auth.change_password'), 1);
        }

        return $this->toJson(null, trans('api.auth.invalid_current_password'), 0);
    }

    /**
     * Login user in our system.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getUserDetail(Request $request)
    {
        $user = \Auth::user();
        $userDetail = ApiHelper::getUserById($user->id)->first();
        $userDetail = ApiHelper::getUserWithIsPayment($userDetail);
        $isVerified = (int) ($user->isMobileVerify == 1 || $userDetail->isEmailVerified == 1);
        $notificationCount = Notification:: where(['userId' => $user->id,'isRead' => 0])->count();
        $messageCount = UserMessage:: where(['userId' => $user->id,'isRead' => 0])->count();

        $todayDate = Carbon::now()->format('Y-m-d');
        $expirePlanDate = SubscriptionHistories:: where(['userId'=> $user->id])->max('subscriptionExpiryDate');
        $freeTrialDate = SubscriptionHistories :: selectRaw('subscriptionExpiryDate,subscriptionValidity')->where(['userId'=> $user->id,'isTrial' => 1])->first();
        
        $freeTrialExpireCount = $this->dateDiffInDays($todayDate, $freeTrialDate['subscriptionExpiryDate']); 
        $isTrialExpired = ApiHelper::getIstrialExpired($user->id);

        $tokenResult = '';
        if ($isVerified) {
            $tokenResult = $user->createToken('Bttips')->accessToken;
        }

        if(empty($request->version) || empty($userDetail))
        {
            $userToken = Auth::user()->token();
            if(!empty($userToken))
            {
                $userToken->revoke();
            }
            \Session::flush();

            $message = empty($request->version) ? 'Kindly update your app' : 'Your session has been expired';
            return $this->toJson([], $message, 0);
        }

        return $this->toJson([
            'isSocialLogin'     => $userDetail->socialType == null ? 0 : 1,
            'isVerified'        => $isVerified,
            'messageCount'      => $messageCount,
            'notificationCount' => $notificationCount,
            'isTrialExpired'     => $isTrialExpired,
            'isPlanExpired'     => $todayDate <= $expirePlanDate ? 0 : 1,
            'freeTrialExpireCount' => $freeTrialExpireCount >= 0 ? $freeTrialExpireCount : 0,
            'freeDays'           => $freeTrialDate['subscriptionValidity'],
            'userDetail'        => $userDetail,
            'accessToken'       => $tokenResult,
            'androidAppVersion' => "5",
            'iosAppVersion' => "1.2"
        ], trans(''), 1);
    }
 
    /**
     * Get Login user details in our system.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getProfileDetail(Request $request)
    {
        $user = \Auth::user();
        $userDetail = ApiHelper::getUserById($user->id)->first();
        $userDetail = ApiHelper::getUserWithIsPayment($userDetail);
        $messageCount = UserMessage:: where(['userId' => $user->id,'isRead' => 0])->count();
        $notificationCount = Notification:: where(['userId' => $user->id,'isRead' => 0])->count();
        $isVerified = (int) ($user->isMobileVerify == 1 || $userDetail->isEmailVerified == 1);

        $expirePlanDate = SubscriptionHistories:: where(['userId'=> $user->id])->max('subscriptionExpiryDate');
        $freeTrialDate = SubscriptionHistories :: selectRaw('subscriptionExpiryDate,subscriptionValidity')->where(['userId'=> $user->id,'isTrial' => 1])->first();
        
        $todayDate = Carbon::now()->format('Y-m-d');

        $dateDiff = $this->dateDiffInDays($todayDate, $freeTrialDate['subscriptionExpiryDate']); 
        $isTrialExpired = ApiHelper::getIstrialExpired($user->id);  
   
        if($userDetail){
            return $this->toJson([
                'isSocialLogin'      => $userDetail->socialType == null ? 0 : 1,
                'isVerified'         => $isVerified,
                'messageCount'       => $messageCount,
                'notificationCount'  => $notificationCount,
                'isPlanExpired'      => $todayDate <= $expirePlanDate ? 0 : 1,
               'isTrialExpired'     => $isTrialExpired,
                'freeTrialExpireCount' => $dateDiff >= 0 ? $dateDiff : 0,
                'freeDays'           => $freeTrialDate['subscriptionValidity'],
                'userDetail'         => $userDetail
            ], trans('api.profile.success'), 1);
        }
        return $this->toJson([], trans('api.profile.error'), 0);
    }

    /**
     * date DiffIn Days for check expire trial plan
     *
     * @param Request $date1 today date 
     * @param Request $date2 expire date
     */

    function dateDiffInDays($date1, $date2)  
    { 
        if($date1 < $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return (int)($diff / 86400); 
        }else if($date1 > $date2){
            return 0;
        }else if($date1 == $date2){
            return 1;
        }
    }

    
    // Not requred for Vttips

    /**
     * User login process.
     *
     * @param Request $request
     * @param Request $user
     *
     * @return json
     */
    private function userLogin(Request $request, $user)
    {
        // Logout from all other device
        UserLogin::where([
            'userId' => $user->id,
            'isLogin' => 1,
        ])->update(['isLogin' => 0]);

        $userLogin = new UserLogin();
        $userLogin->userId = $user->id;
        $userLogin->isLogin = '1';
        $userLogin->fill($request->all());

        $userLogin->save();
    }


    /**
     * Change Pin
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function changePin(Request $request)
    {
        $this->validate($request, [
            'pin' => 'required|max:4',
        ]);

        $user = Auth::guard('api')->user();

        if ($request->pin == $user->pin) {
            return $this->toJson(null, trans('api.pin.same'), 0);
        }

        $user->pin = $request->pin;
        if ($user->save()) {
            $userDetail = ApiHelper::getUserById($user->id)->first();
            $userDetail = ApiHelper::getUserWithIsPayment($userDetail);
            return $this->toJson([
                'userDetail' => $userDetail,
            ], trans('api.pin.success'), 1);
        }

        return $this->toJson(null, trans('api.pin.error'), 0);
    }

}
