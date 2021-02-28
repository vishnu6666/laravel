@extends('admin.layout.auth.index')

@section('title') Admin Sign In @endsection

@section('content')

    <section id="wrapper "> <!-- background-size: 230px;

        background-repeat: round; -->
        <div class="login-register" style="background-image: url( {{ url('admin-assets/images/background/background.png') }});  ">
            <!-- Logo -->
            <div class="text-center" style="margin-bottom: 10px;">
            <img src="{{url('admin-assets/images/logo/full_logo1.png')}}" style="height:60px;" alt="Vttips" />
            </div>
            <!-- End Logo  -->
            <!-- Login box start -->
            <div class="login-box card" style=" box-shadow: 0px 5px 11px 8px #b8d2de;">
                <div class="card-body">
                    @include('admin.common.flash')
                    <form class="form-horizontal form-material" id="loginform" method="post"  action="{{ url('/superadmin/login')}}">
                        {{ csrf_field() }}
                        <h3 class="text-center m-b-20"> Super Admin - Sign In </h3>
                        <div class="form-group ">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="email"  id="email" name="email" placeholder="Your Email" tabindex="1" required data-validation-required-message= "Please enter your email"> 
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <div class="d-flex no-block align-items-center">
                                    <div class="col-xs-12 col-md-12 col-lg-12 ml-auto view-code">
                                        <input class="form-control" type="password" name="password" id="password" placeholder="Password" tabindex="2" required data-validation-required-message= "Please enter your password"> 
                                        <a href="javascript:void(0)" class="inactive" onclick="showPassword()"><span id="passwordIconId"><i  class="fas fa-eye-slash"></i></span></a> 
                                    </div>
                                    <div class="col-xs-1 col-md-1 col-lg-1">
                                        
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <div class="d-flex no-block align-items-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Remember me</label>
                                    </div>
                                    <div class="ml-auto">
                                        <a href="{{ route('AdminForgotPassword') }}"  class="text-muted"> Forgot password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 col-md-12 col-lg-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-primary btn-rounded btnLogin" type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Login box stop -->
        </div>
    </section>
@endsection

@section('js')

    <script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>
    <script type="text/javascript">
        function showPassword() 
        {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") 
            {
                passwordInput.type = "text";
                $('#passwordIconId').find('i').removeClass("fas fa-eye-slash").addClass("fas fa-eye");
            } 
            else 
            {
                passwordInput.type = "password";
                $('#passwordIconId').find('i').removeClass("fas fa-eye").addClass("fas fa-eye-slash");
            }
        } 
       
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

            $("#loginform").validate({
                rules: {
                    email: {
                        required: true,
                        email:email,
                        regex:emailpattern
                    },
                    password: {
                        required: true,
                    }
                },
                messages: {
                    email: {
                        required: "Please enter email",
                        email:"Please enter valid email",
                        regex:"Please enter valid email"
                    },
                    password: {
                        required: "Please enter password",
                    }
                },
                submitHandler: function (form)
                {   
                    $('.btnLogin').attr('disabled',true);
                    form.submit();
                }
            });
        });
    </script>
@endsection