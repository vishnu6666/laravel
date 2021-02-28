@extends('user.layout.index')

@section('title') Payment Methods @endsection

@section('css')
    <style>
        .errorCardBlock {color: #FF0000; font-weight: normal; display: none; padding-top: 1px; padding-bottom: 10px; font-size: 20px;}
    </style>
@endsection

@section('content')
            <section class="title-box-top">
				<h1 class="page-title"><b>Payment Methods</b></h1>
				<h2 class="x-small-title"><strong>You may add or remove any cards from which you would like to make the payments.</strong></h2>
			 </section>  
			<section class="payment-method-inner no-padding"  >
 					<div class="md-main-box">
					  <!-- <div class="conn-action-btn gray-bg-box flex-space-box align-items-baseline">
						<div class="flex-space-box full-width">
							<div>Add New Payment Method</div>
							<a class="btn-box" >
								<img src="{{ url('front/images/plush-sign.svg') }}">    
							</a>
						</div>
                      </div> -->

                      <div class="conn-action-btn gray-bg-box flex-space-box align-items-baseline">
						<div class="flex-space-box full-width">
							<div>Add New Payment Method</div>
							<button class="no-border" data-toggle="modal" data-target="#addNewPay"> <img src="{{ url('front/images/plush-sign.svg') }}"> </button> 
						</div>
					  </div>
                      
                      <span id="CardList"> </span>
                      
                      <center><img id="loading" heght="50px" width="50px" src="{{ url('front/images/loader.gif') }}"></center>

					</div> 
            </section>
            <!-- remove card model start -->
            <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered modal-md remove-pay-model">
                    <div class="modal-content pad-30">
                        <div class="flex-space-box">
                            <h2 class="title"><b>Remove Card</b></h2>
                            <button type="button" id="closeremoveCardPopup" class="close" data-dismiss="modal" aria-label="Close">
                                <img src="{{ url('front/images/cross-button.png') }}">
                            </button>
                        </div>
                        <div class="model-content-box"> 
                            <div class="form-box">
                                    <div class="input-box">
                                        <p>Are you sure you want to remove this card?</p>
                                    </div>
                                    <input type="hidden" id="cardTokenValue" value="">
                                    <button class="border-btn  text-center" data-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button id="removeCard" class="blue-button text-center">Remove</button>
                                    <img id="loadingremoveCard" class="text-center" heght="50px" width="50px" src="{{ url('front/images/loader.gif') }}">
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- remove card model end -->

             <!-- Make Default card model start -->
             <div class="modal fade" id="makeDefaultCardlargeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered modal-md remove-pay-model">
                    <div class="modal-content pad-30">
                        <div class="flex-space-box">
                            <h2 class="title"><b>Make Default Card</b></h2>
                            <button type="button" id="closemakeDefaultCardPopup" class="close" data-dismiss="modal" aria-label="Close">
                                <img src="{{ url('front/images/cross-button.png') }}">
                            </button>
                        </div>
                        <div class="model-content-box"> 
                            <div class="form-box">
                                    <div class="input-box">
                                        <p>Are you sure you want to create default this card?</p>
                                    </div>
                                    <input type="hidden" id="cardTokenDefaultValue" value="">
                                    <button class="border-btn  text-center" data-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button id="makeDefaultCard" class="blue-button text-center">Yes</button>
                                    <img id="loadingMakeDefault" class="text-center" heght="50px" width="50px" src="{{ url('front/images/loader.gif') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Make Default  card model end -->
        <!-- Add card model start -->
        <div class="modal fade" id="addNewPay" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog  modal-dialog-centered modal-lg add-new-payment-model">
			<div class="modal-content pad-30">
				<div class="flex-space-box pad-b-40">
					<h2 class="title"><b>Add New Payment Method</b></h2>
					<button type="button" id="closePopup" class="close" data-dismiss="modal" aria-label="Close">
						<img src="{{ url('front/images/cross-button.png') }}">
					</button>
                </div>
				<div class="model-content-box "> 
					<div class="form-box">
						<div class="form no-padding">
							<div class="row">
							  	<div class="col-sm-6 col-md-6 general-input-group">
									<label>Card Number</label>
									<input class="input full-width" onkeyup="formatCreditCard(this.value)" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="0000-0000-0000-0000" id="cardNumber" value="" formControlName="cardNumber"  required="" type="text" mask="0000-0000-0000-0000" maxlength="19"   />
                                    <span class="errorBlock" id="errorcardNumber"><br></span>
                                </div>
							  	<div class="col-sm-6 col-md-6 general-input-group">
									<label>Expiry MM/YY</label>
									<input class="input full-width" id="expiryDate" placeholder="MM/YY" formControlName="expiryDate" value="" required="" type="text" mask="M0/00" maxlength="5" onkeyup="if (/[^\d/]/g.test(this.value)) this.value = this.value.replace(/[^\d/]/g,'')"/>
                                    <span class="errorBlock" id="errorexpiryDate"><br></span>  
                                </div> 
							  	<div class="col-sm-6 col-md-6 general-input-group  ">
									<label>CVV</label>
								  	<input class="input cvv-input input-x-small" id="cvv" placeholder="CVV" formControlName="cvv" value="" maxlength="4" mask="XXX" type="text" onkeyup="if (/[^\d/]/g.test(this.value)) this.value = this.value.replace(/[^\d/]/g,'')" required/>
                                      <span>On the back of the card</span>
                                      <span class="errorBlock" id="errorcvv"><br></span>
							  	</div>
							  	<div class="col-sm-6 col-md-6 general-input-group">
									<label>Card Holder's Name</label>
									<input class="input full-width" id="cardholderName" placeholder="Card Holder's Name" value="" formControlName="cardHolderName" required="" type="text" maxlength="50" required /> 
                                    <span class="errorBlock" id="errorcardholderName"><br></span>
                                </div>
								  <div class="action-btn full-width flex-space-box justify-content-flexend">
                                  <span class="errorCardBlock" id="errorAddCard"><br></span>
                                  <center><img id="loadingaddCard" heght="50px" width="50px" src="{{ url('front/images/loader.gif') }}"></center>
									  <button type="button" class="blue-button text-center" id="addCard">Add</button>
								  </div>
							</div>
						  </div>
					</div>
				</div>
			</div>
		</div>
    </div>
    <!-- Add card model end -->


@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script type="text/javascript">

$( document ).ready(function() {
    $("#expiryDate").keypress(function(e){
       if(e.keyCode != 8){ 
        var text = this.value;
        if(text.length ==2){
           this.value=text+'/'; 
         }
        }
     });
});

$('#loadingremoveCard').hide();
$('#loadingaddCard').hide();
$('#loadingMakeDefault').hide();
$('#cardTokenValue').val('');
$('#cardTokenDefaultValue').val('');

getUserCards();
function getUserCards(){
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
            url:"{{ route('getUserCard') }}",
            beforeSend: function(){
                $("#loading").show();
            },
            data:{deviceType:'Web'},
            success:function(data){
                
                if(data.status == 1){
                    
                    for(var i=0; i < data.result.cards.length; i++){
                        var isDefault = '';
                        var disable ='';
                        var cardDefault = data.result.cards[i].cardDefault;
                        if(cardDefault == 1)
                        {
                            isDefault = 'checked';
                            disable = 'disabled';
                        }
                        var infoData='';
                        infoData+='<div class="mg-b-30 payment-row-gorup flex-space-box "  >';
                        infoData+='<div class="flex-space-box">';
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
                        infoData+='<div class="action-group-btn flex-space-box">';
                        infoData+='<button  class="btn-black border-btn" id="'+data.result.cards[i].cardToken+'" onClick="removeCardToken(this.id)"> Remove </button>'; 
                        infoData+='<div class="general-check-box round">';
                        infoData+='<input type="checkbox" '+isDefault+' ' +disable+' id="defaultCard_'+data.result.cards[i].cardToken+'"  value="'+data.result.cards[i].cardToken+'" onChange="makeDefaultCardToken(this.value)">';
                        infoData+='<label for="defaultCard_'+data.result.cards[i].cardToken+'"> </sub></label>';
                        infoData+='</div>';
                        infoData+='</div>';
                        infoData+='</div>';
                        $('#CardList').append(infoData);
                        $("#loading").hide();
                    }
                    
                }else if(data.status == 0){
                    var info='';
                    info +='<div class="conn-action-btn gray-bg-box flex-space-box align-items-baseline">';
                    info +='<div class="flex-space-box full-width">';
                    info +='<div>'+data.message+'</div>';
                    info +='</div>';
                    info +='</div>';
                    $('#CardList').append(info);
                    $("#loading").hide();
                }

            }
        });
}


    function removeCardToken(cardToken) {
        $('#cardTokenValue').val('');
        $('#cardTokenValue').val(cardToken);
        $("#removeCard").show();
        $('#largeModal').modal('show');
        $('#loadingremoveCard').hide();
    } 

    $('#removeCard').click(function () {
        var cardId = $('#cardTokenValue').val(); 
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
            url:"{{ route('deleteUserCard') }}",
            beforeSend: function(){
                $("#removeCard").hide();
                $("#loadingremoveCard").show();
            },
            data:{ deviceType:'Web',cardId:cardId },
            success:function(data){
                
                if(data.status == 1){
                    $('#CardList').empty();
                    $('#closeremoveCardPopup').trigger('click');
                    getUserCards();

                }else if(data.status == 0){
                    $('#CardList').empty();
                    $('#closeremoveCardPopup').trigger('click');
                    var info='';
                    info +='<div class="conn-action-btn gray-bg-box flex-space-box align-items-baseline">';
                    info +='<div class="flex-space-box full-width">';
                    info +='<div>'+data.message+'</div>';
                    info +='</div>';
                    info +='</div>';
                    $('#CardList').append(info);
                    $("#loadingremoveCard").hide();
                }

            }
        });

    });

    $(document).ready(function(){
            $("#makeDefaultCardlargeModal").on('hide.bs.modal', function(){
                var cardTokenDefaultId = $('#cardTokenDefaultValue').val();
                $('#defaultCard_'+cardTokenDefaultId).prop('checked', false);
        });
    });

    function makeDefaultCardToken(defaultcardToken) {
        $('#cardTokenDefaultValue').val('');
        $('#cardTokenDefaultValue').val(defaultcardToken);
        $("#makeDefaultCard").show();
        $('#loadingMakeDefault').hide();
        $('#makeDefaultCardlargeModal').modal('show');
    }

    $('#makeDefaultCard').click(function () {
        var defaultCardcardId = $('#cardTokenDefaultValue').val(); 
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
            url:"{{ route('setDefaultUserCard') }}",
            beforeSend: function(){
                $("#loadingMakeDefault").show();
                $("#makeDefaultCard").hide();
            },
            data:{ deviceType:'Web',cardId:defaultCardcardId },
            success:function(data){
                
                if(data.status == 1){
                    $('#CardList').empty();
                    $('#closemakeDefaultCardPopup').trigger('click');
                    $('#CardList').empty();
                    getUserCards();

                }else if(data.status == 0){
                    $('#closemakeDefaultCardPopup').trigger('click');
                    var info='';
                    info +='<div class="conn-action-btn gray-bg-box flex-space-box align-items-baseline">';
                    info +='<div class="flex-space-box full-width">';
                    info +='<div>'+data.message+'</div>';
                    info +='</div>';
                    info +='</div>';
                    $('#CardList').append(info);
                    $("#loadingMakeDefault").hide();
                }

            }
        });

    });

    function formatCreditCard(cardvalue) {
        // if(cardvalue.length >= 6){
        //     cardType = creditCardTypeFromNumber(cardvalue);
        //     alert(cardType);
        // }
        var x = document.getElementById("cardNumber");
        var index = x.value.lastIndexOf('-');
        var test = x.value.substr(index + 1);
        if (test.length === 4){
            if(index < 14){
                x.value = x.value + '-';
            }
        }
    }

    // function creditCardTypeFromNumber(num) {

    // num = num.replace(/[^\d]/g,'');

    // if (num.match(/^5[1-5]\d{14}$/)) {
    //     return 'MasterCard';
    // } else if (num.match(/^4\d{15}/) || num.match(/^4\d{12}/)) {
    //     return 'Visa';
    // } else if (num.match(/^3[47]\d{13}/)) {
    //     return 'AmEx';
    // } else if (num.match(/^6011\d{12}/)) {
    //     return 'Discover';
    // }
    // return 'UNKNOWN';
    // }

    $(document).ready(function(){
            $("#addNewPay").on('hide.bs.modal', function(){
                $('#cardNumber').val('');
                $('#cardholderName').val('');
                $('#expiryDate').val('');
                $('#cvv').val('');
                $("#loadingaddCard").hide();
        });
    });

    // Add Card validation
    function cardAddValidation() {
        if ($('#cardNumber').val().length > 0) {
            if ($('#cardNumber').val().replace(/-/g, "").length >= 15) {
            $("#errorcardNumber").css("display", "none");
        }else{
            $("#errorcardNumber").css("display", "block");
            $('#errorcardNumber').html('Please enter valid card number.');
        }
        } else {
            $("#errorcardNumber").css("display", "block");
            $('#errorcardNumber').html('Please enter card number');
        }

        if ($('#cardholderName').val().length > 0) {
            if ($('#cardholderName').val().length > 2) {
            $("#errorcardholderName").css("display", "none");
        }else{
            $("#errorcardholderName").css("display", "block");
            $('#errorcardholderName').html('Card holder name length should be more then 2 letter.');
        }
        } else {
            $("#errorcardholderName").css("display", "block");
            $('#errorcardholderName').html('Please enter card holder name');
        }

        if ($('#expiryDate').val().length > 0) {
            var currVal = $('#expiryDate').val().split("/");
            var dtMonth= currVal[0],dtYear = currVal[1];

            var currentDate = new Date();
            var cur_time = currentDate.getTime();

            var expire_date =  currVal[0]+"/30/"+currVal[1]; 
            var expire_dateObject = new Date(expire_date);
            var expiry_time = expire_dateObject.getTime();
            
            if (dtMonth < 1 || dtMonth > 12) {
                $("#errorexpiryDate").css("display", "block");
                $('#errorexpiryDate').html('Please enter valid MM/YY');
            } else if (expiry_time < cur_time)
            {
                $("#errorexpiryDate").css("display", "block");
                $('#errorexpiryDate').html('Please enter valid MM/YY');
            } else{
                $("#errorexpiryDate").css("display", "none");
            }

        } else {
            $("#errorexpiryDate").css("display", "block");
            $('#errorexpiryDate').html('Please enter MM/YY');
        }

        if ($('#cvv').val().length > 0) {
            if ($('#cvv').val().length == 3 || $('#cvv').val().length == 4) {
            $("#errorcvv").css("display", "none");
        }else{
            $("#errorcvv").css("display", "block");
            $('#errorcvv').html('Cvv length should be 3 to 4 letter.');
        }
        } else {
            $("#errorcvv").css("display", "block");
            $('#errorcvv').html('Please enter cvv');
        }
        var nullCounter = $(".errorBlock:visible").length;
  
        if (nullCounter === 0) {
            return true;
        } else {
            return false;
        }
    }

    $('#addCard').click(function () {
        //$('#loadingaddCard').hide();
        var carddata = $('#cardNumber').val();
        let cardNumber = carddata.replace(/-/g, "");
        var cardholderName = $('#cardholderName').val();
        var expiryDate = $('#expiryDate').val();
        var cvv = $('#cvv').val();
        var cardType = 'Web';

        var isValidate = cardAddValidation();
        if (isValidate === true) {
            $.ajax({
                type:'POST',
                headers: {'X-CSRF-TOKEN': $('[name=_token]').val() },
                url:"{{ route('createUserCard') }}",
                beforeSend: function(){
                    $("#loadingaddCard").show();
                },
                data:{ deviceType:'Web',cardNumber:cardNumber,cardholderName:cardholderName,expiryDate:expiryDate,cvv:cvv,cardType:cardType },
                success:function(data){
                    
                    if(data.status == 1){
                        $('#CardList').empty();
                        $('#closePopup').trigger('click');

                        getUserCards();

                    }else if(data.status == 0){
                        $("#errorAddCard").css("display", "block");
                        $('#errorAddCard').html(data.message);
                        $("#loadingaddCard").hide();
                    }

                }
            });
        }

    });
    </script>
@endsection