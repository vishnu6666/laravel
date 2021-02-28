@extends('mail.layout.index')
@section('header')
<tr>
    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
            <tr>
                <td class="w3l-4h user_name" height="84px" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:74px !important;">Hello {{ ucfirst($groupData['name'])  }},</td>
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
                                <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">
                                You have received a promo code "<b>{{ $groupData['promoCode'] }}</b>" which you can use on your next purchase of the <b>{{ $groupData['planName'] }}</b> subscription plan.
                                <br><br>
                                Once applied, you will get <b>{{ $groupData['discountAmount'] }}</b> discount on the subscription plan. 
                                <br><br>
                                This code will expire on 
                                <b>{{ $groupData['endDate'] }}</b> – please apply the code before this date.</td>
                            </tr>
                            <tr>
                                <td height="25" style="font-size: 1px;">&nbsp;</td>
                            </tr>
    
                            <tr>
                                <td height="15" style="font-size: 1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">Thanks for using <span style="color:#8a0000">Vttips Tips.</span> Stay tuned !
                                </td>
                            </tr>
                            <!-- <tr><td class="w3l-p2 scale-center-both" style="padding:10px 0 0; font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:center;">Stay tuned !</td></tr> -->
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection