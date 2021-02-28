<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use App\Model\UserSubscriptionHistories;

use App\Http\Controllers\Controller;

class UserPaymentHistoryController extends Controller
{
    /**
     * Show User plan history 
     *
     * @return view
    */
    public function index()
    {   
        $user = \Auth::guard('web')->user();
        $historyList = UserSubscriptionHistories::selectRaw("id,planType,planName,packageName,planAmount,DATE_FORMAT(createdAt,'%D %b %Y, %h:%i %p') as createdAt")->where(['userId'=>$user->id,'isTrial' => 0])->orderBy('id', 'DESC')->get();
        return view('user.history_list',['historyList' => $historyList]);
    } 
    
}

