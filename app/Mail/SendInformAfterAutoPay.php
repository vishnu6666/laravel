<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInformAfterAutoPay extends Mailable
{
    use Queueable, SerializesModels;

    public  $user = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subscriptionHistoryData)
    {
        $this->subscriptionHistoryPlanData = $subscriptionHistoryData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your '.$this->subscriptionHistoryPlanData->planName.' subscription plan is extended successfully ')
            ->view('mail.users.informAfterAutoPay')
            ->with(['subscriptionHistoryData' => $this->subscriptionHistoryPlanData,'link' => url('/')]);
    }
}
