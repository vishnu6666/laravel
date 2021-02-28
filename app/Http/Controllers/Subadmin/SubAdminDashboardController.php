<?php

namespace App\Http\Controllers\SubAdmin;

use DateTime;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Game;
use App\Model\Package;
use App\Model\SubscriptionHistories;
use App\Model\ContactUs;

use App\Http\Controllers\Controller;

class SubAdminDashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles  admin users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Show admin dashboard
     *
     * @return view
    */
    public function index()
    {   
        $users = User::where('isActive', 1)->count();
        $game = Game::where('isActive', 1)->count();
        $package = Package::where('isActive', 1)->count();
 
        
        $inquiry = ContactUs::count();
        return view('subadmin.dashboard',[
            'users' => $users,
            'game' => $game,
            'package' => $package,
            'inquiry' => $inquiry,
        ]);
    }   

}

