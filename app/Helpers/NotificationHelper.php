<?php

namespace App\Helpers;

use App\Model\Notification;
use App\Model\NotificationRequest;
use App\Model\RestaurantWaiter;
use App\Model\User;

class NotificationHelper
{
    /*
  |--------------------------------------------------------------------------
  | Notification Helper
  |--------------------------------------------------------------------------
  |
  | This helper handles customer and waiter notifications.
 */

    /**
     * Get user local lang
     *
     * @param $tableName
     * @param $userId
     * @return mixed
     */
    public static function getUserLang($tableName, $userId){

        if($tableName == 'restaurant_waiters'){
            $waiterLang = RestaurantWaiter::where('restaurant_waiters.id', $userId);
        }else{
            $waiterLang = User::where('users.id', $userId);
        }

        $waiterLang = $waiterLang->select($tableName.'.languageId','l.shortName')
            ->leftJoin('languages as l', function ($query) use($tableName){
                $query->on('l.id', $tableName.'.languageId');
            })->first();

        return $waiterLang->shortName;
    }

    /**
     * Create & Send notification to waiter
     *
     * @param $notificationDetail
     * @param $tableNo
     * @param $notificationRequest
     */
    public static function sendNotificationToWaiter($notificationDetail, $tableNo, $notificationRequest = null){

        $lang = self::getUserLang('restaurant_waiters', $notificationDetail['waiterId']);

        $notification = self::setNotification(
            $notificationDetail, 'Waiter', $notificationRequest);

        $notification = self::setNotificationToWaiterContent(
            $notificationDetail, $tableNo, $notification, $lang);

        $notification->tableAllocationId = $notificationDetail['tableAllocationId'];
        $notification->notificationTitle = $notificationDetail['notificationTitle'];
        $notification->isActionDone = isset($notificationDetail['isActionDone']) ? $notificationDetail['isActionDone'] : 0;
        $notification->save();

        $cmd = 'cd '.base_path().' && php artisan pushnotification:send '.$notification->id.' false';
        
        exec($cmd. '> /dev/null &');
    }

    /**
     * Create & Send notification to chef
     *
     * @param $notificationDetail
     * @param $tableNo
     * @param $notificationRequest
     */
    public static function sendNotificationToChef($notificationDetail, $tableNo, $notificationRequest = null){

        $lang = self::getUserLang('restaurant_waiters', $notificationDetail['chefId']);
        
        $notification = self::setNotification(
            $notificationDetail, 'Chef', $notificationRequest);
        
        $notification = self::setNotificationToChefContent(
            $notificationDetail, $tableNo, $notification, $lang);

        $notification->tableAllocationId = $notificationDetail['tableAllocationId'];
        $notification->notificationTitle = $notificationDetail['notificationTitle'];
        $notification->isActionDone = isset($notificationDetail['isActionDone']) ? $notificationDetail['isActionDone'] : 0;
        $notification->save();

        $cmd = 'cd '.base_path().' && php artisan pushnotification:send '.$notification->id.' false';

        exec($cmd. '> /dev/null &');
    }

    /**
     * Create & Send notification to Customer
     *
     * @param $notificationDetail
     * @param $tableNo
     * @param $notificationRequest
     */
    public static function sendNotificationToCustomer($notificationDetail, $notificationRequest = null){

        $lang = self::getUserLang('users', $notificationDetail['userId']);

        $notification = self::setNotification(
            $notificationDetail, 'User', $notificationRequest);

        $notification = self::setNotificationToCustomerContent(
            $notificationDetail, $notification, $lang);

        $notification->tableAllocationId = $notificationDetail['tableAllocationId'];
        $notification->notificationTitle = $notificationDetail['notificationTitle'];
        $notification->isActionDone = isset($notificationDetail['isActionDone']) ? $notificationDetail['isActionDone'] : 0;
        $notification->save();

        $cmd = 'cd '.base_path().' && php artisan pushnotification:send '.$notification->id.' false';
        exec($cmd. '> /dev/null &');
    }

    /**
     * Send notification to Admin
     *
     * @param $notificationDetail
     * @param $tableNo
     */
    public static function sendNotificationToAdmin($notificationDetail){

        $notification = self::setNotification(
            $notificationDetail, 'Admin');

        $notification = self::setNotificationToAdminContent($notificationDetail, $notification);

        $notification->save();

        $cmd = 'cd '.base_path().' && php artisan pushnotification:send '.$notification->id.' false';
        exec($cmd. '> /dev/null &');
    }

