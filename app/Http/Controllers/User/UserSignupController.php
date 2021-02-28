<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;

use App\Http\Controllers\Controller;

class UserSignupController extends Controller
{
    /**
     * Show user Signup form
     *
     * @return view
    */
    public function signup()
    {   
        return view('user.auth.signup');
    } 
    
}

