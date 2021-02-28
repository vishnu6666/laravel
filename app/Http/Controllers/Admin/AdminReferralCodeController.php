<?php

namespace App\Http\Controllers\Admin;

use App\Model\Promocode;
use App\Model\Referralcode;
use App\Model\SubscriptionPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\CommonHelper;

class AdminReferralCodeController extends Controller
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
        return view('admin.referral_code.index');
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
            $coupons= Referralcode::selectRaw('referral_codes.*');

            $coupons=$coupons->where(function ($query) use ($request){
                $query->orWhere('referral_codes.referCode', 'like', '%'.$request->search['value'].'%')
                ->orWhere('referral_codes.title', 'like', '%' . $request->search['value'] . '%');
                    
            });

            $coupons = $coupons->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $coupons['recordsFiltered'] = $coupons['recordsTotal'] = $coupons['total'];

            foreach ($coupons['data'] as $key=> $coupon){
                
                $params = [
                    'referCode' => $coupon['id']
                ];

                $coupons['data'][$key]['sr_no'] = $startNo+$key;

                $editRoute = route('referCode.edit', $params);
                $statusRoute = route('referCode.status', $params);
                $deleteRoute = route('referCode.destroy', $params);

                $status = ($coupon['isActive']) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">
                Inactive</span>';

                $coupons['data'][$key]['action'] = '<a href="'.$editRoute.'" class="btn btn-success" title="Edit Promocode Information"><i class="fas fa-pencil-alt"></i></a>&nbsp&nbsp';
                $coupons['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Promocode Information Delete" title="Delete"><i class="fas fa-trash"></i></a>';
                $coupons['data'][$key]['isActive'] = '<a href="javascript:void(0);" data-url="'.$statusRoute.'" class="btnChangeStatus">'.$status.'</a>';
               
                // $startDate = date('d F Y',strtotime($coupon['startDate']) );
                // $endDate= date('d F Y',strtotime($coupon['endDate']) );
                // $rangeDate = $startDate .' to '.$endDate;
                // $coupons['data'][$key]['rangeDate'] =  $rangeDate;

                // $coupons['data'][$key]['minTotalAmount'] =  "$" .' '. $coupon['minTotalAmount'];
                // if($coupon['discountType'] == 'Percentage')
                // {
                //     $coupons['data'][$key]['discountAmount'] =  $coupon['discountAmount'].'%';
                // }
                // else
                // {
                //     $coupons['data'][$key]['discountAmount'] =  "$" .' '. $coupon['discountAmount'];
                // }
                
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
        return view('admin.referral_code.create',['subscriptionPlan' => $subscriptionPlan]);
    }

    /**
     * Store a newly created resource in promocode.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, $this->validationRules);

        $referCode = new Referralcode();
        $referCode->fill($request->all());
       
        if($referCode->save()){
            return redirect(route('referCode.index'))->with('success', trans('messages.referCode.create.success'));
        }
        return redirect(route('referCode.index'))->with('error', trans('messages.referCode.create.error'));
    }


    /**
     * Show the form for editing the specified promocode
     *
     * @param  Promocode  $Promocode
     * @return coupon
     */
    public function edit(Referralcode $referCode)
    {   
        $subscriptionPlan = SubscriptionPlan:: where('isActive',1)->get();
        return view('admin.referral_code.create',['referCode'=> $referCode,'subscriptionPlan' => $subscriptionPlan]);
    }

    
    /**
     * Update the specified resource in promocode.
     *
     * @param Request  $request
     * @param promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Referralcode $referCode)
    {
        // $this->validate($request, $this->validationRules);
        $referCode->fill($request->all());
        if($referCode->save()){
            return redirect(route('referCode.index'))->with('success', trans('messages.referCode.update.success'));
        }
        return redirect(route('referCode.index'))->with('success', trans('messages.referCode.update.error'));

    }

    /**
     * Change status of promocode.
     *
     * @param promocode $promocode
     * @return mixed
     */
    public function changeStatus(Referralcode $referCode){

        $referCode->isActive = !$referCode->isActive;

        if($referCode->save()){
            $status = $referCode->isActive ? 'Active' : 'Inactive';
            return redirect(route('referCode.index'))->with('success', trans('messages.referCode.status.success',['status' => $status]));
        }
        return redirect(route('referCode.index'))->with('error',trans('messages.referCode.status.error'));
    }

    /**
     * Remove the specified resource from promocode.
     *
     * @param  promocode $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Referralcode $referCode)
    {
        if($referCode->delete()){
            return redirect(route('referCode.index'))->with('success', trans('messages.referCode.delete.success'));
        }
        return redirect(route('referCode.index'))->with('success', trans('messages.referCode.delete.error'));
    }
}
