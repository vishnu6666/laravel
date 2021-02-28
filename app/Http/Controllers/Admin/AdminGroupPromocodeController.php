<?php

namespace App\Http\Controllers\Admin;

use App\Model\Promocode;
use App\Model\GroupPromocode;
use App\Model\groupPromocodeUser;
use App\Model\User;
use App\Model\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\CommonHelper;

class AdminGroupPromocodeController extends Controller
{
    /**
     * ValidationRules for group
     * @var array
    */
    private $validationRules = [
        'groupName' => 'required|max:250',
        'description' => 'required|max:300',
        'promoCode' => 'required|max:90',
        'discountType' => 'required|in:Percentage,Flat',
        'discountAmount' => 'required',
        'promo_date_range' =>'required',
    ];

    /**
     * Display a listing of the group.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.group_promocode.index');
    }

    /**
     * Load  group content
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request){

        if($request->ajax()){
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"','',$request->columns[$orderColumnId]['name']);

            $groups = GroupPromocode::selectRaw('groups_promocodes.*');
                                      
            $groups=$groups->where(function ($query) use ($request){
                $query->orWhere('groups_promocodes.promoCode', 'like', '%'.$request->search['value'].'%');
                    
            });

            $groups = $groups->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $groups['recordsFiltered'] = $groups['recordsTotal'] = $groups['total'];

            foreach ($groups['data'] as $key=> $group){
                
                $params = [
                    'group' => $group['id']
                ];

                $groups['data'][$key]['sr_no'] = $startNo+$key;

                $editRoute = route('groups.edit', $params);
                $statusRoute = route('groups.status', $params);
                $deleteRoute = route('groups.destroy', $params);
                $viewRoute = route('groups.show', $params);
                $usersRoute = route('groups.users', $params);

                $status = ($group['isActive']) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">
                Inactive</span>';
                $groups['data'][$key]['action'] = '<a href="'.$usersRoute.'" class="btn btn-primary" title="View group Members"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
                $groups['data'][$key]['action'] .= '<a href="'.$viewRoute.'" class="btn btn-primary" title="Create Members"><i class="fa fa-plus-circle"></i></a>&nbsp&nbsp';
                $groups['data'][$key]['action'] .= '<a href="'.$editRoute.'" class="btn btn-success" title="Edit group Information"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
                $groups['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Group Information Delete" title="Delete"><i class="fas fa-trash"></i></a>';
                $groups['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>'; 

                $startDate = date('d M Y',strtotime($group['startDate']) );
                $endDate= date('d M Y',strtotime($group['endDate']) );

                $rangeDate = $startDate .' to '.$endDate;
                $groups['data'][$key]['rangeDate'] =  $rangeDate;

                if($group['discountType'] == 'Percentage')
                {
                    $groups['data'][$key]['discountAmount'] =  $group['discountAmount'].'%';
                }
                else
                {
                    $groups['data'][$key]['discountAmount'] =  "$" .' '. $group['discountAmount'];
                }
            }

            return response()->Json($groups);
        }
    }


    /**
     * Show the form for creating a new group.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscriptionPlan = SubscriptionPlan:: where(['isActive'=>1,'isTrial' => 0])->get();
        return view('admin.group_promocode.create',['subscriptionPlan' => $subscriptionPlan]);
    }

    /**
     * Store a newly created resource in group.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, $this->validationRules);

        $groupPromocode = new GroupPromocode();
        $groupPromocode->groupName = $request->groupName;
        $groupPromocode->promoCode = $request->promoCode;
        $groupPromocode->description = $request->description;
        
        $promoDateRange = explode(' - ',$request->promo_date_range);
        $groupPromocode->startDate = Carbon::parse($promoDateRange[0])->format('Y-m-d');
        $groupPromocode->endDate = Carbon::parse($promoDateRange[1])->format('Y-m-d');
        $groupPromocode->discountType = $request->discountType;
        $groupPromocode->discountAmount = $request->discountAmount;
        $groupPromocode->isApplyMultiTime = 1;
        $groupPromocode->isActive = $request->status;

        if($request->planId != 'any'){
            $planData = SubscriptionPlan:: selectRaw('planName')->where('id',$request->planId)->first();
            $groupPromocode->planId = $request->planId;
            $groupPromocode->planName = $planData->planName;
        }else{
            $groupPromocode->planId = null;
            $groupPromocode->planName = $request->planId;
        }

        if($groupPromocode->save()){
            return redirect(route('groups.index'))->with('success', trans('messages.groups.create.success'));
        }
        return redirect(route('groups.index'))->with('error', trans('messages.groups.create.error'));
    }


    /**
     * Show the form for editing the specified group
     *
     * @param  Promocode  $Promocode
     * @return coupon
     */
    public function edit(GroupPromocode $group)
    {   
        $subscriptionPlan = SubscriptionPlan:: where(['isActive'=>1,'isTrial' => 0])->get();
        return view('admin.group_promocode.create',['subscriptionPlan' => $subscriptionPlan,'groups' => $group]);
    }

    
    /**
     * Update the specified resource in promocode.
     *
     * @param Request  $request
     * @param GroupPromocode $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,GroupPromocode $group)
    {   
        $this->validate($request, $this->validationRules);

        $group->groupName = $request->groupName;
        $group->promoCode = $request->promoCode;
        $group->description = $request->description;
        
        $promoDateRange = explode(' - ',$request->promo_date_range);
        $group->startDate = Carbon::parse($promoDateRange[0])->format('Y-m-d');
        $group->endDate = Carbon::parse($promoDateRange[1])->format('Y-m-d');
        $group->discountType = $request->discountType;
        $group->discountAmount = $request->discountAmount;
        $group->isApplyMultiTime = 1;
        $group->isActive = $request->status;

        if($request->planId != 'any'){
            $planData = SubscriptionPlan:: selectRaw('planName')->where('id',$request->planId)->first();
            $group->planId = $request->planId;
            $group->planName = $planData->planName;
        }else{
            $group->planId = null;
            $group->planName = $request->planId;
        }

        if($group->save()){
            return redirect(route('groups.index'))->with('success', trans('messages.groups.update.success'));
        }
        return redirect(route('groups.index'))->with('success', trans('messages.groups.update.error'));

    }

    /**
     * Change status of group.
     *
     * @param promocode $group
     * @return mixed
     */
    public function changeStatus(GroupPromocode $group){

        $group->isActive = !$group->isActive;

        if($group->save()){
            $status = $group->isActive ? 'Active' : 'Inactive';
            return redirect(route('groups.index'))->with('success', trans('messages.groups.status.success',['status' => $status]));
        }
        return redirect(route('groups.index'))->with('error',trans('messages.group.groups.error'));
    }

