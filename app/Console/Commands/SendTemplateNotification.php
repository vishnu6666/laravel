<?php

namespace App\Console\Commands;

use App\Model\NotificationTemplate;
use App\Model\RestaurantCustomer;
use Illuminate\Console\Command;
use App\Model\Notification;
use App\Model\User;
use App\Helpers\PushNotificationHelper;

class SendTemplateNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  
    protected $signature = 'SendTemplateNotification:send {notificationType?} {notificationTemplateId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to all user by notification template';

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
        $notificationType = $this->argument('notificationType');
        $notificationTemplateId = $this->argument('notificationTemplateId');


        // Check notification template data is available or not
        if ($notificationType == 4 && !empty($notificationTemplateId)) {

             User::selectRaw('users.id,users.fcmToken,not.notificationType,not.module,not.title,not.content,not.isSend,not.id as notificationId')
                ->join('notifications as not', 'not.userId', 'users.id')
                ->where(['users.isActive' => 1,'users.userType' => 'User','not.notificationType' => $notificationType,'isSend'=> 0])
                 ->where('users.fcmToken', '!=', null)
                ->where('not.notificationType', $notificationType)
                ->where('not.notificationTemplateId', $notificationTemplateId)
                ->orderby('users.fcmToken','desc')
                ->chunkById(500, function ($users)  use($notificationType, $notificationTemplateId) {

                   
                    $fcmTokens = $users->unique('fcmToken')->pluck('fcmToken')->all();
                    
                    // $fcmTokens =  array_filter($fcmTokens);

                    $notificationId = $users->pluck('notificationId')->toArray();
                     Notification::whereIn('id', $notificationId)->update(['isSend' => 1]);

                    $notificationDetail = [
                        'deviceTokens' => $fcmTokens,
                        'title' => isset($users[0]->title) ? $users[0]->title : '',
                        'content' => isset($users[0]->content) ? $users[0]->content : ''
                    ];

                      //\Log::debug($notificationDetail);
                     //\Log::debug('Admin sent notification Type' . $notificationType . '  ' . $notificationTemplateId);
                     PushNotificationHelper::sendTemplateNotification($notificationDetail);
                  }, 'not.id');

        }
    }

}
