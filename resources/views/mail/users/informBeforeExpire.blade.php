@extends('mail.layout.index')
@section('header')
<tr>
    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
            <tr>
                <td class="w3l-4h user_name" height="84px" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:74px !important;">Hello {{ ucfirst($subscriptionHistoryData->name)  }},</td>
            </tr>
        </table>
    </td>
</tr>
@endsection

@section('content')
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td bgcolor="#f3f3f3">
            <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
                <tr>
                    <td bgcolor="#FFFFFF">
                        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="agile1 scale">
                            <tr>
                                <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">Thank you for using Vttips Tips - we hope you have enjoyed our platform so far.</td>
                            </tr>
                            
                            <tr>
                                <td height="25" style="font-size: 1px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">
                                Your
                                @if($subscriptionHistoryData->isTrial == 1)
                                    <b>{{ $subscriptionHistoryData->subscriptionValidity }} days free trial</b>
                                @elseif($subscriptionHistoryData->isTrial == 0)
                                    <b>{{ $subscriptionHistoryData->planName }} subscription plan </b>
                                @endif
                                   will expire tomorrow. You can gain immediate and ongoing access to your favourite sports tips by joining a subscription plan here.</td>
                            </tr>

                            <tr>
                                <td height="25" style="font-size: 1px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="esd-block-button shop_btn" align="center" style="padding:15px 0;"> 
                                    <span class="es-button-border" style="border-radius: 20px; border-style: solid; border-width: 0px;"> 
                                        <a href="{{ $link }}" class="es-button" target="_blank" style="padding:5px 20px; border-radius: 20px; font-weight: 400;font-weight: normal; font-size: 18px; border-width: 10px 35px; text-decoration: none; background: #9ac5d8; color: rgb(255, 255, 255);">
                                            Subscription Plans
                                        </a> 
                                    </span> 
                                </td>
                            </tr>

                            <tr>
                                <td height="15" style="font-size: 1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">Thanks for using <span style="color:#8a0000">Vttips Tips.</span> Stay tuned !
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection