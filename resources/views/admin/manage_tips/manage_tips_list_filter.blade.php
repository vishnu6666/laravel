@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endsection
@section('title')
Manage tips list
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
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
                    <h4 class="text-themecolor">Manage tips list</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Manage tips list</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            @include('admin.common.flash')
            
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="form-group col-md-2">
                        <h5>Start Date <span class="text-danger"></span></h5>
                            <div class="controls">
                                <input type="date" name="start_date" id="start_date" class="form-control datepicker-autoclose" placeholder="Please select start date"> 
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                        <h5>End Date <span class="text-danger"></span></h5>
                            <div class="controls">
                                <input type="date" name="end_date" id="end_date" class="form-control datepicker-autoclose" placeholder="Please select end date"> 
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                        <h5>Package <span class="text-danger"></span></h5>
                            <div class="controls">
                                <select name="filter_package" id="filter_package" class="form-control" required>
                                    <option value="">Select packages</option>
                                    @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->packageName }}</option>
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                        <h5>Game <span class="text-danger"></span></h5>
                            <div class="controls">
                                <select name="filter_game" id="filter_game" class="form-control" required>
                                    <option value="">Select game</option>
                                    @foreach($games as $game)

                                    <option value="{{ $game->id }}">{{ $game->gameName }}</option>

                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="form-group col-md-3" align="center" style="margin-top: 27px;">
                            <div class="controls">
                                <button type="text" id="btnFiterSubmitSearch" class="btn btn-info">Filter</button>
                                &nbsp;
                                <button type="button" name="reset" id="reset" class="btn btn-danger">Reset</button>
                                &nbsp;
                                <a class="btn btn-info" href="{{ route('updateresult') }}">Result Update</a>
                                 {{-- <a class="btn btn-info" href="{{ route('updateresult') }}">Result Update</a> --}}
                                <div class="help-block"></div>
                            </div>
                        </div>

                      

                    </div>
                    <br>

                    <div class="card">
                        <div class="card-body">

                               <a href="{{ route('manage-tips.create')}}" type="button" style="float: right" class="btn btn-info d-none d-lg-block col-md-2">
                                        <i class="fa fa-plus-circle"></i> Create New
                                    </a>
                            <div class="table-responsive m-t-40">

                               

                                <table id="tips" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Game name</th>
                                            <th>Package name</th>
                                            <th>Tips</th>
                                            <th>Odds</th>
                                            <th>Units</th>
                                            {{-- <th>Track</th> --}}
                                            <th>Date</th>
                                            <th>Create date</th>
                                            <th>Win</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
    </div>
@include('admin.common.forms')
@endsection

@section('js')
<script src="{{ url('admin-assets/node_modules/datatables/datatables.min.js') }}"></script>

<script>
 $(document).ready( function () {
    fill_datatable();

    function fill_datatable(filter_game = '', filter_package = '' ,start_date = '' , end_date = '')
    {
         dataTable = $('#tips').DataTable({
        	processing: true,
            serverSide: true,
            "ajax" : {
                "url" : '{{ url( route("manage-tips.search") ) }}',
                "type" : 'post',
                'async' : false,
                'data' : function(d)
                {
                    d.filter_game = filter_game,
                    d.filter_package = filter_package,
                    d.start_date = start_date,
                    d.end_date = end_date
                }
            },
            columns: [
                { data: 'sr_no' ,name:'sr_no', orderable: false },
                { data: 'gameName', name: 'gameName'},
                { data: 'packageName', name: 'packageName'},
                { data: 'tips', name: 'tips'},
                { data: 'odds', name: 'odds'},
                { data: 'units', name: 'units'},
                { data: 'date', name: 'date'},
                { data: 'createdAt', name: 'createdAt'},
                { data: 'IsWin', name: 'IsWin' },
                { data: 'action', name: 'action', orderable: false },
            ],
            "aaSorting": [[8,'desc']],
         });
    }

    $('#btnFiterSubmitSearch').click(function(){
        var filter_game = $('#filter_game').val();
        var filter_package = $('#filter_package').val();
        
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        $('#tips').DataTable().destroy();
        fill_datatable(filter_game, filter_package ,start_date , end_date);
    });

    $('#reset').click(function(){
        $('#filter_game').val('');
        $('#filter_package').val('');
        $('#start_date').val('');
        $('#end_date').val('');
        $('#tips').DataTable().destroy();
        fill_datatable();
    });


     $('#filter_package').on('change',function (e) {
                    var packageId = $(this).val();
                        if(packageId)
                        {
                                $.ajax({
                                        url:  url+"/getPackageGame",
                                        method: "POST",
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        data: {
                                            'packageId': packageId
                                        },
                                        success: function (result)
                                        {
                                        if(result.status == 1)
                                        {
                                            $('#filter_game').html(result.html);
                                        }
                                        }
                                    });
                        }
                    });



});

</script>

@endsection