    /**
     * Send notification to Customer and waiter
     *
     * @param $notificationDetail
     * @param $tableNo
     * @param $notificationRequest
     */
    public static function sendNotificationToWaiterAndCustomer($notificationDetail, $tableNo, $notificationRequest = null){

        $lang = self::getUserLang('restaurant_waiters', $notificationDetail['waiterId']);

        $isCancelOrder = "false";

        $waiterNotification = self::setNotification(
            $notificationDetail, 'Waiter', $notificationRequest);

        $waiterNotification = self::setNotificationToWaiterContent(
            $notificationDetail, $tableNo, $waiterNotification, $lang);

        $waiterNotification->tableAllocationId = $notificationDetail['tableAllocationId'];
        $waiterNotification->notificationTitle = $notificationDetail['notificationTitle'];
        $waiterNotification->isActionDone = isset($notificationDetail['isActionDone']) ? $notificationDetail['isActionDone'] : 0;

        $waiterNotification->save();

        $cmd = 'cd '.base_path().' && php artisan pushnotification:send '.$waiterNotification->id.' '.$isCancelOrder;

        exec($cmd. '> /dev/null &');

        // Send notification to customer

        $lang = self::getUserLang('users', $notificationDetail['userId']);

        //\Log::debug(\GuzzleHttp\json_encode($lang));

        $customerNotification = self::setNotification(
            $notificationDetail, 'User', $notificationRequest);

        if($notificationDetail['requestType'] == 11){
            $tableNo = $notificationDetail['customerName'];
        }

        $customerNotification = self::setNotificationToCustomerContent(
            $notificationDetail, $customerNotification, $lang);

        $customerNotification->tableAllocationId = $notificationDetail['tableAllocationId'];
        $customerNotification->notificationTitle = $notificationDetail['notificationTitle'];
        $customerNotification->isActionDone = isset($notificationDetail['isActionDone']) ? $notificationDetail['isActionDone'] : 0;

        $customerNotification->save();

        if(isset($notificationDetail['isCancelOrder'])){
            $isCancelOrder = "true";
        }

        $cmd = 'cd '.base_path().' && php artisan pushnotification:send '.$customerNotification->id.' '.$isCancelOrder;

        exec($cmd. '> /dev/null &');
    }


    /**
     * Create Notification request
     *
     * @param $notificationData
     * @return mixed
     */
    public static function createNotificationRequest($notificationData){

        $notificationRequest = new NotificationRequest();
        $notificationRequest->userId = $notificationData['userId'];
        $notificationRequest->waiterId = $notificationData['waiterId'];
        $notificationRequest->notificationType = $notificationData['notificationType'];
        $notificationRequest->requestType = $notificationData['requestType'];

        $notificationRequest->status = 'str_requested';
        $notificationRequest->save();

        return $notificationRequest->id;
    }



    /**
     * Set notification
     *
     * @param $notificationData
     * @param $userType
     * @param $notificationReqId
     * @return Notification|mixed
     */
    public static function setNotification($notificationData, $userType, $notificationReqId = null){

        $addNotification = new Notification();

        if($userType == 'Waiter')
            $addNotification->userId = $notificationData['waiterId'];

        else if($userType == 'User')
            $addNotification->userId = $notificationData['userId'];

        else if($userType == 'Chef')
            $addNotification->userId = $notificationData['chefId'];

        else
            $addNotification->userId = $notificationData['adminId'];

        
        $addNotification->userType = $userType;
        $addNotification->notificationType = $notificationData['notificationType'];


        $addNotification->requestId = (!empty($notificationReqId) ? $notificationReqId : (!empty($notificationData['notificationReqId']) ? $notificationData['notificationReqId'] : null));

        $addNotification->orderId = isset($notificationData['orderId']) ? $notificationData['orderId'] : 0;

        // 5 ,6, 7, 8, 9 for order related notification set data
        if($notificationData['requestType'] == 5 || $notificationData['requestType'] == 6
            || $notificationData['requestType'] == 7 || $notificationData['requestType'] == 8
            || $notificationData['requestType'] == 9 || $notificationData['requestType'] == 20
            || $notificationData['requestType'] == 21)
        {
            $addNotification->notificationData = json_encode(['orderId' => $notificationData['orderCode']]);
            $addNotification->clickAction = 'OrderDetail';
        }

        return $addNotification;
    }

