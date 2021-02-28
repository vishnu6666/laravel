<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;
use App\Mail\ResendOtpOnEditprofileMail;
use Illuminate\Support\Facades\Mail; 

class sendResendOtpCommand extends Command
{
    /**
     * SendOtpMail
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:resendotp {email} {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend otp mail on profile update';

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

        $email = $this->argument('email');

        $user = User::find($userId);
        
        if (!empty($user)) {

            Mail::to($email)->send(new ResendOtpOnEditprofileMail($user));
        }
    }
}
