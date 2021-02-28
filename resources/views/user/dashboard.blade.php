@extends('user.layout.index')

@section('title') User Dashboard @endsection

{{-- @section('css')

@endsection --}}

@if (count($errors) > 0)
    <div class="alert alert-danger alertdisapper">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style type="text/css">
    
</style>
@section('content')
<section class="title-box-top">
    <h1 class="page-title" style="padding-bottom: 7px !important;"><b>Subscription Plans</b></h1>
    <h2 class="x-small-title"><strong>You may choose the plans which will be best suited your requirements.</strong></h2>
</section>
<!-- <section class="main-plan-item current-plan">
     @if(!empty($currentPlanDetail))
     <h2 class="title-small section-title"><strong>Your Current Plan</strong></h2>
        @foreach($currentPlanDetail as $currentPlan)
        <div class="plan-box highlight-border">
            <div class="flex-space-box flex-align-top">
                <div class="label-line"><span class="highlight-color">{{ $currentPlan->planType }} Subscription Plan |</span> Valid till {{ $currentPlan->expiryDate }}</div>
                <span class="page-title"><strong>{{ config('constant.CURRENCY') }}{{ $currentPlan->planAmount }}</strong></span>
                <img src="{{ url('front/images/checked-round-green.svg') }}">
            </div>
            <h3 class="title"><b>{{ $currentPlan->planName }}</b></h3>
            <div class="flex-space-box flex-align-bottom">
                <h4 class="x-small-title medium">{{ str_replace(',', ', ', $currentPlan->packageNames) }}</h4>
                <a href="#" class="blue-button  md-size-btn">Cancel</a>
            </div>
        </div>
        <br>
        @endforeach
    @endif
</section> -->
            <section class="main-plan-item current-plan">
            @if(!empty($currentPlanDetail))
                <h2 class="title-small section-title"><strong>Your Current Plan</strong></h2>
                @foreach($currentPlanDetail as $currentPlan)
				<div class="plan-box highlight-border">
					<div class="flex-space-box flex-align-top">
						<div class="label-line"><span class="highlight-color">{{ $currentPlan->planType }} Subscription Plan 
                        @if($currentPlan->isCancel == 1)
                        </span>| Valid till {{ $currentPlan->expiryDate }}
                        @endif</div>

                        @if($currentPlan->isCancel == 0)
                        <span class="page-title"><strong>{{ config('constant.CURRENCY') }}{{ $currentPlan->planAmount }}</strong>
                        @endif
						
                        <img  class="circle-check-img" src="{{ url('front/images/checked-round-green.svg') }}">
						</span>
					</div>
					<h3 class="title"><b>{{ $currentPlan->planName }}</b></h3>
					<div class="flex-space-box flex-align-center">
                        <h4 class="x-small-title medium">{{ str_replace(',', ', ', $currentPlan->packageNames) }}</h4>
                        @if($currentPlan->isCancel == 0)
                        <button type="button" class="blue-button cancelPlan md-size-btn" id="{{ $currentPlan->id }}" onClick="cancelPlan(this.id)">Cancel</button>
                        @endif
                        @if($currentPlan->isCancel == 1)
                        <span class="page-title"><strong>{{ config('constant.CURRENCY') }}{{ $currentPlan->planAmount }}</strong>
                        @endif
					</div>
                </div>
                <br>
                @endforeach
            @endif
			</section>

