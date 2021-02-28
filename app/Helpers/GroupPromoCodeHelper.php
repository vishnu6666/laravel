<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Model\Promocode;
use App\Model\GroupPromocode;
use App\Model\groupPromocodeUser;
use App\Model\SubscriptionHistories;

class GroupPromoCodeHelper
{
    public static function getGroupPromocodes($promoCode ='',$planId='',$user)
    {
        //$user = \Auth::guard('api')->user();
        $todayDate = Carbon::now()->format('Y-m-d');
        $promoCodeList = groupPromocodeUser::selectRaw('groups_promocodes.id as id,groups_promocodes.groupName as title,
                                            groups_promocodes.description as description,groups_promocodes.promoCode as promoCode,
                                            groups_promocodes.discountAmount as discountAmount,
                                            groups_promocodes.planId,'. ApiHelper::getExpiringStatus('groups_promocodes.endDate', 'expireStatus') .',
                                            groups_promocodes.isApplyMultiTime as isApplyMultiTime,
                                            groups_promocodes.discountType as discountType,
                                            groups_promocodes_users.userId as userId')
                                            ->where('groups_promocodes.isActive', 1);

                                            if(!empty($promoCode)){
                                                $promoCodeList = $promoCodeList->where('groups_promocodes.promoCode',$promoCode);
                                            }  

                                            if(!empty($planId)){
                                                $promoCodeList = $promoCodeList->where('groups_promocodes.planId',$planId);
                                            }    
                                            
                                            $promoCodeList = $promoCodeList->where(function($query) use ($todayDate){
                                                $query->where('groups_promocodes.endDate','>=',$todayDate);
                                                $query->orWhereNull('groups_promocodes.planId');
                                            })
                                            ->Join('groups_promocodes',function($join) use($user){
                                                $join->on('groups_promocodes.id','=','groups_promocodes_users.groupId');
                                                $join->where('groups_promocodes_users.userId',$user->id);
                                            })
                                            
                                            ->orderBy('endDate','asc');
                                            
                   
                    $promoCodeList = $promoCodeList->get();
        return $promoCodeList;
    }

    /**
     * Apply promo code.
     *
     * @param $orderAmount
     * @param $promoCodeaplied
     * @param $planId
     * @return array
     */
    public static function applyPromoCode($orderAmount, $promoCodeaplied, $planId, $authUser)
    {
        $promoCode = self::getGroupPromocodes($promoCodeaplied,'',$authUser)->first();
        //$authUser = \Auth::guard('api')->user();
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
                    
                    $countDiscount = 0;
                    
                    if ($promoCode->discountType == 'Percentage')
                    {
                        $couponDiscountAmount = $orderAmount * ($promoCode->discountAmount / 100);
                        $discountAmount = $promoCode->discountAmount;

                    }
                    else
                    {
                        $couponDiscountAmount = $promoCode->discountAmount;
                        $discountAmount = $couponDiscountAmount;
                        
                        if($couponDiscountAmount > $orderAmount){
                            $couponDiscountAmount = $orderAmount;
                            $discountAmount = $couponDiscountAmount;
                        }
                    }
                    
                    return [
                        'status' => 1,
                        'msg' => trans('api.promocode.success'),
                        'data' => [
                            'id' => $promoCode->id,
                            'promoCode' => $promoCode->promoCode, 
                            'percentage' => $discountAmount, 
                            'promoCodeDiscountAmount' => round($couponDiscountAmount, 2),
                            'discountType' => $promoCode->discountType,
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
