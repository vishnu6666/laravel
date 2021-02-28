<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail; 
use App\Model\SubscriptionHistories;
use App\Model\User;
use App\Mail\SendInvoiceMail;

class SendInvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:SendInvoiceCommand {subscriptionHistoriesId} {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invoice after subscription.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscriptionHistoriesId = $this->argument('subscriptionHistoriesId');
        $userId = $this->argument('userId');
        $user = User::find($userId);
        Mail::to($user->email)->send(new SendInvoiceMail($subscriptionHistoriesId));
    }
}
