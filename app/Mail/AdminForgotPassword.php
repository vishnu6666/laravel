<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdminForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user = null;
    public $token = null;
    
    public function __construct($user,$token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url('superadmin/password/reset/'.base64_encode($this->user->email).'/'.$this->token);
        
        $customerName = $this->user->name;

        return $this->subject('Vttips Super Admin Forgot Password Mail')
            ->with(['customerName' => $customerName, 'url' => $url])
            ->view('mail.adminMailSend.reset_password_mail');
    }
}
