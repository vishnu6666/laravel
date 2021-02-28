<html>
<head>
    <title>Game Report</title>
</head>
<body>
<table border="1" id="customers" style="font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-collapse:collapse; margin: 0 50px 0 0;">
    <tbody><tr>
    <th colspan="2" style="color:white;text-align:center;background-color: #03a9f3;padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;"><img height="50px" width="100px" src="{{ url('admin-assets/images/logo/full_logo_new.png')}}" >
     </th>
     <th colspan="2" style="text-align:center;background-color: #03a9f3;padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
        <h3><strong>Game Report </strong></h3> 
         Date of Report : {{$reportInfo['rangeDate']}}
    </th>
    </tr>
    <tr>
    <th  style="width:10px; color: white; background-color: #03a9f3;padding: 12px 8px; text-align: left;"> No.</th>
    <th style=" width:150px; color: white; background-color: #03a9f3;padding: 12px 8px; text-align: left; ">Name</th>
    <th style="width:50px; color: white; background-color: #03a9f3;padding: 12px 8px; text-align: left;">Full Name</th>
    <th style="width:150px; color: white; background-color: #03a9f3;padding: 12px 8px; text-align: left;">Launch Date</th>
    </tr>
    @foreach($gameReport as  $key => $value)

    @php
    @endphp
    <tr>
        @php $key++; @endphp
        <td  style="padding:8px; width:10px;" >{{ $key  ?? '' }}</td>
        <td  style="padding:8px; width:150px;" >{{ $value['gameName']  ?? '' }}</td>
        <td  style="padding:8px; width:50px;" >{{ $value['gameFullName'] ?? ''}}</td>
        <td  style="padding:8px; width:150px;" >{{ $value['launchDate'] ?? ''}}</td>
    </tr>
    @endforeach
   
    </tbody></table>
</body>
</html>