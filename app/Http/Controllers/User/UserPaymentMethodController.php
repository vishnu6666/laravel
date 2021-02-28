<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use DateTime;

use App\Http\Controllers\Controller;

class UserPaymentMethodController extends Controller
{
    /**
     * Show User Payment Methods
     *
     * @return view
    */
    public function index()
    {   
        return view('user.payment_method');
    } 
    
}

