<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\SubscriptionHistories;
use App\Model\UserPackagesSubscriptionHistories;
use App\Model\userGameHistory;
use Carbon\Carbon;

class ExpiredSubscriptionDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ExpiredSubscriptionDelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use for delete expired subscription plan.';

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
        $expiredsubscriptionId = SubscriptionHistories::selectRaw('id,subscriptionExpiryDate')
                                                        ->whereDate('subscriptionExpiryDate','=', Carbon::yesterday())
                                                        ->pluck('id')
                                                        ->toArray();
        if(!empty($expiredsubscriptionId)){
            //SubscriptionHistories::where('isTrial',1)->delete();
            UserPackagesSubscriptionHistories::whereIn('subscriptionHistoriesId',  $expiredsubscriptionId)->delete();
            userGameHistory::whereIn('subscriptionHistoriesId',  $expiredsubscriptionId)->delete();
        }

    }
}
