<html>
<head>
    <title>Game Tips Report</title>
</head>
<body>
<table border="1" id="customers" style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-collapse:collapse; margin: 0 50px 0 0;">
    <tbody><tr>
    <th colspan="2" style="color:white;text-align:center;background-color: #03a0e7;padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
        <img height="50px" width="100px" src="{{ url('admin-assets/images/logo/full_logo_new.png')}}" >
     </th>
     <th colspan="6" style="text-align:center;background-color: #03a9f3;padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
        <h3><strong>Game Tips Report </strong></h3> 
         Date of Report : {{$reportInfo['rangeDate']}}
    </th>
    </tr>
    <tr>
    <th style="width:25px; color: white; background-color: #03a9f3;padding:10px; text-align: left;"> No.</th>
    <th style="width:25px; color: white; background-color: #03a9f3;padding:10px; text-align: left; ">Package Name</th>
    <th style="width:25px; color: white; background-color: #03a9f3;padding:10px; text-align: left;">Game Name</th>
    <th style="width:25px; color: white; background-color: #03a9f3;padding:10px; text-align: left;">Tip Name</th>
    <th style="width:25px; color: white; background-color: #03a9f3;padding:10px; text-align: left;">Odds</th>
    <th style="width:25px; color: white; background-color: #03a9f3;padding:10px; text-align: left;">Units</th>
    <th style="width:25px; color: white; background-color: #03a9f3;padding:10px; text-align: left;">Win Loss</th>
    <th style="width:25px; color: white; background-color: #03a9f3;padding:10px; text-align: left;">Text</th>
    {{-- <th style="width:10px; color: white; background-color: #03a9f3;padding:10px; text-align: left;">Win Loss</th>
    <th style="width:30px; color: white; background-color: #03a9f3;padding:10px; text-align: left;">Text</th> --}}
   
</tr>

 @foreach($gameTripReports as  $key => $value)
    @php
    @endphp
    <tr>
        @php $key++; @endphp
        <td  style="padding:10px; width:25px;text-align:left;word-break: break-all;" >{{ $key  ?? '' }}</td>
        <td  style="padding:10px; width:25px;text-align:left;word-break: break-all;" >{{ $value['packageName']  ?? '' }}</td>
        <td  style="padding:10px; width:25px;text-align:left;word-break: break-all;" >{{ $value['gameName'] ?? ''}}</td>
        <td  style="padding:10px; width:25px;text-align:left;word-break: break-all;" >{{ $value['tips'] ?? ''}}</td>
        <td  style="padding:10px; width:25px;text-align:left;word-break: break-all;" >{{ $value['odds'] ?? '' }}</td>
        <td  style="padding:10px; width:25px;text-align:left;word-break: break-all;" >{{ $value['units'] ?? '' }}</td>
        <td  style="padding:10px; width:25px;text-align:left;word-break: break-all;" >{{ $value['IsWin'] ?? '' }}</td>
        <td  style="padding:10px; width:25px;text-align:left;word-break: break-all;" >{{ $value['text'] ?? '' }}</td>
         {{-- <td  style="padding:4px; width:10px;text-align:left;word-break: break-all;" >{{ $value['IsWin'] ?? '' }}</td>
        <td  style="padding:4px; width:10px;text-align:left;word-break: break-all;" >{{ $value['text'] ?? '' }}</td> --}}
    </tr>
    @endforeach

   
    </tbody></table>
</body>
</html>


 {{-- <th  style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;"> No.</th>
    <th style=" width:15px; color: white; background-color: #03a9f3;padding:2px; text-align: left; ">Package Name</th>
    <th style="width: 15px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Game Name</th>
    <th style="width:15px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Tip Name</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Odds</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Units</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Result</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Profit Los For Tips</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Profit Los For Day</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Daily Pot</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Profit Loss Cumulative</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Pot</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Profit One Unit</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Profit Two Unit</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Profit Three Unit</th>
    <th style="width:10px; color: white; background-color: #03a9f3;padding:2px; text-align: left;">Text</th> --}}


     {{-- <td  style="padding:2px; width:10px;text-align: left;word-break: break-all;" >{{ $key  ?? '' }}</td>
        <td  style="padding:2px; width:15px;text-align:left;word-break: break-all;" >{{ $value['packageName']  ?? '' }}</td>
        <td  style="padding:2px; width:15px;text-align:left;word-break: break-all;" >{{ $value['gameName'] ?? ''}}</td>
        <td  style="padding:2px; width:15px;text-align:left;word-break: break-all;" >{{ $value['tips'] ?? ''}}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['odds'] ?? '' }}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['units'] ?? '' }}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['profitLosForTips']  ?? '' }}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['profitLosForDay'] ?? ''}}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['dailyPot'] ?? ''}}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['profitLossCumulative'] ?? '' }}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['pot'] ?? '' }}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['profitOneUnit'] ?? '' }}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['profitTwoUnit'] ?? '' }}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['profitThreeUnit'] ?? '' }}</td>
        <td  style="padding:2px; width:10px;text-align:left;word-break: break-all;" >{{ $value['text'] ?? '' }}</td> --}}