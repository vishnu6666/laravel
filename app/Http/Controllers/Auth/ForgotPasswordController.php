<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    /**
     * Forgot Password api.
     *
     * @param Request $request
     * 
     * @return json
     */
    public function forgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required'
        ]);

        // check Email or Mobile No unique

        $param['email'] = $request->email;

        $userDetail = ApiHelper::getUserByEmailOrMobile('email', $param)->first();
        
        if (!empty($userDetail))
        {
            if ($userDetail->isActive != 1) {
                return $this->toJson([], trans('api.login.inactive'), 0);
            }

            $otp = rand(1000,9999);
            $userDetail->otp = $otp;
            $userDetail->save();

            $cmd = 'cd ' . base_path() . ' && php artisan mail:sendforgotpassword "' . $userDetail->id . '"';
            exec($cmd . ' > /dev/null &');
            
                // Send OTP in email
                return $this->toJson([
                    'otp' => $userDetail->otp,
                ], trans('api.forgot_password.email_success'), 1);
            
        }

        return $this->toJson([], trans('api.forgot_password.email_error'), 0);
    }
}
