<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use App\Model\Game;
use App\model\GameTip;
use App\model\userGameHistory;
use App\model\PackagesAsignToGame;
use App\model\UserPackagesSubscriptionHistories;
use App\model\SubscriptionHistories;

class HomeController extends Controller
{
    // send custom notification by command
    public function customNotification()
    {
        $cmd = 'cd ' . base_path() . ' && php artisan command:customNotification';
        exec($cmd . ' > /dev/null &');
    }

    // free trial extend
    public function importTrial()
    {
        echo "importTrial";exit;
        dd('ok');
       // SELECT * FROM subscription_histories WHERE subscriptionExpiryDate < CURDATE() ORDER BY `userId` ASC
       //$recordCount= \DB::table('subscription_histories')->selectRaw('id,userId,planId')->whereDate('subscriptionExpiryDate', '<', date('Y-m-d'))->get();
       $recordCount = \DB::table('subscription_histories')->selectRaw('id,userId,planId,subscriptionExpiryDate,isTrial')->where(['isTrial' => 1])->whereDate('subscriptionExpiryDate', '<=', '2020-10-31')->get();

       $daysToAdd  = 7;
       foreach ($recordCount as $key => $value) {

            $cmd = 'cd ' . base_path() . ' && php artisan mail:sendFreeTrialInfoCommand ' . $value->userId . ' ';
            exec($cmd . ' > /dev/null &');

            SubscriptionHistories::where('id', $value->id)
            ->update([
                'subscriptionExpiryDate' => date('Y-m-d',strtotime('+7 day'))
            ]);

        $sportPackageId =[1,2,10,11];
        $userId=$value->userId;
        $subscriptionhistoriesId=$value->id;

        $packagesData = [];
        foreach ($sportPackageId as $packageKey => $package) {
            $packagesData[] = [
                'userId'                    => $userId,
                'subscriptionHistoriesId'   => $subscriptionhistoriesId,
                'sportPackageId'            => $package,
                'isTrial'                   => 1
            ];
        }

        UserPackagesSubscriptionHistories::insert($packagesData);

        // end user_packages_subscription_histories

        // start packages wise user game history

        $packageByGameData = PackagesAsignToGame::whereIn('packageId', $sportPackageId)
                            ->pluck('gameId')->toArray();
        $gameData = [];
        foreach ($packageByGameData as $gameKey => $game) {
            $gameData[] = [
                'userId'                    => $userId,
                'subscriptionHistoriesId'   => $subscriptionhistoriesId,
                'gameId'                    => $game,
                'isTrial'                   => 1,
                'isSubscribed'              => 1
            ];
        }

        userGameHistory::insert($gameData);
        }
        echo "SuccessFull";
    }

    public function checkIsGameAvailble($gamename)
    {
        $isCheckGameAvailable = Game::where('gameName',$gamename)->first();
        if(!empty($isCheckGameAvailable)){
            return $isCheckGameAvailable->id;
        }
    }

    public function checkIsTipsAvailble($result){
        $isCheckTipsAvailable = GameTip::where(['gameId' => $result['gameId']])->first();
        if(!empty($isCheckTipsAvailable)){
            return true;
        }else{
            return false;
        }
    }

}
