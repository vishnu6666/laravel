<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Promocode;
use App\Model\UserReferralCodes;
use App\Model\Referralcode;
use App\Helpers\PromoCodeHelper;
use App\Helpers\GroupPromoCodeHelper;
use App\Helpers\ApiHelper;
use Carbon\Carbon;
use App\Model\SubscriptionPlan;


class PromocodeController extends Controller
{
    
    /**
     * Apply Discount code
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    
    public function applyPromocode(Request $request)
    {
        $this->validate($request, [
            'orderAmount' => 'required',
            'promoCode'  => 'required',
            'planId'    => 'required'
        ]);

        $user = \Auth::guard('api')->user();

        if(!empty($request->deviceType)){
            $user = \Auth::guard('web')->user();
        }

        $todayDate = Carbon::now()->format('Y-m-d');
        $promocodeList = GroupPromoCodeHelper::getGroupPromocodes($request->promoCode,'',$user)->first();

        $countReferral = UserReferralCodes:: where(['referralTo' => $user->id,'isApplied' => 0,'isSubscribed' => 1])->count();    
        $referralcodeList = Referralcode::selectRaw('id,IF(numberOfparson <= '.$countReferral.',true,false) as isUnlock,title,description,numberOfparson,percentage,referCode')->where(['isActive' => 1,'referCode' => $request->promoCode])->first();
       
        if(!empty($promocodeList)){

            $discountCode =   GroupPromoCodeHelper::applyPromoCode($request->orderAmount, $request->promoCode, $request->planId, $user);
           
            return $this->toJson($discountCode['data'], $discountCode['msg'], $discountCode['status']);
        }else if(!empty($referralcodeList)){
         
            if($referralcodeList->isUnlock == 1)
            {
                $discountAmount = $request->orderAmount * $referralcodeList->percentage/100;

                if($discountAmount >= $request->orderAmount){
                    
                    $discountAmount = $request->orderAmount;
                }
    
                $discountCode['data'] =  [
                    'id' => $referralcodeList->id,
                    'promoCode' => $referralcodeList->referCode, 
                    'percentage' => $referralcodeList->percentage, 
                    'promoCodeDiscountAmount' => round($discountAmount, 2),
                    'discountType' => 'Percentage',
                    
                ];

                return $this->toJson($discountCode['data'], trans('api.aplydiscount.success'), 1);

            }

            return $this->toJson([], trans('api.aplydiscount.referralcodeNotvalid'), 0);

        }else{
            $resp['status'] = 0;
            return $this->toJson(['data' => []], trans('api.aplydiscount.dicountcodeNotvalid'), 0);  
        }
        
    }

    /**
     * Promocode list
     *
     * @param Request null
     *
     * @return Response Json
     *
    */
    public function getDiscounts()
    {
        $authUser = \Auth::guard('api')->user();

        $promocodeList = GroupPromoCodeHelper::getGroupPromocodes('','',$authUser);
        
        $countReferral = UserReferralCodes:: where(['referralTo' => $authUser->id,'isApplied' => 0,'isSubscribed' => 1])->count();

        $referralcodeList = Referralcode::selectRaw('id,referCode,title,description,numberOfparson,percentage')->where(['isActive' => 1])->get();

        foreach ($referralcodeList as $key => $result) {
            if($countReferral == 0){
                $status = 'Share the app with '.$result['numberOfparson'].' friends to unlock';
            }else{
                $referalcount =  $result['numberOfparson'] - $countReferral;
                $status = 'Share the app with '.$referalcount.' more friends to unlock';
            }
            $referralcodeList[$key]['isUnlock'] = $result['numberOfparson'] <= $countReferral ? true : false;
            $referralcodeList[$key]['status']   = $referralcodeList[$key]['isUnlock'] ? 'You have successfully unlocked the code' : $status;
        }

        if(!empty($promocodeList) || !empty($referralcodeList))
        {
            return $this->toJson(['referralcodeList' => $referralcodeList,'promoCodeList' => $promocodeList], trans('api.discounts.found'), 1);
        }

        return $this->toJson([], trans('api.discounts.not_found'), 0);
    }


    /**
     * Promocode list
     *
     * @param Request null
     *
     * @return Response Json
     *
    */

    public function getPromocode(Request $request)
    {
        $this->validate($request, [
            'planId'    => 'required'
        ]);
        
        $user = \Auth::guard('api')->user();
        $promocodeList = GroupPromoCodeHelper::getGroupPromocodes('',$request->planId,$user);
        if($promocodeList->isNotEmpty())
        {
            return $this->toJson(['promoCodeList' => $promocodeList], trans('api.promocode.found'), 1);
        }

        return $this->toJson([], trans('api.promocode.invalid_plan'), 0);
    }


}
