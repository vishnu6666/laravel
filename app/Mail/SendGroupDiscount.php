<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendGroupDiscount extends Mailable
{
    use Queueable, SerializesModels;

    public  $groupData = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($groupData)
    {
        $this->groupData = $groupData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Vttips - Discount Code')
            ->view('mail.groupCode.groupDiscountCode')
            ->with(['groupData' => $this->groupData]);
    }
}
