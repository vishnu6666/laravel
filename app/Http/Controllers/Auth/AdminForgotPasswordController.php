<?php

namespace App\Http\Controllers\Auth;

use App\Model\AdminUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Mail\AdminForgotPassword;
use Illuminate\Support\Facades\Mail;
use DB;
use Carbon\Carbon;

class AdminForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Forgot Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    use SendsPasswordResetEmails;

    /**
     * Display login form.
     *
     * @return void
     */
    public function index(){

        return view('admin.auth.forgot_password');
        
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
     * Check email address is admin user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function checkUserIsAdmin(Request $request){

        $this->validateEmail($request);
        
        $user= AdminUser::where('email',$request->email)->first();
        
        if(!empty($user))
        {
             //create a new token to be sent to the user. 
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => str_random(60),
                'created_at' => Carbon::now()
            ]);

            $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

            $token = $tokenData->token;
            
            Mail::to($user->email)->send(new AdminForgotPassword($user,$token));
            
            return redirect()->back()->with('success',trans('Your password reset link send to your mail') );            
            //session()->put('passwordBroker','admin');
        
            return $this->sendResetLinkEmail($request);
        }
        return redirect()->back()->with('error',trans('auth.wrongemail') );
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request,$response)
    {
        return back()->with('success', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
            ->withInput($request->only('email'))
            ->with('error', trans($response));
    }
}
