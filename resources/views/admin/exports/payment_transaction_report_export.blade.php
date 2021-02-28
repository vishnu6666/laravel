<html>
<head>
    <title>Payment Tranaction Report</title>
</head>
<body>
<table border="1" id="customers" style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-collapse:collapse; margin: 0 50px 0 0;">
    <tbody><tr>
    <th colspan="2" style="color:white;text-align:center;background-color: #03a9f3;padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;"><img height="50px" width="100px" src="{{ url('admin-assets/images/logo/full_logo_new.png')}}" >
     </th>
     <th colspan="4" style="text-align:center;background-color: #03a9f3;padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
        <h3><strong>Payment Tranaction Report </strong></h3> 
         Date of Report : {{$reportInfo['rangeDate']}}
    </th>
    </tr>
    <tr>
    <th  style="width:10px; color: white; background-color: #03a9f3;padding:  8px; text-align: left;"> No.</th>
    <th style=" width:150px; color: white; background-color: #03a9f3;padding:  8px; text-align: left; ">User Name</th>
    <th style="width:150px; color: white; background-color: #03a9f3;padding:   8px; text-align: left;">Plan Name</th>
    <th style="width:150px; color: white; background-color: #03a9f3;padding:  8px; text-align: left;">Plan Type</th>
    <th style="width:100px; color: white; background-color: #03a9f3;padding:  8px; text-align: left;">Paid Amount</th>
    <th style="width:150px; color: white; background-color: #03a9f3;padding:  8px; text-align: left;">Expiry Date</th>
    </tr>
    @foreach($paymentTransactionReports as  $key => $user)
    @php
    @endphp
    <tr>
        @php $key++; @endphp
        <td  style="padding:8px; width:10px;" >{{ $key  ?? '' }}</td>
        <td  style="padding:8px; width:150px;" >{{ $user['name']  ?? '' }}</td>
        <td  style="padding:8px; width:150px;" >{{ $user['planName'] ?? ''}}</td>
        <td  style="padding:8px; width:150px;" >{{ $user['planType'] ?? ''}}</td>
        <td  style="padding:8px; width:100px;" >{{ $user['amount'] ?? '' }}</td>
        <td  style="padding:8px; width:150px;" >{{ $user['subscriptionExpiryDate'] ?? '' }}</td>
    </tr>
    @endforeach
    </tbody></table>
</body>
</html>