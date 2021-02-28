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
                    <h4 class="card-title">Tips detail</h4>
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
                        {{-- <div class="col-md-2 col-xs-6 b-r"> <strong>Track</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->track ?? '-'  }}</p>
                        </div> --}}
                        <div class="col-md-2 col-xs-6 b-r"> <strong>profit-loss for tips</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->profitLosForTips ?? '-'  }}</p>
                        </div>
                        
                        <div class="col-md-2 col-xs-6 b-r"> <strong>weekly profit loss</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->weeklyProfitLos ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Weekly Pot</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->weeklyPot ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Monthly Profit Loss</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->monthlyProfitLos ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Monthly Pot</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->monthlyPot ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>All Time Profit Los</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->allTimeProfitLos ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>all Time Pot</strong>
                            <br>
                            <p class="text-muted">{{ $gameTipInfo->allTimePot ?? '-'  }}</p>
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
                           @if($gameTipInfo->IsWin == 'win')
                                <span class="label label-success">Win</span>
                           @elseif($gameTipInfo->IsWin == 'loss')
                                <span class="label label-danger">Loss</span>
                           @elseif($gameTipInfo->IsWin == 'pending')
                                <span class="label label-danger">Pending</span>
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