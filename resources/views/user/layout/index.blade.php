<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('admin-assets/images/logo/logo_icon.png')}}">

    <title>@yield('title') | {{ config('app.name') }}</title>
    
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ url('front/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('front/custom.css') }}">
    <!-- <style >
        a { color: #0E8BE8 !important }
    </style> -->
 
</head>



<body class="horizontal-nav skin-default-dark fixed-layout">

	<div class="main-box">
		<div class="design-box">

            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->

            @include('user.common.header')

            @yield('content')
        </div>

        <div class="photo-box">
        <img src="{{ url('front/images/sidebar-panel.jpg') }}">
        </div>

    </div>

       @php
        $refundPolicy = \App\Model\PageContent::where('slug','refund-policy')->first();
        $cancellationPolicy = \App\Model\PageContent::where('slug','cancellation-policy')->first();
        $termsOfUse = \App\Model\PageContent::where('slug','terms-of-use')->first();
        $privacyPolicy = \App\Model\PageContent::where('slug','Privacy-policy')->first();
        @endphp


    	<div class="modal fade" id="termAndConditionModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered modal-lg custom-modal-large">
			<div class="modal-content">

				<div class="model-content-box tabbing-box">
					<ul class="nav nav-tabs tab-header-box">
						<li class="medium active"><a data-toggle="tab" class="active" href="#home">Terms of Use</a></li>
						<li class="medium"><a data-toggle="tab" href="#menu1">Privacy Policy</a></li>
						<li class="medium"><a data-toggle="tab" href="#menu2">Cancellation Policy</a></li>
						<li class="medium"><a data-toggle="tab" href="#menu3">Refund Policy</a></li>
					</ul>
					<div class="tab-content">
						<div id="home" class="tab-pane fade in active show">
                            {!! $termsOfUse->content !!}
						</div>
						<div id="menu1" class="tab-pane fade">
                            {!! $privacyPolicy->content !!}
						</div>
						<div id="menu2" class="tab-pane fade">
                            {!! $cancellationPolicy->content !!}
						</div>
						<div id="menu3" class="tab-pane fade">
                            {!! $refundPolicy->content !!}
						</div>
					</div>
					<div class="flex-space-box bottom-content-box full-width">
                    
						<div class="agreed-box">
							<div class="general-check-box with-no-label">
                              <input type="hidden" name="termConditionId" id="termConditionId" value="{{ Auth::guard('web')->user()->termsAndConditions }}" >

                            <form name="termConditionForm" id="termConditionForm" method="post" action="{{ route('updateTermAndCondition') }}">
								
                               {{ csrf_field() }}
                                <input type="hidden" name="userId" value="{{ Auth::guard('web')->user()->id }}" >
                                <input type="checkbox"  id="termCondition" name="termCondition">
								<label for="termCondition" class="title-small">I accept the <span class="highlight-color">terms of use</span> and <span class="highlight-color">privacy policy</span>.</label>
                            </div>
                            <span class="errorBlock" style="font-size: 18px !important;" id="errorTermAndCondition"><br></span>
						</div>

						<div class="action-btn">
                                <button type="button" class="blue-button submitBtn">Submit</button>
						</div>
                        </form>
					</div>

				</div>
			</div>
		</div>
	</div>


    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  -->
    @yield('js')

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
  

    var termConditionId  = $('#termConditionId').val();

    if(termConditionId == 0)
    {
        $('#termAndConditionModal').modal('show'); 
    }

    $(document).on('click','.submitBtn',function(){
        
        var termValue =  $('#termCondition:checked').val();

        if(!termValue)
        {
            $("#errorTermAndCondition").css("display", "block");
            $('#errorTermAndCondition').html('Please accept the terms of use and privacy policy.');
        }else{
            $('#termConditionForm').submit();
        }
    });



</script>
</body>
</html>