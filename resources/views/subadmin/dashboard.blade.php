@extends('subadmin.layout.index')

@section('title') Dashboard @endsection
@section('css')

@endsection
@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Dashboard </h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                          
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Info box -->
            <!-- ============================================================== -->

            @include('subadmin.common.flash')
            <div class="card-group">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3 class="text-cyan"><i class="fas fa-users"></i></h3>
                                                <p class="text-muted text-uppercase">No. of Tips</p>
                                            </div>
                                            <div class="ml-auto">
                                                 <h2 class="counter text-cyan"> 50 </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-cyan" role="progressbar" style="width:100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{route('packages.index')}}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3 class="text-success"><i class="fas fa-tablet"></i></h3>
                                            <p class="text-muted text-uppercase">No. of packages</p>
                                        </div>
                                        <div class="ml-auto">
                                        <h2 class="counter text-success">  {{ $package ?? '0' }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width:100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{route('games.index')}}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3 class="text-info"><i class="far fa-object-group"></i></h3>
                                            <p class="text-muted text-uppercase">No. of games</p>
                                        </div>
                                        <div class="ml-auto">
                                        <h2 class="counter text-info">  {{ $game ?? '0' }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width:100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{route('inquiry.index')}}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3 class="text-primary"><i class="far fa-question-circle"></i></h3>
                                            <p class="text-muted text-uppercase">No. of inquiry</p>
                                        </div>
                                        <div class="ml-auto">
                                        <h2 class="counter text-primary">  {{ $inquiry ?? '0' }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width:100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div> --}}
            </div>
         
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
    </div>
@endsection
@section('js')
<!-- Chart JS -->
<script src="{{ url('admin-assets/node_modules/morrisjs/morris.min.js') }}"></script>
<script> 
</script>
@endsection