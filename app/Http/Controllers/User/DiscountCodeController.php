<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Promocode;
use App\Model\UserReferralCodes;
use App\Model\Referralcode;
use App\Helpers\PromoCodeHelper;
use App\Helpers\GroupPromoCodeHelper;
use Carbon\Carbon;
use App\Model\SubscriptionPlan;
use App\Model\PageContent;

class DiscountCodeController extends Controller
{
    /**
     * Promocode list
     *
     * @param Request null
     *
     * @return Response Json
     *
    */
    public function index()
    {
        $authUser = \Auth::guard('web')->user();

        $promocodeList = GroupPromoCodeHelper::getGroupPromocodes('','',$authUser)->first();

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
    
        return view('user.discountCode', [
            'referralcodeList' => $referralcodeList,
            'promocodeList' => $promocodeList
        ]);
    }
}
