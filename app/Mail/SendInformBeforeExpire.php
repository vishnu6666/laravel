<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInformBeforeExpire extends Mailable
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
        return $this->subject('Your plan is expiring soon')
            ->view('mail.users.informBeforeExpire')
            ->with(['subscriptionHistoryData' => $this->subscriptionHistoryPlanData,'link' => url('/')]);
    }
}
