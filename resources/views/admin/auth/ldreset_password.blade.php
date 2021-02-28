@extends('admin.layout.auth.index')
@section('title') Reset Password @endsection

@section('content')

     <section id="wrapper ">
        <div class="login-register" style="
  background-image:
    url( {{ url('admin-assets/images/background/background.png') }});  ">
            <!-- Logo -->
            <div class="text-center" style="margin-bottom: 10px;">
                <a href="javascript:void(0)"  class="text-center m-b-40">
                    <img src="{{url('admin-assets/images/logo/full_logo1.png')}}" style="height: 60px;" alt="Vttips" />
                </a>
            </div>
            <!-- End Logo  -->
                
        @if(!$alreadySet)
            <!-- Login box start -->
            <div class="login-box card"  style=" box-shadow: 0px 5px 11px 8px #8a00009e;">

                @include('admin.common.flash')

                <div class="card-body">

                    <form class="form-horizontal form-material" id="Resetform" method="post"  action="{{ route('admin.password.reset_process')}}">
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
        @else
                <div class="login-box text-center" style="box-shadow:0px 5px 11px 8px #8a00009e">
                    <span class="card" style="background:white"><h3> Password has been already reset. </h3></span>
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
                        required: "Please enter email",
                        regex:"Please provide valid email",
                        maxlength:"Email may not be greater than {0} characters.",
                    },
                    password: {
                        required: "Please provide your password",
                        minlength: "Password must be 8 to 16 alphanumeric characters.",
                        maxlength: "Password must be 8 to 16 alphanumeric characters.",
                    },
                    password_confirmation: {
                        required: "Please provide confirm password",
                        equalTo: "Password and Confirm Password should be same"
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