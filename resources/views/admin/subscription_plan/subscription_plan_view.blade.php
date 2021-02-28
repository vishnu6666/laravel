@extends('admin.layout.index')
@section('title') Subscription plan detail @endsection
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
                     <a href="{{ route('subscription-plans.index')}}">Subscription plans</a>
                  </li>
                  <li class="breadcrumb-item active">Subscription plan detail</li>
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
                    <h4 class="card-title">Subscription Plans Detail </h4>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong> Plan name</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionPlansInfo->planName ?? '-'   }} </p>
                        </div>

                        <div class="col-md-3 col-xs-6"> <strong>Plan sub title</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionPlansInfo->planSubTitle ?? '-'  }}</p>
                        </div>

                        {{-- <div class="col-md-2 col-xs-6"> <strong>plan duration</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionPlansInfo->planDuration ?? '-'  }}</p>
                        </div> --}}

                        <div class="col-md-2 col-xs-6"> <strong>Plan weekly price</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionPlansInfo->planWeeklyPrice ?? '-'  }}</p>
                        </div>

                        <div class="col-md-2 col-xs-6"> <strong>Plan monthly price</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionPlansInfo->planMonthlyPrice ?? '-'  }}</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong>Status</strong>
                            <br>
                           @if($subscriptionPlansInfo->isActive == 1)
                                <span class="label label-success">Active</span>
                           @else
                                <span class="label label-danger">Inactive</span>
                           @endif
                        </div>
                        <div class="col-md-2 col-xs-6"> <strong>Packages Heading</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionPlansInfo->planFullPackagesTitle ?? '-'  }}</p>
                        </div>


                        {{-- <div class="col-md-2 col-xs-6 offset-md-1"> <strong>No. Of Packages</strong>
                            <br>
                            <p class="text-muted">{{ $subscriptionPlansInfo->planFullPackages ?? '-'  }}</p>
                        </div> --}}
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