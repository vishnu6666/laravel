<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;
use App\Mail\AdminSentInuiryReply;
use Illuminate\Support\Facades\Mail; 

class sendInquiryReplyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:sendinuiryreply {userId} {inquiryId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send inquiry reply mail by admin';

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

        $inquiryId = $this->argument('inquiryId');

        $user = User::find($userId);

        if (!empty($user)) {

            Mail::to($user->email)->send(new AdminSentInuiryReply($user,$inquiryId));
        }
    }
}
