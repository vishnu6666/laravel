@extends('admin.layout.index')
@section('title') Game detail @endsection
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
                     <a href="{{ route('games.index')}}">Games</a>
                  </li>
                  <li class="breadcrumb-item active">Game detail</li>
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
                    <h4 class="card-title">Game detail </h4>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong>Game image</strong>
                            <br>
                            @php
                            $gameImage = (!empty($gameInfo->gameImage)) ?  url('images/gameimage/'.$gameInfo->gameImage )  :  url('images/default.jpg');
                            @endphp
                            <img src="{{ $gameImage }}" width="50px">
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong> Game name</strong>
                            <br>
                            <p class="text-muted">{{ $gameInfo->gameName ?? '-'   }} </p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Game full name</strong>
                            <br>
                            <p class="text-muted">{{ $gameInfo->gameFullName ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>launch date</strong>
                            <br>
                            <p class="text-muted">{{ $gameInfo->launchDate ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6 b-r"> <strong>Create date</strong>
                            <br>
                            <p class="text-muted">{{ $gameInfo->createDate ?? '-'  }}</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong>Status</strong>
                            <br>
                           @if($gameInfo->isActive == 1)
                                <span class="label label-success">Active</span>
                           @else
                                <span class="label label-danger">Inactive</span>
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