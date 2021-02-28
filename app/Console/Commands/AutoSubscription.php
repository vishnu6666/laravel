<?php

namespace App\Console\Commands;

use App\Model\User;
use App\Model\SubscriptionHistories;
use App\Model\UserSubscriptionHistories;
use Illuminate\Console\Command;
use App\Mail\SendInformAfterAutoPay;
use Illuminate\Support\Facades\Mail; 
use Carbon\Carbon;
use App\Helpers\StripeHelper;

class AutoSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AutoPaySubscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Subscription of paid Subscription plan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expirePlanData = SubscriptionHistories::selectRaw('users.id as userId,users.name,users.email,users.stripeId as customer,users.cardToken as cardId,subscription_histories.id as subscriptionHistoryId,subscription_histories.planName as planName,subscription_histories.subscriptionValidity,subscription_histories.subscriptionExpiryDate,subscription_histories.isTrial,subscription_histories.planAmount as amount,subscription_histories.planId,subscription_histories.planType')
                                    ->leftJoin('users',function($join){
                                        $join->on('users.id','=','subscription_histories.userId');
                                    })
                                    ->where(['isTrial'=> 0 ,'isCancel' => 0])
                                    ->whereNotNull('cardToken')
                                    ->whereDate('subscription_histories.subscriptionExpiryDate','=', Carbon::tomorrow())
                                    ->get();
                                    
        if (!empty($expirePlanData)) {
            $planData = [];
            foreach($expirePlanData as $key => $expirePlan)
            {
                if(!empty($expirePlan->customer) && !empty($expirePlan->cardId)){
                    $expirePlanData[$key]['subscriptionExpiryNewDate'] = Carbon::now()->addDays($expirePlan->subscriptionValidity);
                    
                    $paymentData = StripeHelper::createPayment($expirePlan);
                    if (!empty($paymentData))
                    {
                        SubscriptionHistories::where('id',$expirePlan->subscriptionHistoryId)->update(['subscriptionExpiryDate' => $expirePlan->subscriptionExpiryNewDate]);

                        $planData[] = [
                            'subscriptionHistoryId'     => $expirePlan->subscriptionHistoryId,
                            'userId'                    => $expirePlan->userId,
                            'planId'                    => $expirePlan->planId,
                            'planName'                  => $expirePlan->planName,
                            'planAmount'                => $expirePlan->amount,
                            'planType'                  => $expirePlan->planType,
                            'subscriptionValidity'      => $expirePlan->subscriptionValidity,
                            'subscriptionExpiryDate'    => $expirePlan->subscriptionExpiryNewDate,
                            'paymentType'               => 'Auto Paid',
                            'amount'                    => $expirePlan->amount,
                            'isTrial'                   => 0,
                            'isAutoPay'                 => 1,
                            'isCancel'                  => 0,
                            'paymentStatus'             => 'success',
                            'transactionId'             => $paymentData['id'],
                            'paymentResponse'           => json_encode($paymentData)
                        ];

                    }
                    
                }
            }

            if(!empty($planData)){
                UserSubscriptionHistories::insert($planData);
            }
        }
    }
}
