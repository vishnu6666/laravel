@extends('user.layout.index')

@section('title') Payment History @endsection

@section('css')

@endsection

@section('content')
        <section class="title-box-top">
				<h1 class="page-title"><b>Payment History</b></h1>
 		</section>
			<section class="payment-history no-padding-bottom ">
				<div class="offer-parents">
                    @if(!$historyList->isEmpty())
                    
                        @foreach($historyList as $history)
                            <div class="offer-item">
                                <div class="flex-space-box flex-align-top">
                                <div class="subscp-plan">{{ $history->planType }} Subscription Plan</div>
                                    <img src="{{ url('front/images/checked-round-small-green.svg') }}" > 
                                </div>
                                <h3 class="title"><b>{{ $history->planName }}</b> | {{ $history->packageName }}</h3> 
                                <div class="flex-space-box flex-align-bottom"> 
                                    <h4 class="medium">{{ $history->createdAt }}</h4> 
                                    <span class="page-title"><strong>${{ $history->planAmount }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="offer-item">
                            <div class="flex-space-box flex-align-top">
                                <div class="flex-space-box flex-align-bottom"> 
                                    <h4 class="medium">Data Not Found</h4>
                                </div>
                            </div>
                        </div>
					@endif
				</div>   
			</section>  
@endsection

@section('js')

@endsection