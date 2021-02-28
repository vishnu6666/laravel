@extends('user.layout.auth.index')

@section('title') Verify OTP @endsection

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

            <!-- Login box start -->
            <div class="login-box card "  style=" box-shadow: 0px 5px 11px 8px #b8d2de;">
                <div class="card-body">
                    @include('user.common.flash')
                    <form class="form-horizontal form-material" id="otpform" method="post"  action="{{ route('verifyotp')}}">

                        {{ csrf_field() }}

                        <h3 class="box-title m-b-20">Verify OTP </h3>

                        <div class="form-group {{ $errors->has('otp') ? ' has-error' : '' }}">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="text" id="otp" name="otp" placeholder="Enter OTP" tabindex="1" data-validation-required-message= "Please enter your otp">
                            </div>
                        </div>

                        <div class="form-group text-center p-b-10">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <button class="btn btn-block btn-lg btn-primary btn-rounded btnForgot" type="submit">Verify</button>
                            </div>
                            <div class="ml-auto m-t-10">
                                <a href="{{ route('userLogin') }}"  class="text-muted"> Back to Sign In </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')

    <script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>

    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });

    </script>

    <!--  jquery validation -->
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#otpform").validate({
                rules: {
                    otp: {
                        required: true,
                        number:true,
                    }
                },
                messages: {
                    otp: {
                        required: "Please enter OTP",
                        number:"Please enter numbers Only"
                    }
                },
                submitHandler: function (form)
                {   
                    //$('.btnForgot').attr('disabled',true);
                    form.submit();
                }
            });


        });
    </script>

@endsection