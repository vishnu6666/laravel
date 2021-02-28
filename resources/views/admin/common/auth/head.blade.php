<!-- ============================================================== -->
<!-- login head file -->
<!-- ============================================================== -->

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- Favicon icon -->
<link rel="icon" type="image/png" sizes="16x16" href="{{ url('admin-assets/images/logo/logo_icon.png') }}">
<title>@yield('title') | Admin {{ config('app.name') }}</title>
<!-- page css -->
<link href="{{ url('admin-assets/css/pages/login-register-lock.css') }}" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{ url('admin-assets/css/style.min.css') }}" rel="stylesheet">
<!-- Jquery custom  css for error messages -->
<link href="{{ url('admin-assets/css/custom.css') }}" rel="stylesheet">

@yield('css')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->