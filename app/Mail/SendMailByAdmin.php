<?php

namespace App\Mail;

use App\Model\SendMail;
use Illuminate\Mail\Mailable;

class SendMailByAdmin extends Mailable
{

    protected $teamMember = '';
    public $sendMail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendMail)
    {
        $this->sendMail = $sendMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailSend = $this->view('mail.adminMailSend.adminMailSend')
                            ->with(['messageData' => $this->sendMail->message])
                            ->subject($this->sendMail->subject);
            if(!empty($this->sendMail->attachments))
            {
                $attachments = json_decode($this->sendMail->attachments);
                foreach($attachments as $filePath){
                    $mailSend->attach(public_path('mailAttachment/').$filePath);
                }
            }                
        return $mailSend;
    }
}
