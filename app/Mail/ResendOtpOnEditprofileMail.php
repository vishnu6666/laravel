<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResendOtpOnEditprofileMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user = null;
    
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
        return $this->view('mail.users.otp')
            ->with(['userDetail' => $this->user])
            ->subject('Send OTP from Vttips');
    }
}
