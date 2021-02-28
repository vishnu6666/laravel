<?php

namespace App\Console\Commands;

use App\Mail\SendWelcomeMail;
use App\Model\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:sendWelcomeMail {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command send mail to new user signup';

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

        if(!empty($user))
        {
           $abc = Mail::to($user->email)->send(new SendWelcomeMail($user));

            //\Log::debug($abc);

        }
    }
}
