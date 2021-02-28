@extends('subadmin.layout.auth.index')

@section('title') Forgot Password @endsection

@section('content')
    <section id="wrapper ">
        <div class="login-register" style="
  background-image:
    url( {{ url('admin-assets/images/background/background.png') }});  ">
             <!-- Logo -->
             <div class="text-center" style="margin-bottom: 10px;">
                <img src="{{url('admin-assets/images/logo/full_logo1.png')}}" style="height:30px;" alt="Vttips" />
            </div>
            <!-- End Logo  -->

            <!-- Login box start -->
            <div class="login-box card "  style=" box-shadow: 0px 5px 11px 8px #b8d2de;">
                <div class="card-body">
                    @include('subadmin.common.flash')
                    <form class="form-horizontal form-material" id="forgotform" method="post"  action="{{ route('subAdminForgotPassword')}}">

                        {{ csrf_field() }}

                        <h3 class="box-title m-b-20">Forgot your Password?</h3>

                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <input class="form-control" type="email" id="email" name="email" placeholder="Your Email" tabindex="1" data-validation-required-message= "Please enter your email">
                            </div>
                        </div>

                        <div class="form-group text-center p-b-10">
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <button class="btn btn-block btn-lg btn-primary btn-rounded btnForgot" type="submit">Send</button>
                            </div>
                            <div class="ml-auto m-t-10">
                                <a href="{{ route('subadminLogin') }}"  class="text-muted"> Back to Login </a>
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
            var emailpattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

            $.validator.addMethod(
                    "regex",
                    function(value, element, regexp) {
                        var re = new RegExp(regexp);
                        return this.optional(element) || re.test(value);
                    },
                    "Please check your input."
            );

            $("#forgotform").validate({
                rules: {
                    email: {
                        required: true,
                        email:email,
                        regex:emailpattern,
                    }
                },
                messages: {
                    email: {
                        required: "Please enter email",
                        email:"Please enter valid email ",
                        regex:"Please enter valid email address"
                    }
                },
                submitHandler: function (form)
                {   

                     $('.btnForgot').attr('disabled',true);
                    form.submit();
                }
            });


        });
    </script>

@endsection