<?php

namespace App\Helpers;

use Braintree;
use Braintree\CredentialsParser;
use Braintree\Gateway;
use Braintree\Http;
use Braintree\Merchant;
use Braintree\Test\MerchantAccount;
use Test;
use Test\Setup;
use Test\Integration\OAuthTest;
use Test\Braintree\OAuthTestHelper;
use Test\Integration\MerchantAccountTest;
use App\Models\PaymentRecord;

class BraintreeHelper
{

    /*
    |--------------------------------------------------------------------------
    |  Braintree Helper
    |--------------------------------------------------------------------------
    |
    | This helper is used for payment related controllers.
    | In this helper common methods written for Braintree payment gateway that is used at multiple locations.
    | 
    */

    /**
     * This method is used for Generate Token.
     *
     * @param array $configData 
     *
     * @return string token
    */
    public static function generateToken($configData)
    {
        $gateway = self::configureBraintree($configData);

        return $gateway->clientToken()->generate();
    }
    
    
    /**
     * This method is used for Get transaction.
     *
     * @param array $configData
     * @param object $request
     *
     * @return object transaction data
    */
    public static function getTransaction($configData, $request)
    {
        $gateway = self::configureBraintree($configData);

        return $gateway->transaction()->find($request->transactionId);
    }

    /**
     * This method is used for Create transaction.
     *
     * @param array $configData
     * @param array $paymentCred
     *
     * @return array transaction sale
    */
    public static function createTransaction($configData, $paymentCred)
    {
        $gateway = self::configureBraintree($configData);
        return $gateway->transaction()->sale([
            'amount' => $paymentCred['amount'],
            'paymentMethodToken' => $paymentCred['paymentMethodToken'],
            'options' => [
                'submitForSettlement' => True,
            ],
            //'merchantAccountId' => 'USD2'
        ]);
    }
    
    /**
     * This method is used for Configure Braintree.
     *
     * @param array $configData
     *
     * @return array gateway
    */
    public static function configureBraintree($configData)
    {
        $gateway = new Gateway([
            'environment' => 'sandbox',
            'merchantId' => $configData['merchantId'],
            'publicKey' => $configData['publicKey'],
            'privateKey' => $configData['privateKey'],
            'merchantAccountId' => 'Australian_Dollar',
            //'merchantAccountId' => 'USD2'
        ]);

        return $gateway;
    }
    
    /**
     * This method is used for refund amount.
     *
     * @param array $configData
     * @param array $param
     *
     * @return array refund transaction
    */
    public static function refundAmount($param)
    {
        $configData = config('constant.BRAINTREE_CREDENTIALS');
        $gateway = self::configureBraintree($configData);
        
        return $gateway->transaction()->refund($param['paymentTransactionId'], $param['amount']);

    }

    /**
     * This method is used for create customer at braintree payment gateway.
     *
     * @param array $user
     * @param string $nonce
     *
     * @return boolean customer create status
    */
    public static function creatCustomer($user)
    {
        $configData = config('constant.BRAINTREE_CREDENTIALS');
        $gateway = self::configureBraintree($configData);

        $result = $gateway->customer()->create([
            'id' => $user->id,
            'firstName' => $user->name,
            // 'firstName' => $user->first_name,
            // 'lastName' => $user->last_name,
            'company' => 'Vttipsh Tips',
            'email' => $user->email,
            'phone' => $user->mobileNumber,
        ]);

        return $result;
    }

    /**
     * This method is used for add card at payment gateway in particular user.
     *
     * @param array $user
     * @param array $request
     * @param string $makeDefault
     *
     * @return boolean customer create status
    */
    public static function addCard($user, $request, $makeDefault)
    {
        $configData = config('constant.BRAINTREE_CREDENTIALS');
        $gateway = self::configureBraintree($configData);

        $result = $gateway->creditCard()->create([
            'customerId' => $user->id,
            'cardholderName' => $request->cardholderName,
            'number' => (int) str_replace(' ', '', $request->cardNumber),
            'expirationDate' => $request->expiryDate,
            'cvv' => $request->cvv,
            'options' => [
                'failOnDuplicatePaymentMethod' => false,
                'makeDefault' => $makeDefault,
                'verifyCard' => true,
              ]
        ]);

        return $result;
    }

    /**
     * This method is used for get all cards of particular user.
     *
     * @param array $userCard
     *
     * @return object $cardList
    */
    public static function getCards($userCard, $key)
    {
        switch($key){
            case 'getCard' :
                $userCard = $userCard;
                break;
            case 'addCard' : 
                $userCard = $userCard->creditCard;
                break;
            case 'setCard' : 
                $userCard = $userCard->paymentMethod;
                break;
        }
        if($userCard->cardholderName != null)
        {
            $creditCardDetail['cardHolderName'] = $userCard->cardholderName;
        }
        else
        {
            $creditCardDetail['cardHolderName'] = null;
        }
        $creditCardDetail['last4'] = 'xxxxxxxxxxxx'.$userCard->last4;
        $creditCardDetail['cardType'] = str_replace(' ', '', $userCard->cardType);
        $creditCardDetail['expiryDate'] = $userCard->expirationMonth.'/'.$userCard->expirationYear;
        $creditCardDetail['cardToken'] = $userCard->token;
        $creditCardDetail['cardDefault'] = (int)$userCard->default;
        
        return $creditCardDetail;
    }

    /**
     * Get user payment history
     *
     * Get Method
     * 
     * @return Json
    */
    public static function getPaymentHistory($userId)
    {
        $user = ApiHelper::getUserById($userId);
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
            ->where('userId', $userId)->orderBy('id', 'DESC')->get();

            return $payHistory;
            //return $this->toJson(['userPaymentHistory' =>$payHistory], trans('api.payment.paymet_history'));
        }
        
        return 0;
        //return $this->toJson([], trans('api.payment.not_found_payment'), 0);
    }

    /**
     * Credit card validation method
     *
     * @param Request $request
     * 
     * @return String Credit card token
    */
    public static function checkCreditCard($cardList, $cardId, $key)
    {
        $configData = config('constant.BRAINTREE_CREDENTIALS');
        //$gateway = BraintreeHelper::configureBraintree($configData);
        
        if(sizeOf($cardList->original['result']['cards'])>0)
        {
            foreach($cardList->original['result']['cards'] as $key => $cardToken)
            {
                if($cardToken['cardToken'] == $cardId)
                {
                    try {
                        switch($key){
                            case 'cardRemove' :
                                $removeCard = $gateway->creditCard()->delete($cardId);
                                return $removeCard->success;
                            case 'cardPayment' : 
                                return true;
                            case 'cardSet' : 
                                return true;
                        }
                    } 
                    catch (\Exception $e) {
                        return $e->getMessage();
                    }
                }
                else
                {
                    $response = false;
                }
            }
            return $response;
        }
        else
        {
            return $this->toJson(['cards' => 0], trans('api.user.card_not_found'), 0);
        }
    }
}