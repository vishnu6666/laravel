@extends('admin.layout.index')
@section('title') View Notification @endsection

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
                        <h4 class="text-themecolor"> View Notification</h4>
                    </div>
                    
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('AdminDashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('notifications.index') }}">Notification</a>
                                </li>
                                <li class="breadcrumb-item active">View Notification</li>
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
                <!-- .row -->
                <div class="row">
                    <div class="col-sm-12">
                        @include('admin.common.flash')
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">View Notification </h4>
                              
                                    <div class="form-group m-t-40 row">
                                        <label for="title" class="col-2 col-form-label">Notification Title</label>
                                        <div class="col-10 col-form-label">
                                            <label>{{ $notification->title ?? '' }}</label>
                                        </div>
                                    </div>

                                     <div class="form-group m-t-40 row">
                                        <label for="content" class="col-2 col-form-label">Notification Content</label>
                                        <div class="col-10 col-form-label">
                                        <label>{{ $notification->content ?? '' }}</label>
                                        </div>
                                    </div>
                                
                                        @if($notification->notificationType == 1)
                                        <div class="form-group m-t-40 row">
                                            <label for="content" class="col-2 col-form-label">Notification Type</label>
                                            <div class="col-10 col-form-label">
                                            <span class="label label-success">Game</span>
                                            </div>
                                        </div>

                                        <div class="form-group m-t-40 row">
                                            <label for="content" class="col-2 col-form-label">Media</label>
                                            <div class="col-10 col-form-label">
                                            @if(!empty($notification->media))
                                             <img height="50px" width="50px" src="{{ $mediapath.'/'.$notification->media ?? '' }}"></img>
                                            @endif
                                           
                                            </div>
                                        </div>
                                        @elseif($notification->notificationType == 2)

                                        <div class="form-group m-t-40 row">
                                            <label for="content" class="col-2 col-form-label">Notification Type</label>
                                            <div class="col-10 col-form-label">
                                                <span class="label label-success">Package</span>
                                            </div>
                                        </div>

                                        <div class="form-group m-t-40 row">
                                            <label for="content" class="col-2 col-form-label">Package name</label>
                                            <div class="col-10 col-form-label">
                                                <label>{{ $notification->packageName }}</label>
                                            </div>
                                        </div>

                                        @elseif($notification->notificationType == 3)
    
                                        <div class="form-group m-t-40 row">
                                            <label for="content" class="col-2 col-form-label">Notification Type</label>
                                            <div class="col-10 col-form-label">
                                                <span class="label label-success">Offer</span>
                                            </div>
                                        </div>
                                        @elseif($notification->notificationType == 4)
    
                                        <div class="form-group m-t-40 row">
                                            <label for="content" class="col-2 col-form-label">Notification Type</label>
                                            <div class="col-10 col-form-label">
                                                <span class="label label-success">Send by admin</span>
                                            </div>
                                        </div>
                                        @endif
                                       
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
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>
<script type="text/javascript">
   
</script>
@endsection