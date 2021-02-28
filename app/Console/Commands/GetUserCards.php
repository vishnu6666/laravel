<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\StripeHelper;
use App\Model\User;
use App\Model\UserCard;

class GetUserCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getUserCards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get user card from payment gatway';

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
        $userCardData = User::whereNotNull('stripeId')->whereNull('cardToken')->whereNull('deletedAt')->get();
        $usersArray = array();
        foreach($userCardData as $key => $userdata){
                array_push($usersArray,$userdata->id);
                $cardDatas = StripeHelper::getAllCards($userdata);

                $cardsData = [];
                foreach ($cardDatas as $keydata => $card) {
                    $cardsData[] = [
                        'userId'        => $userdata->id,
                        'cardHolderName'=> $card['cardHolderName'],
                        'last4'         => $card['last4'],
                        'cardType'      => $card['cardType'],
                        'expiryDate'    => $card['expiryDate'],
                        'cardToken'     => $card['cardToken'],
                        'isDefault'   => $keydata == 0 ? 1 : 0,
                        'cardImage'     => $card['cardImage'],
                    ];
                }

                UserCard::insert($cardsData);
        }
        $usersDefaultCard = UserCard::selectRaw('userId,cardToken')->whereIn('userId',$usersArray)->where('isDefault',1)->get();

        foreach($usersDefaultCard as $defaultCard){
            User::where(['id' => $defaultCard['userId']])->update(['cardToken' => $defaultCard['cardToken']]);
        }
    }
}
