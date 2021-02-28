<?php

namespace App\Helpers;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\StripeClient;
use Stripe\Tokens;
use App\Model\User;
use App\Model\UserCard;

class StripeHelper
{
    /**
     * This method is used for Generate Token.
     *
    */
    public static function configureStripe()
    {
        $configData = config('constant.stripeDetails');

        Stripe::setApiKey($configData['secret_key']);

        return $stripe = new \Stripe\StripeClient(
             $configData['secret_key']
        );
    }

    /**
     * This method is used for check Customer Created or not.
     *
     * @param int $userId 
     *
    */
    public static function checkCustomerCreatedById($userId)
    {
        $user = User::where('id',$userId)->whereNull('stripeId')->first();
        if($user){
            return false;
        }
        return true;
    }

     /**
     * This method is used for create customer.
     *
     * @param array $userData 
     *
    */
    public static function creatCustomer($userData)
    {
        $stripe = self::configureStripe();

        $customer = $stripe->customers->create([
            'name'  => $userData['name'],
            'email' => $userData['email']
        ]);

        User::where('id',$userData['id'])->update(['stripeId' => $customer['id']]);
        
        return $customer;
 
    }

    /**
     * This method is used for add card at payment gateway in particular user.
     *
     * @param array $user
     * @param array $request
    */
    public static function addCard($user, $request)
    {
        $expiryDate = explode('/',$request['expiryDate']);
        
        $stripe = self::configureStripe();

        $tokenResponse = $stripe->tokens->create([
            'card' => [
              'object'      => 'card',
              'number'      => (int) str_replace(' ', '', $request['cardNumber']),
              'exp_month'   => $expiryDate[0],
              'exp_year'    => $expiryDate[1],
              'cvc'         => $request['cvv'],
              //'brand'       => $request['cardType'],
              'name'        => $request['cardholderName']
            ],
          ]);

        $cardResponse =  $stripe->customers->createSource(
            $user['stripeId'],
            ['source' => $tokenResponse['id']]
        );

        return $cardResponse;
    }

    /**
     * This method is used for get all added cards.
     *
     * @param array $user
    */
    public static function getAllCards($user)
    {
        $stripe = self::configureStripe();
        $cardList = [];

        $responceAllCards = $stripe->customers->allSources(
            $user['stripeId'],
            ['object' => 'card', 'limit' => 10]
        );
      
        foreach($responceAllCards['data'] as $key => $userCard)
        {
            $cardList[$key] = self::getCards($userCard,$user);
        }
        return $cardList;
    }

    /**
     * This method is used for get all cards of particular user.
     *
     * @param array $userCard
     *
     * @return object $creditCardDetail
    */
    public static function getCards($userCard,$user)
    {
        $cardCount = UserCard::where(['userId' => $user->id])->count();

        $stripe = self::configureStripe();

        $getCard = $stripe->customers->retrieveSource(
            $userCard['customer'],
            $userCard['id']
        );

        if($getCard->name != null)
        {
            $creditCardDetail['cardHolderName'] = $getCard->name;
        }
        else
        {
            $creditCardDetail['cardHolderName'] = null;
        }
        $creditCardDetail['last4'] = 'xxxxxxxxxxxx'.$getCard->last4;
        $creditCardDetail['cardType'] = str_replace(' ', '', $getCard->brand);
        $creditCardDetail['expiryDate'] = $getCard->exp_month.'/'.$getCard->exp_year;
        $creditCardDetail['cardToken'] = $getCard->id;
        $creditCardDetail['cardDefault'] = $cardCount == 0 ? 1 : 0;
        $creditCardDetail['cardImage'] = Self::getcardImage(str_replace(' ', '', strtolower($getCard->brand)));
        return $creditCardDetail;
    }

    public static function getcardImage($brand)
    {
        if($brand =='mastercard'){
            return '/front/images/mastercard.png';
        }else if($brand =='visa'){
            return '/front/images/visa.png';
        }else if($brand =='americanexpress'){
            return '/front/images/americanexpress.png';
        }else if($brand =='discover'){
            return '/front/images/discover.png';
        }else if($brand =='dinersclub'){
            return '/front/images/dinersclub.png';
        }else if($brand =='jcb'){
            return '/front/images/jcb.png';
        }else if($brand =='unionpay'){
            return '/front/images/unionpay.png';
        }else {
            return '/front/images/mastercard.png';
        }
        
    }

    /**
     * This method is used for delete card.
     *
     * @param array $user
    */
    public static function deleteCard($cardId,$stripeUserId)
    {
        $stripe = self::configureStripe();

        $responceDeleteCard = $stripe->customers->deleteSource(
            $stripeUserId,
            $cardId
        );
        return $responceDeleteCard;
    }

     /**
     * This method is used for create payment.
     *
     * @param array $user
    */
    public static function createPayment($request)
    {
        $stripe = self::configureStripe();
        
        try 
            {
                $paymentData = [
                    'amount' => number_format($request['amount'],2,'.', '')*100,
                    'currency' => 'aud',
                    'card' => $request['cardId'],
                    'description' => 'Subscribed '.$request['planName'].' package.',
                    'customer' => $request['customer']
                ];

                // if($request->deviceType != 'Web')
                // {
                //     $paymentData['customer'] =  $request['customer'];
                // }

                $payment = $stripe->charges->create($paymentData);

            }
        catch (\Exception $e) {
            
                $payment = false;
        }
        
        return $payment;
    }

    /**
     * Get user card list
     *
     * @param array $user
    */
    public static function getUsercardsData($user)
    {
        return UserCard::selectRaw('id,cardHolderName,last4,cardType,cardToken,isDefault as cardDefault,cardImage')
                                ->where('userId',$user->id)
                                ->orderBy('id','desc')
                                ->get();
    }
}

