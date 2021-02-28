<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Game;
use App\Model\Package;
use App\Model\GameTip;
use App\Model\PackagesAsignToGame;
use Carbon\Carbon;
use App\Model\userGameHistory;
use App\Model\UserPackagesSubscriptionHistories;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\DB;


class TipsController extends Controller
{
    /**
     * get UpcomingGameDetail , LatestGameDetail , SpecificGameDetail.
     *
     * @param Request $request
     *
     * @return json
     */

    public function getHome(Request $request)
    {
        $this->validate($request, [
            'limit' => 'required',
        ]);


        $nowDate = Carbon::now()->format('Y-m-d');

        $gameDetailUpcoming = $this->getUpcomingAndLatestGameData()->where('games.isActive',1)->where('games.launchDate','>',$nowDate)->orderBy('games.launchDate','asc')->limit($request->limit)->get();

        $gameDetailLatest = $this->getUpcomingAndLatestGameData()->having('isSubscribed', 1)->having('totalTips', '>', 0)->orderBy('tipDate','desc')->limit($request->limit)->get();
        $authUser = \Auth::guard('api')->user();

        
        $data = $this->getspecificGameData();

        //$data = [];
        $mySport = collect($data)->sortByDesc('id')->where('isSubscribed', 1)->values()->slice(0,$request->limit);
        
        $all = collect($data)->sortBy('id')->slice(0,$request->limit)->values()->toArray();  
        $popular = collect($data)->sortByDesc('totalUsersCount')->slice(0,$request->limit)->values()->toArray();

        $frequent =  collect($data)->sortByDesc('id')->slice(0,$request->limit)->values()->toArray(); 

        return $this->toJson([
            'UpcomingGameDetail' => $gameDetailUpcoming,
            'LatestGameDetail'   => $gameDetailLatest,
            'SpecificGameDetail' => ['mySport'=>  $mySport,'all' => $all, 'popular' => $popular,'frequent' => $frequent ],
        ], trans('api.gametips.homedetail'), 1);

        /*if(!$gameDetailUpcoming->isEmpty() || !$gameDetailLatest->isEmpty() || !$mySport->isEmpty() || !empty($all) || !$popular->isEmpty() || !$frequent->isEmpty())
        {
            
        }*/
        return $this->toJson(null, trans('api.gametips.homeerror'), 0);
    }

    /**
     * getUpcoming And Latest GameData.
     *
     *
     * @return json
     */

