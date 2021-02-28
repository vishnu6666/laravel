@extends('admin.layout.index')
@section('title') {{ empty($subscriptionPlan) ? 'Create subscription plan' : 'Edit subscription plan' }} @endsection
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
                    <h4 class="text-themecolor">{{ empty($subscriptionPlan) ? 'Create subscription plan' : 'Edit subscription plan' }}</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('subscription-plans.index')}}">Subscription plan</a>
                            </li>
                            <li class="breadcrumb-item active">{{ empty($subscriptionPlan) ? 'Create subscription plan' : 'Edit subscription plan' }}</li>
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
                            @if(empty($subscriptionPlan))
                                <form class="form" name="SubscriptionPlan" id="SubscriptionPlan" method="post" enctype="multipart/form-data" action="{{ route('subscription-plans.store') }}">
                            @else
                                <form class="form" name="SubscriptionPlanupdate" id="SubscriptionPlanupdate" method="post" enctype="multipart/form-data" action="{{ route('subscription-plans.update', ['subscription_plan' => $subscriptionPlan->id]) }}">
                                    @method('PUT')
                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id" value="{{ $subscriptionPlan->id ?? '' }}" />
                                    <div class="form-group m-t-40 row">
                                        <label for="planName" class="col-2 col-form-label">Plan Name<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $subscriptionPlan->planName ?? '' }}"
                                                    name="planName" id="planName" placeholder="Please enter plan name" tabindex="1" required >
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="planSubTitle" class="col-2 col-form-label">Plan Sub Title<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $subscriptionPlan->planSubTitle ?? '' }}"
                                                    name="planSubTitle" id="planSubTitle" placeholder="Please enter plan sub title" tabindex="1" required >
                                                    <br> 
                                    <span class="help-block"><small style="color: #757981;">Ex: silver,gold... </small></span>
                                         </div>
                                    </div>

                                    <div class="form-group m-t-40 row hide">
                                        <label for="planSubTitle" class="col-2 col-form-label">Plan Trial Expire Days<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <div class="form-group">
                                                <select class="form-control custom-select" name="planDuration" id="planDuration" tabindex="1">
                                                    <option value="">Select plan trial expire day</option>
                                                    <?php for($i=1 ; $i <= 8 ; $i++){?>
                                                    <option value="<?php echo $i;?>" {{ $i==7 ? 'selected' : '' }} <?php if(isset($subscriptionPlan->planDuration) && ($subscriptionPlan->planDuration == $i)){ echo "selected";}?>><?php echo $i;?> Day</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="planFullPackages" class="col-2 col-form-label">No. Of Packages <span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $subscriptionPlan->planFullPackages ?? '' }}"
                                                    name="planFullPackages" id="planFullPackages" placeholder="Please enter number of packages" tabindex="1" required >
                                                  <br> 
                                    <span class="help-block"><small style="color: #757981;">Ex: 2,3,...no of games </small></span>
                                                
                                                </div>
                                    </div>
                                    

                                    <div class="form-group m-t-40 row">
                                        <label for="planFullPackagesTitle" class="col-2 col-form-label">Packages Heading<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $subscriptionPlan->planFullPackagesTitle ?? '' }}"
                                                    name="planFullPackagesTitle" id="planFullPackagesTitle" placeholder="Please enter packages heading" tabindex="1" required >
                                        <br> 
                                    <span class="help-block"><small style="color: #757981;">Ex: Any 2 full package </small></span>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group m-t-40 row">
                                        <label for="planSubTitle" class="col-2 col-form-label">plan-Type<span class="text-danger">*</span></label>
                                            <div class="col-5">
                                                <div class="form-group">
                                                    <select class="form-control custom-select" name="planType" id="planType" tabindex="1">
                                                        <option value="">Select plan Type</option>
                                                        <option value="Weekly" <?php if(isset($subscriptionPlan->planType) && ($subscriptionPlan->planType =='Weekly')){ echo "selected";}?>>Weekly</option>
                                                        <option value="Monthly" <?php if(isset($subscriptionPlan->planType) && ($subscriptionPlan->planType =='Monthly')){ echo "selected";}?>>Monthly</option>
                                                    </select>
                                                </div>
                                            </div>
                                    </div> -->

                                    <div class="form-group m-t-40 row">
                                        <label for="planWeeklyPrice" class="col-2 col-form-label">Plan Weekly Price<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $subscriptionPlan->planWeeklyPrice ?? '' }}"
                                                    name="planWeeklyPrice" id="planWeeklyPrice" placeholder="Please enter plan weekly price" tabindex="1" required >
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="planMonthlyPrice" class="col-2 col-form-label">Plan Monthly Price<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $subscriptionPlan->planMonthlyPrice ?? '' }}"
                                                    name="planMonthlyPrice" id="planMonthlyPrice" placeholder="Please enter plan monthly price" tabindex="1" required >
                                        </div>
                                    </div>
                                
                                    {{-- <div class="form-group m-t-40 row">
                                        <label for="status" class="col-2 col-form-label">Status<span class="text-danger">*</span></label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status1" name="status" value="1"
                                            class="custom-control-input" {{ (!empty($subscriptionPlan) && $subscriptionPlan->isActive == 1) ? 'checked' : ''}} {{ (empty($subscriptionPlan)) ? 'checked' : '' }} >
                                            <label class="custom-control-label" for="status1">Active</label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status2" name="status" value="0" 
                                            class="custom-control-input" {{ (!empty($subscriptionPlan) && $subscriptionPlan->isActive == 0) ? 'checked' : ''}}>     
                                            <label class="custom-control-label" for="status2">Inactive</label>
                                        </div>
                                    </div> --}}

                                    <div class="form-group row">
                                        <div class="col-5 text-center">
                                            <button type="submit" class="btn btn-success"> {{ empty($subscriptionPlan) ? 'Create' : 'Update' }}</button>

                                            @if(!empty($subscriptionPlan)) 
                                    
                                            <a  href="{{ route('subscription-plans.edit',[ 'subscription_plan' => $subscriptionPlan->id] )}}" class="btn btn-danger"> Reset</a>

                                            @else

                                            <a  href="{{ route('subscription-plans.create')}}" class="btn btn-danger"> Reset</a>

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
            var packagesCount = {{ $packagesCount }};
            
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
            
            $('.dropify').dropify();
            $(".form").validate({
                ignore: [],
                rules: {
                    planName: {
                        required: true,
                        maxlength:10,
                        minlength:3,
                    },
                    planFullPackages: {
                        required: true,
                        max:packagesCount,
                        min:1,
                        number: true
                    },
                    planFullPackagesTitle: {
                        required: true,
                        maxlength:50,
                        minlength:3,
                    },
                    planSubTitle: {
                        required: true,
                        maxlength:50,
                        minlength:3,
                    },
                    planDuration: {
                        required: true
                    },
                    planType: {
                        required: true
                    },
                    planWeeklyPrice: {
                        required: true,
                        max:9999999999,
                        min:1,
                        number: true
                    },
                    planMonthlyPrice: {
                        required: true,
                        max:9999999999,
                        min:1,
                        number: true
                    },
                },
                messages: {
                    planName: {
                        required: "Please enter plan name",
                        maxlength:"The plan name may not be greater than 10 characters.",
                    },
                    planFullPackages: {
                        required: "Please enter plan full packages",
                        max: jQuery.validator.format("Please enter a value less than or equal to {0} packages."),
                        min: jQuery.validator.format("Please enter a value greater than or equal to {0} packages.")
                    },
                    planFullPackagesTitle: {
                        required: "Please enter plan full packages title ",
                        maxlength:"The plan Full Packages title may not be greater than 50 characters.",
                    },
                    planSubTitle: {
                        required: "Please enter plan sub title",
                        maxlength:"The plan title may not be greater than 50 characters.",
                    },
                    planDuration: {
                        required: "Please select Plan trial expire days"
                    },
                    planWeeklyPrice: {
                        required: "Please enter plan weekly price.",
                        max: jQuery.validator.format("Please enter plan weekly price may not be greater than or equal to {0}."),
                        min: jQuery.validator.format("Please enter plan weekly price may not be less than or equal to {0}.")
                    },
                    planMonthlyPrice: {
                        required: "Please enter plan monthly price",
                        max: jQuery.validator.format("Please enter plan monthly price may not be greater than or equal to {0}."),
                        min: jQuery.validator.format("Please enter plan monthly price may not be less than or equal to {0}.")
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
