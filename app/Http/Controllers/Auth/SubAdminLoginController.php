<?php

namespace App\Http\Controllers\Auth;

use App\Model\UserLogin;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
class SubAdminLoginController extends LoginController
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

    protected $redirectTo = '/subadmin/dashboard';

    /**
     * Set current Guard.
     *
     * @var string
     */
    protected $guard = 'admin';

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
        if(!\Auth::guard('admin')->check()){
          
            return view('subadmin.auth.login');
        }
        return redirect(route('subAdminDashboard'));
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
        if(\Auth::guard('admin')->check())
        {
            if ($user->userType == 'Admin')
            {
                if($user->isActive == 0)
                {
                    $this->guard('admin')->logout();
                    return redirect('admin')->with('error', trans('auth.inactive'));
                }
                else
                {
                    return redirect('admin/dashboard');
                }

            }else{
                $this->guard('admin')->logout();
                return redirect('admin')->with('error', trans('auth.unauthorized'));
            }
        }
        else
        {   
            $this->guard('admin')->logout();
            return redirect('admin')->with('error', trans('auth.failed'));
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
        $userLogin->userId = $this->guard('admin')->user()->id;
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
        
        $this->guard('admin')->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/admin/login');
    }
}
