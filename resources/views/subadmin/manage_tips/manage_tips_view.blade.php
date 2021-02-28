@extends('admin.layout.index')
@section('title') Tips detail @endsection
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
                     <a href="{{ route('manage-tips.index')}}">Manage Tips</a>
                  </li>
                  <li class="breadcrumb-item active">Tips detail</li>
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
                    <h4 class="card-title">Transaction detail </h4>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-6 b-r"> <strong> Game name</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->gameName ?? '-'   }} </p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong> Package name</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->packageName ?? '-'   }} </p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Game full name</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->gameFullName ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Tips</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->tips ?? '-'  }}</p>
                        </div>
                       
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Odds</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->odds ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Track</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->track ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>profit-loss for tips</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->profitLosForTips ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>profit-loss for day</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->profitLosForDay ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Daily Pot</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->dailyPot ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>profit-loss cumulative</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->profitLossCumulative ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Pot</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->pot ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Profit one unit</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->profitOneUnit ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Profit two unit</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->profitTwoUnit ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Profit three unit</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->profitThreeUnit ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Percentage</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->percentage ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Date</strong>
                            <br>
                            <p class="text-muted">{{ date('jS F Y', strtotime($gameTipInfo->date)) ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Create date</strong>
                            <br>
                            <p class="text-muted">{{ date('jS F Y', strtotime($gameTipInfo->createdAt)) ?? '-'  }}</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong>Win</strong>
                            <br>
                           @if($gameTipInfo->IsWin == 1)
                                <span class="label label-success">Yes</span>
                           @else
                                <span class="label label-danger">No</span>
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