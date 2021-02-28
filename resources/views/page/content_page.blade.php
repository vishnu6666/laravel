<html>
    <head>
       <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('admin-assets/images/logo/logo_icon.png')}}">
    <title>{{ $pageContent->title }} | {{ config('app.name') }}</title>
        <style>
            .logo{
                text-align: center;
                padding-top: 8px;
                background-color: #03a9f3;
                height: 79px;
            }
            .heading{
                text-align: center;
                padding-top: 8px;
            }
            .content{
                padding: 10px 46px 14px 46px;
            }
        </style>
    </head>
    <body >
        <div class="logo">
            <a href="">
                <img src="{{ url('admin-assets/images/logo/full_logo_new.png')}}" heght="100px" width="100px" alt="Best Ride" id="logo" data-height-percentage="100" />
			</a>
        </div>
        <h1 class="heading">{{  $pageContent->title }}</h1>
        <p class="content">{!! $pageContent->content !!}</p>
    </body>
</html>