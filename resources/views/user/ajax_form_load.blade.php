@php 
  $configData = config('constant.stripeDetails');
  $publishableKey = $configData['publishable_key'];
@endphp

<form action="{{ route('payPayment') }}" method="post">
  @csrf
  @if(!empty($requestData['amount']))
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="{{ $publishableKey }}"
          data-name="Vttips"
          data-description="Vttips"
          data-image="{{ url('front/images/vpt70.png') }}" 
          data-email="{{ \Auth::guard('web')->user()->email }}" 
          data-amount="{{ $requestData['amount'] * 100}}"
          data-locale="auto">
      </script>
 @else
 <input type="hidden" name="planId" value="{{$requestData['planId']}}">
 <input type="hidden" name="stripeToken" value="vttips">
 <button type="submit" class="stripe-button-el"><span style="display: block; min-height: 30px;">Pay with Card</span></button>
 @endif

 <input type="hidden" name="planId" value="{{$requestData['planId']}}">
  @if(!empty($requestData['sportPackageIds']))
      @foreach($requestData['sportPackageIds'] as $packageId)
        <input type="hidden" name="sportPackageId[]" value="{{$packageId}}">
      @endforeach
  @endif

  <input type="hidden" name="planType" value="{{ $requestData['planType']}}">
  <input type="hidden" name="discountCode" value="{{ $requestData['discountCode']}}">
  <input type="hidden" name="deviceType" value="Web">

</form>