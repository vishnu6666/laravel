<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="baseUrl" content="{{ url('/') }}">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('admin-assets/images/logo/logo_icon.png')}}">
    <title>@yield('title') | User {{ config('app.name') }}</title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="{{ url('admin-assets/node_modules/morrisjs/morris.css') }}" rel="stylesheet">
    <!-- Jquery custom  css for error messages -->
    {{-- <link href="{{ url('admin-assets/css/custom.css') }}" rel="stylesheet"> --}}
    <!--Toaster Popup message CSS -->
    <link href="{{ url('admin-assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ url('admin-assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ url('admin-assets/css/custom.css') }}" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="{{ url('admin-assets/css/pages/dashboard1.css') }}" rel="stylesheet">
    <link href="{{ url('admin-assets/node_modules/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    
    <!--This CSS is used for multiple select box-->
    <link href="{{ url('admin-assets/node_modules/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ url('admin-assets/node_modules/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{ url('admin-assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link href="{{ url('admin-assets/node_modules/multiselect/css/multi-select.css')}}" rel="stylesheet">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
     <!--[if lt IE 9]>
    <script src="{{ url('admin-assets/js/html5shiv.js') }}"></script>
    <script src="{{ url('admin-assets/js/respond.min.js') }}"></script>
    <![endif]-->    
<style>
    .attribute_div,.attr_combination_div {
        border:solid gray;
    }
</style>

@yield('css')