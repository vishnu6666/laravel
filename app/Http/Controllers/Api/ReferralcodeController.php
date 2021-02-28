<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referralcode;
use App\Model\UserReferralCodes;

class ReferralcodeController extends Controller
{
    /**
     * Referralcode list
     *
     * @param Request null
     *
     * @return Response Json
     *
    */
    public function getReferralcode(Request $request)
    {   
        $authUser = \Auth::guard('api')->user();
        // $countReferral = UserReferralCodes:: where(['referralFrom' => $authUser->id,'isApplied' => 0,'isSubscribed' => 1])->count();
        
        // $referralcodeList = Referralcode::selectRaw('id,IF(numberOfparson <= '.$countReferral.',true,false) as isUnlock,title,description,numberOfparson,percentage')->where(['isActive' => 1])->get();
        // if($referralcodeList->isNotEmpty())
        // {
        //     return $this->toJson(['referralcodeList' => $referralcodeList], trans('api.referralcode.found'), 1);
        // }

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

        if(!empty($referralcodeList))
        {
            return $this->toJson(['referralcodeList' => $referralcodeList], trans('api.referralcode.found'), 1);
        }

        return $this->toJson([], trans('api.referralcode.not_found'), 0);
    }
}
