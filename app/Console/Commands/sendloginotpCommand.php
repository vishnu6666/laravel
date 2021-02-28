<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;
use App\Mail\LoginOtpMail;
use Illuminate\Support\Facades\Mail; 

class sendloginotpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:sendloginotpCommand {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send otp for login on mail';

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

            Mail::to($user->email)->send(new LoginOtpMail($user));
        }
    }
}
