<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name=”x-apple-disable-message-reformatting”>
  <title>Vttips Invoice</title>

  <style type="text/css">
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
     * {
        font-family: 'Helvetica Neue', 'Arial', 'sans-serif' !important;
      }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
      background: #ffffff;
    }

    td {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 13px;
      color: #000000;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

     [class*="pull-left"] {
      text-align: left;
    }

    [class*="pull-right"] {
      text-align: right;
    }
    [class*="product span"] {
    margin-left: 10px;
    float:left;
    }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      line-height: normal;
      padding: 0px 0 0;
      color: #1c85c7;
    }
    .mini-block .header-sm {
      font-weight: 700;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
      color: #000000;
    }

    .content-padding {
/*       padding: 20px 0 5px; */
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .mobile-header-padding-left img {
      display: block;
      width: 150px;
      margin: 0 auto;
    }

    .free-text {
      width: 100% !important;
      padding: 10px 20px 0px;
      /*font-weight: bold;*/
    }

    .button {
      padding: 30px 0;
    }


    .mini-block {
      background-color: #ffffff;
      padding: 12px 0px 15px;
      text-align: left;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
      vertical-align: top;
    }

    .product {
      text-align: left;
      vertical-align: middle;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
      font-size: 13px;
    }
    .table-store-header {
        padding-bottom: 8px;
        display: inline-block;
        font-size: 14px;
        font-weight: bold; color: #4d4d4d;
    }

    .item-table {
      padding: 10px 20px;
      width: 560px;
    }
    .item-table tr.title_bg {
      background: #c3c3c3;
      float: left;
      /*width: 100%;*/
      border-radius: 10px;
    }
    .item-table tbody {
        float: left;
    }

    td[class~="item"] {
        width: 300px !important;
     }

    td[class~="mobile-hide-img"] {
      display: block;
      height: auto;
      text-align: left;
      width: 90px;
      padding-left: 10px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 10px;
      text-align: center;
      vertical-align: middle;
      font-weight: bold;
      color: #000000;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

    .product_title {
        float:left;
        /*width:330px;*/
        padding: 10px;
    }
    .quantity {
        width:100px;
        padding: 10px 5px;
      font-size: 11px;
      text-align: left !important;
    }
    .price {
        width: 77px;
        padding: 10px 5px 10px 0px;
      font-size: 11px;
      text-align: left !important;
    }
    .red {
        color: #f00;
        font-weight: bold;
    }
    .qty_title {
      float:left;
      text-align: center;
      padding: 10px;
    }

    .total_title {
      float:left;
      text-align: left;
      padding: 10px;
        margin-left: 14px;
    }
    .header-lg {
      margin-top: 20px; float: left; width: 100%; text-align: center;
    }
    .free-text {
      color: #000000; text-align: left; font-size: 18px;
    }
    .product span {
      color: #4d4d4d; font-weight:normal; font-size: 13px; margin-left: 10px; float: left;
    }
    .total-space {
      font-weight: bold; color: #4d4d4d;
    }
 
      @media only screen and (min-width: 767px)  {
     
      .total_title {
            width: 100px !important;
       }
    }
 
    /* Mobile styles */
    @media only screen and  (max-width: 480px) and (min-width: 401px)  {

    [class*="product"] span {
    float:left;
    }
      .product_title {
      width: 180px !important;
      }
      .qty_title {
        width: 52px;
      }
      .total_title {
        width: 80px;
      }
      .price {
        text-align: left !important;
      }

       table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
        max-width: 600px !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 100% !important;
      }


      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 0px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: block !important;
        width: 30px !important;
        line-height: 0 !important;
      }

      .item {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        /*width: 50px !important;*/
      }

      td[class~="price"] {
        width: 125px;
      }

      td[class="item-table"] {
        padding: 0px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;

    }
  }
 
    /* Mobile styles 2 */
    @media only screen and (max-width: 374px) and (min-width: 310px)  {

        [class*="title-dark"] {
          color: #ffffff;
        }

      [class*="product"] span {
    margin-left: 0px !important;
    }
     [class*="free-text"] {
        font-size: 18px !important;
      }
      .product {
        padding-left: 10px;
      }

      .mobile-header-padding-left { 
        text-align: center;
      }

      .header-lg, .header-md, .header-sm {
        padding-top: 10px;
      }

       .product_title {
      float:left;
      padding: 8px;
      width: 130px !important;
      }
       [class*="qty_title"] {
        width: 50px !important;
      }
      [class*="total_title"] {
        width: 70px !important;
      }

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
        max-width: 600px !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 100% !important;
      }


      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 0px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        /*display: none !important;*/
        display: block !important;
        width: 30px !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        /*width: 50px !important;*/
      }
      td[class*="price"] {
        width: 90px;
      }

      td[class*="item-table"] {
        padding: 0px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }
    }
 
    /* Mobile styles 3 */
    @media (max-width: 413px) and (min-width: 375px)  {

      [class*="product"] span {
    margin-left: 0px !important;
    }
     [class*="free-text"] {
        font-size: 18px !important;
      }
      .product {
        padding-left: 10px;
      }

      .mobile-header-padding-left { 
        text-align: center;
      }

      .header-lg, .header-md, .header-sm {
        padding-top: 10px;
      }

       .product_title {
      float:left;
      padding: 8px;
      width: 155px;
      }
       [class*="qty_title"] {
        width: 70px;
      }
      [class*="total_title"] {
        width: 80px;
      }

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
        max-width: 600px !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 100% !important;
      }


      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 0px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: block !important;
        width: 30px !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        /*width: 50px !important;*/
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 0px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }
    }
    .page-break {
        page-break-inside: avoid;
        /*page-break-after: always;*/
    }
    .headingTag {
        color: #000000;
        text-align: left;
        font-size: 20px;
        width: 100%;
    }
  </style>

</head>

<body>

<div class="page-break">

<table align="center" cellpadding="0" cellspacing="0" class=" container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
        <table cellspacing="0" cellpadding="0" width="100%">
          <tr>
            <td width="100%" height="40" valign="top" style="text-align: center; vertical-align:middle; background:#1c85c7;">
            <!--[if gte mso 9]>
            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:80px; v-text-anchor:middle;">
              <v:fill type="tile" src="logo_top.png" color="#ffffff" />
              <v:textbox inset="0,0,0,0">
            <![endif]-->
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle; text-align: center;">
                      <a href=""><img width="150" src="{{ url('admin-assets/images/logo/full_logo1.png') }}" alt="logo" title="logo"></a>
                    </td>
                  </tr>
                </table>
              </center>
              <!--[if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
 <tr>
    <td align="center" valign="top" width="100%" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
             YOUR ORDER
            </td>
          </tr>

          <tr>

            <td>
                <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse:separate !important;">

                    <tr>

                        <td class="mini-block" width="50%" valign="top" style="padding-left: 23px">
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

    {{--<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="container-for-gmail-android">--}}
        {{--<tr>--}}
            {{--<td class="w3-gr w3l-p2 scale-center-both">--}}
                {{--<a href="{{env('GOOGLE_PLAYSTORE_URL')}}" target="_blank"><img src="{{url('assets/frontend/images/google_pay_img.png')}}" class="google_play" alt="" title=""></a>--}}
                {{--<a href="{{env('IPHONE_APPSTORE_URL')}}" target="_blank"><img src="{{url('assets/frontend/images/apps_store_img.png')}}" class="itune" alt="" title=""></a>--}}
            {{--</td>--}}
        {{--</tr>--}}
    {{--</table>--}}

</div>



</body>
</html>
