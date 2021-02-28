<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\SubscriptionPlan;
use App\Model\SubscriptionHistories;

use App\Model\Package;
use App\Model\Game;
use App\Model\PackagesAsignToGame;

class PlansController extends Controller
{
    /**
     * get all plan.
     *
     * @param Request $request
     *
     * @return json
     */

    public function getPlans(Request $request)
    {
        $plansDetail = SubscriptionPlan::selectRaw('subscription_plans.id,subscription_plans.planName,subscription_plans.planFullPackages,subscription_plans.planSubTitle,subscription_plans.planDuration,subscription_plans.planWeeklyPrice,subscription_plans.planMonthlyPrice,subscription_plans.isActive')->where('isActive', 1)->get();

        if (!$plansDetail->isEmpty()) {

            return $this->toJson(['plansDetail' => $plansDetail], trans('api.plan.detail'), 1);
        }

        return $this->toJson(null, trans('api.plan.error'), 0);
    }
    /**
     * get current plan with all plans.
     *
     * @param Request $request
     *
     * @return json
    */

    public function getCurrentPlans(Request $request)
    {
        $dateFormat = config('constant.DATE_FORMAT');
        $authUser = \Auth::guard('api')->user();
        $subscribedPlan = SubscriptionHistories::distinct('planId')->where('userId', $authUser->id)->where('subscriptionExpiryDate','>',date('Y-m-d'))->pluck('planId');
 
        $upgradeplanDetail = SubscriptionPlan::selectRaw('subscription_plans.id,subscription_plans.planName,subscription_plans.planSubTitle,subscription_plans.planFullPackagesTitle,subscription_plans.planFullPackages,subscription_plans.planWeeklyPrice,subscription_plans.planMonthlyPrice')->where(['isActive'=> 1,'isTrial' => 0])->whereNotIn('id',$subscribedPlan)->get();
      
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

            $upgradeplanData = array();
            foreach ($upgradeplanDetail as $key => $result) {
                $result['packages'] = $this->getSportPackages();
                $upgradeplanData[]  =$result;
            }

            foreach($currentPlanDetail as $key => $value){
                $currentPlanDetail[$key]['isCancel'] = $value->isTrial == 1 ? 1 : $value->isCancel;
                $currentPlanDetail[$key]['packageNames'] = str_replace(',',', ',$value->packageNames);
                unset($currentPlanDetail[$key]['isTrial']);
            }
            return $this->toJson(['currentPlanDetail' => $currentPlanDetail,
                                'upgradeplanDetail' => $upgradeplanData
                                ], trans('api.plan.detail'), 1);
        }

        return $this->toJson(null, trans('api.plan.error'), 0);
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
     *  cancel subscription Plan
     *
     * @param Request $request
     * @return json
     */
    public function cancelSubscriptionPlan(Request $request)
    {
        $this->validate($request, [
            'subscriptionHistoryId'    => 'required',
            'isCancel' => 'required|in:1,0'
        ]);

        $user = \Auth::guard('api')->user();
        
        if($request->deviceType =='Web'){
            $user = \Auth::guard('web')->user();
        }

        $isCancel = $request->isCancel;

        if(SubscriptionHistories::where(['userId' => $user->id,'id' => $request->subscriptionHistoryId])->update(['isCancel' => $isCancel]))
        {
            return $this->toJson(['isCancel' => $isCancel], trans('api.subscriptions.cancel_subscription_plan_update'), 1);   
        }
        return $this->toJson(null, trans('api.subscriptions.cancel_subscription_plan_error'), 0);   
    }
    
}
