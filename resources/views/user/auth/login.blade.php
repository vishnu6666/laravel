@extends('user.layout.auth.index')

@section('title') Sign In @endsection

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
                    @include('user.common.flash')
                    <form class="form-horizontal form-material" id="loginform" method="post"  action="{{ url('/user/login')}}">
                        {{ csrf_field() }}
                        <h3 class="text-center m-b-20"> Sign In </h3>
                        <div class="form-group ">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="text"  id="email" name="email" placeholder="Email/Mobile" tabindex="1"  maxlength="40" required data-validation-required-message= "Please enter a valid Email/Mobile"> 
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="col-xs-12 col-md-12 col-lg-12 ml-auto view-code">
                                <input class="form-control" type="password" name="password" id="password" placeholder="Password" tabindex="2" maxlength="16" required data-validation-required-message= "Please enter your password"> 
                                <a href="javascript:void(0)" class="inactive" onclick="showPassword()"><span id="passwordIconId"><i  class="fas fa-eye-slash"></i></span></a> 
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
                                        <a href="{{ route('signup') }}"  class="text-muted"> Sign up ?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 col-md-12 col-lg-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-primary btn-rounded btnLogin" type="submit">Sign In</button>
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
            $("#loginform").validate({
                rules: {
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength : 8,
                        maxlength: 16,
                    },
                },
                messages: {
                    email: {
                        required: "Please enter a valid Email/Mobile",
                    },
                    password: {
                        required: "Please enter Password",
                        minlength: "Password must be 8 to 16 characters",
                        maxlength: "Password must be 8 to 16 characters",
                    },
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