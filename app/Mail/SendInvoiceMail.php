<?php

namespace App\Mail;

use App\Model\SubscriptionHistories;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subscriptionHistoriesId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subscriptionHistoriesId)
    {
        $this->subscriptionHistoriesId = $subscriptionHistoriesId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subscriptionHistoriesInfo = SubscriptionHistories::selectRaw('subscription_histories.id,users.name,users.email,users.mobileNumber,subscription_histories.planName,
                                                        subscription_histories.planType,subscription_histories.planAmount,
                                                        subscription_histories.amount,
                                                        subscription_histories.subscriptionValidity,
                                                        subscription_histories.isTrial,
                                                        DATE_FORMAT(subscription_histories.subscriptionExpiryDate,"'.config('constant.DATE_TIME_FORMAT'). '") as subscriptionExpiryDate,
                                                        DATE_FORMAT(subscription_histories.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createdAt,
                                                        subscription_histories.discountAmount,subscription_histories.paymentStatus,
                                                        subscription_histories.appliedPromocode,subscription_histories.paymentType,
                                                        referral_codes.title
                                                        ')
                                                        ->leftJoin('users',function($query){
                                                            $query->on('users.id','=','subscription_histories.userId');
                                                        })
                                                        ->leftJoin('referral_codes',function($query){
                                                            $query->on('subscription_histories.referralcodeId','=','referral_codes.id');
                                                        })
                                                        ->where('subscription_histories.id',$this->subscriptionHistoriesId)
                                                        ->first();
        return $this->subject('Subscription has been sucessfull.')
            ->view('mail.invoice.orderSuccessInvoiceSendMail')
            ->with(['order' => $subscriptionHistoriesInfo]);
    }
}