    public function getUpcomingAndLatestGameData()
    {
        $gameImagePath = url(config('constant.GAMEIMAGES'));
        $authUser = \Auth::guard('api')->user();
        $todayDate = Carbon::now()->format('Y-m-d');
            return Game::selectRaw('games.id as id,games.gameName as gameName, 
                                games.gameFullName as gameFullName,
                                IF(user_game_history.isSubscribed = 1,1,0) as isSubscribed,
                                IF(user_game_history.isKeepNotification = 1,1,0) as isKeepNotification,
                                IF(games.launchDate = "'.$todayDate.'",1,0) as isLatest,
                                CONCAT("' . $gameImagePath . '/",games.gameImage) as gameImage,
                                games.totalUsersCount AS totalUsersCount,
                                games.gameTotalTips AS totalTips,
                                games_tips.createdAt as tipDate,
                                0 as percentage,
                                DATE_FORMAT(games.launchDate,"%D %M") as gameDate')
                                ->where('isActive','1')
                                ->whereNull('games_tips.deletedAt')

                                ->leftJoin('user_game_history', function ($join) use($authUser){
                                        $join->on('games.id', 'user_game_history.gameId')
                                        ->where('user_game_history.userId', $authUser->id);
                                })

                                ->leftJoin('games_tips', function ($join) {
                                    $join->on('games_tips.gameId', '=', 'games.id');
                                })
                                ->groupBy('games.id');
    }

    /**
     * get specific GameData.
     *
     *
     * @return json
     */

    public function getspecificGameData($search = '', $date = '')
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $gameImagePath = url(config('constant.GAMEIMAGES'));
        $authUser = \Auth::guard('api')->user();
        //->where('user_game_history.userId',$authUser->id) IF(games_tips.createdAt = "'.$todayDate.'",1,0) as isLatest,
        $where = '';
        $join = '';
        $select = '';
        $having = '';
        if(!empty($search))
        {
            $search = "'".'%'.$search.'%'."'";
            $where = ' left join `packages_asign_to_game` on `packages_asign_to_game`.`id` = `games`.`id`
            left join `packages` on `packages_asign_to_game`.`packageId` = `packages`.`id`
            where packages.packageName LIKE  '.$search.'  OR games.gameName LIKE '.$search.' ';
        }

        if(!empty($date))
        {
            $select = 'count(DISTINCT(gt.id)) as totalDateTipsCount,';
            $join =  'left join `games_tips` AS gt on gt.`gameId` = `games`.`id` AND DATE(gt.createdAt) = "'.$date.'"';
            $having = 'HAVING totalDateTipsCount > 0';
        }

        $data = \DB::select('select games.id,games.gameName,games.gameFullName,
                                        CONCAT("' . $gameImagePath . '/",games.gameImage) as gameImage,
                                        games.totalUsersCount as totalUsersCount,
                                        games.gameTotalTips as totalTips,
                                        IF(`ugh`.`isKeepNotification` = 1,1,0) as isKeepNotification,
                                        '.$select.'
                                        IF(COUNT(DISTINCT(user_messages.id)) > 0, 1, 0) as isLatest,
                                        0 as percentage,
                                        IF(user_game_history.userId = "'.$authUser->id.'",1,0) as isSubscribed from `games` 
                                        join `user_game_history` on `games`.`id` = `user_game_history`.`gameId` and `isActive` = 1
        left join `user_game_history` AS `ugh` on `games`.`id` = `ugh`.`gameId` and `ugh`.`userId`= "'.$authUser->id.'" and  `isActive` = 1
        left join `user_messages` on `user_messages`.`gameId` = `games`.`id` and `user_messages`.`isRead` = 0 and `user_messages`.`userId` = "'.$authUser->id.'" 
        left join `games_tips` on `games_tips`.`gameId` = `games`.`id` AND games_tips.deletedAt IS NULL
        '.$join.'
        '.$where.'
        group by `games`.`id` '.$having.' order by `games`.`id`');

        return $data;
    }

    /**
     * get All Upcoming Game.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getAllUpcomingGame(Request $request)
    {
        $nowDate = Carbon::now()->format('Y-m-d');

        $gameDetailUpcoming = $this->getUpcomingAndLatestGameData()->where('games.launchDate','>',$nowDate)->orderBy('games.id','desc')->get();
        if(!$gameDetailUpcoming->isEmpty())
        {
            return $this->toJson([
                'UpcomingGameDetail' => $gameDetailUpcoming
            ], trans('api.gametips.upcomingdetail'), 1);
        }
        return $this->toJson(null, trans('api.gametips.upcomingdetailerror'), 0);   
    }

    
    /**
     * get tips with filter.
     *
     * @param Request $request
     *
     * @return json
     */

    public function getTipswithFilter(Request $request)
    {
        $this->validate($request, [
            'filter' => 'required|in:all,today,yesterday,mysport,popular,frequent'
        ]);

        $joinType = 'leftJoin';
        $todayDate = Carbon::now()->format('Y-m-d');

        $gameImagePath = url(config('constant.GAMEIMAGES'));


        $authUser = \Auth::guard('api')->user();
        $date = '';
        if($request->filter == 'today')
        {
            $date = Carbon::now()->format('Y-m-d');
        }
        else if($request->filter == 'yesterday')
        {
            $date = Carbon::yesterday()->toDateString();
        }

        $searchSport='';
        if($request->searchSport != '')
        {
            $searchSport = $request->searchSport;
        }
        
        $data = $this->getspecificGameData($searchSport,$date);
        //$data = [];

        if($request->filter == 'mysport')
        {
            $gameTipsDetail = collect($data)->where('isSubscribed', 1)->values();
        }
        else if($request->filter == 'popular')
        {
            $gameTipsDetail = collect($data)->sortByDesc('totalUsersCount')->values();
        }
        else if($request->filter == 'frequent')
        {
            $gameTipsDetail = collect($data)->sortByDesc('id')->values();
        }
        else if($request->filter == 'all')
        {
            $gameTipsDetail = collect($data)->sortByDesc('id')->values();
        }
        else
        {
            $gameTipsDetail = collect($data)->sortByDesc('id')->values();

        }


        if(!$gameTipsDetail->isEmpty())
        {
            return $this->toJson([
                'gameTipsDetail' => $gameTipsDetail,
            ], trans('api.gametips.detail'), 1);
        }
        return $this->toJson(null, trans('api.gametips.error'), 0);
    }

    /**
     * get game tips 
     *
     * @param Request $request
     * @return json
     */
    public function getTips(Request $request)
    {
        $this->validate($request, [
            'indexId' => 'required',
            'isGreater' => 'required',
            'gameId' => 'required'
        ]);

        $gameImagePath = url(config('constant.GAMEIMAGES'));

        $authUser = \Auth::guard('api')->user();
        $isSubscribedGame = userGameHistory:: select('*')->where('userId',$authUser->id )->where('gameId',$request->gameId )->get();

        if(!$isSubscribedGame->isEmpty())
        {
        
        $qry = "";
        $dateFormat = config('constant.DAY_MONTH_DATE_FORMAT');

        if($request->isGreater == 0)
        {
            $qry = "SELECT games_tips.id,DATE_FORMAT(games_tips.createdAt,'%d %b %Y') as tipsDate,games_tips.createdAt,". ApiHelper::getTipsDateFormate('games_tips.createdAt', 'tipsTime') .",games_tips.tips,games_tips.odds,games_tips.units,1 as isWin,games_tips.IsWin as winLoss,games_tips.profitLosForTips,games_tips.weeklyProfitLos,games_tips.weeklyPot,games_tips.monthlyProfitLos,games_tips.monthlyPot,games_tips.allTimeProfitLos,games_tips.allTimePot,games_tips.tipsImage,games_tips.text,games_tips.gameId,DATE_FORMAT(games_tips.date,'%d %b %Y') as gameTipsDate FROM games_tips
                    WHERE games_tips.id < $request->indexId
                    AND games_tips.gameId = $request->gameId
                    AND games_tips.deletedAt IS NULL
                    ORDER BY games_tips.id ASC LIMIT 10";
                    $gameTips = DB::select($qry);
        }
        else
        {
            $qry = "SELECT games_tips.id,DATE_FORMAT(games_tips.createdAt,'%d %b %Y') as tipsDate,games_tips.createdAt,". ApiHelper::getTipsDateFormate('games_tips.createdAt', 'tipsTime') .",games_tips.tips,games_tips.odds,games_tips.units,1 as isWin,games_tips.IsWin as winLoss,games_tips.profitLosForTips,games_tips.weeklyProfitLos,games_tips.weeklyPot,games_tips.monthlyProfitLos,games_tips.monthlyPot,games_tips.allTimeProfitLos,games_tips.allTimePot,games_tips.tipsImage,games_tips.text,games_tips.gameId,DATE_FORMAT(games_tips.date,'%d %b %Y') as gameTipsDate FROM games_tips
                    WHERE games_tips.id > $request->indexId
                    AND games_tips.gameId = $request->gameId
                    AND games_tips.deletedAt IS NULL
                    ORDER BY games_tips.id DESC LIMIT 10";
                    $gameTips =DB::select($qry);
                    sort($gameTips);
        }
            if(!empty($gameTips))
                {
                    return $this->toJson([
                        'gameTipsDetail' => $gameTips,
                    ], trans('api.gametips.detail'), 1);
                }
            return $this->toJson(null, trans('api.gametips.error'), 0);
        }

        return $this->toJson(null, trans('api.gametips.notsubscribederror'), 0);

    }

    /**
     * get Mysport with tips count.
     *
     * @param Request $request
     * @return json
     */
    public function getMysport(Request $request)
    {
        $gameImagePath = url(config('constant.GAMEIMAGES'));
        $authUser = \Auth::guard('api')->user();
        $todayDate = Carbon::now()->format('Y-m-d');

            $mySportDetails = Package::selectRaw('packages.id,packages.packageName,IF(MAX(user_packages_subscription_histories.isNew = 1),1,0) as isNew')
            ->Join('user_packages_subscription_histories', function ($join) use ($authUser) {
                $join->on('user_packages_subscription_histories.sportPackageId', '=', 'packages.id')
                        ->where('user_packages_subscription_histories.userId',$authUser->id); 
            })
            ->with(['games'=> function($qry) use($gameImagePath,$todayDate, $authUser){
                $qry->selectRaw('packages_asign_to_game.gameId as id ,
                packages_asign_to_game.gameId,
                packages_asign_to_game.packageId,
                CONCAT(games.gameName," ") as gameName,
                games.gameFullName,
                CONCAT("' . $gameImagePath . '/",games.gameImage) as gameImage,
                games.totalUsersCount AS totalUsersCount,
                games.gameTotalTips AS totalTips,
                IF(user_game_history.isSubscribed = 1,1,0) as isSubscribed,
                IF(ugh.isKeepNotification = 1,1,0) as isKeepNotification,
                IF(games.launchDate = "'.$todayDate.'",1,0) as isLatest,
                0 as percentage')
                ->leftJoin('games', function ($join) {
                    $join->on('games.id', '=', 'packages_asign_to_game.gameId');
                })
                ->leftJoin('games_tips', function ($join) {
                    $join->on('games_tips.gameId', '=', 'games.id');
                })
                ->leftJoin('user_game_history', function ($join) {
                    $join->on('user_game_history.gameId', '=', 'games.id');
                })
                ->leftJoin('user_game_history as ugh', function ($join) use ($authUser) {
                    $join->on('games.id', '=', 'ugh.gameId')->where('isActive','1')
                         ->where('ugh.userId', $authUser->id);
                })
                ->groupBy('games.id')
                ->whereNull('games_tips.deletedAt')
                ->where('isActive',1)                      
                ->get();
            }])
            ->groupBy('user_packages_subscription_histories.sportPackageId')   
            ->orderBy('packages.id','asc') 
            ->get();
            
        if(!empty($mySportDetails))
        {
            UserPackagesSubscriptionHistories::where('userId',$authUser->id)->update(['isNew' => 0]);
            return $this->toJson([
                'mySportDetails' => $mySportDetails,
            ], trans('api.getMysport.detail'), 1);
        }
        return $this->toJson(null, trans('api.getMysport.error'), 0);
        
    }

    /**
     *  keep Notification status change for game
     *
     * @param Request $request
     * @return json
     */
    public function keepNotification(Request $request)
    {
        $this->validate($request, [
            'gameId'    => 'required',
            'isKeepNotification' => 'required|in:1,0'
        ]);

        $user = \Auth::guard('api')->user();

        $isKeepNotification = $request->isKeepNotification;

        if(userGameHistory::where(
                                    ['userId' => $user->id,'gameId' => $request->gameId]
                                )->update(['isKeepNotification' => $isKeepNotification]))
        {
            return $this->toJson(['isKeepNotification' => $isKeepNotification], trans('api.gametips.keepNotificationupdate'), 1);   
        }
        return $this->toJson(null, trans('api.gametips.keepNotificationerror'), 0);   
    }

    /**
     * get search Sport 
     *
     * @param Request $request
     * @return json
     */
    public function searchSport(Request $request)
    {
        $this->validate($request, [
            'searchSport'    => 'required'
        ]);

        $gameImagePath = url(config('constant.GAMEIMAGES'));
        $authUser = \Auth::guard('api')->user();
        $searchPackage = Package::where('packageName','LIKE','%'.$request->searchSport.'%')
                                    ->pluck('id')
                                    ->toArray();

        if($searchPackage){             
            $searchSportDetails = Package::selectRaw('id,packageName')
                                ->with(['games'=> function($qry) use($gameImagePath){
                                    $qry->selectRaw('packages_asign_to_game.gameId,packages_asign_to_game.packageId,
                                    games.gameName,games.gameFullName,
                                    CONCAT("' . $gameImagePath . '/",games.gameImage) as gameImage,
                                    games.totalUsersCount AS totalUsersCount,
                                    games.gameTotalTips AS totalTips,
                                    IF(user_game_history.isSubscribed = 1,1,0) as isSubscribed,
                                    IF(user_game_history.isKeepNotification = 1,1,0) as isKeepNotification,
                                    0 as percentage')
                                    ->leftJoin('games', function ($join) {
                                        $join->on('games.id', '=', 'packages_asign_to_game.gameId');
                                    })
                                    ->leftJoin('games_tips', function ($join) {
                                        $join->on('games_tips.gameId', '=', 'games.id');
                                        $join->whereNull('games_tips.deletedAt');
                                    })
                                    ->leftJoin('user_game_history', function ($join) {
                                        $join->on('user_game_history.gameId', '=', 'games.id');
                                    })
                                    ->groupBy('games.id')                      
                                    ->get();
                                }])
                                
                                ->whereIn('id',$searchPackage)     
                                ->get();
            if(!empty($searchSportDetails))
            {
                return $this->toJson([
                    'searchSportDetails' => $searchSportDetails,
                ], trans('api.searchSport.detail'), 1);
            }
            return $this->toJson(null, trans('api.searchSport.error'), 0);
        }
        return $this->toJson(null, trans('api.searchSport.error'), 0);
        
    }

    /**
     * get Compare Sport with tips count.
     *
     * @param Request $request
     * @return json
     */
    public function getCompareSport(Request $request)
    {
        $gameImagePath = url(config('constant.GAMEIMAGES'));
        $authUser = \Auth::guard('api')->user();
        $todayDate = Carbon::now()->format('Y-m-d');

            $comparesportDetails = Package::selectRaw('packages.id,packages.packageName,IF(MAX(user_packages_subscription_histories.isNew = 1),1,0) as isNew')
            ->Join('user_packages_subscription_histories', function ($join) use ($authUser) {
                $join->on('user_packages_subscription_histories.sportPackageId', '=', 'packages.id')
                        ->where('user_packages_subscription_histories.userId',$authUser->id); 
            })
            ->with(['games'=> function($qry) use($gameImagePath,$todayDate){
                $qry->selectRaw('packages_asign_to_game.gameId as id ,
                packages_asign_to_game.gameId,
                packages_asign_to_game.packageId,
                CONCAT(games.gameName," ") as gameName,
                games.gameFullName,
                CONCAT("' . $gameImagePath . '/",games.gameImage) as gameImage,
                games.totalUsersCount AS totalUsersCount,
                games.gameTotalTips AS totalTips,
                IF(games.launchDate = "'.$todayDate.'",1,0) as isLatest,
                0 as percentage,
                0 AS accuracyRate')
                ->leftJoin('games', function ($join) {
                    $join->on('games.id', '=', 'packages_asign_to_game.gameId');
                })
                ->leftJoin('games_tips', function ($join) {
                    $join->on('games_tips.gameId', '=', 'games.id');
                })
                ->leftJoin('games_tips AS gt', function ($join) {
                    $join->on('gt.gameId', '=', 'games.id')->whereRaw('gt.spreadsheetsId IS NOT NULL');
                })
                ->leftJoin('user_game_history', function ($join) {
                    $join->on('user_game_history.gameId', '=', 'games.id');
                })
                ->whereNull('gt.deletedAt')
                ->groupBy('games.id')                      
                ->where('isActive',1)                      
                ->get();
            }])
            ->groupBy('user_packages_subscription_histories.sportPackageId')   
            ->orderBy('packages.id','asc') 
            ->get();
            
        if(!empty($comparesportDetails))
        {
            UserPackagesSubscriptionHistories::where('userId',$authUser->id)->update(['isNew' => 0]);
            return $this->toJson([
                'compareSportDetails' => $comparesportDetails,
            ], trans('api.compareSportDetails.detail'), 1);
        }
        return $this->toJson(null, trans('api.compareSportDetails.error'), 0);
        
    }

    /**
     * get tips with compare
     *
     * @param Request $request
     * @return json
     */
    public function getTipsCompare(Request $request)
    {
        $this->validate($request, [
            'indexId' => 'required',
            'isGreater' => 'required',
            'gameId' => 'required'
        ]);

        $gameImagePath = url(config('constant.GAMEIMAGES'));

        $authUser = \Auth::guard('api')->user();
        $isSubscribedGame = userGameHistory:: select('*')->where('userId',$authUser->id )->where('gameId',$request->gameId )->get();

        if(!$isSubscribedGame->isEmpty())
        {
        
        $qry = "";
        $dateFormat = config('constant.DAY_MONTH_DATE_FORMAT');

        if($request->isGreater == 0)
        {
            $qry = "SELECT games_tips.id,DATE_FORMAT(games_tips.createdAt,'%d %b %Y') as tipsDate,games_tips.createdAt,". ApiHelper::getTipsDateFormate('games_tips.createdAt', 'tipsTime') .",games_tips.gameId,CONCAT(ROUND(games_tips.pot,2)) as pot,CONCAT(games_tips.accuracyRate,' %') AS accuracyRate,games_tips.tips,games_tips.odds,games_tips.units,1 as isWin,games_tips.IsWin as winLoss,games_tips.profitLosForTips,games_tips.weeklyProfitLos,games_tips.weeklyPot,games_tips.monthlyProfitLos,games_tips.monthlyPot,games_tips.allTimeProfitLos,games_tips.allTimePot,games_tips.tipsImage,games_tips.text,DATE_FORMAT(games_tips.date,'%d %b %Y') as gameTipsDate FROM games_tips
                    WHERE games_tips.id < $request->indexId
                    AND games_tips.gameId = $request->gameId
                    AND  games_tips.spreadsheetsId IS NOT NULL
                    AND games_tips.deletedAt IS NULL
                    ORDER BY games_tips.id ASC LIMIT 10";
                    $gameTips = DB::select($qry);
        }
        else
        {
            $qry = "SELECT games_tips.id,DATE_FORMAT(games_tips.createdAt,'%d %b %Y') as tipsDate,games_tips.createdAt,". ApiHelper::getTipsDateFormate('games_tips.createdAt', 'tipsTime') .",games_tips.gameId,CONCAT(ROUND(games_tips.pot,2)) as pot,CONCAT(games_tips.accuracyRate,' %') AS accuracyRate,games_tips.tips,games_tips.odds,games_tips.units,1 as isWin,games_tips.IsWin as winLoss,games_tips.profitLosForTips,games_tips.weeklyProfitLos,games_tips.weeklyPot,games_tips.monthlyProfitLos,games_tips.monthlyPot,games_tips.allTimeProfitLos,games_tips.allTimePot,games_tips.tipsImage,games_tips.text,DATE_FORMAT(games_tips.date,'%d %b %Y') as gameTipsDate FROM games_tips
                    WHERE games_tips.id > $request->indexId
                    AND games_tips.gameId = $request->gameId
                    AND  games_tips.spreadsheetsId IS NOT NULL
                    AND games_tips.deletedAt IS NULL
                    ORDER BY games_tips.id DESC LIMIT 10";
                    $gameTips =DB::select($qry);
                    sort($gameTips);
        }
            if(!empty($gameTips))
                {
                    return $this->toJson([
                        'compareTipsDetail' => $gameTips,
                    ], trans('api.comparetips.detail'), 1);
                }
            return $this->toJson(null, trans('api.comparetips.error'), 0);
        }

        return $this->toJson(null, trans('api.comparetips.notsubscribederror'), 0);

    }

}
