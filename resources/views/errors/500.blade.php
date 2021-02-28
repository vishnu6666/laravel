
<!DOCTYPE html>
<html lang="en">
<head>
<title>500 Error</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" ></script>
    <style type="text/css">
        body{
          margin-top: 150px;
            background-color: #C4CCD9;
        }
        .error-main{
          background-color: #fff;
          box-shadow: 0px 10px 10px -10px #5D6572;
        }
        .error-main h1{
          font-weight: bold;
          color: #444444;
          font-size: 100px;
          text-shadow: 2px 4px 5px #6E6E6E;
        }
        .error-main h6{
          color: #42494F;
        }
        .error-main p{
          color: #9897A0;
          font-size: 14px; 
        }
    </style>
</head>
<body>
    <div class="container">
      
         <div class="page-wrapper">
        
        <br>
         <div class="row">
                <div class="col-lg-12">
                    <div class="text-center sorder-logo-inner light-logo"> 
                        <img src="{{ url('admin-assets/images/logo/full_logo_new.png') }}" alt="Footer Logo">
                    </div>
                    
                    <div class="text-center col-md-12">
                        <h1>500</h1>
                        <h2>Oops! Internal server error</h2>
                        
                        <a class="btn btn-danger" href="{{ route('AdminDashboard') }}">Go To Home</a>
                    </div>     
                </div>
            </div>
  
    </div>
    </div>
</body>
</html>

