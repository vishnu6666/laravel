<?php

namespace App\Console\Commands;

use App\Model\User;
use App\Model\SubscriptionHistories;
use Illuminate\Console\Command;
use App\Mail\SendInformBeforeExpire;
use Illuminate\Support\Facades\Mail; 
use Carbon\Carbon;

class InformBeforeExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:InformBeforeExpire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $expireTommorowData = SubscriptionHistories::selectRaw('users.id,users.name,users.email,subscription_histories.planName,subscription_histories.subscriptionValidity,subscription_histories.subscriptionExpiryDate,subscription_histories.isTrial')
                                    ->Join('users',function($join){
                                        $join->on('users.id','=','subscription_histories.userId');
                                    })
                                    ->whereDate('subscription_histories.subscriptionExpiryDate','=', Carbon::tomorrow())
                                    ->get();
                                    
        if (!empty($expireTommorowData)) {

            foreach($expireTommorowData as $key => $expireTommorow)
            {
                $expireTommorow['subscriptionExpiryDate'] = date("F j, Y", strtotime($expireTommorow['subscriptionExpiryDate']));
      
                Mail::to($expireTommorow->email)->send(new SendInformBeforeExpire($expireTommorow));
            }
        }
    }
}