<section class="plans-box">
    <h2 class="title-small section-title"><strong>Subscription Plans</strong></h2>
    <div id="accordion" class="accordion">
        @if(!empty($upgradeplanData))
            @foreach($upgradeplanData as $upgradeplan)
            <div class="accordion-item plan-box">
            <div class="card-header collapsed" id="accordian_link_{{$upgradeplan->id}}" data-toggle="collapse" href="#plan_{{$upgradeplan->id}}">
                    <div class="flex-space-box top-arrow-box card-inner">
                        <h3 class="title"><b>{{ $upgradeplan->planName }} <sub>(Any {{ $upgradeplan->planFullPackages }} Full Packages)</sub></b></h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="21.988" height="12.569" viewBox="0 0 21.988 12.569"><path id="Path_22845" data-name="Path 22845" d="M10.992,0A1.542,1.542,0,0,0,9.9.46L.453,9.927a1.549,1.549,0,0,0,2.191,2.19l8.35-8.376,8.348,8.372a1.549,1.549,0,1,0,2.195-2.187L12.093.455A1.537,1.537,0,0,0,11,0Z" fill="#232323"/></svg>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="general-check-box round">
                        
                            <input type="radio" name="planId" data-package-name="{{ $upgradeplan->planName }}" data-subplan-name="Weekly" data-package-count="{{$upgradeplan->planFullPackages}}" data-value="{{$upgradeplan->planWeeklyPrice}}"  value="{{$upgradeplan->id}}" id="{{'weekly_'.$upgradeplan->id}}">
                            <label for="{{'weekly_'.$upgradeplan->id}}">{{ config('constant.CURRENCY') }}{{$upgradeplan->planWeeklyPrice}} <sub> Weekly Plan</sub>
                        </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="general-check-box round">
                        <input type="radio" name="planId" data-package-name="{{ $upgradeplan->planName }}" data-subplan-name="Monthly"  data-package-count="{{$upgradeplan->planFullPackages}}" data-value="{{$upgradeplan->planMonthlyPrice}}" value="{{$upgradeplan->id}}" id="{{'monthly_'.$upgradeplan->id}}">
                            <label for="{{'monthly_'.$upgradeplan->id}}">{{ config('constant.CURRENCY') }}{{$upgradeplan->planMonthlyPrice}} <sub>Monthly Plan</sub></label>
                        </div>
                    </div>
                </div>
                <div id="plan_{{$upgradeplan->id}}" class="card-body collapse" {{--data-parent="#accordion"--}} >
                   
                    @if(!empty($upgradeplan->packages))

                        @foreach($upgradeplan->packages as $planPackage)
                            <div id="plan_sub_{{$upgradeplan->id}}" class="accordion sub-accordion">
                                <div class="accordion-item">
                                    <div class="card-header collapsed" data-toggle="collapse" href="#package_{{$planPackage['id']}}_{{$upgradeplan->id}}">
                                            <div class="flex-space-box card-inner">
                                               
                                                <div class="general-check-box with-no-label">
                                            
                                                        @php
                                                        $gameData = '';
                                                        $gameFullData = '';
                                                        if($planPackage['gamesData']->isNotEmpty())
                                                        {
                                                            foreach($planPackage['gamesData'] as $game)
                                                            {
                                                                $gameFullData = $gameFullData.$game->gameFullName.' ('.$game->gameName.'), ';
                                                                $gameData = $gameData.$game->gameName.', ';
                                                            }   
                                                        }
                                                        $gameFullData = rtrim($gameFullData, ', ');
                                                        $gameData = rtrim($gameData, ', ');
                                                        @endphp

                                                    <input type="checkbox" id="packageId_{{ $upgradeplan->id }}_{{$planPackage['id']}}" data-packageData="" data-package="{{$planPackage['packageName']}}" data-game="{{$gameData}}" data-packageLabelClass="{{--plan_packages_label_{{ $upgradeplan->id }}--}}" data-packageClass="plan_packages_{{ $upgradeplan->id }}" class="plan_packages plan_packages_{{ $upgradeplan->id }}" value="{{$planPackage['id']}}">
                                                    <label for="packageId_{{ $upgradeplan->id }}_{{$planPackage['id']}}" class="title-small plan_packages_label plan_packages_label_{{ $upgradeplan->id }}">{{ $planPackage['packageName'] }}</label>
                                                </div>
                                            
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21.988" height="12.569" viewBox="0 0 21.988 12.569"><path id="Path_22845" data-name="Path 22845" d="M10.992,0A1.542,1.542,0,0,0,9.9.46L.453,9.927a1.549,1.549,0,0,0,2.191,2.19l8.35-8.376,8.348,8.372a1.549,1.549,0,1,0,2.195-2.187L12.093.455A1.537,1.537,0,0,0,11,0Z" fill="#232323"/></svg>
                                    </div>
                                        {{--<div class="flex-space-box top-arrow-box">
                                        
                                            <h3 class="title-small"><b>{{ $planPackage['packageName'] }}</b></h3>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21.988" height="12.569" viewBox="0 0 21.988 12.569"><path id="Path_22845" data-name="Path 22845" d="M10.992,0A1.542,1.542,0,0,0,9.9.46L.453,9.927a1.549,1.549,0,0,0,2.191,2.19l8.35-8.376,8.348,8.372a1.549,1.549,0,1,0,2.195-2.187L12.093.455A1.537,1.537,0,0,0,11,0Z" fill="#232323"/></svg>
                                        </div>--}}
                                    </div>
                                    
                                    <div id="package_{{$planPackage['id']}}_{{$upgradeplan->id}}" class="card-body collapse" {{--data-parent="#plan_sub_{{$upgradeplan->id}}" --}} >
                                        <div class="sub-accordion-content">
                                                
                                            <p>{{ $gameFullData }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endforeach
        @endif
    </div>
</section>

{{-- Discount promocode section start  --}}
<section class="no-padding-top">
    <div class="plan-box gray-box d-none " id="viewSelectedPlanDiv">
        <div class="flex-space-box flex-align-top">
            <h3 class="title"><b><span id="viewSelectedPlan"></span></b></h3>
            <h3 class="title"><b><span id="viewPlanPrice"></span></b></h3>
        </div>
        <div id="viewSelectedPackages">
               
        </div>
    </div>
    <br>
    <div class="plan-box gray-box d-none" id="discountDiv">
        <div class="flex-space-box flex-align-top">
            <h3 class="title"><b><span id="appliedPromoCode"></span></b> <img width="35" height="35" src="{{ url('front/images/checked-round-green.svg') }}"></h3>
            <h3 class="title"><b><span id="appliedPromoCodeAmount"></span></b></h3>
            <div class="close-icon">
                <a href="" id="removeDiscount"><img src="{{ url('front/images/cross-button.png') }}"></a>
            </div>
        </div>
        <h4 class="x-small-title medium">You will get a discount of <span id="appliedpromoCodeDiscountAmount"> </span> on total amount.</h4>
    </div>
    <br>
    <div class="plan-box gray-box d-none" id="viewPayableAmount">
            <div class="flex-space-box flex-align-top">
                <h3 class="title"><b><span>Total Amount</span></b></h3>
                <h3 class="title"><b><span id="viewPaymentPrice"></span></b></h3>
            </div>
            <div id="viewSelectedPackages">
                   
            </div>
        </div>
</section>

<div> <a href="" class="d-none" id="applyCode" data-toggle="modal" data-target="#largeModal" data-backdrop="static" data-keyboard="false">Apply Discount Code ?</a> </div>

<input type="hidden" id="discountCode" value="">
<input type="hidden" id="discountAmount" value="">
<input type="hidden" id="percentageAmount" value="">
<input type="hidden" id="paymentAmount" value="">
<input type="hidden" id="planAmount" value="">
<input type="hidden" id="typeDiscount"  value="">
<input type="hidden" id="planType"  value="">
<input type="hidden" id="cardToken"  value="">


    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg custom-modal-large">
			<div class="modal-content">
				<button type="button" id="closePopup" class="close" data-dismiss="modal" aria-label="Close">
					<img src="{{ url('front/images/cross-button.png') }}">
				</button>
				<div class="model-content-box">
					<div class="side-image-box">
						<img src="{{ url('front/images/popup-side-image.png') }}">
					</div>
                        <div class="form-box text-center">
                            <img src="{{ url('front/images/small-logo.svg') }}">
                            <h3 class="title"><b>Apply Discount Code</b></h3>
                            <h4 class="x-small-title">To get the most of value for the subscription plan</h4>
                            <form id="discountform" name="discountform">
                                {{ csrf_field() }}
                                <div class="input-box" for="promoCode">
                                    <input type="text" name="promoCode" id="promoCode" placeholder="Discount code" class="full-width">
                                    <span class="errorBlock" id="errorpromoCode"><br></span>
                                    <span class="successBlock" id="successpromoCode"><br></span>
                                </div>
                            <button type="button" class="blue-button full-width text-center " id="applyDiscountButton">Apply</button>
                        </div>
                        </form>
				</div>
			</div>
		</div>
	</div>

{{-- Discount promocode section end  --}}

<section class="payment-method-inner no-padding subscription-plan-pay">
    <div class="md-main-box"> 
        <div class="mg-b-30 subs-plan-inner flex-space-box">  
            
            <img id="loadingGetcard" heght="50px" width="50px" src="{{ url('front/images/loader.gif') }}">
            <div class="card-dropdown-list card-list   btn-group cardListDiv">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="flex-space-box justify-content-flexstart payment-method-col">
                        <div class="visa-img">
                            <span class="cardImage"></span>
                        </div>
                        <div class="flex-space-box">
                            <div class="visa-detail">
                                <h4 class="md-title text-left"><b class="cardHolderName"></b></h4>
                                <span class="xs-small-title lastFour"></span>
                            </div>
                        </div>
                    </div>
                    <span id="defaultCard"></span>
                    <a href="#" class="card-arrow"><img src="{{ url('front/images/subs-list-right Arrow.svg') }}"></a>
                </button> 
                
                <ul class="dropdown-menu cardTokenList" id="cardList"> 

                </ul>
            </div> 
            <span class="addCardLink"></span> 
                
            <div class="action-btn  flex-space-box">
            <button type="button" id="proceedToPay" class="blue-button medium-size-btn">Proceed to Pay</button>
            <img id="loading" heght="50px" width="50px" src="{{ url('front/images/loader.gif') }}">
            <br>
            <span class="errorBlock" id="errorProceedToPay"><br></span>
                <!-- <a href="#" class="blue-button medium-size-btn" data-toggle="modal" data-target="#largeModal">Proceed to Pay</a> -->
            </div> 
        </div>  
    </div> 
</section> 

<!-- <section class="action-btn">
    <button type="button" id="proceedToPay" class="blue-button medium-size-btn">Proceed to Pay</button>
    <img id="loading" heght="50px" width="50px" src="{{ url('front/images/loader.gif') }}">
    <br>
    <span class="errorBlock" id="errorProceedToPay"><br></span>
</section> -->

<div id="formHtml" class="d-none">
</div>

<!-- cancel plan model start -->
<div class="modal fade" id="cancelPlanlargeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md remove-pay-model">
        <div class="modal-content pad-30">
            <div class="flex-space-box">
                <h2 class="title"><b>Cancel subscription plan</b></h2>
                <button type="button" id="closecancelPlanYesPopup" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ url('front/images/cross-button.png') }}">
                </button>
            </div>
            <div class="model-content-box"> 
                <div class="form-box">
                        <div class="input-box">
                            <p>Are you sure you want to Cancel this subscription plan ?</p>
                        </div>
                        <input type="hidden" id="cancelPlanId" value="">
                        <button class="border-btn  text-center" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button id="cancelPlanYes" class="blue-button text-center">Yes</button>
                        <img id="loadingMakeCancel" class="text-center" heght="50px" width="50px" src="{{ url('front/images/loader.gif') }}">
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- cancel plan model end -->

