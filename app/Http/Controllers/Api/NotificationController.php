<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\Model\User;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Illuminate\Support\Carbon;


class NotificationController extends Controller
{

    /*
     |--------------------------------------------------------------------------
     | NotificationController Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles notifications Related apis & features.
    */

    
    /**
     * Get Notification list
     * 
     * @param Request $request
     * 
     * @return Response Json
    */
    public function getNotificationList(Request $request)
    {
        $user = \Auth::user();

        $id = !empty($request->id) ? $request->id : 0;

        $recents = $this->recentsNotification($user)->get();
        
        $earlier = $this->baseQueryNotification($user)->where('notifications.isRead', 1);

        if(!empty($id))
        {
           $earlier = $earlier->where('id', '<', $id);
        }

        $earlier = $earlier->limit($this->perPage)->get()->toArray();


       //if(!empty(count($earlier)- 1 != -1))
       //{
         
       //}

       //$nextPageCount = $earlier = $this->baseQueryNotification($user)->where('notifications.isRead', 1)->$earlier->where('id', '<', )->count();

       $hasMoreResults = !empty($earlier) ? 1 : 0;

        $this->baseQueryNotification($user)->where('isRead', 0)->update(['isRead' => 1]);

        return $this->toJson([
            'notifications' => ['recents' => $recents ,'earlier' => $earlier],
            'hasMore' => $hasMoreResults
        ],trans('api.notifications.found'),1);
    }

    /**
     * Base query of notifications
     * 
     * @return object query
     * 
    */

    private function baseQueryNotification($user)
    {
        $gamePath = url(config('constant.GAMEIMAGES'));
        return Notification::where('userId', $user->id)
            ->selectRaw('notifications.id,notifications.userId,notifications.notificationType,
        notifications.title,
        notifications.content,
        notifications.isRead,
        notifications.media as mediaPic,
        notifications.packageName,
        '. ApiHelper::getDateFormate('notifications.createdAt', 'notificationTime') .'')
            ->orderBy('notifications.id', 'desc');
    }

    public function recentsNotification($user)
    {
        $gamePath = url(config('constant.GAMEIMAGES'));
        return Notification::where(['notifications.userId' => $user->id,'notifications.isRead'=> 0])
            ->selectRaw('notifications.id,notifications.userId,notifications.notificationType,
        notifications.title,
        notifications.content,
        notifications.isRead,
        notifications.media as mediaPic,
        notifications.packageName,
        '. ApiHelper::getDateFormate('notifications.createdAt', 'notificationTime') .'')
            ->orderBy('notifications.id', 'desc');
    }

    // not use
    /**
     * Remove notifications
     * @param Request $request
     * 
     * @return Response Json
     * 
     */
    public function removeNotification(Request $request)
    {
        $this->validate($request, [
            'notificationId' => 'required'
        ]);

        $notification = Notification::where('id', $request->notificationId)->first();

        if (empty($notification)) 
        {
            return $this->toJson(null, trans('api.notifications.not_found'), 0);
        }

        if($notification->delete())
        {
            return $this->toJson(null, trans('api.notifications.remove_success'), 1);
        }

        return $this->toJson(null, trans('api.notifications.remove_error'), 0);
    }
}