    /**
     * set Notification content message for waiter
     *
     * @param $notificationDetail
     * @param $tableNo
     * @param $notification
     * @param $lang
     * @return mixed
     */
    public static function setNotificationToWaiterContent($notificationDetail, $tableNo, $notification, $lang){

        switch ($notificationDetail['requestType']){
            case 1: // Payment
                if($notificationDetail['paymentType'] == 'Card')
                {
                    $notification->title = trans('api.notifications.payment_card.request_waiter.title', ['tableNo' => $tableNo ], $lang);
                    $notification->content = trans('api.notifications.payment.request_waiter.content', [], $lang);
                }
                else{

                    $notification->title = trans('api.notifications.payment.request_waiter.title', ['tableNo' => $tableNo ], $lang);
                    $notification->content = trans('api.notifications.payment.request_waiter.content', [], $lang);
                }
                break;

            case 2: // Table switch
                $notification->title = trans('api.notifications.table_switch.request_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.table_switch.request_waiter.content', [], $lang);
                $notification->oldTableNo = $tableNo;
                break;

            case 3: // Raised complaint
                $notification->title = trans('api.notifications.complaint.request_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.complaint.request_waiter.content', [], $lang);
                break;

            case 4: // Assist
                $notification->title = trans('api.notifications.assist.request_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.assist.request_waiter.content', [], $lang);
                break;

            case 5: // Place order
                $notification->title = trans('api.notifications.place_order.send_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.place_order.send_waiter.content', [], $lang);
                break;

            case 6: // Order Cancelled
                $notification->title = trans('api.notifications.cancelled_order.send_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.cancelled_order.send_waiter.content', [], $lang);
                break;

            case 9: // Order Delivered
                $notification->title = trans('api.notifications.delivered_order.send_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.delivered_order.send_waiter.content', [], $lang);
                break;

            case 10: // Foods Delivered
                $notification->title = trans('api.notifications.delivered_order_food.send_waiter.title', ['tableNo' => $tableNo, 'foods' => $notificationDetail['foods']], $lang);
                $notification->content = trans('api.notifications.delivered_order_food.send_waiter.content', [], $lang);
                break;

            case 11: // Paid Order
                $notification->title = trans('api.notifications.paid_payment.request_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.paid_payment.request_waiter.content', [], $lang);
                break;

            /*case 12: // TableSwitch success
                $notification->title = trans('api.notifications.table_switch.send_waiter.title', ['tableNo' => $tableNo, 'newTableNo' => $notification->oldTableNo ]);
                $notification->content = trans('api.notifications.table_switch.send_waiter.content');
                break;*/

            case 13: // Complaint Resolved
                $notification->title = trans('api.notifications.complaint_resolved.send_waiter.title', ['tableNo' => $tableNo ],  $lang);
                $notification->content = trans('api.notifications.complaint_resolved.send_waiter.content',[], $lang);
                break;

            case 14: // Assist Done
                $notification->title = trans('api.notifications.assist_done.send_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.assist_done.send_waiter.content', [], $lang);
            break;

            case 15: // order not placed send notification
                $notification->title = trans('api.notifications.order_not_placed.send_waiter.title', ['tableNo' => $tableNo ], $lang);
                $notification->content = trans('api.notifications.order_not_placed.send_waiter.content', [], $lang);
            break;

            case 20: // chef delivered products
                $notification->title = trans('api.notifications.chef_delivered_order.send_waiter.title', ['tableNo' => $tableNo], $lang);
                $notification->content = trans('api.notifications.chef_delivered_order.send_waiter.content', [], $lang);
            break;
        }

        return $notification;

    }


    /**
     * set Notification content message for customer
     *
     * @param $notificationDetail
     * @param $tableNo
     * @param $notification
     * @param $lang
     * @return mixed
     */
    public static function setNotificationToCustomerContent($notificationDetail, $notification, $lang){

        switch ($notificationDetail['requestType']){
            case 2: // Table switch
                $notification->title = trans('api.notifications.table_switch.send_customer.title',[], $lang);
                $notification->content = trans('api.notifications.table_switch.send_customer.content',[], $lang);
                break;

            case 3: // Request for Compliant sent
                $notification->title = trans('api.notifications.complaint.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.complaint.send_customer.content', [], $lang);
                break;

            case 4: // Request for  Assist sent
                $notification->title = trans('api.notifications.assist.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.assist.send_customer.content', [], $lang);
                break;

            case 6: // Order cancelled success
                $notification->title = trans('api.notifications.cancelled_order.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.cancelled_order.send_customer.content', [], $lang);
                break;

            case 7: // Accept order
                $notification->title = trans('api.notifications.accept_order.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.accept_order.send_customer.content', [], $lang);
                break;

            case 8: // Order in kitchen
                $notification->title = trans('api.notifications.in_kitchen_order.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.in_kitchen_order.send_customer.content', [], $lang);
                break;

            case 9: // Order delivered success
                $notification->title = trans('api.notifications.delivered_order.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.delivered_order.send_customer.content', [], $lang);
                break;

            case 10: // Foods Delivered
                $notification->title = trans('api.notifications.delivered_order_food.send_customer.title', ['foods' => $notificationDetail['foods']], $lang);
                $notification->content = trans('api.notifications.delivered_order_food.send_customer.content', [], $lang);
                break;

            case 11: // Payment Request send
                $notification->title = trans('api.notifications.payment.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.payment.send_customer.content', [], $lang);
                break;

            case 12: // Switch Table success
                $notification->title = trans('api.notifications.switch_table_done.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.switch_table_done.send_customer.content', [], $lang);
                break;

            case 13: // Complaint resolved
                $notification->title = trans('api.notifications.complaint_resolved.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.complaint_resolved.send_customer.content', [], $lang);
                break;

            case 14: // Assist Resolved
                $notification->title = trans('api.notifications.assist_done.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.assist_done.send_customer.content', [], $lang);
                break;

            case 15: // Release Table
                $notification->title = trans('api.notifications.release_table.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.release_table.send_customer.content', [], $lang);
                break;

            case 17: // Waiter Cancel request for switch table
                $notification->title = trans('api.notifications.cancel_switch_table.send_customer.title', [], $lang);
                $notification->content = trans('api.notifications.cancel_switch_table.send_customer.content', [], $lang);
                break;

            case 20: // order delivered within 15 min
                $notification->title = trans('api.notifications.chef_delivered_order.send_customer.title', ['deliveryMin' => config('constant.delivery_min')], $lang);
                $notification->content = trans('api.notifications.chef_delivered_order.send_customer.content', [], $lang);
                break;
        }

        return $notification;
    }


