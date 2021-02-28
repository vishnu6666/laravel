@extends('admin.layout.auth.index')
@section('title') Reset Password @endsection

@section('content')

     <section id="wrapper ">
        <div class="login-register" style="
  background-image:
    url( {{ url('admin-assets/images/background/background.png') }});  ">
            <!-- Logo -->
            <div class="text-center" style="margin-bottom: 10px;">
                <img src="{{url('admin-assets/images/logo/full_logo1.png')}}" style="height:60px;" alt="Vttips" />
            </div>
            <!-- End Logo  -->
        @if($alreadySet == 0)
            <!-- Login box start -->
            <div class="login-box card"  style="box-shadow: 0px 5px 11px 8px #b8d2de;">

                @include('admin.common.flash')

                <div class="card-body">

                    <form class="form-horizontal form-material" id="Resetform" method="post"  action="{{ route('superAdminPasswordResetProcess')}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" id="token" value="{{ $token }}">
                        <h3 class="text-center m-b-20">Reset Password</h3>
                        <div class="form-group ">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="email"  id="email" name="email" placeholder="Your Email" tabindex="1"> </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="password"  id="password" name="password" placeholder="New password" tabindex="2"> </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" tabindex="3" > </div>
                        </div>

                        <div class="form-group text-center">
                            <div class="col-xs-12 col-md-12 col-lg-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-primary btn-rounded btnReset" type="submit">Reset</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        @elseif ($alreadySet == 1)
            <div class="login-box card"  style="box-shadow: 0px 5px 11px 8px #b8d2de;">
                    <div class="card-body">
                        <div style="text-align: center; color:red" >
                            <h3>{{ $message }}</h3>
                        </div>
                    </div>
            </div>
        @elseif ($alreadySet == 2)
            <div class="login-box card"  style="box-shadow: 0px 5px 11px 8px #b8d2de;">
                    <div class="card-body">
                        <div style="text-align: center; color:red" >
                            <h3> {{ $message }}</h3>
                        </div>
                    </div>
            </div>
        @elseif ($alreadySet == 3)
            <div class="login-box card"  style="box-shadow: 0px 5px 11px 8px #b8d2de;">
                    <div class="card-body">
                        <div style="text-align: center; color:green" >
                            <h3>{{ $message }}</h3>
                        </div>
                        <div class="ml-auto" style="text-align:center">
                            <a href="{{ route('adminLogin') }}"  class="text-muted"> Log In </a>
                        </div>
                    </div>
            </div>
        @endif
            <!-- Login box stop -->
        </div>
    </section>

@endsection

@section('js')

    <script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>
    <script type="text/javascript">
        $(document).ready(function ()
        {
            var emailpattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

            $.validator.addMethod(
                    "regex",
                    function(value, element, regexp) {
                        var re = new RegExp(regexp);
                        return this.optional(element) || re.test(value);
                    },
                    "Please check your input."
            );

            $("#Resetform").validate({
                rules: {
                    email: {
                        required: true,
                        regex:emailpattern,
                        maxlength:150,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength:16,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    email: {
                        required: "Email is required",
                        regex:"Please provide valid email address",
                        maxlength:"Email may not be greater than {0} characters.",
                    },
                    password: {
                        required: "New Password is required",
                        minlength: "Password must be at least {0} characters.",
                        maxlength:"Password may not be greater than {0} characters.",
                    },
                    password_confirmation: {
                        required: "Confirm password is required",
                        equalTo: "Confirm password must be same as password"
                    }
                },
                submitHandler: function (form)
                {   
                    $('.btnReset').attr('disabled',true);
                    form.submit();
                }
            });

            $.validator.addMethod("same_value", function(value, element) {
                return $('#password').val() != $('#confirm_password').val()
            });
        });
    </script>
@endsection