{{--<form action="{{ route('payPayment') }}" method="post">
    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="pk_test_nQy7beDZgtgJt9nehe3iJgsz00hReqvluS"
        data-name="Vttips"
        data-description="Vttips"
        data-image="{{ url('front/images/logo.svg') }}" 
        data-amount="4400"
        data-locale="auto">
    </script>
/* you can pass parameters to php file in hidden fields, for example - plugin ID */ ?>
  <input type="hidden" name="plugin_id" value="12121212">
</form> --}}

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script type="text/javascript">
$('#loading').hide();

    function cancelPlan(subscriptionHistoryId) {
        $('#cancelPlanId').val('');
        $('#cancelPlanId').val(subscriptionHistoryId);
        $("#cancelPlanYes").show();
        $('#loadingMakeCancel').hide();
        $('#cancelPlanlargeModal').modal('show');
    } 

    $(document).ready(function(){
            $("#cancelPlanlargeModal").on('hide.bs.modal', function(){
                $('#cancelPlanId').val('');
        });
    });

    $('#cancelPlanYes').click(function () {
        var subscriptionHistoryId = $('#cancelPlanId').val(); 
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
            url:"{{ route('cancelSubscriptionPlan') }}",
            data:{ deviceType:'Web',subscriptionHistoryId:subscriptionHistoryId,isCancel:1 },
            beforeSend: function(){
                $("#loadingMakeCancel").show();
                $("#cancelPlanYes").hide();
            },
            success:function(data){
                
                if(data.status == 1){
                    location.reload();
                }else if(data.status == 0){
                    $("#loadingMakeCancel").hide();
                    alert(data.message);
                }

            }
        });
    });
    
    var previousPlanId = '';
    //var planId = '';
    //var amount = '';
    //var planId = '';
    //var planId = $("input:radio[name=planId]:checked").val();
    //var orderAmount = $("input:radio[name=planId]:checked").attr('data-value');
    var orderAmount = 0.00;
    var planType = '';
    var packageData = [];
    $('#discountDiv').hide();

    $('[name=planId]').click(function(){
        
        $('#removeDiscount').trigger('click');
        $('#applyCode').removeClass('d-none');

        var planId = $("input:radio[name=planId]:checked").val();
       
        orderAmount = $("input:radio[name=planId]:checked").attr('data-value');
        var discountType = $('#discountType').val();
        var promoCode = $("#promoCode").val();
        var promoCodeDiscountAmount = $('#promoCodeDiscountAmount').val();
        var packageClass = 'plan_packages_'+planId;
        var packageLabelClass = 'plan_packages_label_'+planId;
        

        if(!$('#plan_'+planId).hasClass('show'))
        {
            $('#accordian_link_'+planId).trigger('click');
        }

        if(planId != previousPlanId)
        { 
            
            packageData = [];

            $('.plan_packages').prop("checked", false);
            $('.plan_packages').attr("disabled", true);
            $('.plan_packages_label').addClass("disabled");

            $('.'+packageClass).prop("disabled", false);
            $('.'+packageLabelClass).removeClass("disabled");

            //$('.plan_packages').prop("checked", false);
            //$('.plan_packages').prop("disabled", true);
            //$('.plan_packages_label').addClass("disabled");

            //$('.'+packageClass).attr("disabled", false);
            //$('.'+packageLabelClass).removeClass("disabled");
        }

        $('#paymentAmount').val(orderAmount);
        $('#viewPaymentPrice').html('$'+orderAmount);
        $('#viewPayableAmount').removeClass('d-none');
        var planEl = $("input:radio[name=planId]:checked");
        planType = planEl.data('subplan-name');

        var planName = planEl.data('package-name')+' ('+planEl.data('subplan-name')+ ' Plan)';
        
        $('#viewSelectedPlanDiv').removeClass('d-none');
        $('#viewSelectedPlan').html(planName);
        
        $('#viewPlanPrice').html('$ '+orderAmount);


        if(checkPackageValid(packageClass, packageLabelClass))
        {
            //loadForm();
        }
        
        previousPlanId = planId;
   	});

    $('.plan_packages').change(function(e){

        var packageClass = $(this).data('packageclass');
        var packageLabelClass = $(this).data('packagelabelclass'); 
        checkPackageValid(packageClass, packageLabelClass);
    });

    function checkPackageValid(packageClass, packageLabelClass)
    {
        packageData = [];
        
        var packageCount =  $("input:radio[name=planId]:checked").data('package-count');
        
        var viewSelectedPackagesHtml = '';
        $('.'+packageClass).each(function (index, obj) {
           
           if($(this).prop("checked") == true) 
           {
               packageData.push($(this).val());

               viewSelectedPackagesHtml += '<h4 class="x-small-title medium" id="">'+$(this).data('package')+' ('+$(this).data('game')+') </h4>'; 
           }
       });

       if(packageData.length == packageCount)
       {
            $('.'+packageClass).each(function (index, obj) {
                if($(this).prop("checked") == false) 
                {
                    $(this).prop("checked", false);
                    $(this).prop("disabled", true);
                    $(this).parent().find("label").addClass("disabled");
                }
           });
       }
       else
       {
            $('.'+packageClass).each(function (index, obj) {
                if($(this).prop("checked") == false) 
                {
                    $(this).prop("disabled", false);
                    $(this).parent().find("label").removeClass("disabled");
                }
                
           });
       }

       console.log(packageData);
       console.log(packageClass);
       console.log(packageLabelClass);

       $('#viewSelectedPackages').html(viewSelectedPackagesHtml)
       return true;

    }
     

 // apply discount code validation
    function promoValidation() {
        if ($('#promoCode').val().length > 0) {
            if ($('#promoCode').val().length > 3) {
            $("#errorpromoCode").css("display", "none");
        }else{
            $("#errorpromoCode").css("display", "block");
            $('#errorpromoCode').html('Discount code length should be min 4 letter.');
        }
        } else {
            $("#errorpromoCode").css("display", "block");
            $('#errorpromoCode').html('Please enter discount code');
        }

        var nullCounter = $(".errorBlock:visible").length;
  
        if (nullCounter === 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // apply discount code 
        $('#applyDiscountButton').on('click', function (e) {
            e.preventDefault();

            var isValidate = promoValidation();

            if (isValidate === true) {
                var promoCode = $("#promoCode").val();
                var planId = $("input:radio[name=planId]:checked").val();
                orderAmount = $("input:radio[name=planId]:checked").attr('data-value');

                    $.ajax({
                        type:'POST',
                        headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
                        url:"{{ route('applyPromocode') }}",
                        data:{promoCode:promoCode, orderAmount:orderAmount, planId:planId, deviceType:'Web'},
                        success:function(data){
                            if(data.status == 1){

                                $('#closePopup').trigger('click');
                                //$("#successpromoCode").css("display", "block");
                                //$('#successpromoCode').html(data.message);

                                $('#typeDiscount').val(data.result.discountType);
                                $('#discountCode').val(data.result.promoCode);
                                var promocodeDiscountHtml = '';
                                var discountAmount = 0.00;
                                var percentage = 0.00;

                                if(data.result.discountType =='Percentage'){
                                    percentage = data.result.percentage;
                                    promocodeDiscountHtml = percentage+'%';
                                   
                                }else if(data.result.discountType =='Flat'){
                                    promocodeDiscountHtml = data.result.promoCodeDiscountAmount+' AUD';
                                }
                                discountAmount = data.result.promoCodeDiscountAmount;
                                
                                $("#appliedpromoCodeDiscountAmount").html(promocodeDiscountHtml);
                                $("#appliedPromoCode").html( data.result.promoCode);
                                $('#appliedPromoCodeAmount').html('- $ '+discountAmount);
                                $('#discountAmount').val(discountAmount);
                                $('#percentageAmount').val(percentage);
                                $('#paymentAmount').val(Math.round(orderAmount - discountAmount, 2));
                                $('#viewPaymentPrice').html('$'+$('#paymentAmount').val());
                                $('#discountDiv').show();
                                $('#discountDiv').removeClass('d-none');

                                //$('#discountType').val(data.result.promoCode);
                                //loadForm();
                                
                            }else if(data.status == 0){
                                $("#errorpromoCode").css("display", "block");
                                $('#errorpromoCode').html(data.message);
                                $('#successpromoCode').hide();
                                $('#discountDiv').hide();
                                $('#discountCode').val('');
                                $('#discountAmount').val('');
                                $('#percentageAmount').val('');
                                $('#paymentAmount').val($("input:radio[name=planId]:checked").attr('data-value'));
                                $('#viewPaymentPrice').html('$'+$('#paymentAmount').val());
                            }

                        }
                    });

            }
        });

        $('#applyCode').on('click', function (e) {
            $("#promoCode").val('');
            $("#errorpromoCode").css("display", "none");
        });
        
    // Remove applied discount code
        $('#removeDiscount').on('click', function (e) {
            e.preventDefault();
            $('#discountDiv').hide();
            $('#successpromoCode').hide();
            $('#discountCode').val('');
            $('#discountAmount').val('');
            $('#percentageAmount').val('');
            $('#paymentAmount').val($("input:radio[name=planId]:checked").attr('data-value'));
            $('#viewPaymentPrice').html('$'+$('#paymentAmount').val());
            //loadForm();
        });

        $('#proceedToPay').on('click', function (e) {

            $("#errorProceedToPay").css("display", "none");

            var planId = $("input:radio[name=planId]:checked").val();

            if (typeof planId === "undefined")
            {
                alert('Please select plan');
                return false;
            }

            packageCount = $("input:radio[name=planId]:checked").data('package-count');

            if(packageData.length != packageCount)
            {
                alert('Please select exact '+packageCount+ ' package for selected plan');
                return false;
            }

            //loadForm();
            proccedToPay();
            
        });

        function proccedToPay()
        {
            var planId = $("input:radio[name=planId]:checked").val();
            var sportPackageId = packageData;
            var discountCode = $('#discountCode').val();
            var cardId = $('#cardToken').val();
            if (cardId.length > 0) {
                return $.ajax({
                    type:'POST',
                    headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
                    url:"{{ route('payPayment') }}",
                    beforeSend: function(){
                        $("#loading").show();
                        $('#proceedToPay').prop('disabled', true);
                        $('#proceedToPay').css('color', 'lightgray');
                        $('#proceedToPay').css('background', '#F5F5F5');
                        $('#proceedToPay').css('cursor', 'none');
                        $('#proceedToPay').css('pointer-events', 'none');
                    },
                    data:{ deviceType:'Web',planId:planId ,cardId :cardId,sportPackageId:sportPackageId,planType:planType,discountCode:discountCode },
                    success:function(data){
                        if(data.status == 1){
                                window.location.href ="{{ route('success') }}";
                        }else{
                            setTimeout(function(){
                                $('#proceedToPay').prop('disabled', true);
                                $('#proceedToPay').css('color', 'lightgray');
                                $('#proceedToPay').css('background', '#F5F5F5');
                                $('#proceedToPay').css('cursor', 'none');
                                $('#proceedToPay').css('pointer-events', 'none');
                                $('#loading').hide();
                                $("#errorProceedToPay").css("display", "block");
                                $('#errorProceedToPay').html(data.message);
                            }, 1000);
                                
                        }
                    }
                });
            }else{
                alert('please select card');
            }
        }

        // function loadForm()
        // {
        //     var data = {
        //        amount:  $('#paymentAmount').val(),
        //        planId:  $("input:radio[name=planId]:checked").val(),
        //        sportPackageIds: packageData,
        //        planType: planType,
        //        discountCode: $('#discountCode').val()
        //     };
        //     return $.ajax({
        //         type:'POST',
        //         headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
        //         url:"{{ route('ajaxFormLoad') }}",
        //         data:data,
        //         success:function(data){
        //             $('#formHtml').html(data);
        //             setTimeout(function(){
        //                 $('.stripe-button-el').trigger('click');
        //              }, 1000);

        //             return true;
        //         }
        //     });
        // }

        $('#discountform').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) { 
                e.preventDefault();
                return false;
            }
        });

