<?php

namespace App\Helpers;
use App\Model\User;
use App\Model\Game;
use App\Model\GameTip;
use App\Model\Attendee;
use App\Model\TravelFlight;
use Illuminate\Support\Str;
use App\Models\LessonRating;
use App\Helpers\CommonHelper;
use Carbon\Carbon;
use App\Model\SubscriptionHistories;
use App\Model\UserSubscriptionHistories;
use App\Model\ContactUs;
class ReportHelper
{
	/**
	*
	* get report information
	*
	* @param $request
	* @return
	**/
	public static function getReportInfo($request)
    {
      //  dd($request->all());
       $rangedate = '' ;

      $daterange = explode(' - ', $request->daterange);
      $startDate = Carbon::parse($daterange[0])->format('Y-m-d');
      $endDate = Carbon::parse($daterange[1])->format('Y-m-d');


       if(!empty($startDate))
       {
          $startDate =date("d-M-Y", strtotime($startDate) );
          $endDate = date("d-M-Y", strtotime($endDate) );
          $rangedate = $startDate.' to '.$endDate;

       }
      return  $levelName = [

         //  'date' => date("d-M-Y", strtotime($request->rangedate)),
          'rangeDate' => $rangedate,

       ];
    }

   /**
    *
    * User report query
    *
    * @param $request
    * @return $userInfos
    *
    **/
	public static function userReportExport($request)
	{

      // echo "<pre>";
      // print_r($request->all());
      // exit;

      $daterange = explode(' - ', $request->daterange);
      //dd($daterange); 
      $startDate = Carbon::parse($daterange[0])->format('Y-m-d');
      $endDate = Carbon::parse($daterange[1])->format('Y-m-d');

      $userInfos = User::whereRaw('DATE_FORMAT(users.createdAt,"%Y-%m-%d") between "' . $startDate . '" and "' . $endDate . '"');


      $userInfos =  $userInfos->selectRaw('id,name,email,mobileNumber,referralCode')
                     ->where('userType', 'User')
                     ->orderBy('createdAt','desc')
                     ->get();
            
      return $userInfos;
   }


   public static function gameReportExport($request)
   {
      $daterange = explode(' - ', $request->daterange);
      //dd($daterange); 
      $startDate = Carbon::parse($daterange[0])->format('Y-m-d');
      $endDate = Carbon::parse($daterange[1])->format('Y-m-d');

      $gameReport = Game::whereRaw('DATE_FORMAT(games.createdAt,"%Y-%m-%d") between "' . $startDate . '" and "' . $endDate . '"');
      $gameReport =  $gameReport->selectRaw('id,gameName,gameFullName,DATE_FORMAT(games.launchDate,"%d %b %Y") as launchDate')
      ->orderBy('createdAt', 'desc')
      ->get();

      return $gameReport;
   }
   


   public static function gameTripReportExport($request)
   {
      $daterange = explode(' - ', $request->daterange);
      //dd($daterange); 
      $startDate = Carbon::parse($daterange[0])->format('Y-m-d');
      $endDate = Carbon::parse($daterange[1])->format('Y-m-d');

      $gameTrip = GameTip::whereRaw('DATE_FORMAT(games_tips.createdAt,"%Y-%m-%d") between "' . $startDate . '" and "' . $endDate . '"');
      $gameTrip =  $gameTrip->selectRaw('packages.packageName,games.gameName,tips,games_tips.odds,games_tips.units,
                     games_tips.IsWin,games_tips.profitLosForTips,
                     games_tips.profitLosForDay, games_tips.dailyPot,games_tips.profitLossCumulative,
                     games_tips.pot,profitOneUnit,profitTwoUnit,profitThreeUnit,text
                  ')
                     ->join('packages', 'packages.id', 'games_tips.packageId')
                     ->join('games', 'games.id', 'games_tips.gameId')
  
         ->orderBy('games_tips.createdAt', 'desc')
         ->get();

      return $gameTrip;
   }


   public static function paymentTransactionExport($request)
   {

      $daterange = explode(' - ', $request->daterange);
      //dd($daterange); 
      $startDate = Carbon::parse($daterange[0])->format('Y-m-d');
      $endDate = Carbon::parse($daterange[1])->format('Y-m-d');


      $paymentTransaction = UserSubscriptionHistories::whereRaw('DATE_FORMAT(user_subscription_histories.createdAt,"%Y-%m-%d") between "' . $startDate . '" and "' . $endDate . '"');
      $paymentTransaction = $paymentTransaction->selectRaw('user_subscription_histories.id,users.name,user_subscription_histories.planName,
                                                        user_subscription_histories.planType,user_subscription_histories.planAmount,
                                                        user_subscription_histories.amount,user_subscription_histories.subscriptionExpiryDate,
                                                        user_subscription_histories.subscriptionValidity,
                                                        user_subscription_histories.isTrial,user_subscription_histories.createdAt')
      ->leftJoin('users', function ($query) {
         $query->on('users.id', '=', 'user_subscription_histories.userId');
      })
         ->where('isTrial', 0)->orderBy('user_subscription_histories.id', 'desc')->get();


      return $paymentTransaction;

   }


   public static function contactUsExport($request)
   {

      // echo "<pre>";
      // print_r($request->all());
      // exit;

      $daterange = explode(' - ', $request->daterange);
      //dd($daterange); 
      $startDate = Carbon::parse($daterange[0])->format('Y-m-d');
      $endDate = Carbon::parse($daterange[1])->format('Y-m-d');

      $contactUs = ContactUs::whereRaw('DATE_FORMAT(contact_us.createdAt,"%Y-%m-%d") between "' . $startDate . '" and "' . $endDate . '"');


      $contactUs =  $contactUs->selectRaw('contact_us.id,contact_us.userId,contact_us.isReply,contact_us.subject,contact_us.message,contact_us.reply_message,contact_us.createdAt,users.name')
      ->leftJoin('users', 'users.id', 'contact_us.userId')
      ->orderBy('createdAt', 'desc')
      ->get();

      return $contactUs;
   }

   public static function gameResultReportExport($request)
   {
      $daterange = explode(' - ', $request->daterange);
      $startDate = Carbon::parse($daterange[0])->format('Y-m-d');
      $endDate = Carbon::parse($daterange[1])->format('Y-m-d');
      $gameTrip = GameTip::whereRaw('DATE_FORMAT(games_tips.createdAt,"%Y-%m-%d") between "' . $startDate . '" and "' . $endDate . '"');
      $gameTrip =  $gameTrip->selectRaw('users.name,packages.packageName,games.gameName,tips,games_tips.odds,games_tips.units,
                     games_tips.IsWin,games_tips.profitLosForTips,
                     games_tips.profitLosForDay, games_tips.dailyPot,games_tips.profitLossCumulative,
                     games_tips.pot,profitOneUnit,profitTwoUnit,profitThreeUnit,text
                  ')
                  ->where('games_tips.spreadsheetsId', '<>', '')
                  ->join('packages', 'packages.id', 'games_tips.packageId')
                  ->join('users', 'games_tips.userId', 'users.id')
                  ->join('games', 'games.id', 'games_tips.gameId')
  
         ->orderBy('games_tips.createdAt', 'desc')
         ->get();

      return $gameTrip;
   }

}
