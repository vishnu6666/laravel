<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\SubscriptionPlan;
use Illuminate\Pagination\Paginator;
use App\Model\Package;

class AdminSubscriptionPlanController extends Controller
{
    
/**
 * List out validation for subscription plan
 *
 */
private $validationRules = [
    'planName'      => 'required|string|min:3|max:30',
    'planSubTitle'  => 'required|string|min:3|max:50',
    'planDuration'  => 'required|int',
    'planFullPackages'       => 'required|int',
    'planFullPackagesTitle'  => 'required|string|min:3|max:50',
    'planWeeklyPrice'   => 'required',
    'planMonthlyPrice'  => 'required'
];

/**
 * Show subscription plan List view
 *
 * @return view
 */
 public function index()
 {   
     return view('admin.subscription_plan.subscription_plan_list');
 }
 
/**
 * Show the form for creating a new subscription plan .
 *
 */
 public function create()
 {
    $packagesCount = Package::where('isActive',1)->count();
    return view('admin.subscription_plan.subscription_plan_create',['packagesCount' => $packagesCount]);
 }

 /**
  * Search subscription plan.
  *
  * @param Request $request
  * 
  * @return json
  */
 public function search(Request $request)
 {
     if($request->ajax()) {

         $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);
         
         Paginator::currentPageResolver(function () use ($currentPage) {
             return $currentPage;
         });

         $startNo = ( $request->start == 0 ) ? 1 : ( ($request->length) * ($currentPage -1) )+1;
         $query = SubscriptionPlan::selectRaw('subscription_plans.id,subscription_plans.planFullPackages,subscription_plans.planFullPackagesTitle,subscription_plans.planName,subscription_plans.planSubTitle,subscription_plans.planDuration,subscription_plans.planWeeklyPrice	,subscription_plans.planMonthlyPrice,subscription_plans.isActive,subscription_plans.isTrial,DATE_FORMAT(subscription_plans.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createDate');
                        
         $orderDir = $request->order[0]['dir'];
         $orderColumnId = $request->order[0]['column'];
         $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

         $query->where(function ($query) use ($request) {
             
             $query->orWhere('subscription_plans.planName', 'like', '%'.$request->search['value'].'%')
                    ->orWhere('subscription_plans.planSubTitle', 'like', '%'.$request->search['value'].'%')
                    ->orWhere('subscription_plans.planDuration', 'like', '%'.$request->search['value'].'%')
                    ->orWhere('subscription_plans.planWeeklyPrice', 'like', '%'.$request->search['value'].'%')
                    ->orWhere('subscription_plans.planMonthlyPrice', 'like', '%'.$request->search['value'].'%');
         });

         $subscriptionPlans = $query->orderBy($orderColumn, $orderDir)
                         ->paginate($request->length)->toArray();

         $subscriptionPlans['recordsFiltered'] = $subscriptionPlans['recordsTotal'] = $subscriptionPlans['total'];
         
         foreach ($subscriptionPlans['data'] as $key => $subscription) {
             $params = [ 'subscription_plan' => $subscription['id'] ];
             $subscriptionPlans['data'][$key]['sr_no'] = $startNo+$key;
             $viewRoute = route('subscription-plans.show', $params);
             //$statusRoute = route('subscription-plans.status', $params);
             $editRoute = route('subscription-plans.edit', $params);
             $deleteRoute = route('subscription-plans.destroy', $params);
           
             $delete = ($subscription['isTrial']== 1) ? '' : '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Delete Plan Information" title="Delete"><i class="fas fa-trash"></i></a>';
             $subscriptionPlans['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="Plan Information"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
             $subscriptionPlans['data'][$key]['action'] .= '<a href="'.$editRoute.'" class="btn btn-success" title="Edit Plan Info"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
             $subscriptionPlans['data'][$key]['action'] .= $delete;
            // $subscriptionPlans['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
             $subscriptionPlans['data'][$key]['createDate'] = $subscription['createDate'] == '' ? '-' : $subscription['createDate'];
         }

         return response()->json($subscriptionPlans);
     }
 }

