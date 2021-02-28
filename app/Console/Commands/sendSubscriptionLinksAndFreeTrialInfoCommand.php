<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SubscriptionLinksAndFreeTrialInfo;
use App\Model\User;
use Illuminate\Support\Facades\Mail; 

class sendSubscriptionLinksAndFreeTrialInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:sendFreeTrialInfoCommand {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify OTP after send Free Trial Info to user';

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
        $userId = $this->argument('userId');

        $user = User::find($userId);

        if (!empty($user)) {

            Mail::to($user->email)->send(new SubscriptionLinksAndFreeTrialInfo($user));
        }
    }
}
