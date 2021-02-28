<?php

namespace App\Http\Middleware;

use Closure;

class UserCheckIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::guard('web')->user();

        if($user->isEmailVerified == 0)
        {
            return redirect('user/login');
            //return redirect(route('user'))->with('error', trans('messages.otpVerify.verifyAccount'));
        }
        
        return $next($request);
    }
}
