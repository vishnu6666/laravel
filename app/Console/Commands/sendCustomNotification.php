<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Model\Notification;
use App\Helpers\PushNotificationHelper;
use Illuminate\Support\Facades\DB;

class sendCustomNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:customNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send notification to user for any particular reasone';

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
        
        $title = addslashes('"Alert!"');
        $content = addslashes('Please update your email address from the profile section of the app. You will need this for all future logins.');

        $senderId = 1;
        $notificationType = 4;
        $module = 'FromAdmin';
        $image =  url('admin-assets/images/logo/vp.png');
        $notification_template = 'NULL';

        $dbQuery = "INSERT INTO notifications(senderId, userId,notificationTemplateId,notificationType, module, title,content,media,createdAt)
            SELECT DISTINCT
            '" . $senderId . "',
            id,
             '" . $notification_template . "',
            '" . $notificationType . "',
            '" . $module . "',
            '" . $title . "',
            '" . $content . "',
             '" . $image . "',
            '" . Carbon::now() . "'
            FROM 
            users where userType = 'User' AND socialType = 'apple' AND email LIKE '%appleid.com';";

        DB::select($dbQuery);


        User::selectRaw('users.id,users.fcmToken,not.notificationType,not.module,not.title,not.content,not.isSend,not.id as notificationId')
                ->join('notifications as not', 'not.userId', 'users.id')
                ->where(['users.isActive' => 1,'users.userType' => 'User','not.notificationType' => $notificationType,'isSend'=> 0])
                ->where('users.fcmToken', '!=', null)
                ->where('not.notificationType', $notificationType)
                ->where('not.notificationTemplateId', $notification_template)
                ->orderby('users.fcmToken','desc')
                ->chunkById(500, function ($users)  use($notificationType, $notification_template) {

                    $fcmTokens = $users->unique('fcmToken')->pluck('fcmToken')->all();
                    
                    $notificationId = $users->pluck('notificationId')->toArray();
                    Notification::whereIn('id', $notificationId)->update(['isSend' => 1]);

                    $notificationDetail = [
                        'deviceTokens' => $fcmTokens,
                        'title' => isset($users[0]->title) ? $users[0]->title : '',
                        'content' => isset($users[0]->content) ? $users[0]->content : ''
                    ];

                    \Log::debug($notificationDetail);
                    \Log::debug('Admin sent notification Type' . $notificationType . '  ' . $notification_template);
                    
                    PushNotificationHelper::sendTemplateNotification($notificationDetail);
                  }, 'not.id');    
    }
}
