@extends('subadmin.layout.index')
@section('title') Edit Profile @endsection
@section('css')

<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
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
                <h4 class="text-themecolor">Profile</h4>
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
                @include('subadmin.common.flash')
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Admin Profile</h4>

                        <form class="form" id="Profileform" method="post" enctype="multipart/form-data" action="{{route('UpdateSubAdminProfile')}}">

                            {{ csrf_field() }}

                            <div class="form-group m-t-40 row">
                                <label for="name" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Full Name<span class="text-danger">*</span></label>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <input class="form-control alphaOnly" tabindex="1" placeholder="Please enter full name" maxlength="50" type="text" value="{{ $admin->name ?? '' }}" name="name" id="name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Email<span class="text-danger">*</span></label>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <input class="form-control"  tabindex="2" value="{{ $admin->email ?? '' }}" readonly="" type="email" id="email" name="email">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="profilePic" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label"> Profile Image <span class="text-danger"></span></label>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <input class="form-control dropify" type="file"  value="" id="profilePic"  data-min-width="98" data-min-height="98" data-max-width="998" data-max-height="998"  name="profilePic" tabindex="5" placeholder="Please upload photo"  data-show-remove="false" accept="image/x-png,image/jpg,image/gif,image/jpeg"  data-allowed-file-extensions="jpg png jpeg gif" data-default-file="{{$adminImage }}" >
                                    <label id="profilePic-error" class="error" for="profilePic" style="display: none;">Please select profile image</label>
                                    <small class="form-control-feedback"> Please upload image size minimum (width) 100 x 100 (Height) and maximum (width) 1000 x 1000 (Height) </small>
                                            
                                </div>
                                
                                    @if(!empty($admin->profilePic))
                                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">

                                        <img src="{{$adminImage}}" class=" radius" height="50px" />
                                            @php $deleteRoute = route('SubAdminProfileImageDelete',$admin->id); @endphp

                                        <button type="button" class="btn btnProfileDelete btn-danger  btn-circle "  data-url="{{ $deleteRoute ?? '' }}" ><i class="fas fa-trash"></i></button>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-center">
                                    <button type="submit" class="btn btn-success"> Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete image of Admin -->
    
            <form id="profileDeleteForm" action="" method="POST" name="profileDeleteForm">
            @csrf
            @method('DELETE')

        </form>
        <!-- End Delete image of Admin -->
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
    <script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function ()
        {   
            // image dropify 
            $('.dropify').dropify({
            error: {
                'minWidth': ' The profile image width must be greater than 100px', 
                'maxWidth': 'The profile image width must be less than 1000px',
                'minHeight': ' The profile image height must be greater than 100px',
                'maxHeight': 'The profile image height must be less than 1000px',
                'imageFormat': 'The profile image format is not allowed jpg png gif jpeg only.'
            }
            });

            $("#Profileform").validate({
                debug:true,
                rules: {
                    name: {
                        required: true,
                        minlength:3,
                        maxlength:50,
                    },
                    email: {
                        required: true,
                        regex:emailpattern,
                        maxlength:150,
                    },
                   
                   
                },
                messages: {
                    name: {
                        required: "Please enter full name",
                        maxlength: 'Full name must be less than {0} characters',
                        minlength: 'Full name must be greater than {0} characters',
                    },
                    email: {
                        required: "Please enter email",
                        regex:"Please enter valid email address",
                        maxlength:"Email must be less than {0} characters",
                    },
                   
                   
                },
                submitHandler: function (form)
                {
                    form.submit();
                }
            });

        });
  
    //For profile image delete
    $(document).on("click",'.btnProfileDelete',function(event) {

        event.preventDefault();
        var title = $(this).data('title');
        var url = $(this).data('url');
        var form = '';
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Profile Image ",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
        }).then(function (result) {

            if(result.value)
        {
            form = $('#profileDeleteForm');
            form.attr('action', url);
            form.submit();
        }
    });
    });

    </script>
@endsection