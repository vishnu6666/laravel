<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Model\AdminUser;

class SubAdminResetPasswordController extends ResetPasswordController
{
    /*
    |--------------------------------------------------------------------------
    |  Admin Reset Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $email = null,  $token = null)
    {  
        $alreadySet = 1; // Not able for reset
        $message = "Password Already Reset.";
        $email = base64_decode($email,true);
        $record = \DB::table('password_resets')->where(['email' => $email ,'token' => $token])->first();

        if(!empty($record)){
            $alreadySet = 0; // able for reset
            $message = "";
        }
        return view('subadmin.auth.reset_password', ['email' => $request->email, 'token' => $token, 'alreadySet' => $alreadySet,'message' => $message]);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request,$response)
    {   
        return redirect($this->redirectPath())
                            ->with('success', trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {   
        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = \Hash::make($password);
        $user->passwordShow = $password;

        $user->setRememberToken(Str::random(60));
        
        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }
      /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Set forgot password
     *
     * @param $email
     * @return mixed
     */

    public function changeAdminPassword(Request $request)
    {
        echo "tttrrr";
        exit;
       
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6|max:12',
            'password_confirmation' => 'required|same:password',
        ]);

        //$email = base64_decode($request->email);

        $user = AdminUser::where("email", $request->email)->first();
        if(!empty($user))
        {
            $record = \DB::table('password_resets')->where(['email' => $request->email])->first();
            
            if(!empty($record)){
      
                $user->showPassword = $request->password;
                $user->password = bcrypt($request->password);
              
                if($user->save())
                {
                    $record = \DB::table('password_resets')->where(['email' => $record->email,'token' => $record->token])->delete();
                    $message = "Your password reset successfully";
                    // return redirect('adminLogin')->with('success', $message);
                    return view('subadmin.auth.reset_password', ['message' => $message, 'email' => $request->email, 'api' => 0, 'alreadySet' => 3]);
                }
            }else{
                $message = "Password Already Reset.";
                return view('subadmin.auth.reset_password', ['message' => $message, 'email' => $request->email, 'api' => 0, 'alreadySet' => 1]);
            }
            
        }else{
            $message = "Email is not available in system.";
            return view('subadmin.auth.reset_password', ['message' => $message, 'email' => $request->email, 'api' => 0, 'alreadySet' => 2]);
        }
    }
}
