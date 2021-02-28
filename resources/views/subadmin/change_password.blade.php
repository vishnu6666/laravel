@extends('subadmin.layout.index')
@section('title') Change Password @endsection
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
                    <h4 class="text-themecolor">Change Password</h4>
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
                            <h4 class="card-title">Change Password</h4>

                            <form class="form" id="Resetform" method="post" enctype="multipart/form-data" action="{{ route('UpdateSubAdminChangePassword')}}">
                                {{ csrf_field() }}
                                <div class="form-group m-t-40 row">
                                    <label for="current_password" class="col-2 col-form-label">Current Password<span class="text-danger">*</span></label>
                                    <div class="col-5">
                                        <input class="form-control" type="password"  id="current_password" name="current_password" placeholder="Current Password" tabindex="1">
                                    </div>
                                </div>
                                <div class="form-group m-t-40 row">
                                    <label for="password" class="col-2 col-form-label">New Password<span class="text-danger">*</span></label>
                                    <div class="col-5">
                                        <input class="form-control" type="password"  id="password" name="password" placeholder=" New Password" tabindex="2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-2 col-form-label">Confirm Password<span class="text-danger">*</span></label>
                                    <div class="col-5">
                                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required placeholder="Confirm Password" tabindex="3">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-5 text-center">
                                        <button type="submit" class="btn btn-success"> Change Password</button>
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

    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#Resetform").validate({
                rules: {
                    current_password: {
                        required: true,
                        minlength:6,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength:16,
                        same_value: true,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                  messages: {
                    current_password: {
                        required: "Please enter current password",
                        minlength: "Current password must be 8 to 16 alphanumeric characters"
                    },
                    password: {
                        required: "Please enter new password",
                        minlength: "New password must be 8 to 16 alphanumeric characters",
                        maxlength: "New password must be 8 to 16 alphanumeric characters",
                        same_value: "New password and confirm password should be same",
                    },
                    password_confirmation: {
                        required: "Please enter confirmation password",
                        equalTo: "New password and confirm password should be same"
                    }
                },
                submitHandler: function (form)
                {
                    form.submit();
                }
            });

            $.validator.addMethod("same_value", function(value, element) {
                return $('#current_password').val() != $('#password').val()
            });
        });
    </script>
@endsection