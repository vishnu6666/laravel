@extends('admin.layout.index')
@section('title') {{ empty($admin) ? 'Create admin' : 'Edit admin' }} @endsection
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
                    <h4 class="text-themecolor">{{ empty($admin) ? 'Create admin' : 'Edit admin' }}</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('users.index')}}">Users</a>
                            </li>
                            <li class="breadcrumb-item active">{{ empty($admin) ? 'Create admin' : 'Edit admin' }}</li>
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
                            @if(empty($admin))
                                <form class="form" name="Userstore" id="Userstore" method="post" enctype="multipart/form-data" action="{{ route('admins.store') }}">
                            @else
                                <form class="form" name="Userupdate" id="Userupdate" method="post" enctype="multipart/form-data" action="{{ route('admins.update', ['admin' => $admin->id]) }}">
                                    @method('PUT')
                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id" value="{{ $admin->id ?? '' }}" />
                                    <div class="form-group m-t-40 row">
                                        <label for="name" class="col-2 col-form-label">Full name<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $admin->name ?? '' }}"
                                                    name="name" id="name" placeholder="Please enter full name" tabindex="1" required >
                                        </div>
                                    </div>
                                    <div class="form-group m-t-40 row">
                                        <label for="email"  class="col-2 col-form-label ">Email <span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="email" value="{{ $admin->email ?? '' }}" name="email" id="email"  placeholder="Please enter email" tabindex="2" required  >
                                        </div>
                                    </div>
                                    <div class="form-group m-t-40 row">
                                        <label for="package"  class="col-2 col-form-label ">Package <span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <select name="package" id="package" class="form-control" required>
                                                    <option value="">Select packages</option>
                                                    @foreach($packages as $package)
                                                    <option value="{{ $package->id }}" <?php if(isset($admin)){if($package->id == $admin->packageId){ echo 'selected' ;}}?>>{{ $package->packageName }}</option>

                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="profilePic" class="col-2 col-form-label">Profile picture<span class="text-danger"></span> </label>
                                        <div class="col-5">
                                            <div class="">
                                                <input type="file" id="profilePic" name="profilePic" class="dropify" tabindex="3" data-show-remove="false" accept="image/x-png,image/jpg,image/gif,image/jpeg"  data-allowed-file-extensions="jpg png jpeg gif" data-default-file="{{ !empty($user->profilePic) ? url('images/profiles/'.$user->profilePic) : ''}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-t-40 row">
                                        <label for="password"  class="col-2 col-form-label ">Password <span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $admin->showPassword ?? '' }}" name="password" id="password"  placeholder="Please enter password" tabindex="4" required >
                                        </div>
                                    </div>
                                    <div class="form-group m-t-40 row">
                                        <label for="password_confirmation"  class="col-2 col-form-label "> Confirm password <span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text"  name="password_confirmation" value="{{ $admin->showPassword ?? '' }}" id="password_confirmation"  placeholder="Please enter confirm password" tabindex="5" required >
                                        </div>
                                    </div>
                                    <div class="form-group m-t-40 row">
                                        <label for="status" class="col-2 col-form-label">Status<span class="text-danger">*</span></label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status1" name="status" value="1"
                                            class="custom-control-input" {{ (!empty($admin) && $admin->isActive == 1) ? 'checked' : ''}} {{ (empty($admin)) ? 'checked' : '' }} >
                                            <label class="custom-control-label" for="status1">Active</label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status2" name="status" value="0" 
                                            class="custom-control-input" {{ (!empty($admin) && $admin->isActive == 0) ? 'checked' : ''}}>     
                                            <label class="custom-control-label" for="status2">Inactive</label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-5 text-center">
                                            <button type="submit" class="btn btn-success"> {{ empty($admin) ? 'Create' : 'Update' }}</button>

                                            @if(!empty($admin)) 
                                    
                                            <a  href="{{ route('users.edit',[ 'user' => $admin->id] )}}" class="btn btn-danger"> Reset</a>

                                            @else

                                            <a  href="{{ route('users.create')}}" class="btn btn-danger"> Reset</a>

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
    <script src="{{url('admin-assets/js/jquery.validate.min.js')}}"></script> 
    <script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
    <script type="text/javascript">
          
        $(document).ready(function ()
        {   
            $.validator.addMethod(
                "regex",
                function(value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "Please check your input."
            );
            
            //override required method
            $.validator.methods.required = function(value, element, param) {
                return (value == undefined) ? false : (value.trim() != '');
            }
            
            var emailpattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            $('.dropify').dropify();
            $(".form").validate({
                ignore: [],
                rules: {
                    name: {
                        required: true,
                        maxlength:50,
                        minlength:3,
                    },
                    email: {
                        required: true,
                        email:true,
                        regex:emailpattern,
                        maxlength:190,
                        remote: {
                            url:url+'/admin/check/unique/users/email',
                            type: "post",
                            data: {
                                value: function() {
                                    return $( "#email" ).val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            }
                        },
                    },
                    password: {
                        required: {{ empty($admin) ? 'true' : 'false' }},
                        minlength : 8,
                        maxlength:16,
                    },
                    password_confirmation: {
                        required: true,
                        minlength : 8,
                        maxlength:16,
                        equalTo: '#password',
                    },
                    package: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter full name",
                        maxlength:"The full name may not be greater than 180 characters.",
                    },
                    email: {
                        required: "Please enter email",
                        email:"Please enter valid email",
                        regex:"Please enter valid email",
                        maxlength:"The email may not be greater than 190 characters.",
                        remote: 'This email id already exists',
                    },
                    password: {
                        required: "Please enter password",
                        minlength: "Password must be at least 8 characters.",
                        maxlength:"Password must not be greater than 16 characters.",
                    },
                    password_confirmation: {
                        required: 'Please enter confirm password',
                        equalTo: 'Password and confirm password do not match',
                        minlength: "Confirm password must be at least 8 characters.",
                        maxlength:"Confirm password must not be greater than 16 characters.",
                    },
                    package: {
                        required: "Please select package",
                    },
                },
                submitHandler: function (form)
                {
                    form.submit();
                }
            });
        });
    </script>
@endsection
