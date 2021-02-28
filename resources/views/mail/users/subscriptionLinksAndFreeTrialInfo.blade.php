@extends('mail.layout.index')
@section('header')
<tr>
    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
            <tr>
                <td class="w3l-4h user_name" height="84px" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:74px !important;">Hello {{ ucfirst($userDetail->name)  }},</td>
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
                                <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">Thank you for signing up with Vttips Tips.</td>
                            </tr>
                            <tr>
                                <td height="25" style="font-size: 1px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">Your {{ $subscriptionHistoryData->subscriptionValidity }} days free trial period starts now. Your first live tip will be landing in your app very soon.</td>
                            </tr>

                            <tr>
                                <td height="25" style="font-size: 1px;">&nbsp;</td>
                            </tr>
                            
                            @if(!empty($userDetail->socialId))
                                    <tr>
                                        <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">Your login credentials are,</td>
                                    </tr>

                                    <tr>
                                        <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">Email: <span style="font-weight: 400; color: blue; font-size: 15px; text-align:left; height:34px !important;">{{ $userDetail->email }} </span></td>
                                    </tr>

                                    <tr>
                                        <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">Password: {{ $userDetail->showPassword }} </td>
                                    </tr>

                                    <tr>
                                        <td height="25" style="font-size: 1px;">&nbsp;</td>
                                    </tr>
                            @endif
                            
                            

                            {{-- <tr>
                                <td class="esd-block-button shop_btn" align="center" style="padding:15px 0;"> 
                                    <span class="es-button-border" style="border-radius: 20px; border-style: solid; border-width: 0px;"> 
                                        <a href="{{ $link }}" class="es-button" target="_blank" style="padding:5px 20px; border-radius: 20px; font-weight: 400;font-weight: normal; font-size: 18px; border-width: 10px 35px; text-decoration: none; background: #9ac5d8; color: rgb(255, 255, 255);">
                                            Subscription Plans
                                        </a> 
                                    </span> 
                                </td>
                            </tr> --}}

                            <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">Thanks for using <span style="color:#8a0000">Vttips Tips.</span> We’ll talk soon!
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