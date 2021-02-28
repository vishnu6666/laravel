@extends('admin.layout.index')
@section('title') {{ empty($notificationTemplate) ? 'Create Notification Template' : 'Edit Notification Template' }} @endsection

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
                        <h4 class="text-themecolor">{{ empty($notificationTemplate) ? 'Create Notification Template' : 'Edit Notification Template' }}</h4>
                    </div>
                    
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('AdminDashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('notification-templates.index') }}">Notification Templates</a>
                                </li>
                                <li class="breadcrumb-item active">{{ empty($notificationTemplate) ? 'Create Notification Template' : 'Edit Notification Template' }}</li>
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
                                <h4 class="card-title">{{ empty($notificationTemplate) ? 'Create Notification Template' : 'Edit Notification Template' }} </h4>
                                @if(empty($notificationTemplate))
                                <form class="form" name="createNotificationTemplates" id="createNotificationTemplates" method="post" enctype="multipart/form-data" action="{{ route('notification-templates.store') }}">
                                
                                @else
                                <form class="form" name="editNotificationTemplates" id="editNotificationTemplates" method="post" enctype="multipart/form-data" action="{{ route('notification-templates.update', ['notification_template' => $notificationTemplate->id]) }}">
                                @method('PUT')
                                @endif
                                
                                  {{ csrf_field() }}
                                    
                                    <input type="hidden" name="id" id="id" value="{{ $notificationTemplate->id ?? '' }}" />

                                    <div class="form-group m-t-40 row">
                                        <label for="title" class="col-2 col-form-label">Notification Title<span class="text-danger">*</span></label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" name="title" id="title" placeholder="Please enter notification title"  tabindex="1" value="{{ $notificationTemplate->title ?? '' }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="content" class="col-2 col-form-label">Notification Content<span class="text-danger">*</span></label>
                                        <div class="col-10">
                                             <textarea name="content" id="content" class="form-control" placeholder="Please enter notification content" row="3" col="3" required >{{ $notificationTemplate->content ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="form-group m-t-40 row">
                                        <label for="status" class="col-2 col-form-label">Status<span class="text-danger">*</span></label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status1" name="status" value="1" class="custom-control-input" {{ (!empty($notificationTemplate) && $notificationTemplate->status == '1') ? 'checked' : ''}} {{ (empty($notificationTemplate)) ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="status1">Active</label>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status2" name="status" value="0" class="custom-control-input" {{ (!empty($notificationTemplate) && $notificationTemplate->status == '0') ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="status2">Inactive</label>
                                        </div>
                                    </div> --}}
                                    
                                    <div class="form-group row">
                                        <div class="col-6 text-center">
                                            <button type="submit" class="btn btn-success"> {{ empty($notificationTemplate) ? 'Create' : 'Update' }}</button>

                                            @if(!empty($notificationTemplate)) 
                                            
                                              <a  href="{{ route('notification-templates.edit', ['notification_template' => $notificationTemplate->id]) }}" class="btn btn-danger"> Reset</a>

                                            @else

                                              <a  href="{{ route('notification-templates.create')}}" class="btn btn-danger"> Reset</a>
                                            @endif
                                        </div>
                                    </div>
                                 </form>
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
<script src="{{ url('assets/js/jquery.validate.min.js') }}" ></script>
<script type="text/javascript">
    $(document).ready(function ()
    { 
    	//override required method
    	$.validator.methods.required = function(value, element, param) {

          return (value == undefined) ? false : (value.trim() != '');
    	}
        
        $(".form").validate({
        	ignore: [],
            rules: {
            	title: {
                    required: true,
                    maxlength:80,
                   
                },
                content: {
                    required: true,
                    maxlength:150,
                },
               
            },
            messages: {
            	title: {
                    required: 'Please enter notification title',
                    maxlength: 'Notification Title  must be less than 80 character',
                },
                content: {
                    required: 'Please enter notification content',
                     maxlength: 'Notification Content  must be less than 150 character',
                },
                 
            },
        });
    
    });
</script>
@endsection