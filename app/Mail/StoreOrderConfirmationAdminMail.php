<?php

namespace App\Mail;

use App\Model\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StoreOrderConfirmationAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invoicePath = public_path('storeInvoice/' . $this->order->orderNumber . '.pdf');
        
        return $this->subject('Received new order')
            ->view('mail.stores.orderSuccessAdminMail')
            ->with(['order' => $this->order])
            ->attach($invoicePath);
        
    }
}
