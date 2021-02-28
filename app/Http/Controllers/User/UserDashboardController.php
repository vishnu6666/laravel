<?php

namespace App\Http\Controllers\User;

use DateTime;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\model\SubscriptionHistories;
use App\model\SubscriptionPlan;
use App\model\Package;
use App\model\PackagesAsignToGame;
use App\Model\Game;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Dashboard Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles  users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Show user dashboard
     *
     * @return view
    */
    public function index()
    {   
        $authUser = \Auth::guard('web')->user();

        $subscribedPlan = SubscriptionHistories::distinct('planId')->where('userId', $authUser->id)->where('subscriptionExpiryDate','>',date('Y-m-d'))->pluck('planId');
 
        $upgradeplanDetail = SubscriptionPlan::selectRaw('subscription_plans.id,subscription_plans.planName,subscription_plans.planSubTitle,subscription_plans.planFullPackagesTitle,subscription_plans.planFullPackages,subscription_plans.planWeeklyPrice,subscription_plans.planMonthlyPrice')->where(['isActive'=>1,'isTrial' => 0])->whereNotIn('id',$subscribedPlan)->get();
      
        $currentPlanDetail = SubscriptionHistories:: selectRaw('subscription_histories.id,subscription_histories.planName,subscription_histories.planAmount,
                                                                IF(subscription_histories.id,1,0) as isSelected,
                                                                subscription_histories.planType,DATE_FORMAT(subscription_histories.subscriptionExpiryDate,"%d %b,%Y") AS expiryDate,
                                                                group_concat(packages.packageName) as packageNames,subscription_histories.isTrial,subscription_histories.isCancel')
                                            
                                            ->leftJoin('user_packages_subscription_histories', function ($join){
                                                $join->on('subscription_histories.id', '=', 'user_packages_subscription_histories.subscriptionHistoriesId');
                                            })
                                            
                                            ->leftJoin('packages', function ($join){
                                                $join->on('packages.id', '=', 'user_packages_subscription_histories.sportPackageId');
                                            })
                                            ->where('subscription_histories.userId',$authUser->id)
                                            ->where('subscription_histories.subscriptionExpiryDate','>',date('Y-m-d'))
                                            ->groupBy('subscription_histories.id')->get();
        
        
        if (!$upgradeplanDetail->isEmpty() || !$currentPlanDetail->isEmpty()) {

            foreach($currentPlanDetail as $key => $value){
                $currentPlanDetail[$key]['isCancel'] = $value->isTrial == 1 ? 1 : $value->isCancel;
            }

            $upgradeplanData = array();
            foreach ($upgradeplanDetail as $key => $result) {
                $result['packages'] = $this->getSportPackages();
                $upgradeplanData[]  =$result;
            }
        }
        
        return view('user.dashboard', [
            'currentPlanDetail' => $currentPlanDetail,
            'upgradeplanData' => $upgradeplanData,
            
        ]);
    } 

    public function getSportPackages()
    {
        $packageDetail = Package::selectRaw('id,packageName')
                                ->where('isActive', 1)
                                ->get();
   
        if (!$packageDetail->isEmpty()) {
            $listpackageData = array();
            foreach ($packageDetail as $key => $result) {
                $nestedData['id']           = $result['id'];
                $nestedData['packageName']  = $result['packageName'];
                $nestedData['isSelected']   = false ;
                $nestedData['gamesData']    = self::get_games($result['id']);
                $listpackageData[]          = $nestedData;
            }
            return $listpackageData;

        } else {
            return null;
        }
    }

    public function get_games($packId)
    {
        $gameIds = PackagesAsignToGame::selectRaw('*')->where('packageId',$packId)->distinct('gameId')->pluck('gameId')->toArray();
        return Game::selectRaw('games.id,games.gameName,games.gameFullName')->where('isActive',1)->whereIn('games.id', $gameIds)->get();
    }


     /**
     * Show user dashboard
     *
     * @return view
    */
    public function ajaxFormLoad(Request $request)
    {
        return view('user.ajax_form_load', [
            'requestData' => $request->all()
        ]);
    }

     /**
     * Show success page after successfully payment
     *
     * @return view
    */
    public function success()
    {
        return view('user.success');
    }  

}

