<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;
use App\Model\Notification;
use App\Model\RestaurantWaiter;
use App\Model\Customer;
use App\Helpers\PushNotificationHelper;

class SendPushNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushnotification:send {notificationId} {isCancelOrder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notification to user from notification id';

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
        $notificationId = $this->argument('notificationId');
        $isCancelOrder = $this->argument('isCancelOrder');

        $notification = Notification::where([
            'notifications.id' => $notificationId
        ])
            ->selectRaw('notifications.id as notificationId,notifications.title,notifications.userType,
            notifications.notificationTitle,notifications.content,notifications.notificationType,
            notifications.orderId,notifications.userId,notifications.notificationData,notifications.isActionDone,
                IF(notifications.orderId = 0,(IF(notifications.isActionDone = 1,nr.status,"str_requested")),orders.orderStatus) as notificationStatus,
                 orders.paymentStatus,notifications.isRead ,ifnull(nr.requestType, 0) as requestType,
                 ifnull(notifications.tableAllocationId, 0) as tableAllocationId,notifications.createdAt,
                 count(op.id) as cartCount')

            ->leftJoin('orders','orders.id','=','notifications.orderId')
            ->leftJoin('order_products as op', function ($query){
                $query->on('op.orderId', 'orders.id')
                    ->where('op.productStatus', '!=', 'str_cancelled');
            })
            ->leftJoin('notification_requests as nr', 'nr.id','=','notifications.requestId')
            ->orderBy('notifications.id','desc')
            ->groupBy('notificationId')
            ->first();

        //$notification = Notification::find($notificationId);

        // Check notification is available or not
        if(!empty($notification))
        {
            $type = 'data';

            if(in_array($notification->userType, ['Waiter', 'Chef']))
            {
                $user = RestaurantWaiter::find($notification->userId);
                $type = 'data';

            }

            else
            {
                $user = User::selectRaw('users.fcmToken,ul.deviceType')->where('users.id',$notification->userId)
                    ->leftJoin('users_login as ul', function ($query){
                        $query->on('users.id', '=', 'ul.userId')
                            ->where('ul.isOnline',1);
                    })->first();


                if(!empty($user) && $user->deviceType == 'Ios'){
                    $type = 'notification';
                }else{
                    $type = 'data';
                }
            }

            $clickAction = ($notification->userType == 'User') ? 'Fastmenu_Splash' : $notification->userType;
            $isWaiter = ($notification->userType == 'User') ? 0 : 1;

            // Check user is available or not
            if(!empty($user))
            {
                $fields = [
                    'registration_ids' => [$user->fcmToken],
                    $type => [
                        'title' => $notification->notificationTitle,
                        'body' => $notification->title,
                        'click_action' => $clickAction,
                        'icon' => '',
                        'sound' => 'default',
                        'tags' => [
                            'notificationId' => $notification->notificationId,
                            'notificationType' => $notification->notificationType,
                            'requestType' => $notification->requestType,
                            'tableAllocationId' => $notification->tableAllocationId,
                            'notificationStatus' => $notification->notificationStatus,
                            'isActionDone' => $notification->isActionDone,
                            'orderId' => 0
                        ],
                    ],
                ];


                if(!empty($notification->notificationData))
                {
                    $fields[$type]['tags']['orderId'] = $notification->orderId;
                }

                if(isset($isCancelOrder) && $isCancelOrder == 'true'){
                    $fields[$type]['tags']['cartCount'] = $notification->cartCount;
                }


                PushNotificationHelper::sendPushNotification($fields, $isWaiter);
            }
        }
    }
}

