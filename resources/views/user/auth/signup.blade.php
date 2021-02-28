@extends('user.layout.auth.index')

@section('title') Sign Up @endsection

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
                    <form class="form-horizontal form-material" id="signform" method="post"  action="{{ url('/user/signup')}}">
                        {{ csrf_field() }}
                        <h3 class="text-center m-b-20"> Sign Up </h3>
                        
                        <input class="form-control" type="hidden"  id="deviceType" name="deviceType" value="Web" required>
                        <input class="form-control" type="hidden"  id="deviceToken" name="deviceToken" value="null"  required> 
                        <div class="form-group">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="name"  id="name" name="name" placeholder="Full Name" tabindex="1" maxlength="30" required data-validation-required-message= "Please enter Full Name"> 
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="email"  id="email" name="email" placeholder="Email Address" tabindex="2" maxlength="40" required data-validation-required-message= "Please enter Email"> 
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="mobileNumber"  id="mobileNumber" name="mobileNumber" placeholder="Mobile Number" tabindex="3" maxlength="16"> 
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="referralCodeBy"  id="referralCodeBy" name="referralCodeBy" placeholder="Referral Code" tabindex="4" maxlength="12"> 
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-12 col-lg-12 ml-auto view-code">
                                <input class="form-control" type="password" name="password" id="password" placeholder="Password" tabindex="5" maxlength="16" required data-validation-required-message= "Please enter Password"> 
                                <a href="javascript:void(0)" class="inactive" onclick="showPassword()"><span id="passwordIconId"><i  class="fas fa-eye-slash"></i></span></a> 
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <div class="d-flex no-block align-items-center">
                                    {{-- <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="termsAndConditions" name="termsAndConditions">
                                        <label class="custom-control-label" for="termsAndConditions">Terms and Conditions</label>
                                    </div> --}}
                                    <div class="ml-auto">
                                        <a href="{{ route('userLogin') }}"  class="text-muted"> Sign In ?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 col-md-12 col-lg-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-primary btn-rounded btnSignUp" type="submit">Sign Up</button>
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

   
    <script src="{{url('admin-assets/js/jquery.validate.min.js')}}"></script> 
    <script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
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

            $("#signform").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength:30,
                        minlength:2,
                    },
                    email: {
                        required: true,
                        email:email,
                        regex:emailpattern,
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
                            },
                        },
                    },
                    password: {
                        required: true,
                        minlength : 8,
                        maxlength: 16,
                    },
                    mobileNumber: {
                        number:true,
                        maxlength:15,
                        minlength:6,
                    },
                    referralCodeBy: {
                        maxlength:12,
                        minlength:12,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter Full Name",
                        maxlength: "Please enter atleast 2-30 characters in Full Name",
                        minlength: "Please enter atleast 2-30 characters in Full Name",
                    },
                    email: {
                        required: "Please enter Email",
                        email:"Please enter a valid Email",
                        regex:"Please enter a valid Email",
                        remote: 'An entered Email already registered with us',
                    },
                    password: {
                        required: "Please enter Password",
                        minlength: "Password must be 8 to 16 characters",
                        maxlength: "Password must be 8 to 16 characters",
                    },
                    mobileNumber: {
                        number:"Please enter numbers Only",
                        minlength: "Please enter atleast 6 digits in Mobile Number.",
                        maxlength: "Please enter a valid Mobile Number",
                    },
                    referralCodeBy: {
                        minlength: "Please enter 12 characters in Referral Code.",
                        maxlength: "Please enter 12 characters in Referral Code.",
                    },
                },
                submitHandler: function (form)
                {   
                    $('.btnSignUp').attr('disabled',true);
                    form.submit();
                }
            });
        });
    </script>
@endsection