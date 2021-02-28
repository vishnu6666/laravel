<?php

namespace App\Http\Controllers\Auth;

use App\Model\UserLogin;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
class UserLoginController extends LoginController
{
    /*
    |--------------------------------------------------------------------------
    | Web Users Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating web users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    protected $redirectTo = '/user/dashboard';

    /**
     * Set current Guard.
     *
     * @var string
     */
    protected $guard = 'web';

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //parent::__construct();
       // return redirect('user/dashboard');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {   
        $user = Auth::guard('web')->user();

        if(is_null($user)){
            return view('user.auth.login');
        }

        if($user->isEmailVerified == 1){
            return redirect('user/dashboard');
        }
        
        return view('user.auth.login');
    }

     /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if(is_numeric($request->email)){
            return ['mobileNumber'=>$request->email,'password'=>$request->password];
        }
        return ['email' => $request->email,'password' => $request->password];
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {   
        if(\Auth::guard('web')->check())
        {
            if ($user->userType == 'User')
            {

                if($user->isEmailVerified == 0)
                {
                    return redirect('user/otp');
                }
                else
                {
                    return redirect('user/dashboard');
                }

            }else{
                $this->guard('web')->logout();
                return redirect('/user/login')->with('error', trans('auth.unauthorized'));
            }
        }
        else
        {   
            $this->guard('web')->logout();
            return redirect('/user/login')->with('error', trans('auth.failed'));
        }
        
    }

    protected function otp()
    {
        return view('user/auth/verifyOtp');
    }

    protected function verifyotp(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required'
        ]);

        $user = $this->guard('web')->user();

        if ($request->otp ==  $user->otp) {
            
            $user->isEmailVerified = 1;
            $user->otp = null;
            $user->save();

            $cmd = 'cd ' . base_path() . ' && php artisan mail:sendFreeTrialInfoCommand ' . $user->id . ' ';
            exec($cmd . ' > /dev/null &');

            return redirect(route('userDashboard'))->with('success', trans('messages.otpVerify.success'));
        }else{
           
            return redirect(route('otp'))->with('error', trans('messages.otpVerify.error'));
        }

        //
        //dd($request->all(),$user);      
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {  
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

       // Store login detail
        $userLogin = new UserLogin();
        $userLogin->userId = $this->guard('web')->user()->id;
        $userLogin->isLogin = 1;
        $userLogin->platform = 'WEB';
        $userLogin->save();

        session()->put('lastLoginId',$userLogin->id);

        $lastLoginId = session()->get('lastLoginId');

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        $lastLoginId = session()->get('lastLoginId');
        // Store login detail
        
        $userLogin = UserLogin::find($lastLoginId);
        if(!empty($userLogin))
        {
            $userLogin->isLogin = 0;
            $userLogin->save();
        }
        
        $this->guard('web')->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/user/login');
    }
}
