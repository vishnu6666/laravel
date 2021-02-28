<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\sendMail;
use App\Mail\SendMailByAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class sendAdminMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sendAdminMailCommand {sendMailId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail by admin.';

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
        DB::beginTransaction();
        $sendMailId = $this->argument('sendMailId');
        $sendMail = SendMail::find($sendMailId);
        
        Mail::to($sendMail->email)->send(new SendMailByAdmin($sendMail));

        DB::commit();
    }
}
