@extends('admin.layout.index')
@section('title') Transaction detail @endsection
@section('css')

@endsection
@section('content')
<div class="page-wrapper">
   <!-- ============================================================== -->
   <!-- Container fluid  -->
   <!-- ============================================================== -->
   <div class="container-fluid">
      <!-- ============================================================== -->
      <!-- Bread crumb and right sidebar toggle -->
      <!-- ============================================================== -->
      <div class="row page-titles">
         <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor"></h4>
         </div>
         <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                     <a href="{{ route('AdminDashboard')}}">Home</a>
                  </li>
                  <li class="breadcrumb-item">
                     <a href="{{ route('manage-transaction.index')}}">Subscription</a>
                  </li>
                  <li class="breadcrumb-item active">Subscription detail</li>
               </ol>
            </div>
         </div>
      </div>
      <!-- ============================================================== -->
      <!-- End Bread crumb and right sidebar toggle -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Start Page Content -->
      <!-- ============================================================== -->
      <div class="row">
         <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Subscription detail </h4>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-6 b-r"> <strong> User name</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->name ?? '-'   }} </p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Plan name</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->planName ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Plan type</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->planType ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Plan amount</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->planAmount ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Subscription validity</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->subscriptionValidity ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Subscription expiry date</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->subscriptionExpiryDate ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Paid amount</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->amount ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Applied promocode</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->appliedPromocode ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Applied referral code</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->title ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Discount amount</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->discountAmount ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>PaymentStatus</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->paymentStatus ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Transaction date</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionHistoriesInfo->createdAt ?? '-'  }}</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong>Status</strong>
                            <br>
                           @if($subscriptionHistoriesInfo->isTrial == 1)
                                <span class="label label-success">Free</span>
                           @else
                                <span class="label label-danger">Paid</span>
                           @endif
                        </div>
                    </div>
                </div>
                </div>
            </div>
         </div>
      </div>
      <!-- /.row -->
      <!-- ============================================================== -->
      <!-- End Page Content or Row-->
      <!-- ============================================================== -->
   </div>
   <!-- ============================================================== -->
   <!-- End Container fluid  -->
   <!-- ============================================================== -->
</div>
@endsection
@section('js')

@endsection