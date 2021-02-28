<?php

namespace App\Http\Controllers\Admin;

use App\Model\SubscriptionHistories;
use App\Model\UserSubscriptionHistories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Model\userGameHistory;
use App\Model\UserPackagesSubscriptionHistories;

class AdminManageTransactionController extends Controller
{   
    /**
     * Display a listing of the transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('admin.manage_transaction.manage_transaction_list');
    }

    /**
     * Search transaction.
     *
     * @param Request $request
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
            $query = UserSubscriptionHistories::selectRaw('user_subscription_histories.id,users.name,user_subscription_histories.planName,
                                                        user_subscription_histories.planType,user_subscription_histories.planAmount,
                                                        user_subscription_histories.amount,user_subscription_histories.subscriptionExpiryDate,
                                                        user_subscription_histories.subscriptionValidity,
                                                        user_subscription_histories.isTrial,user_subscription_histories.createdAt')
                                                        ->leftJoin('users',function($query){
                                                            $query->on('users.id','=','user_subscription_histories.userId');
                                                        })
                                                        ->orderBy('id','DESC')
                                                        ->where('isTrial',0);
            
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('user_subscription_histories.planName', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('user_subscription_histories.planType', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('user_subscription_histories.subscriptionExpiryDate', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('user_subscription_histories.createdAt', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('user_subscription_histories.subscriptionValidity', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('user_subscription_histories.amount', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('user_subscription_histories.planAmount', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('user_subscription_histories.isTrial', 'like', '%'.$request->search['value'].'%');
                $query->orWhere('users.name', 'like', '%'.$request->search['value'].'%');
            });
            
            $subscriptionHistory = $query->orderBy($orderColumn, $orderDir)
                            ->paginate($request->length)->toArray();

            $subscriptionHistory['recordsFiltered'] = $subscriptionHistory['recordsTotal'] = $subscriptionHistory['total'];

            foreach ($subscriptionHistory['data'] as $key => $history) {
                
                $params = [
                    'manage_transaction' => $history['id']
                ];
                
                $subscriptionHistory['data'][$key]['sr_no'] = $startNo+$key;
                $viewRoute = route('manage-transaction.show', $params);
                $invoiceRoute = route('manage-transaction.invoice', $params);
                $deleteRoute = route('manage-transaction.destroy', $params);
                $statusRoute = route('manage-transaction.status', $params);
                
                $status = ($history['isTrial']== 1) ? '<span class="label label-success">Free</span>' : '<span class="label label-danger">Paid</span>';
                $subscriptionHistory['data'][$key]['action'] = '<a href="'.$viewRoute.'" class="btn btn-primary" title="Transaction Information"><i class="fas fa-eye"></i></a>&nbsp&nbsp';
                $subscriptionHistory['data'][$key]['action'] .= '<a target="_blank" href="'.$invoiceRoute.'" class="btn btn-primary" title="Invoice for transaction Information"><i class="fas fa-paperclip"></i></a>&nbsp&nbsp';
                // $subscriptionHistory['data'][$key]['action'] .= '<a href="javascript:void(0);" data-url="'.$deleteRoute.'" class="btn btn-danger btnDelete" data-title="Transaction" title="Delete"><i class="fa fa-trash"></i></a>';
                $subscriptionHistory['data'][$key]['isTrial'] = '<p></p>'.$status.'</p>';
                $subscriptionHistory['data'][$key]['subscriptionExpiryDate'] = date('jS F Y', strtotime($history['subscriptionExpiryDate']));
                $subscriptionHistory['data'][$key]['createdAt'] = date('jS F Y', strtotime($history['createdAt']));
                $subscriptionHistory['data'][$key]['planName'] =  str_limit($history['planName'],100,'...');
          }
            return response()->json($subscriptionHistory);
        }
    }

    /**
    * 
    * View transaction Information
    * 
    * @param Request $request
    * @param string $id
    * 
    * @return view 
    */
    public function show(Request $request, UserSubscriptionHistories $manage_transaction)
    {    
        $subscriptionHistoriesInfo = UserSubscriptionHistories::selectRaw('user_subscription_histories.id,users.name,user_subscription_histories.planName,
                                                        user_subscription_histories.planType,user_subscription_histories.planAmount,
                                                        user_subscription_histories.amount,
                                                        user_subscription_histories.subscriptionValidity,
                                                        user_subscription_histories.isTrial,
                                                        DATE_FORMAT(user_subscription_histories.subscriptionExpiryDate,"'.config('constant.DATE_TIME_FORMAT'). '") as subscriptionExpiryDate,
                                                        DATE_FORMAT(user_subscription_histories.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createdAt,
                                                        user_subscription_histories.discountAmount,user_subscription_histories.paymentStatus,
                                                        user_subscription_histories.appliedPromocode,
                                                        referral_codes.title
                                                        ')
                                                        ->leftJoin('users',function($query){
                                                            $query->on('users.id','=','user_subscription_histories.userId');
                                                        })
                                                        ->leftJoin('referral_codes',function($query){
                                                            $query->on('user_subscription_histories.referralcodeId','=','referral_codes.id');
                                                        })
                                                        ->where('user_subscription_histories.id',$manage_transaction->id)
                                                        ->first();
        return view('admin.manage_transaction.manage_transaction_view',[
            'subscriptionHistoriesInfo' => $subscriptionHistoriesInfo,
        ]);
    }


    /**
     * Remove the specified resource from transaction.
     *
     * @param SubscriptionHistories $manage_transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserSubscriptionHistories $manage_transaction)
    {
        if($manage_transaction->delete())
        {
            userGameHistory ::where('subscriptionHistoriesId',$manage_transaction->id)->delete();
            UserPackagesSubscriptionHistories ::where('subscriptionHistoriesId',$manage_transaction->id)->delete();
            return redirect(route('manage-transaction.index'))->with('success', trans('messages.manage_transaction.delete.success'));
        }
        return redirect(route('manage-transaction.index'))->with('error', trans('messages.manage_transaction.delete.error'));
    }

    public function invoice(UserSubscriptionHistories $manage_transaction)
    {
        $subscriptionHistoriesInfo = UserSubscriptionHistories::selectRaw('user_subscription_histories.id,users.name,users.email,users.mobileNumber,user_subscription_histories.planName,
                                                        user_subscription_histories.planType,user_subscription_histories.planAmount,
                                                        user_subscription_histories.amount,
                                                        user_subscription_histories.subscriptionValidity,
                                                        user_subscription_histories.isTrial,
                                                        DATE_FORMAT(user_subscription_histories.subscriptionExpiryDate,"'.config('constant.DATE_TIME_FORMAT'). '") as subscriptionExpiryDate,
                                                        DATE_FORMAT(user_subscription_histories.createdAt,"'.config('constant.DATE_TIME_FORMAT'). '") as createdAt,
                                                        user_subscription_histories.discountAmount,user_subscription_histories.paymentStatus,
                                                        user_subscription_histories.appliedPromocode,user_subscription_histories.paymentType,
                                                        referral_codes.title
                                                        ')
                                                        ->leftJoin('users',function($query){
                                                            $query->on('users.id','=','user_subscription_histories.userId');
                                                        })
                                                        ->leftJoin('referral_codes',function($query){
                                                            $query->on('user_subscription_histories.referralcodeId','=','referral_codes.id');
                                                        })
                                                        ->where('user_subscription_histories.id',$manage_transaction->id)
                                                        ->first();
        return view('pdf.order_invoice',[
            'order' => $subscriptionHistoriesInfo,
        ]);
    }
}