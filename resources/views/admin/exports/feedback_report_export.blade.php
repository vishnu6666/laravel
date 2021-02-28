<html>
<head>
    <title>Complaints and feedbacks Report</title>
</head>
<body>
<table border="1" id="customers" style="text-align:center;font-family:Trebuchet MS,Arial,Helvetica,sans-serif;width:100%;border-collapse:collapse; margin: 0 50px 0 0;">
    <tbody>
        <tr>
    <th colspan="2" style="text-align:center;color:white;background-color: #03a9f3;padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;"><img height="50px" width="100px" src="{{ url('admin-assets/images/logo/full_logo_new.png')}}" >
     </th>
     <th colspan="3" style="background-color: #03a9f3;padding:12px 8px;border:1px solid #DDDDDD;border-radius: 0; border-right: 0px;">
        <h3><strong>Complaints and feedbacks Report </strong></h3> 
         Date of Report : {{$reportInfo['rangeDate']}}
    </th>
    </tr>
    <tr>
    <th  style="width:10px; color: white; background-color: #03a9f3;padding:  8px; text-align: left;"> No.</th>
    <th style=" width:100px; color: white; background-color: #03a9f3;padding:  8px; text-align: left; ">User Name</th>
    <th style="width:100px; color: white; background-color: #03a9f3;padding:  8px; text-align: left;">Subject</th>
    <th style="width:150px; color: white; background-color: #03a9f3;padding:  8px; text-align: left;">Message</th>
    <th style="width:100px; color: white; background-color: #03a9f3;padding:  8px; text-align: left;">Reply Message</th>
    </tr>
    @foreach($contactUsReports as  $key => $user)
    <tr>
        @php $key++; @endphp
        <td  style="padding:8px; width:10px;" >{{ $key  ?? '' }}</td>
        <td  style="padding:8px; width:100px;" >{{ $user['name']  ?? '' }}</td>
        <td  style="padding:8px; width:100px;" >{{ $user['subject'] ?? ''}}</td>
        <td  style="padding:8px; width:150px;" >{{ $user['message'] ?? ''}}</td>
         <td  style="padding:8px; width:100px;">{{ $user['reply_message'] ?? '' }}</td>
        
       
    </tr>
    @endforeach
   
    </tbody></table>
</body>
</html>