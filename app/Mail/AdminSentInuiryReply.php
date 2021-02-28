<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\model\ContactUs;

class AdminSentInuiryReply extends Mailable
{
    use Queueable, SerializesModels;

    public  $user = null;
    public  $inquiryId = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$inquiryId)
    {
        $this->user = $user;
        $this->inquiryId = $inquiryId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $contactUsData = ContactUs::selectRaw('contact_us.id,contact_us.userId,contact_us.subject,contact_us.message,contact_us.createdAt,contact_us.reply_message,users.name,users.email,users.mobileNumber')
                                    ->leftJoin('users','users.id','=','contact_us.userId')
                                    ->where(['contact_us.userId' => $this->user['id'],
                                            'contact_us.id' => $this->inquiryId])
                                    ->first();

        return $this->view('mail.adminMailSend.contactUs')
            ->with(['mailContent' => $contactUsData])
            ->subject($contactUsData['subject']);

    }
}
