<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\UserCard;
use App\Model\UserReferralCodes;
use App\Model\Referralcode;
use App\Model\BrainTreeCardDetail;
use App\Model\SubscriptionHistories;
use App\Model\UserPackagesSubscriptionHistories;
use App\Model\PackagesAsignToGame;
use App\Model\Promocode;
use App\Model\userGameHistory;
use App\Model\PaymentRecord;
use App\Model\SubscriptionPlan;
use App\Helpers\BraintreeHelper;
use App\Helpers\ApiHelper;
use App\Helpers\PromoCodeHelper;
use App\Helpers\CommonHelper;
use Braintree\creditCard;
use Braintree\Gateway;
use Braintree\Braintree_CustomerSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\User;
use App\Helpers\StripeHelper;
use App\Helpers\GroupPromoCodeHelper;

class UserCardsController extends Controller
{
    /**
     * Get user card
     * 
     * @param Request $request
     * 
     * @return Json
    */
    public function getUserCard(Request $request)
    {
        $user = \Auth::guard('api')->user();

        if(!empty($user))
        {
            $configData = config('constant.BRAINTREE_CREDENTIALS');
            $gateway = BraintreeHelper::configureBraintree($configData);
            $cardList = [];
            try 
            {
                $customer = $gateway->customer()->find($user->id);

                foreach($customer->creditCards as $key => $userCard)
                {
                    $cardList[$key] = BraintreeHelper::getCards($userCard, "getCard");
                }

                return $this->toJson(['cards' => $cardList], trans('api.user.card_list'));
            }
            catch (\Exception $e) {
                return $this->toJson([], trans('api.user.card_not_found'), 0);
            }
        }
        else
        {
            return $this->toJson([], trans('api.user.not_available'), 0);
        }
    }

