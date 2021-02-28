<?php

namespace App\Http\Controllers\Auth;

use App\Model\UserLogin;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
class AdminLoginController extends LoginController
{
    /*
    |--------------------------------------------------------------------------
    | Admin Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    protected $redirectTo = '/superadmin/dashboard';

    /**
     * Set current Guard.
     *
     * @var string
     */
    protected $guard = 'superadmin';

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //parent::__construct();
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {   
        if(!\Auth::guard('superadmin')->check()){
          
            return view('admin.auth.login');
        }
        return redirect(route('AdminDashboard'));
    }

     /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {   
        return [
            'email' => $request->email,
            'password' => $request->password,
        ];
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
        if(\Auth::guard('superadmin')->check())
        {
            if ($user->userType == 'SuperAdmin')
            {
                if($user->isActive == 0)
                {
                    $this->guard('superadmin')->logout();
                    return redirect('superadmin')->with('error', trans('auth.inactive'));
                }
                else
                {
                    return redirect('superadmin/dashboard');
                }

            }else{
                $this->guard('superadmin')->logout();
                return redirect('superadmin')->with('error', trans('auth.unauthorized'));
            }
        }
        else
        {   
            $this->guard('superadmin')->logout();
            return redirect('superadmin')->with('error', trans('auth.failed'));
        }
        
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
        $userLogin->userId = $this->guard('superadmin')->user()->id;
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
        
        $this->guard('superadmin')->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/superadmin/login');
    }
}