    /**
     * Remove the specified resource from promocode.
     *
     * @param  promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupPromocode $group)
    {
        if($group->delete()){
            groupPromocodeUser::where('groupId',$group->id)->delete();
            return redirect(route('groups.index'))->with('success', trans('messages.groups.delete.success'));
        }
        return redirect(route('groups.index'))->with('success', trans('messages.groups.delete.error'));
    }

    /**
     * Show the form for editing the specified group
     *
     * @param  Promocode  $Promocode
     * @return coupon
     */
    public function users(GroupPromocode $group)
    {   
        
        return view('admin.group_promocode.asigned_users',['group' => $group]);
    }

    /**
     * 
     * View users for asining group promocode
     * 
     * @param Request $request
     * @param string $id
     * 
     * @return view 
    */
    public function show(GroupPromocode $group)
    {    
        return view('admin.group_promocode.group_users',['group' => $group]);
    }

    /**
     * Search searchGroupUsers.
     *
     * @param Request $request
     * 
     * @return json
    */
    public function searchGroupUsers(Request $request)
    {
        if($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);
            
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
                
            $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;
          

            $query = User::selectRaw('users.id as userInfoId,users.name,users.email,users.isActive,
                         users.userType,users.profilePic,DATE_FORMAT(users.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as joinDate
                         ,groups_promocodes_users.groupId as groupId,groups_promocodes_users.id as userAssignedId')
                         ->where('users.userType','User')
                         ->where('users.isActive',1)
                         ->leftJoin('groups_promocodes_users',function($join){
                            $join->on('groups_promocodes_users.userId','=','users.id');
                         });
                         
                         if($request->page == 'group_users'){
                            $query->whereNull('groups_promocodes_users.groupId');
                         }else if($request->page == 'asigned_users'){
                            $query->whereNotNull('groups_promocodes_users.groupId');
                            $query->where('groups_promocodes_users.groupId',$request->groupId);
                         }
                       
                           
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                
                $query->orWhere('users.name', 'like', '%'.$request->search['value'].'%')
                       ->orWhere('users.email', 'like', '%'.$request->search['value'].'%')
                       ->orWhere('users.userType', 'like', '%'.$request->search['value'].'%');
            });

            $users = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $users['recordsFiltered'] = $users['recordsTotal'] = $users['total'];

            foreach ($users['data'] as $key => $user) {
                
                $params = [
                    'user' => $user['userInfoId']
                ];
                
                $users['data'][$key]['sr_no'] = $startNo+$key;
                
                $viewRoute = route('users.show', $params);
                $statusRoute = route('users.status', $params);
                $editRoute = route('users.edit', $params);
                $deleteRoute = route('users.destroy', $params);
                if($request->page == 'group_users'){
                    $users['data'][$key]['action'] = '<label><input type="checkbox" class="checkUserId" value="'.$user['userInfoId'].'"><span class="sr-only"> Select Row</span></label>';
                }else if($request->page == 'asigned_users'){
                    $users['data'][$key]['action'] = '<label><input type="checkbox" class="checkUserId" value="'.$user['userAssignedId'].'"><span class="sr-only"> Select Row</span></label>';
                }
               
                $users['data'][$key]['joinDate'] = $user['joinDate'] == '' ? '-' : $user['joinDate'];
            }
            
            return response()->json($users);
        }
    }

    public function asignUserGroup(Request $request, GroupPromocode $group)
    {   
        if ($request->ajax()) {
            $userIdArray = json_decode($request['userId'], true);

            $groupData = [];
            foreach ($userIdArray as $Key => $userId) {
                $groupData[] = [
                    "userId"    => (int) $userId,
                    "groupId"   => (int) $request['groupId'],
                ];
            }

            if(!empty($userIdArray)){
                if(groupPromocodeUser::insert($groupData)){
                    $cmd = 'cd ' . base_path() . ' && php artisan mail:SendGroupDiscount "' . $request['groupId'] . '"';
                    exec($cmd . ' > /dev/null &');
                    return 1;
                }else{
                    return 2;
                }
            }
            return 2;
        }
        
    }

    public function removeGroupUser(Request $request)
    {   
        if ($request->ajax()) {
            $userIdArray = json_decode($request['userId'], true);
            
            if(!empty($userIdArray)){
                if(groupPromocodeUser::whereIn('id',$userIdArray)->delete()){
                    return 1;
                }else{
                    return 2;
                }
            }
            return 2;
        }
        
    }

}