    /**
     * Add User Cards
     *
     * @param Request $request
     * 
     * @return Json
    */
    public function addUserCard(Request $request)
    {
        $this->validate($request, [
            'cardNumber' => 'required',
            'cardholderName' => 'required',
            'expiryDate' => 'required',
            'cvv' => 'required',
            'cardType' => 'required'
        ]);

        $user = \Auth::guard('api')->user();

        if(!empty($user))
        {
            $configData = config('constant.BRAINTREE_CREDENTIALS');
            $gateway = BraintreeHelper::configureBraintree($configData);

            //$getCard = self::getUserCard($request);

            $createCustomer = false; $customer = false; $makeCardDefault = false; 
            
            try {
                $customer = $gateway->customer()->find($user->id);
            }
            catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                \Log::Info($errorMsg);
                $customer = BraintreeHelper::creatCustomer($user);
                $createCustomer = $customer->success;
                $makeCardDefault = true;
            }
           
            if($createCustomer || $customer)
            {
                $addCard = BraintreeHelper::addCard($user, $request, $makeCardDefault);
                if($addCard->success)
                {
                    $cardList = BraintreeHelper::getCards($addCard, "addCard");
                    return $this->toJson(['card' => $cardList], trans('api.card.add_success'));
                }
                else
                {   
                    //$errorMsg = empty($errorMsg) ? $addCard->message : $errorMsg;
                    return $this->toJson([], trans('api.card.add_failed'), 0);
                }
            }
        }
        return $this->toJson([], trans('api.user.not_available'), 0);
    }   

    /**
     * Remove User Cards
     *
     * @param Request $request
     * 
     * @return Json
    */
    public function removeUserCard(Request $request)
    {
        $this->validate($request, [
            'cardId' => 'required'
        ]);

        $user = \Auth::guard('api')->user();
        if(!empty($user))
        {
            $configData = config('constant.BRAINTREE_CREDENTIALS');
            $gateway = BraintreeHelper::configureBraintree($configData);
            $cardList = self::getUserCard($request);
            try {
                $creditCard = $gateway->creditCard()->find($request->cardId);
            }
            catch (\Exception $e) {
                return $this->toJson([], trans('api.card.not_at_payment_gateway'), 0);
            }

            $cardList = self::getUserCard($request);
            if(!empty($request->cardId))
            {
                foreach($cardList->original['result']['cards'] as $key => $cardToken)
                {
                    if($cardToken['cardToken'] == $request->cardId)
                    {
                        try {
                            $removeCard = $gateway->creditCard()->delete($request->cardId);
                            if($removeCard->success)
                            {
                                return self::getUserCard($request);
                            }
                        } 
                        catch (\Exception $e) {
                            return $this->toJson([], trans('api.card.delete.error'), 0);
                        }
                    }
                }
                return $this->toJson(['cards' => []], trans('api.user.card_not_found'), 1);
            }
            else
            {
                return $this->toJson([], trans('api.card.delete.error', 0));
            }
        }

        return $this->toJson([], trans('api.user.not_available'), 0);
    }

    /**
     * Braintree payment gateway create transaction
     * 
     * @param float $offerPrice
     * @param string $paymentMethodNonce
     * 
     * @return Response array
     *
    */
    public function braintreeCreateTransaction(Request $request)
    {
        $this->validate($request, [
            'cardId'    => 'required',
            'planId'    => 'required',
            'sportPackageId' => 'required',
            'planType'  => 'required|in:Monthly,Weekly',
            // 'discountCode'=> 'string',
        ]);

        
        $planData = SubscriptionPlan::where('id',$request->planId)->first();
        $user = \Auth::guard('api')->user();

        if(empty($request->discountCode)){
            $paymentType ='onlyPlan';
        }else{
            $todayDate = Carbon::now()->format('Y-m-d');
            $promocodeList = Promocode::selectRaw('id,promoCode,discountAmount,planId,isApplyMultiTime,discountType')->where(['isActive'=> 1,'promoCode' => $request->discountCode])->first();
            $countReferral = UserReferralCodes:: where(['referralTo' => $user->id,'isApplied' => 0,'isSubscribed' => 1])->count();    
            $referralcodeList = Referralcode::selectRaw('id,IF(numberOfparson <= '.$countReferral.',true,false) as isUnlock,title,description,numberOfparson,percentage,referCode')->where(['isActive' => 1,'referCode' => $request->discountCode])->first();
            if(!empty($promocodeList)){
                $paymentType ='applyPromoCode';
            }elseif(!empty($referralcodeList)){
                $paymentType ='applyReferralCode';
            }else{
                $paymentType ='codeNotValid';
            }
        }

        if($request->planType == 'Monthly'){
            $planAmount = round($planData->planMonthlyPrice,2);
        }else if($request->planType == 'Weekly'){
            $planAmount = round($planData->planWeeklyPrice,2);
        }
    
        $braintreeCredential = config('constant.BRAINTREE_CREDENTIALS');

        switch ($paymentType) {

            case "onlyPlan":
                if(!empty($request->cardId))
                {
                    if($planData){
                        $paymentCred['amount'] = $planAmount;

                        $paymentCred['paymentMethodToken'] = $request->cardId;
                        $paymentData = BraintreeHelper::createTransaction($braintreeCredential, $paymentCred);
        
                        if ($paymentData->success)
                        {
                            $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => null ,'appliedPromocode' => null,'discountAmount' => 0.00];
                          
                            $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                            
                            if($paymentHistoryData['status'] == 1){

                                $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();
                                
                                
                                if(!empty($referralTocount)){
                                   
                                    UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                }

                                \DB::commit();
                                return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                            }else{
                                DB::rollback();
                                $resp['status'] = 0;
                                $resp['message'] = $paymentData->message;
                                return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                            }
                        }else{
                            $resp['status'] = 0;
                            $resp['message'] = $paymentData->message;
                            return $this->toJson(['payment' => $resp], $paymentData->message, 0);
                        }
                    }
                    $resp['status'] = 0;
                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                }
              break;

            case "applyPromoCode":
                $isVerifyDiscount = false;
                if(!empty($promocodeList->id)){
                    
                    if($promocodeList){
                        $promocodeStatus = PromoCodeHelper::applyPromoCode($planAmount, $promocodeList->promoCode, $request->planId, $user);

                        if($promocodeStatus['status'] === 1){
                            $isVerifyDiscount = true;   
                        }
                    }
                }

                if($isVerifyDiscount == true){
                    if(!empty($request->cardId))
                    {
                        $paymentCred['amount'] = $planAmount - $promocodeStatus['data']['promoCodeDiscountAmount'];
                        $paymentCred['paymentMethodToken'] = $request->cardId;
                        $paymentData = BraintreeHelper::createTransaction($braintreeCredential, $paymentCred);

                        if ($paymentData->success)
                        {
                            $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => $promocodeList->id ,'appliedPromocode' => $promocodeList->promoCode,'discountAmount' => $promocodeStatus['data']['promoCodeDiscountAmount']];
    
                            $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                            
                            if($paymentHistoryData['status'] == 1){
                                
                                $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();

                                if(!empty($referralTocount)){
                                   
                                    UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                }

                                \DB::commit();
                                return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                            }else{
                                DB::rollback();
                                return $paymentHistoryData;
                            }
    
                        }else{
                            
                            $resp['status'] = 0;
                            $resp['message'] = $paymentData->message;
                            return $this->toJson(['payment' => $resp], $paymentData->message, 0);
                        }
                    }
                }
                $resp['status'] = 0;
                $resp['message'] = $promocodeStatus['msg'];
                return $this->toJson(['payment' => $resp], $promocodeStatus['msg'], 0);
              break;

            case "applyReferralCode":
 
                if(!empty($referralcodeList)){

                    if($referralcodeList->isUnlock == 1)
                    {
                        $discountAmount = $planAmount * $referralcodeList->percentage/100;
                        $payAmount = $planAmount - $discountAmount;

                        if($payAmount >= 0){
                            
                            if($referralcodeList->percentage <= 99){
                                
                                if(!empty($request->cardId))
                                {
                                    $paymentCred['amount'] = number_format($payAmount,2);
                                    $paymentCred['paymentMethodToken'] = $request->cardId;
                                    $paymentData = BraintreeHelper::createTransaction($braintreeCredential, $paymentCred);

                                    if ($paymentData->success)
                                    {
                                        $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => null ,'referralcode' => $referralcodeList->referCode, 'referralcodeId' => $referralcodeList->id, 'discountAmount' => $discountAmount];
                                        
                                        $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                                        
                                        if($paymentHistoryData['status'] == 1){

                                            $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();

                                            if(!empty($referralTocount)){
                                                UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                            }
                                            
                                            \DB::update(\DB::raw("UPDATE user_referral_codes set isApplied = 1 WHERE referralTo = $user->id LIMIT $referralcodeList->numberOfparson"));
                                         
                                            \DB::commit();
                                            return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                                        }else{
                                            DB::rollback();
                                            return $paymentHistoryData;
                                        }

                                    }else{
                                        
                                        $resp['status'] = 0;
                                        $resp['message'] = $paymentData->message;
                                        return $this->toJson(['payment' => $resp], $paymentData->message, 0);   
                                    }
                                }
                            }else{
                                
                                $paymentData = '';
    
                                $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => null ,'referralcode' => $referralcodeList->referCode, 'referralcodeId' => $referralcodeList->id, 'discountAmount' => $discountAmount];
                                $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                        
                                if($paymentHistoryData['status'] == 1){

                                    $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();

                                    if(!empty($referralTocount)){
                                    
                                        UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                    }

                                    \DB::update(\DB::raw("UPDATE user_referral_codes set isApplied = 1 WHERE referralTo = $user->id LIMIT $referralcodeList->numberOfparson"));

                                    \DB::commit();
                                    return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                                }else{
                                    DB::rollback();
                                    return $paymentHistoryData;
                                }
                            }
                            
                       }else{
                        $resp['status'] = 0;
                        return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                       }
                        
                    }else{
                        $resp['status'] = 0;
                        return $this->toJson(['payment' => $resp], trans('api.braintree.referralcodeNotvalid'), 0);
                    }
                   
                }
                case "codeNotValid":
                    {
                        $resp['status'] = 0;
                        return $this->toJson(['payment' => $resp], trans('api.braintree.dicountcodeNotvalid'), 0);   
                    }
                
                $resp['status'] = 0;
                return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
              break;
          }
    }


    // not use in  - tips 

    /**
     * Set card as default
     *
     * @param Request $request
     * 
     * @return Json
    */
    public function setCardAsDefault(Request $request)
    {

        $this->validate($request, [
            'card_id' => 'required'
        ]);

        $user = \Auth::guard('api')->user();

        if(!empty($user))
        {
            $configData = config('constant.BRAINTREE_CREDENTIALS');
            $gateway = BraintreeHelper::configureBraintree($configData);

            try {
                $updateResult = $gateway->paymentMethod()->update(
                    $request->card_id,
                    [
                        'options' => [
                        'makeDefault' => true
                        ]
                    ]
                );
            }
            catch (\Exception $e) {
                return $this->toJson([], trans('api.card.not_at_payment_gateway'), 0);
            }

            if($updateResult->success)
            {
                return self::getUserCard($request);
            }
            
            return $this->toJson([], trans('api.card.setAsDefault.error'), 0);
        }

        return $this->toJson([], trans('api.user.not_available'), 0);
    }

    /**
     * Get particular card for payment
     *
     * @param Request $request
     * 
     * @return Json
    */
    public function getCardPayment(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'card_id' => 'required',
            'subcriptionPlanType' => 'required',
        ]);
        $user = \Auth::guard('api')->user();

        //$user = PromoCodeHelper::getUserById($user->user_id);
        if(!empty($user))
        {
            $amount = PromoCodeHelper::select('subcriptionPlanPrice')
                                        ->where('subcriptionPlanType', $request->subcriptionPlanType)->first();
            $request->amount = $amount->subcriptionPlanPrice;
            $response = self::braintreeCreateTransaction($request);
            if($response["status"])
            {
                //$user = PromoCodeHelper::getUserById($user->user_id);
                $user->shareMsg = trans('api.user.share_msg',['promoCode' => $user->invite_code ]);
                return $this->toJson(['user' => $user], trans('api.payment.payment_done'), 1);
            }

            return $this->toJson(['user' => []], trans($response["message"]), 0);
            //return $this->toJson([], trans('api.payment.payment_error'), 0);
        }
        return $this->toJson([], trans('api.payment.payment_error'), 0);
    }

    /**
     * Get Default User Cards
     *
     * Get Method
     * @return mixed
    */
    public function getDefaultUserCard(Request $request)
    {
        //$user = ApiUserBraintreeHelper::getUserById($request->user_id);
        $user = \Auth::guard('api')->user();
        if(!empty($user))
        {
            $cardData = UserCard::selectRaw('id, card_number, card_holder, expiry_date, cvv, isDefault, card_type')
            ->where('user_id', $user->user_id)->where('isDefault', 1)->get();

            return $this->toJson(['cards' => $cardData], trans('api.card.get_default_success'));
        }

        return $this->toJson([], trans('api.user.not_available'), 0);
    }

    /**
     * Get user payment history
     *
     * Get Method
     * 
     * @return Json
    */
    public function getPaymentHistory(Request $request)
    {
        //$user = ApiUserBraintreeHelper::getUserById($request->user_id);
        $user = \Auth::guard('api')->user();
        if(!empty($user))
        {
            $dateTimeFormat = config('constant.DATE_TIME_FORMAT');
            $dateFormat = config('constant.DATE_FORMAT');
            $payHistory = PaymentRecord::selectRaw('userId, amount as subcriptionPlanPrice,
            IF(planType = "Batch","38c per search", "Monthly") as planName,
            IF(planType = "Batch","Charged end of every week or every 5 searches", "Enjoy unlimited searches every month") as planDescription, 
            planType as subcriptionPlanType, expiryDate, 
            IF(paymentType = "CREDIT_CARD","Credit card", "Debit card") as paymentType,
            paymentStatus, transactionId, 
            DATE_FORMAT(expiryDate,"'. $dateFormat. '") AS expiryDate,
            DATE_FORMAT(created_at,"'. $dateTimeFormat. '") AS transactionDate')
            ->where('userId', $user->user_id)->orderBy('id', 'DESC')->get();

            return $this->toJson(['userPaymentHistory' =>$payHistory], trans('api.payment.paymet_history'));
        }

        return $this->toJson([], trans('api.payment.not_found_payment'), 0);
    }




// *************** PAYMENT BY STRIPE PAYMENT GATEWAY ***************** //

    /**
     * Add User Cards
     *
     * @param Request $request
     * 
     * @return Json
    */
    public function createUserCard(Request $request)
    {
        $this->validate($request, [
            'cardNumber' => 'required',
            'cardholderName' => 'required',
            'expiryDate' => 'required',
            'cvv' => 'required',
            'cardType' => 'required'
        ]);

        $user = \Auth::guard('api')->user();

        if($request->deviceType =='Web'){
            $user = \Auth::guard('web')->user();
        }

        if(!empty($user))
        {
            if(empty($user->stripeId))
            {
                $customer = StripeHelper::creatCustomer($user);
                $user = User::find($user->id);
            }
            

            try {

                $addCard = StripeHelper::addCard($user, $request);

                if($addCard)
                {
                    $cardList = StripeHelper::getCards($addCard,$user);

                    $userCard = new UserCard();
                    $userCard->userId = $user->id;
                    $userCard->cardToken = $cardList['cardToken'];
                    $userCard->cardHolderName = $cardList['cardHolderName'];
                    $userCard->last4 = $cardList['last4'];
                    $userCard->cardType = $cardList['cardType'];
                    $userCard->expiryDate = $cardList['expiryDate'];
                    $userCard->isDefault = $cardList['cardDefault'];
                    $userCard->cardImage = $cardList['cardImage'];
                    $userCard->save();

                    if(is_null($user->cardToken)){
                        $user->cardToken = $cardList['cardToken'];
                        $user->save();
                    }
                    return $this->toJson(['card' => $cardList], trans('api.card.add_success'));
                }
            } catch(\Stripe\Exception\CardException $e) {
                $errorMsg = $e->getMessage();
                \Log::Info($errorMsg);
            return $this->toJson([], trans('api.card.add_failed'), 0);
            } catch (\Stripe\Exception\RateLimitException $e) {
                return $this->toJson([], 'Something wrong , try again', 0);
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                return $this->toJson([], 'Something wrong , try again', 0);
            } catch (\Stripe\Exception\AuthenticationException $e) {
                return $this->toJson([], 'Something wrong , try again', 0);
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                return $this->toJson([], 'Something wrong , try again', 0);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return $this->toJson([], 'Something wrong , try again', 0);
            } catch (Exception $e) {
                return $this->toJson([], 'Something wrong , try again', 0);
            }

        }
        return $this->toJson([], trans('api.user.not_available'), 0);
    } 

    /**
     * Get user card
     * 
     * @param Request $request
     * 
     * @return Json
    */
    public function getUserCards(Request $request)
    {
        $user = \Auth::guard('api')->user();
        
        if($request->deviceType =='Web'){
            $user = \Auth::guard('web')->user();
        }
        
        if(!empty($user))
        {
                //$cardList = StripeHelper::getAllCards($user);
                $cardListData = StripeHelper::getUsercardsData($user);

                if($cardListData->isNotEmpty()){
                    return $this->toJson(['cards' => $cardListData], trans('api.user.card_list'),1);
                }
                return $this->toJson([], trans('api.user.card_not_found'), 0);
        }
        else
        {
            return $this->toJson([], trans('api.user.not_available'), 0);
        }
    }

    /**
     * Remove User Cards
     *
     * @param Request $request
     * 
     * @return Json
    */
    public function deleteUserCard(Request $request)
    {
        $this->validate($request, [
            'cardId' => 'required'
        ]);

        $user = \Auth::guard('api')->user();

        if($request->deviceType =='Web'){
            $user = \Auth::guard('web')->user();
        }

        if(!empty($user))
        {
            try {
                $request['customer'] = $user['stripeId'];
                $request['id'] = $request['cardId'];
                $cardList = StripeHelper::getCards($request,$user);
            }
            catch (\Exception $e) {
                return $this->toJson([], trans('api.card.not_at_payment_gateway'), 0);
            }

            if(!empty($cardList))
            {
                if($cardList['cardToken'] == $request['cardId'])
                {
                    $deleteCard = StripeHelper::deleteCard($request['cardId'],$user['stripeId']);
                    if($deleteCard->deleted)
                    {
                        if($request['cardId'] == $user->cardToken){
                                UserCard::where(['userId' => $user->id,'isDefault' => 0])
                                                ->orderBy('id','desc')
                                                ->take(1)
                                                ->update(['isDefault' => 1]);
                        }

                        UserCard::where(['userId' => $user->id ,'cardToken' => $request['cardId']])->delete();
                        //$cardList = StripeHelper::getAllCards($user);
                        $cardListData = StripeHelper::getUsercardsData($user);

                        if($cardListData->isNotEmpty()){

                        if($request['cardId'] == $user->cardToken){
                            $cardToken = UserCard::where(['userId' => $user->id,'isDefault' => 1])->first();
                            $user->cardToken = $cardToken->cardToken;
                            $user->save();
                        }
                            return $this->toJson(['cards' => $cardListData], trans('api.user.card_list'),1);
                        }
                        
                        $user->cardToken = NULL;
                        $user->save();
                        
                        return $this->toJson([], trans('api.user.card_not_found'), 0);

                    }
                    return $this->toJson([], trans('api.card.delete.error'), 0);
                }
                return $this->toJson([], trans('api.card.delete.error'), 0); 
            }
            return $this->toJson(['cards' => []], trans('api.user.card_not_found'), 0);

        }else{
            return $this->toJson(['cards' => []], trans('api.user.not_available'), 0);
        }
    }

    //     return $this->toJson([], trans('api.user.not_available'), 0);
    // }

    /**
     * Set Default User Card
     *
     * @param Request $request
     * 
     * @return Json
    */
    public function setDefaultUserCard(Request $request)
    {
        $this->validate($request, [
            'cardId' => 'required'
        ]);

        $user = \Auth::guard('api')->user();

        if($request->deviceType =='Web'){
            $user = \Auth::guard('web')->user();
        }

        if(!empty($user))
        {
            try {
                $request['customer'] = $user['stripeId'];
                $request['id'] = $request['cardId'];
                $cardList = StripeHelper::getCards($request,$user);
            }
            catch (\Exception $e) {
                return $this->toJson([], trans('api.card.not_at_payment_gateway'), 0);
            }

            if(!empty($cardList))
            {
                if($cardList['cardToken'] == $request['cardId'])
                {
                        UserCard::where('userId', $user->id)->update(['isDefault' => 0]);
                        UserCard::where(['userId' => $user->id,'cardToken' => $request['cardId']])->update(['isDefault' => 1]);
                        $user->cardToken = $request['cardId'];
                        if($user->save())
                        {
                            $cardListData = StripeHelper::getUsercardsData($user);

                            if($cardListData->isNotEmpty()){
                                return $this->toJson(['cards' => $cardListData], trans('api.user.card_list'),1);
                            }
                            return $this->toJson([], trans('api.user.card_not_found'), 0);

                        }
                        return $this->toJson([], trans('api.card.delete.error'), 0);
                }
                return $this->toJson(['cards' => []], trans('api.user.card_not_found'), 0);
            }
            else
            {
                return $this->toJson(['cards' => []], trans('api.user.card_not_found'), 0);
            }
        }

        return $this->toJson([], trans('api.user.not_available'), 0);
    }

    /**
     * stripe payment gateway create transaction
     * 
     * @return Response array
     *
    */
    public function payment(Request $request)
    {
        //dd($request->all());

        $this->validate($request, [
            'cardId'    => 'required_unless:deviceType,Web',
            'planId'    => 'required',
            'sportPackageId' => 'required',
            'planType'  => 'required|in:Monthly,Weekly',
        ]);
        
        $planData = SubscriptionPlan::where('id',$request->planId)->first();

        $user = \Auth::guard('api')->user();

        if($request->deviceType =='Web'){
            $user = \Auth::guard('web')->user();
        }

        if(empty($request->discountCode)){
            $paymentType ='onlyPlan';
        }else{
            $todayDate = Carbon::now()->format('Y-m-d');
            $promocodeList = GroupPromoCodeHelper::getGroupPromocodes($request->discountCode,'',$user)->first();
            $countReferral = UserReferralCodes:: where(['referralTo' => $user->id,'isApplied' => 0,'isSubscribed' => 1])->count();    
            $referralcodeList = Referralcode::selectRaw('id,IF(numberOfparson <= '.$countReferral.',true,false) as isUnlock,title,description,numberOfparson,percentage,referCode')->where(['isActive' => 1,'referCode' => $request->discountCode])->first();
            if(!empty($promocodeList)){
                $paymentType ='applyPromoCode';
            }elseif(!empty($referralcodeList)){
                $paymentType ='applyReferralCode';
            }else{
                $paymentType ='codeNotValid';
            }
        }
        //$request->planType = 'Weekly';
        if($request->planType == 'Monthly'){
            $planAmount = round($planData->planMonthlyPrice,2);
        }else if($request->planType == 'Weekly'){
            $planAmount = round($planData->planWeeklyPrice,2);
        }

        $request['customer'] = $user['stripeId'];
        $request['planName'] = $planData->planName;

        switch ($paymentType) {

            case "onlyPlan":
                if(!empty($request->cardId))
                {
                    if($planData){
                        $request['amount']  = $planAmount;
                        $paymentData = StripeHelper::createPayment($request);
       
                        if (!empty($paymentData))
                        {
                            $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => null ,'appliedPromocode' => null,'discountAmount' => 0.00];
                          
                            $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                            
                            if($paymentHistoryData['status'] == 1){

                                $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();
                                
                                
                                if(!empty($referralTocount)){
                                   
                                    UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                }

                                \DB::commit();
                                
                                if($request->deviceType =='Web'){
                                    
                                    $resp['status'] = 1;
                                    $resp['message'] = 'Payment success';
                                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_success'), 1);
                                    //return redirect()->route('success')->with('success',trans('api.braintree.transaction_success'));
                                }else{
                                    return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                                }

                            }else{
                                DB::rollback();
                                $resp['status'] = 0;
                                $resp['message'] = 'Something wrong, please try again';

                                if($request->deviceType =='Web'){
                                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                                    //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_failed_for_wrong_amount'));
                                }else{
                                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                                }
                            }
                        }else{
                            $resp['status'] = 0;
                            $resp['message'] = 'There is some problem with payment, please check the card details';
                            
                            if($request->deviceType =='Web'){
                                return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                                //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_fail'));
                            }else{
                                return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                            }
                        }
                    }
                    $resp['status'] = 0;
                    
                    if($request->deviceType =='Web'){
                        return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                        //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_failed_for_wrong_amount'));
                    }else{
                        return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                    }
                }
              break;

            case "applyPromoCode":
                $isVerifyDiscount = false;
                if(!empty($promocodeList->id)){
                    if($promocodeList){
                        $promocodeStatus =   GroupPromoCodeHelper::applyPromoCode($planAmount, $promocodeList->promoCode, $request->planId, $user);
                    
                        if($promocodeStatus['status'] === 1){
                            $isVerifyDiscount = true;   
                        }
                    }
                }

                if($isVerifyDiscount == true){
                    if(!empty($request->cardId))
                    {
                        if($promocodeStatus['data']['promoCodeDiscountAmount'] == $planAmount){
                            $paymentData = '';
                            $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => $promocodeList->id ,'appliedPromocode' => $promocodeList->promoCode,'discountAmount' => $promocodeStatus['data']['promoCodeDiscountAmount']];
            
                            $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                            if($paymentHistoryData['status'] == 1){

                                $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();
                                
                                if(!empty($referralTocount)){
                                   
                                    UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                }

                                \DB::commit();
                                if($request->deviceType =='Web'){
                                    $resp['status'] = 1;
                                    $resp['message'] = 'Payment success';
                                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_success'), 1);
                                    //return redirect()->route('success')->with('success',trans('api.braintree.transaction_success'));
                                }else{
                                    return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                                }
                            }else{
                                DB::rollback();
                                $resp['status'] = 0;
                                $resp['message'] = 'Something wrong, please try again';

                                if($request->deviceType =='Web'){
                                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                                    //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_failed_for_wrong_amount'));
                                }else{
                                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                                }
                                
                            }

                        }else{
                            $request['amount']  = $planAmount - $promocodeStatus['data']['promoCodeDiscountAmount'];
                        
                            $paymentData = StripeHelper::createPayment($request);

                            if (!empty($paymentData))
                            {
                                $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => $promocodeList->id ,'appliedPromocode' => $promocodeList->promoCode,'discountAmount' => $promocodeStatus['data']['promoCodeDiscountAmount']];
                           
                                $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                                
                                if($paymentHistoryData['status'] == 1){

                                $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();
                                
                                if(!empty($referralTocount)){
                                   
                                    UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                }

                                    \DB::commit();
                                    if($request->deviceType =='Web'){
                                        $resp['status'] = 1;
                                        $resp['message'] = 'Payment success';
                                        return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_success'), 1);
                                        //return redirect()->route('success')->with('success',trans('api.braintree.transaction_success'));
                                    }else{
                                        return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                                    }
                                }else{
                                    DB::rollback();
                                    $resp['status'] = 0;
                                    $resp['message'] = 'Something wrong, please try again';

                                    if($request->deviceType =='Web'){
                                        return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                                        //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_failed_for_wrong_amount'));
                                    }else{
                                        return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                                    }
                                    
                                }
        
                            }else{
                                
                                $resp['status'] = 0;
                                $resp['message'] = 'There is some problem with payment, please check the card details';
                                
                                if($request->deviceType =='Web'){
                                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                                    //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_fail'));
                                }else{
                                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                                }
                            }
                        }
                    }
                }
                $resp['status'] = 0;
                $resp['message'] = $promocodeStatus['msg'];

                if($request->deviceType =='Web'){
                    return $this->toJson(['payment' => $resp], $promocodeStatus['msg'], 0);
                    //return redirect()->route('userDashboard')->with('error',$promocodeStatus['msg']);
                }else{
                    return $this->toJson(['payment' => $resp], $promocodeStatus['msg'], 0);
                }
              break;

            case "applyReferralCode":
 
                if(!empty($referralcodeList)){

                    if($referralcodeList->isUnlock == 1)
                    {
                        $discountAmount = $planAmount * $referralcodeList->percentage/100;
                        $payAmount = $planAmount - $discountAmount;

                        if($payAmount >= 0){
                            
                            if($referralcodeList->percentage <= 99){
                                
                                if(!empty($request->cardId))
                                {
                                    $request['amount']  = $payAmount;
                                    $paymentData = StripeHelper::createPayment($request);

                                    if (!empty($paymentData))
                                    {
                                        $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => null ,'referralcode' => $referralcodeList->referCode, 'referralcodeId' => $referralcodeList->id, 'discountAmount' => $discountAmount];
                                        
                                        $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                                        
                                        if($paymentHistoryData['status'] == 1){

                                            $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();

                                            if(!empty($referralTocount)){
                                                UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                            }
                                            
                                            \DB::update(\DB::raw("UPDATE user_referral_codes set isApplied = 1 WHERE referralTo = $user->id AND isApplied = 0 LIMIT $referralcodeList->numberOfparson"));
                                         
                                            \DB::commit();

                                            if($request->deviceType =='Web'){
                                                $resp['status'] = 1;
                                                $resp['message'] = 'Payment success';
                                                return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_success'), 1);
                                                //return redirect()->route('success')->with('success',trans('api.braintree.transaction_success'));
                                            }else{
                                                return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                                            }
                                        }else{
                                            DB::rollback();
                                            $resp['status'] = 0;
                                            $resp['message'] = 'There is some problem with payment, please try again';
                                            //return $paymentHistoryData;
                                            if($request->deviceType =='Web'){
                                                return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                                                //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_fail'));
                                            }else{
                                                return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                                            }
                                        }

                                    }else{
                                        
                                        $resp['status'] = 0;
                                        $resp['message'] = 'There is some problem with payment, please check the card details';

                                        if($request->deviceType =='Web'){
                                            return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                                            //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_fail'));
                                        }else{
                                            return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                                        } 
                                    }
                                }
                            }else{
                                
                                $paymentData = '';
    
                                $planAmountData = ['planAmount' => $planAmount, 'promocodeId' => null ,'referralcode' => $referralcodeList->referCode, 'referralcodeId' => $referralcodeList->id, 'discountAmount' => $discountAmount];
                                $paymentHistoryData = ApiHelper::createTransactionHistory($paymentData,$request,$planData,$planAmountData,$paymentType);
                        
                                if($paymentHistoryData['status'] == 1){

                                    $referralTocount = UserReferralCodes::where('referralFrom',$user->id)->where('isSubscribed',0)->first();

                                    if(!empty($referralTocount)){
                                    
                                        UserReferralCodes::where('id',$referralTocount->id)->update(['isSubscribed' => 1]);
                                    }

                                    \DB::update(\DB::raw("UPDATE user_referral_codes set isApplied = 1 WHERE referralTo = $user->id AND isApplied = 0 LIMIT $referralcodeList->numberOfparson"));

                                    \DB::commit();
                                    
                                    if($request->deviceType =='Web'){
                                        $resp['status'] = 1;
                                        $resp['message'] = 'Payment success';
                                        return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_success'), 1);
                                        //return redirect()->route('success')->with('success',trans('api.braintree.transaction_success'));
                                    }else{
                                        return $this->toJson(['payment' => $paymentHistoryData], trans('api.braintree.transaction_success'), 1);
                                    }
                                }else{
                                    DB::rollback();
                                    $resp['status'] = 0;
                                    $resp['message'] = 'There is some problem with payment, please try again';
                                    //return $paymentHistoryData;
                                    if($request->deviceType =='Web'){
                                        return $this->toJson(['payment' => $resp], trans('api.braintree.dicountcodeNotvalid'), 0);
                                        //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_fail'));
                                    }else{
                                        return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_fail'), 0);
                                    }
                                }
                            }
                            
                       }else{
                        $resp['status'] = 0;

                        if($request->deviceType =='Web'){
                            return $this->toJson(['payment' => $resp], trans('api.braintree.dicountcodeNotvalid'), 0);
                            //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_failed_for_wrong_amount'));
                        }else{
                            return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                        }

                       }
                        
                    }else{
                        $resp['status'] = 0;

                        if($request->deviceType =='Web'){
                            return $this->toJson(['payment' => $resp], trans('api.braintree.dicountcodeNotvalid'), 0);
                            //return redirect()->route('userDashboard')->with('error',trans('api.braintree.referralcodeNotvalid'));
                        }else{
                            return $this->toJson(['payment' => $resp], trans('api.braintree.referralcodeNotvalid'), 0);
                        }
                    }
                   
                }
                case "codeNotValid":
                    {
                        $resp['status'] = 0;
                        if($request->deviceType =='Web'){
                            return $this->toJson(['payment' => $resp], trans('api.braintree.dicountcodeNotvalid'), 0);
                           // return redirect()->route('userDashboard')->with('error',trans('api.braintree.dicountcodeNotvalid'));
                        }else{
                            return $this->toJson(['payment' => $resp], trans('api.braintree.dicountcodeNotvalid'), 0);
                        } 
                    }
                
                $resp['status'] = 0;
                if($request->deviceType =='Web'){
                    return $this->toJson(['payment' => $resp], trans('api.braintree.dicountcodeNotvalid'), 0);
                    //return redirect()->route('userDashboard')->with('error',trans('api.braintree.transaction_failed_for_wrong_amount'));
                }else{
                    return $this->toJson(['payment' => $resp], trans('api.braintree.transaction_failed_for_wrong_amount'), 0);
                }

              break;
          }
    }
}
