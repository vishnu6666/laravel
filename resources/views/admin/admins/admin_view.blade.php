@extends('admin.layout.index')
@section('title') Admin Detail @endsection
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
                     <a href="{{ route('admins.index')}}">Admins</a>
                  </li>
                  <li class="breadcrumb-item active">Admin Detail</li>
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
                    <h4 class="card-title">Admin Detail </h4>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong>Profile Image</strong>
                            <br>
                            @php
                            $userProfileImage = (!empty($adminInfo->profilePic)) ?  url('images/profiles/'.$adminInfo->profilePic )  :  url('images/default.jpg');
                            @endphp
                            <img src="{{ $userProfileImage }}" width="50px">
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong> Name</strong>
                            <br>
                            <p class="text-muted">{{ $adminInfo->name ?? '-'   }} </p>
                        </div>
                        <div class="col-md-4 col-xs-6 b-r"> <strong>Email</strong>
                            <br>
                            <p class="text-muted">{{ $adminInfo->email ?? '-'  }}</p>
                        </div>
                        <div class="col-md-2 col-xs-6"> <strong>Create Date</strong>
                            <br>
                            <p class="text-muted">{{ $adminInfo->joinDate ?? '-'  }}</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-6"> <strong>Status</strong>
                            <br>
                           @if($adminInfo->isActive == 1)
                                <span class="label label-success">Active</span>
                           @else
                                <span class="label label-danger">Inactive</span>
                           @endif
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong> Password</strong>
                            <br>
                            <p class="text-muted">{{ $adminInfo->showPassword ?? '-'   }} </p>
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