$('.plan_packages').attr("disabled", true);
$('.plan_packages_label').addClass("disabled");

getUserCards();

function getUserCards(){
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
            url:"{{ route('getUserCard') }}",
            beforeSend: function(){
                $("#loadingGetcard").show();
            },
            data:{deviceType:'Web'},
            success:function(data){
                
                if(data.status == 1){
                    
                    for(var i=0; i < data.result.cards.length; i++){

                        var cardDefault = data.result.cards[i].cardDefault;
                        if(cardDefault == 1)
                        {
                            $('.cardHolderName').html(data.result.cards[i].cardHolderName);
                            $('.lastFour').html(data.result.cards[i].last4);
                            $('.cardImage').html('<img src="{{ url('') }}'+data.result.cards[i].cardImage+'" >');
                            $('#cardToken').val(data.result.cards[i].cardToken);
            
                        }

                        var infoData='';
                        
                        infoData+='<li data-value="'+data.result.cards[i].cardToken+'" data-cardimage="'+data.result.cards[i].cardImage+'" data-cardholdername="'+data.result.cards[i].cardHolderName+'" data-lastfour="'+data.result.cards[i].last4+'" ><a href="#" title="Select this card">';
                        infoData+='<div class="flex-space-box justify-content-flexstart payment-method-col">';
                        infoData+='<div class="visa-img">';
                        infoData+='<img src="{{ url('') }}'+data.result.cards[i].cardImage+'" />';
                        infoData+='</div>';
                        infoData+='<div class="flex-space-box">';
                        infoData+='<div class="visa-detail">';
                        infoData+='<h4 class="md-title"><b>'+data.result.cards[i].cardHolderName+'</b></h4>';
                        infoData+='<span class="xs-small-title">'+data.result.cards[i].last4+'</span>';
                        infoData+='</div>';
                        infoData+='</div>';
                        infoData+='</div>';
                        infoData+='</a></li>';
                        $('#cardList').append(infoData);
                        $("#loadingGetcard").hide();
                    }
                    
                }else if(data.status == 0){
                    $(".cardListDiv").hide();
                    $("#loadingGetcard").hide();
                    $('#proceedToPay').prop('disabled', true);
                    $('#proceedToPay').css('color', 'lightgray');
                    $('#proceedToPay').css('background', '#F5F5F5');
                    $('#proceedToPay').css('cursor', 'none');
                    $('#proceedToPay').css('pointer-events', 'none');
                    $('.addCardLink').html("<a type='button' href='{{ route('paymentMethod') }}' class='blue-button medium-size-btn'>Add Card</a>"); 
                }

            }
        });
}

$(".cardTokenList").on("click", "a", function(e){
    e.preventDefault();
    var $this = $(this).parent();
    $this.addClass("select").siblings().removeClass("select");
    $("#cardToken").val($this.data("value"));
    $(".cardHolderName").html($this.data("cardholdername"));
    $(".lastFour").html($this.data("lastfour"));
    $('.cardImage').html('<img src="{{ url('') }}'+$this.data("cardimage")+'" >');
})

</script>
@endsection