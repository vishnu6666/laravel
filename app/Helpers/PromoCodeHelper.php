<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Model\Promocode;
use App\Model\SubscriptionHistories;

class PromoCodeHelper
{
    
    /**
     * Apply promo code.
     *
     * @param $orderAmount
     * @param $promoCodeaplied
     * @param $planId
     * @return array
     */
    public static function applyPromoCode($orderAmount, $promoCodeaplied, $planId)
    {
        $promoCode = Promocode::selectRaw('id,title,description,promoCode,planName,isApplyMultiTime,discountType,discountAmount,minTotalAmount,maxDiscountAmount,startDate,endDate,planId')->where(['isActive' => 1, 'promoCode' => $promoCodeaplied])->first();
        $authUser = \Auth::guard('api')->user();
        if (!empty($promoCode))
        { 
            $appliedMultiTimeCheck = SubscriptionHistories::where(['userId' => $authUser->id,'appliedPromocode' => $promoCode->promoCode,'planId'=> $planId])->count();

            if($promoCode->isApplyMultiTime == 1 || ( $promoCode->isApplyMultiTime == 0 && $appliedMultiTimeCheck == 0)){
                
                if($promoCode->planId == $planId || $promoCode->planId == null){
                
                    if ( ! empty($promoCode->startDate) && ! empty($promoCode->endDate))
                    {
                        $now = Carbon::now();
                        $startDate = Carbon::parse($promoCode->startDate);
                        $endDate = Carbon::parse($promoCode->endDate);
                        
                        if ($now < $startDate || $now > $endDate)
                        {
                            return [
                                'status' => 0,
                                'msg' => trans('api.promocode.expired'),
                                'data' => [],
                            ];
                        }
                    }
                    
                    if (!empty($promoCode->minTotalAmount))
                    {

                        if ($orderAmount < $promoCode->minTotalAmount)
                        {
                            
                            return [
                                'status' => 0,
                                'msg' => trans('api.promocode.minimum_order_error', ['amount' => $promoCode->minTotalAmount]),
                                'data' => [], 
                            ]; 
                        }
                    }
                    
                    $countDiscount = 0;
                    
                    if ($promoCode->discountType == 'Percentage')
                    {
                        
                        $couponDiscountAmount = $orderAmount * ($promoCode->discountAmount / 100);
                        
                        if ( ! empty($promoCode->maxDiscountAmount))
                        {
                            
                            $maxAmount = $promoCode->maxDiscountAmount;
                            $couponDiscountAmount = ($couponDiscountAmount > $maxAmount) ? $maxAmount : $couponDiscountAmount;
                        }
                    }
                    else
                    {
                        
                        $couponDiscountAmount =$promoCode->discountAmount;
                    }
                    
                    return [
                        'status' => 1,
                        'msg' => trans('api.promocode.success'),
                        'data' => [
                            'id' => $promoCode->id,
                            'promoCode' => $promoCode->promoCode, 
                            'percentage' => $promoCode->discountAmount, 
                            'promoCodeDiscountAmount' => round($couponDiscountAmount, 2),
                            
                        ], 
                    ];
                }

                return [
                    'status' => 0,
                    'msg' => trans('api.promocode.invalid_plan'),
                    'data' => [],
                ];
            }

            return [
                'status' => 0,
                'msg' => trans('api.promocode.already_applied'),
                'data' => [],
            ];
        }
        
        return [
            'status' => 0,
            'msg' => trans('api.promocode.error'),
            'data' =>[],
        ];
    }
    
}
