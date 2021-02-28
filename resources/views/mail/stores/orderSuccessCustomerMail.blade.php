@extends('mail.layout.index')
@section('header')
<tr>
    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
        </table>
    </td>
</tr>

@endsection

@section('content')
  <tr>
    <td align="center" valign="top" width="100%" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Thank you for your order
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Order Number #{{ $order->id }}
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Order Date : {{ date('d-m-Y', strtotime($order->createdAt)) }}
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%">
      <center>
        <table cellpadding="0" cellspacing="0" width="600" class="w320">
            <tr>
              <td class="item-table">
                <table cellspacing="0" cellpadding="0" width="100%">
                  <tr class="title_bg">
                    <td class="title-dark product_title" style="width: 130px;">
                       Product
                    </td>
                    <td class="title-dark qty_title" width="200px" style="text-align: right;">
                      Qty
                    </td>
                    <td class="title-dark total_title" width="100px" align="left" >
                      Total
                    </td>
                  </tr>
{{-- @if(isset($order_products) && !empty($order_products))
@foreach($order_products as $product)
                  <tr>
                    <td class="item-col item">
                      <table cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                          <td class="mobile-hide-img">
                            <img width="80" src="{{ url('doc/product_image/'.(isset($product->image_url)?$product->image_url:'no-images.jpeg')) }}" alt="{{ $product->product_title }}">
                          </td>
                          <td class="product">
                            <span>{{ $product->product_title }} {{ !empty($product->combination_title) ? ' ('.$product->combination_title.') ' : '' }}</span> <br />
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td class="item-col quantity">
                     {{ $product->quantity }}
                    </td>
                    <td class="item-col price">
                      {{ (float)($product->sub_total) }} 
                    </td>
                  </tr>
                  @endforeach
@endif --}}
                  

                  <tr>
                    <td class="item-col item mobile-row-padding"></td>
                    <td class="item-col quantity"></td>
                    <td class="item-col price"></td>
                  </tr>


                  <tr>
                    <td class="item-col item">
                    </td>
                    <td class="item-col quantity" style="text-align:left; padding-right: 10px; border-top: 1px solid #cccccc;">
                      <span class="total-space" style="">Sub&nbsp;Total</span>
                    </td>
                    <td class="item-col price" style="text-align: left; border-top: 1px solid #cccccc;">
                      <span class="total-space">{{ (float)($order->subTotal) }} </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="item-col item">
                    </td>
                    <td class="item-col quantity" style="text-align:left; padding-right: 10px; border-top: 1px solid #cccccc;">
                      <span class="total-space">Shipping &nbsp;Total</span>
                    </td>
                    <td class="item-col price" style="text-align: left; border-top: 1px solid #cccccc;">
                      <span class="total-space">{{ ($order->shippingTotal == 0) ? 'FREE' : (float)($order->shippingTotal).' ' }} </span>
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="item-col item">
                    </td>
                    <td class="item-col quantity" style="text-align:left; padding-right: 10px; border-top: 1px solid #cccccc;">
                      <span class="total-space">Discount</span>
                    </td>
                    <td class="item-col price" style="text-align: left; border-top: 1px solid #cccccc;">
                      <span class="total-space">{{ (float)($order->discountTotal) }} </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="item-col item">
                    </td>
                    <td class="item-col quantity" style="text-align:left; padding-right: 10px; border-top: 1px solid #cccccc;">
                      <span class="total-space">Grand &nbsp;Total</span>
                    </td>
                    <td class="item-col price" style="text-align: left; border-top: 1px solid #cccccc;">
                      <span class="total-space">{{ (float)($order->grandTotal) }} </span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
            {{-- <td class="w320">
              <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td class="mini-container-left">
                    <table cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td class="mini-block-padding">
                          <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse:separate !important;">
                            <tr>
                              <td class="mini-block">
                                <span class="header-sm">Shipping Address</span><br />
                                {{ $shipping_address->first_name ." ". $shipping_address->last_name }}<br />
                                {{ $shipping_address->country .",". $shipping_address->city }}  <br />
                                {{ !empty($shipping_address->block)?$shipping_address->block .",":'' }} {{ !empty($shipping_address->street)?$shipping_address->street .",":'' }} {{ !empty($shipping_address->avenue)?$shipping_address->avenue .",":'' }} {{  !empty($shipping_address->building)?$shipping_address->building:'' }} <br />
                                {{ !empty($shipping_address->floor)?$shipping_address->floor .",":'' }} {{ !empty($shipping_address->apartment)?$shipping_address->apartment:'' }}  <br />
                                {{ $shipping_address->mobile_no }} <span>(mobile)</span>  <br />
                                {{ $shipping_address->landline_no }} @if(!empty($shipping_address->landline_no)) <span>(landline)</span> @endif <br />
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
                                @if($order->payment_type == 'Cash on Delivery')
                                Cash
                                @elseif($order->payment_type == 'Visa')
                                Credit Card
                                @else
                                {{$order->payment_type}}
                                @endif
                                
                                </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td> --}}
          </tr>
        </table>
      </center>
    </td>
  </tr>
@endsection