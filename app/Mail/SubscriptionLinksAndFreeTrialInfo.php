<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Model\SubscriptionHistories;

class SubscriptionLinksAndFreeTrialInfo extends Mailable
{
    use Queueable, SerializesModels;

    public  $user = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subscriptionHistoryData = SubscriptionHistories::where('userId',$this->user->id)->first();

        $subscriptionHistoryData['subscriptionExpiryDate'] = date("F j, Y", strtotime($subscriptionHistoryData['subscriptionExpiryDate']));
 
        return $this->subject('Subscription Link And Free Trial Info')
            ->view('mail.users.subscriptionLinksAndFreeTrialInfo')
            ->with(['userDetail' => $this->user,'subscriptionHistoryData' => $subscriptionHistoryData ,'link' => url('/')]);
    }
}
