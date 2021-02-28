<?php

namespace App\Http\Controllers\Admin;

use App\Model\Promocode;
use App\Model\SubscriptionPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\CommonHelper;

class AdminPromoCodeController extends Controller
{
    /**
     * ValidationRules for promocode
     * @var array
     */
    private $validationRules = [
        'title' => 'required|max:250',
        'description' => 'required|max:300',
        'couponCode' => 'required|max:90',
        'minAmount' => 'required',
        'discountType' => 'required|in:Percentage,Flat',
        'discount' => 'required',
        'maxDiscount' => 'required',
        'promo_date_range' =>'required',
    ];

    /**
     * Display a listing of the promocode.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.coupon_code.index');
    }

    /**
     * Load  promocode content
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
            $coupons=Promocode::selectRaw('promocodes.*,promocodes.createdAt as createDateTime');

            $coupons=$coupons->where(function ($query) use ($request){
                $query->orWhere('promocodes.promoCode', 'like', '%'.$request->search['value'].'%');
                    
            });

            $coupons = $coupons->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $coupons['recordsFiltered'] = $coupons['recordsTotal'] = $coupons['total'];

            foreach ($coupons['data'] as $key=> $coupon){
                
                $params = [
                    'promocode' => $coupon['id']
                ];

                $coupons['data'][$key]['sr_no'] = $startNo+$key;

                $editRoute = route('promocode.edit', $params);
                $statusRoute = route('promocode.status', $params);
                $deleteRoute = route('promocode.destroy', $params);

                $status = ($coupon['isActive']) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">
                Inactive</span>';

                $coupons['data'][$key]['action'] = '<a href="'.$editRoute.'" class="btn btn-success" title="Edit Promocode Information"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
                $coupons['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Promocode Information Delete" title="Delete"><i class="fas fa-trash"></i></a>';
                $coupons['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
               
                $startDate = date('d F Y',strtotime($coupon['startDate']) );
                $endDate= date('d F Y',strtotime($coupon['endDate']) );
                $rangeDate = $startDate .' to '.$endDate;
                $coupons['data'][$key]['rangeDate'] =  $rangeDate;

                $coupons['data'][$key]['minTotalAmount'] =  "$" .' '. $coupon['minTotalAmount'];
                if($coupon['discountType'] == 'Percentage')
                {
                    $coupons['data'][$key]['discountAmount'] =  $coupon['discountAmount'].'%';
                }
                else
                {
                    $coupons['data'][$key]['discountAmount'] =  "$" .' '. $coupon['discountAmount'];
                }
                
            }

            return response()->Json($coupons);
        }
    }


    /**
     * Show the form for creating a new promocode code.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscriptionPlan = SubscriptionPlan:: where('isActive',1)->get();
        return view('admin.coupon_code.create',['subscriptionPlan' => $subscriptionPlan]);
    }

    /**
     * Store a newly created resource in promocode.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, $this->validationRules);

        $promocode = new Promocode();
        $promoDateRange = explode(' - ',$request->promo_date_range);
        $promocode->startDate = Carbon::parse($promoDateRange[0])->format('Y-m-d');
        $promocode->endDate = Carbon::parse($promoDateRange[1])->format('Y-m-d');
        $promocode->title = $request->title;
        $promocode->description = $request->description;
        $promocode->promoCode = $request->couponCode;
        $promocode->discountType = $request->discountType;
        $promocode->minTotalAmount = $request->minAmount;
        $promocode->discountAmount = $request->discount;
        $promocode->maxDiscountAmount = $request->maxDiscount;
        $promocode->isApplyMultiTime = $request->isApplyMultiTime;
        $promocode->isActive = $request->status;
        if($request->planId != 'any'){
            $planData = SubscriptionPlan:: selectRaw('planName')->where('id',$request->planId)->first();
            $promocode->planId = $request->planId;
            $promocode->planName = $planData->planName;
        }else{
            $promocode->planId = null;
            $promocode->planName = $request->planId;
        }
        if($promocode->save()){
            return redirect(route('promocode.index'))->with('success', trans('messages.promocode.create.success'));
        }
        return redirect(route('promocode.index'))->with('error', trans('messages.promocode.create.error'));
    }


    /**
     * Show the form for editing the specified promocode
     *
     * @param  Promocode  $Promocode
     * @return coupon
     */
    public function edit(Promocode $promocode)
    {   
        $subscriptionPlan = SubscriptionPlan:: where('isActive',1)->get();
        return view('admin.coupon_code.create',['coupon'=> $promocode,'subscriptionPlan' => $subscriptionPlan]);
    }

    
    /**
     * Update the specified resource in promocode.
     *
     * @param Request  $request
     * @param promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Promocode $promocode)
    {   

        $this->validationRules['couponCode'] = 'required|string|max:90|unique:promocodes,promoCode,'.$promocode->id;

        $this->validate($request, $this->validationRules);

        $promoDateRange = explode(' - ',$request->promo_date_range);
        $promocode->startDate = Carbon::parse($promoDateRange[0])->format('Y-m-d');
        $promocode->endDate = Carbon::parse($promoDateRange[1])->format('Y-m-d');
        $promocode->title = $request->title;
        $promocode->description = $request->description;
        $promocode->promoCode = $request->couponCode;
        $promocode->discountType = $request->discountType;
        $promocode->minTotalAmount = $request->minAmount;
        $promocode->discountAmount = $request->discount;
        $promocode->maxDiscountAmount = $request->maxDiscount;
        $promocode->isApplyMultiTime = $request->isApplyMultiTime;
        $promocode->isActive = $request->status;
        if($request->planId != 'any'){
            $planData = SubscriptionPlan:: selectRaw('planName')->where('id',$request->planId)->first();
            $promocode->planId = $request->planId;
            $promocode->planName = $planData->planName;
        }else{
            $promocode->planId = null;
            $promocode->planName = $request->planId;
        }

        if($promocode->save()){
            return redirect(route('promocode.index'))->with('success', trans('messages.promocode.update.success'));
        }
        return redirect(route('promocode.index'))->with('success', trans('messages.promocode.update.error'));

    }

    /**
     * Change status of promocode.
     *
     * @param promocode $promocode
     * @return mixed
     */
    public function changeStatus(Promocode $promocode){

        $promocode->isActive = !$promocode->isActive;

        if($promocode->save()){
            $status = $promocode->isActive ? 'Active' : 'Inactive';
            return redirect(route('promocode.index'))->with('success', trans('messages.promocode.status.success',['status' => $status]));
        }
        return redirect(route('promocode.index'))->with('error',trans('messages.promocode.status.error'));
    }

    /**
     * Remove the specified resource from promocode.
     *
     * @param  promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promocode $promocode)
    {
        if($promocode->delete()){
            return redirect(route('promocode.index'))->with('success', trans('messages.promocode.delete.success'));
        }
        return redirect(route('promocode.index'))->with('success', trans('messages.promocode.delete.error'));
    }
}
