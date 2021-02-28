<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Game;
use App\Model\GameTip;
use App\Model\Package;
use App\Model\ContactUs;
use App\Model\SubscriptionHistories;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
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
        // echo Carbon::now('Y-m-d');
        // exit;
        $users = User::where('isActive', 1)->where('userType','User')->count();
        $game = Game::where('isActive', 1)->count();
        $package = Package::where('isActive', 1)->count();
        $revenue = SubscriptionHistories::where('paymentStatus', 'success')->where('isRefundRequest', 0)->get()->sum("amount");
        $gameTip = GameTip::count();

        $subscription = SubscriptionHistories::selectRaw('subscription_histories.id,users.name,subscription_histories.planName,
                                                        subscription_histories.planType,subscription_histories.planAmount,
                                                        subscription_histories.amount,subscription_histories.subscriptionExpiryDate,
                                                        subscription_histories.subscriptionValidity,
                                                        subscription_histories.isTrial,subscription_histories.createdAt')
        ->leftJoin('users', function ($query) {
            $query->on('users.id', '=', 'subscription_histories.userId');
        })
        ->where('isTrial', 0)->count();
       
        
        // echo $revenue;exit;

        // $revenue = SubscriptionHistories::where('paymentStatus', 'success')->where('isRefundRequest', 0)->pluck('amount')->toArray();
        
    

        $inquiry = ContactUs::count();
        return view('admin.dashboard',[
            'users' => $users,
            'game' => $game,
            'package' => $package,
            'inquiry' => $inquiry,
            'revenue' => $revenue,
            'gameTip' => $gameTip,
            'subscription' => $subscription
        ]);
    }   

}