 /**
  * Store a new subscription plan.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(Request $request)
 {
     $this->validate($request, $this->validationRules);
     $planData = new SubscriptionPlan();
     $planData->fill($request->all());
     $planData->planName =$request->planName;
     $planData->planSubTitle =$request->planSubTitle;
     $planData->planDuration =$request->planDuration;
     $planData->planWeeklyPrice =$request->planWeeklyPrice;
     $planData->planMonthlyPrice =$request->planMonthlyPrice;
     $planData->planFullPackages = $request->planFullPackages;
     $planData->planFullPackagesTitle = $request->planFullPackagesTitle;
     $planData->isActive = 1;
    
     if($planData->save()) {
        return redirect()->route('subscription-plans.index')->with('success',trans('messages.subscription_plans.create.success'));
     }
     return redirect()->route('subscription-plans.index')->with('error',trans('messages.subscription_plans.create.error'));
 }

 /**
  * 
  * View subscription plan Information
  * 
  * @param Request $request
  * @param string $id
  * 
  * @return view 
  */
 public function show(Request $request, SubscriptionPlan $SubscriptionPlan)
 {    
     $subscriptionPlansInfo = SubscriptionPlan::selectRaw('subscription_plans.id,subscription_plans.planName,subscription_plans.planSubTitle,subscription_plans.planDuration,subscription_plans.planWeeklyPrice	,subscription_plans.planMonthlyPrice,subscription_plans.isActive,DATE_FORMAT(subscription_plans.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createDate,subscription_plans.planFullPackagesTitle,subscription_plans.planFullPackages')
                       ->where('subscription_plans.id',$SubscriptionPlan->id)
                       ->first();
     return view('admin.subscription_plan.subscription_plan_view',[
         'subscriptionPlansInfo' => $subscriptionPlansInfo,
     ]);
 }

 /**
  * Show the form for editing subscription plan.
  *
  * @param  $SubscriptionPlan
  * @return $SubscriptionPlan
  */
 public function edit(SubscriptionPlan $SubscriptionPlan)
 {   
    $packagesCount = Package::where('isActive',1)->count();
    return view('admin.subscription_plan.subscription_plan_create', [
         'subscriptionPlan'=> $SubscriptionPlan,
         'packagesCount' => $packagesCount
    ]);
 }

 /**
  * Update the specified resource in database storage.
  *
  * @param Request $request
  * @param object $subscriptionPlan
  * 
  * @return Redirect
  */
 public function update(Request $request,SubscriptionPlan $subscriptionPlan)
 {
     $this->validate($request, $this->validationRules);

     $subscriptionPlan->fill($request->all());
     $subscriptionPlan->planName =$request->planName;
     $subscriptionPlan->planSubTitle =$request->planSubTitle;
     $subscriptionPlan->planDuration =$request->planDuration;
     $subscriptionPlan->planWeeklyPrice =$request->planWeeklyPrice;
     $subscriptionPlan->planMonthlyPrice =$request->planMonthlyPrice;
     $subscriptionPlan->planFullPackages = $request->planFullPackages;
     $subscriptionPlan->planFullPackagesTitle = $request->planFullPackagesTitle;
     $subscriptionPlan->isActive = 1;
     
     if($subscriptionPlan->save())
     {
         return redirect()->route('subscription-plans.index')->with('success',trans('messages.subscription_plans.update.success'));
     }

     return redirect()->route('subscription-plans.index')->with('error',trans('messages.subscription_plans.update.error'));
 }

 /**
  * Destroy subscription Plan information.
  *
  * @param object $subscriptionPlan
  * 
  * @return Redirect
 */
 public function destroy(SubscriptionPlan $subscriptionPlan)
 {
     if($subscriptionPlan->delete())
     {
         return redirect(route('subscription-plans.index'))->with('success', trans('messages.subscription_plans.delete.success'));
     }
     
     return redirect(route('subscription-plans.index'))->with('error', trans('messages.subscription_plans.delete.error'));
 }

 /**
  * Change status of the subscription Plan.
  *
  * @param object $subscriptionPlan
  * 
  * @return Redirect
 */
 public function changeStatus($subscription)
 {
     $subscriptionPlan = SubscriptionPlan::find($subscription);

     if (empty($subscriptionPlan)) 
     {
         return redirect(route('subscription-plans.index'))->with('error', trans('messages.subscription_plans.not_found'));
     }

     $subscriptionPlan->isActive = !$subscriptionPlan->isActive;

     if($subscriptionPlan->save())
     {
         $status = $subscriptionPlan->isActive ? 'active' : 'inactive';
         return redirect(route('subscription-plans.index'))->with('success', trans('messages.subscription_plans.status.success', ['status' => $status]));
     }

     return redirect(route('subscription-plans.index'))->with('error', trans('messages.subscription_plans.status.error'));
 }
}
