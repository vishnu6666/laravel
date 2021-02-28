<?php

namespace App\Helpers;

use App\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PushNotificationHelper
{

    
   /**
     *  Send template  notification 
     * @param $notificationDetail-> deviceTokens as array of individual devicetoken, gameid
     * @return boolean
     */
    public static function sendTemplateNotification($notificationDetail){

            $RequestData = [
                'registration_ids' => $notificationDetail['deviceTokens'],
                'notification' => [
                        'title' => $notificationDetail['title'],
                        'body' => $notificationDetail['content'],
                        'click_action' => 'vttips_default',
                        'icon' => '',
                        'sound' => 'default',
                ],
                'data' => [
                    'title' => $notificationDetail['title'],
                    'body' => $notificationDetail['content'],
                    'click_action' => 'splash',
                    'icon' => '',
                    'sound' => 'default',
                ]
            ];

        self::sendPushNotification($RequestData);
    }


    /**
     * Send notification
     * 
     * @param $fields
     * @param $isWaiter when send notification to waiter pass $isWaiter = 1
     * 
     * @return boolean
     */
    public static function sendPushNotification($fields)
    {
        $headers = [
            'Authorization:key='.config('constant.push_notification.token'),
            'Content-Type: application/json',
            'project_id:'.config('constant.push_notification.project_id'),
        ];

        // \Log::debug('header');
        // \Log::debug(json_encode($headers));
        // \Log::debug('fields');
        // \Log::debug(json_encode($fields));

        //\Log::debug('-----------');
        //\Log::debug(json_encode($fields));

        $status = false;

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, config('constant.push_notification.url'));
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                //\Log::debug('-----------');
                //\Log::debug($result);
                $result = json_decode($result, TRUE);
                curl_close($ch);
                $status = (isset($result['success']) && $result['success'] !=0) ? true : false;

            }
            catch (\Exception $e)
            {
                $result = null;
                Log::debug('-----------');
                Log::debug(json_encode($e->getMessage()));
                $status =  false;
            }

            $data['data'] = [
                'status'=>$status,
                'response'=>$result
            ];

            return $data;
    }

}