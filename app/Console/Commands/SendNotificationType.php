<?php

namespace App\Console\Commands;

use App\Model\NotificationTemplate;
use App\Model\RestaurantCustomer;
use Illuminate\Console\Command;
use App\Model\Notification;
use App\Model\User;
use App\Helpers\PushNotificationHelper;

class SendNotificationType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendNotificationType:send {notificationType?} {gameTrip?}';

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

        // Type = 1 = New Game
        // Type = 5 = New Trip
        // Type = 6 = New Message
        // Type = 7 = New Subscription
        $gameTrip = $this->argument('gameTrip');


        $user =   User::selectRaw('users.id,users.fcmToken,not.notificationType,not.module,not.title,not.content,not.isSend,not.id as notificationId')
                ->join('notifications as not', 'not.userId', 'users.id')
                ->where(['users.isActive' => 1, 'users.userType' => 'User', 'not.notificationType' => $notificationType, 'isSend' => 0])
                ->where('users.fcmToken', '!=', null)
                ->orderBy('notificationId','desc');

            if ($notificationType == 1 && !empty($gameTrip)) {
            $user =  $user->where('not.gameId', $gameTrip);
            }
             if ($notificationType == 5 && !empty($gameTrip)) {   
            $user =  $user->where('not.notificationType', $notificationType);
            }
            if ($notificationType == 6 && !empty($gameTrip)) {
              $user =  $user->where('not.messageId', $gameTrip);
            }
            if ($notificationType == 7 && !empty($gameTrip)) {
              $user =  $user->where('not.userGameHistoryId', $gameTrip);
            }

             $user = $user->chunkById(500, function ($users)  use($notificationType, $gameTrip) {
                $fcmTokens = $users->unique('fcmToken')->pluck('fcmToken')->all();
                    $notificationId = $users->pluck('notificationId')->toArray();
                     Notification::whereIn('id', $notificationId)->update(['isSend' => 1]);
                    //dd($fcmTokens);
                    $notificationDetail = [
                        'deviceTokens' => $fcmTokens,
                        'title' => isset($users[0]->title) ? strip_tags($users[0]->title) : '',
                        'content' => isset($users[0]->content) ? strip_tags($users[0]->content) : ''
                    ];

                    //\Log::debug($notificationDetail);
                    //\Log::debug('sent notification Type' . $notificationType . '  ' . $gameTrip);
                     PushNotificationHelper::sendTemplateNotification($notificationDetail);
                  }, 'not.id');
    }


    public function Oldhandle()
    {
        $notificationTemplateId = $this->argument('notificationTemplateId');

   

        $notificationTemplateInfo = NotificationTemplate::where([
            'id' => $notificationTemplateId
        ])
            ->selectRaw('id,title,content,userId')
            ->orderBy('id', 'desc')
            ->first();

        // Check notification template data is available or not
        if (!empty($notificationTemplateInfo)) {
            User::where(['users.isActive' => 1, 'users.userType' => 'User'])
            ->select('users.id', 'users.fcmToken')
            ->where('users.fcmToken', '!=', null)
                ->chunk(1000, function ($users)  use ($notificationTemplateInfo) {

                    $fcmTokens = $users->pluck('fcmToken')->toArray();

                    $notificationDetail = [
                        'deviceTokens' => $fcmTokens,
                        'title' => $notificationTemplateInfo->title,
                        'content' => $notificationTemplateInfo->content
                    ];

                    //\Log::debug('sent notification');
                    PushNotificationHelper::sendTemplateNotification($notificationDetail);
                });
        }
    }

}
