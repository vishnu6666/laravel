@extends('mail.layout.index')
@section('header')

@endsection

@section('content')

  <tr>
    <td align="center" valign="top" width="100%">
      <center>
        <table align="center" cellpadding="0" cellspacing="0" class=" container-for-gmail-android" width="100%">
            <tr>
                <td align="center" valign="top" width="100%" class="content-padding">
                    <center>
                        <table cellspacing="0" cellpadding="0" width="600" class="w320">
                        <br />
                        <tr>
                            <td class="header-lg">
                                <center><span class=""><b>ORDER DETAILS</b></span><br /><br /></center>
                            </td>
                        </tr>
                        <br>
                        <tr>

                            <td>
                                <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse:separate !important;">

                                    <tr>

                                        <td class="mini-block" width="50%" valign="top" style="padding-left: 0px">
                                            Order Number #{{ $order->id }}<br>
                                            Order Date : {{ date('d-m-Y', strtotime($order->createdAt)) }}

                                        </td>
                                        <td class="mini-block" width="50%">
                                            <span class=""><b>Customer Details</b></span><br />
                                            Name : {{ $order->name }} <br />
                                            Email : {{ $order->email ?? '-'}}  <br />
                                            Mobile : {{ $order->mobileNumber ?? '-'}}  <br />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </table>
                    </center>
                </td>
            </tr>
    </table>

<br>
@if(isset($order) && !empty($order))
<center>
<table cellpadding="0" cellspacing="0" width="600" border="0" style="border:1px solid #ccc; border-bottom: 0;" >
    <tr style="background-color:#F1F1F1;">
        <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: bold; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; ">Product</td>
         <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: bold; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; ">Type</td>
        <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: bold; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;">Qty</td>
        <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: bold; border-right:1px solid #ccc; border-bottom: 1px solid #ccc;">Price</td>
        <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: bold; border-bottom: 1px solid #ccc;">Total</td>
    </tr>

	<tr>

  <tr>
    <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: normal; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;">{{ $order['planName'] }} </td>
    <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: normal; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;">{{ $order['planType'] }} </td>
    <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: normal; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;"> 1 </td>
    <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: normal; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;">{{ $order['planAmount'] }}</td>
    <td  valign="top" align="left" style="padding: 10px; font-size: 14px; line-height: 16px; color: #000; font-weight: normal; border-bottom: 1px solid #ccc;">{{ (float)($order['planAmount']) }} </td>
  </tr>

</table>        
</center>          
        
      @endif
<br>
<table align="center" cellpadding="0" cellspacing="0" class=" container-for-gmail-android" width="100%">
    <tr>
        <td align="center" valign="top" width="100%">
            <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">

            <tr>
                <td class="w320">
                    <table width="100%" style="">
                        <tr>
                            <td class="item-col item">
                            </td>
                            <td class="item-col quantity" style="text-align:left; padding-right: 10px; ">
                                <span class="total-space">Sub &nbsp;Total</span>
                            </td>
                            <td class="item-col price" style="text-align: left;">
                                <span class="total-space">{{ !empty($order->planAmount) ? (float)($order->planAmount) : '' }} </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="item-col item">
                            </td>
                            <td class="item-col quantity" style="text-align:left; padding-right: 10px; border-top: 1px solid #cccccc;">
                                <span class="total-space">Discount </span>
                            </td>
                            <td class="item-col price" style="text-align: left; border-top: 1px solid #cccccc;">
                                <span class="total-space">{{$order->discountAmount > 0 ? (float)($order->discountAmount) : '0.00' }} </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="item-col item">
                            </td>
                            <td class="item-col quantity" style="text-align:left; padding-right: 10px; border-top: 1px solid #cccccc;">
                                <span class="total-space">Grand &nbsp;Total</span>
                            </td>
                            <td class="item-col price" style="text-align: left; border-top: 1px solid #cccccc;">
                                <span class="total-space">{{$order->amount > 0 ? (float)($order->amount) : '0.00' }}</span>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
            <td class="w320">
            <br>
              <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td class="mini-container-left">
                    <table cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td class="mini-block-padding">
                          <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse:separate !important;">
                            <tr>
                              <td class="mini-block">
                                <span class="header-sm">Billing Address</span><br />
                                Name : {{ $order->name }}<br />
                                Email : {{ !empty($order->email) ? $order->email ."," : '' }}<br>
                                Phone : {{ !empty($order->mobileNumber) ? $order->mobileNumber ."," : '' }}
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td class="mini-container-right">
                    <table cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td class="mini-block-padding">
                          <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse:separate !important;">
                            <tr>
                              <td class="mini-block">
                                <span class="header-sm">Payment Method</span><br />
                                @if($order->paymentType == 'free')
                                Payment Type : Free
                                @elseif($order->paymentType == 'credit_card')
                                Payment Type : Credit Card
                                @endif
                                <br>Payment Status : {{$order->paymentStatus}} <br>
                                </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>
      </center>
    </td>
  </tr>
@endsection