    /**
     * Set notification content for admin
     *
     * @param $notificationDetail
     * @param $notification
     * @return mixed
     */
    public static function setNotificationToAdminContent($notificationDetail, $notification){

        switch ($notificationDetail['requestType']) {

            case 11: // Request for Compliant sent
                $notification->title = trans('api.notifications.payment.send_admin.title', ['tableNo' => $notificationDetail['tableNo']]);
                $notification->content = trans('api.notifications.payment.send_admin.content');
                break;

            case 12 : // Switch Table Success
                $notification->title = trans('api.notifications.switch_table_done.send_admin.title', ['tableNo' => $notificationDetail['tableNo'], 'newTableNo' => $notificationDetail['newTableNo'] ]);
                $notification->content = trans('api.notifications.switch_table_done.send_admin.content');
                break;

            case 9 : // Order Delivered
                $notification->title = trans('api.notifications.delivered_order.send_admin.title', ['tableNo' => $notificationDetail['tableNo'], 'orderNo' => $notificationDetail['orderCode'] ]);
                $notification->content = trans('api.notifications.delivered_order.send_admin.content');
                break;

            case 13: // Complaint resolved
                $notification->title = trans('api.notifications.complaint_resolved.send_admin.title', ['tableNo' => $notificationDetail['tableNo'] ]);
                $notification->content = trans('api.notifications.complaint_resolved.send_admin.content');
                break;
            case 16: // Waiter did not made any reaction for order
                $notification->title = trans('api.notifications.waiter_warning.send_admin.title');
                $notification->content = trans('api.notifications.waiter_warning.send_admin.content',  ['tableNo' => $notificationDetail['tableNo'], 'orderNo' => $notificationDetail['orderCode'], 'waiterName' => $notificationDetail['waiterName'] ]);
                break;

            case 17: // Waiter Cancel request for switch table
                $notification->title = trans('api.notifications.cancel_switch_table.send_admin.title',  ['tableNo' => $notificationDetail['tableNo'], 'waiterName' => $notificationDetail['waiterName'] ]);
                $notification->content = trans('api.notifications.cancel_switch_table.send_admin.content');
                break;
        }

        return $notification;
    }

    public static function setNotificationToChefContent($notificationDetail, $tableNo, $notification, $lang)
    {
        switch ($notificationDetail['requestType']){
            case 8: // Order in kitchen
                $notification->title = trans('api.notifications.in_kitchen_order.send_chef.title', ['tableNo' => $tableNo, 'waiterName' => $notificationDetail['waiterName'] ], $lang);
                $notification->content = trans('api.notifications.in_kitchen_order.send_chef.content');
                break;

            case 10: // Foods Delivered
                $notification->title = trans('api.notifications.delivered_order_food.send_chef.title', ['tableNo' => $tableNo, 'waiterName' => $notificationDetail['waiterName'], 'foods' => $notificationDetail['foods'] ], $lang);
                $notification->content = trans('api.notifications.delivered_order_food.send_chef.content');
                break;

            case 21: // order delivered
                $notification->title = trans('api.notifications.delivered_order.send_chef.title', ['tableNo' => $tableNo, 'waiterName' => $notificationDetail['waiterName'] ], $lang);
                $notification->content = trans('api.notifications.delivered_order.send_chef.content');
                break;

        }

        return $notification;
    }
}