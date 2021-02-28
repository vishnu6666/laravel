<?php

namespace App\Console\Commands;

use App\Model\User;
use App\Model\GroupPromocode;
use App\Model\groupPromocodeUser;
use Illuminate\Console\Command;
use App\Mail\SendGroupDiscount;
use Illuminate\Support\Facades\Mail; 

class SendDiscountCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:SendGroupDiscount {groupId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send group discount code';

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
        $groupId = $this->argument('groupId');

        $groupData = GroupPromocode::selectRaw('groups_promocodes.id,groups_promocodes.groupName
                                                ,groups_promocodes.description
                                                ,groups_promocodes.promoCode,groups_promocodes_users.userId
                                                ,groups_promocodes.discountType,groups_promocodes.discountAmount
                                                ,groups_promocodes.endDate
                                                ,groups_promocodes.planId
                                                ,subscription_plans.planName
                                                ,users.email
                                                ,users.name,groups_promocodes_users.id as groups_promocodes_usersId')
                                    ->Join('groups_promocodes_users',function($join){
                                        $join->on('groups_promocodes_users.groupId','=','groups_promocodes.id');
                                        $join->where('groups_promocodes_users.isSend',0);
                                    })
                                    ->leftJoin('subscription_plans',function($join){
                                        $join->on('subscription_plans.id','=','groups_promocodes.planId');
                                    })
                                    ->Join('users',function($join){
                                        $join->on('users.id','=','groups_promocodes_users.userId');
                                    })
                                    ->where('groups_promocodes.id',$groupId)
                                    ->get();
        if (!empty($groupData)) {
            $groups_promocodes_usersId = [];
            foreach($groupData as $key => $group)
            {
                $groups_promocodes_usersId[] = $group['groups_promocodes_usersId'];

                if(is_null($group['planName'])){
                    $group['planName'] = 'Any';
                }

                if($group['discountType'] =='Percentage'){
                    $group['discountAmount'] = $group['discountAmount'].'%';
                }elseif($group['discountType'] =='Flat'){
                    $group['discountAmount'] = $group['discountAmount'].'AUD';
                }

                $group['endDate'] = date("d M Y", strtotime($group['endDate']));

              Mail::to($group->email)->send(new SendGroupDiscount($group));
            }

            \DB::table('groups_promocodes_users')
                ->whereIn('id', $groups_promocodes_usersId)
                ->update(array('isSend' => 1)); 
        }
    }
}
