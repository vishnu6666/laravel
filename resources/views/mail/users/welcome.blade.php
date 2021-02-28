@extends('mail.layout.index')
@section('header')
{{-- <tr>
    <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
            <tr>
                <td class="w3l-4h user_name" height="84px" style="color: #000;">Hi , {{ $userDetail->name  }}</td>
            </tr>
        </table>
    </td>
</tr> --}}
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
                                <td class="esd-block-button shop_btn" align="left" style="padding:15px 0;">
                                    <span class="es-button-border" style="border-radius: 20px; border-style: solid; border-width: 0px;">
                                        Dear {{ $userDetail->name ?? ''  }},
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">
                                Thank you for registering with <span style="color:#9ac5d8">Vttips.</span> <br>Your email address({{ $userDetail->email}}) linked with <span style="color:#9ac5d8">Vttips</span> successfully.</td>
                            </tr>
                            <tr>
                                <td class="agile-main scale-center-both" style="font-weight: 400; color: #000; font-size: 15px; text-align:left; height:34px !important;">
                                This is your login credentials for App login.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <table width="540" border="1" cellspacing="0" cellpadding="0" align="center" class="agile1 scale" >
                                    <tr>
                                        <td> Email</td>
                                        <td> {{ $userDetail->email ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td> Password </td>
                                        <td>{{ $userDetail->showPassword ?? '' }} </td>
                                    </tr>
                                </table>
                                <td>
                            </tr>
                            <tr>
                                <td height="25" style="font-size: 1px;">&nbsp;</td>
                            </tr>
                            
                            <tr>
                                <td height="25" style="font-size: 1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">
                                </td>
                            </tr>
                            <tr>
                                <td height="15" style="font-size: 1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="w3l-p2 scale-center-both" style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">Thanks for using <span style="color:#9ac5d8">Vttips.</span> Stay tuned !
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