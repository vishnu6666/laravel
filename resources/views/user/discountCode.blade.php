@extends('user.layout.index')

@section('title') Discount Code @endsection

@section('css')

@endsection

@section('content')
    <section class="title-box-top">
        <h1 class="page-title" style="padding-bottom: 7px !important;"><b>Discounts</b></h1>
        <h2 class="x-small-title"><strong>You may choose any from the available discounts.</strong></h2>
    </section>
    <section class="refer-discount no-padding-bottom">
        <div class="gray-bg-box">Refer and Earn Discount</div>
        <div class="offer-parents">
        @if($referralcodeList)
            @foreach($referralcodeList as $key => $referralcode)
                <div class="offer-item">
                    <div class="flex-space-box flex-align-top">
                        <div class="left-portion-box">
                            <h2 class="page-title"><b>{{ $referralcode->title }}</b></h2>
                            <h3 class="title-small"><strong>{{ $referralcode->description }}</strong></h3>
                        </div>
                        @if($referralcode->isUnlock == true)
                            <div class="tag-box">
                                <div class="offer-tag orange-tag"><strong id="{{ $referralcode->id}}_referralcode">{{ $referralcode->referCode }}</strong></div>
                            </div>
                        @elseif($referralcode->isUnlock == false)
                            <div class="tag-box">
								<div class="offer-tag gray-tag"><strong>XXXXXXX</strong></div>
						    </div>
                        @endif
                        
                    </div>
                    <div class="flex-space-box flex-align-top">
                        <div class="left-portion-box">
                            <h4 class="md-title offer-bottom-line medium">{{ $referralcode->status }}</h4>
                        </div>
                        @if($referralcode->isUnlock == true)
                            <div class="tag-box">
                                <div class="copy-tag commClass" data-id ="#{{ $referralcode->id}}_referralcode" copy-id="{{ $referralcode->id}}_referralcode"><strong class="{{ $referralcode->id}}_referralcode copyDiscoundCode">Copy</strong></div>
                            </div>
                        @elseif($referralcode->isUnlock == false)
                            <div class="tag-box">
								<div class="copy-tag gray-tag"><strong>Copy</strong></div>
							</div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="offer-item">
                <div class="flex-space-box flex-align-top">
                    <div class="left-portion-box">
                        <h2 class="page-title"><b>Referral code not found.</b></h2>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </section>
    @if($promocodeList)
    <section class="more-discount no-padding-top">
        <div class="gray-bg-box">More Discounts</div>
        <div class="offer-parents">
            <div class="offer-item">
                <div class="flex-space-box flex-align-top">
                    <div class="left-portion-box">
                        <h2 class="page-title"><b>{{ $promocodeList->title ?? '-' }}</b></h2>
                        <h4 class="md-title offer-bottom-line medium">{{ $promocodeList->expireStatus ?? '-' }}</h4>
                    </div>
                    <div class="tag-box">
                        <div class="offer-tag orange-tag " ><strong id="{{ $promocodeList->id}}_promocode">{{ $promocodeList->promoCode ?? '-' }}</strong></div>
                    </div>
                </div>
                <div class="flex-space-box flex-align-top">
                    <div class="left-portion-box">
                        <h3 class="title-small"><strong>{{ $promocodeList->description ?? '-'}}</strong></h3>
                    </div>
                    <div class="tag-box">
                        <div class="copy-tag commClass"  data-id ="#{{ $promocodeList->id}}_promocode" copy-id="{{ $promocodeList->id}}_promocode" ><strong class="{{ $promocodeList->id}}_promocode copyDiscoundCode">Copy</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
@endsection

@section('js')

<script>
    $('.commClass').click(function(){
        $('.copyDiscoundCode').html('Copy');
        var element = $(this).attr('data-id');
        var id = $(this).attr('copy-id');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $('.'+id).html('Copied');
        $temp.remove();
    });
</script>

@endsection