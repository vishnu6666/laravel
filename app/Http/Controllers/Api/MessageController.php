<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Game;
use App\Model\Package;
use App\Model\UserMessage;
use App\Model\UserPackagesSubscriptionHistories;
use App\Model\PackagesAsignToGame;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiHelper;

class MessageController extends Controller
{
    /**
     * get get packages wise count messages.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getPackagesWithMessageCount(Request $request)
    {
        $authUser = \Auth::guard('api')->user();
        $packagesMessagesDetail = Package:: selectRaw('packages.id,MAX(packages.packageName) as packageName,
                                                    group_concat(DISTINCT(games.gameName)) as gameName,
                                                    IF(MAX(user_packages_subscription_histories.sportPackageId),1,0) as isSubscribed,
                                                    '. ApiHelper::getDateFormate('user_messages.createdAt', 'messagesTime') .'
                                                    ')
                                            ->where('packages.isActive',1)
                                                    
                                            ->leftJoin('user_messages', function ($join) use($authUser) {
                                                $join->on('packages.id', '=', 'user_messages.sportPackageId');
                                                $join->where('user_messages.userId',$authUser->id);
                                                $join->where('user_messages.isRead',0);
                                            })

                                            ->leftJoin('packages_asign_to_game', function ($join) {
                                                $join->on('packages_asign_to_game.packageId', '=', 'packages.id');
                                                
                                            })

                                            ->leftJoin('games', function ($join) {
                                                $join->on('games.id', '=', 'packages_asign_to_game.gameId');
                                                $join->where('games.isActive',1);
                                            })

                                            ->leftJoin('user_packages_subscription_histories', function ($join) use($authUser) {
                                                $join->on('packages.id', '=', 'user_packages_subscription_histories.sportPackageId');
                                                $join->where('user_packages_subscription_histories.userId',$authUser->id);
                                            })
                                            ->orderBy('packages.createdAt')
                                            ->groupBy('packages.id')->get();
                                            
        if(!empty($packagesMessagesDetail))
        {
            
            foreach($packagesMessagesDetail as $key => $value){
                $packagesMessagesDetail[$key]['gameName'] = str_replace(',',', ',$value->gameName);
                $packagesMessagesDetail[$key]['messageCount'] = UserMessage::where('sportPackageId',$value->id)->where('userId',$authUser->id)->where('isRead',0)->count();
            }
            return $this->toJson([
                'MessageDetail' => $packagesMessagesDetail,
            ], trans('api.packagesmessages.detail'), 1);
        }

        return $this->toJson(null, trans('api.packagesmessages.error'), 0);

    }

    /**
     * get Messages package wise 
     *
     * @param Request $request
     * @return json
     */
    public function getMessages(Request $request)
    {
        $gameImagePath = url(config('constant.GAMEIMAGES')).'/';
        $this->validate($request, [
            'sportPackageId' => 'required',
            'isGreater' => 'required',
            'indexId' => 'required'
        ]);

        $authUser = \Auth::guard('api')->user();
        $dateTimeFormate = url(config('constant.DATE_TIME_FORMAT'));

        $getUserPackages = UserPackagesSubscriptionHistories::where('userId',$authUser->id)->where('sportPackageId',$request->sportPackageId )->first();

        if(!empty($getUserPackages))
        {

        $qry = "";
      
        if($request->isGreater == 0)
        {
            $qry = "SELECT user_messages.id,games.gameName,games.gameFullName,CONCAT('$gameImagePath',games.gameImage) as gameImage,user_messages.content,user_messages.isRead,". ApiHelper::getMessageDateFormate('user_messages.createdAt', 'messageDate') .",user_messages.createdAt, DATE_FORMAT(user_messages.createdAt,'%a,%d %b,%H:%i %p') as messagesTime FROM user_messages 
            LEFT JOIN games ON 
            games.id = user_messages.gameId
            WHERE user_messages.id < $request->indexId 
                    AND user_messages.userId = $authUser->id
                    AND user_messages.sportPackageId = $request->sportPackageId 
                    ORDER BY user_messages.id DESC LIMIT 10";
                    $getMessages = DB::select($qry);
        }
        else
        {
            $qry = "SELECT user_messages.id,games.gameName,games.gameFullName,CONCAT('$gameImagePath',games.gameImage) as gameImage,user_messages.content,user_messages.isRead,". ApiHelper::getMessageDateFormate('user_messages.createdAt', 'messageDate') .",user_messages.createdAt, DATE_FORMAT(user_messages.createdAt,'%a,%d %b,%H:%i %p') as messagesTime FROM user_messages
            LEFT JOIN games ON 
            games.id = user_messages.gameId        
            WHERE user_messages.id > $request->indexId 
                    AND user_messages.sportPackageId = $request->sportPackageId 
                    AND user_messages.userId = $authUser->id
                    ORDER BY user_messages.id DESC LIMIT 10";
                    $getMessages =DB::select($qry);
                    sort($getMessages);
        }

            if(!empty($getMessages))
                {
                     //update isRead
                     UserMessage::where('sportPackageId',$request->sportPackageId)->where('userId',$authUser->id)->update(['isRead' => 1]);

                    return $this->toJson([
                        'messageDetail' => $getMessages,
                    ], trans('api.packagesMessage.detail'), 1);

                }
            return $this->toJson(null, trans('api.packagesMessage.error'), 0);
        }
        return $this->toJson(null, trans('api.packagesMessage.not_found'), 0);
    }

}
