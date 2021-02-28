@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endsection
@section('title')
Game List
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
                    <h4 class="text-themecolor">Games</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Games</li>
                        </ol>
                        <a href="{{ route('games.create')}}" type="button" class="btn btn-info d-none d-lg-block m-l-15">
                            <i class="fa fa-plus-circle"></i> Create New
                        </a>
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
            {{-- <div class="alert alert-success">
            This is Sample File For User Import <a href="{{ url('admin-assets/sample_user_list_import .xls') }}" download> Click Here </a>                
            </div> --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- <a href="{{ route('userImportView')}}" type="button" class="btn btn-info d-none d-lg-block m-l-15 col-md-2">
                                Import Users
                            </a> --}}
                            <div class="table-responsive m-t-40">
                                <table id="games" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Game name</th>
                                            <th>Game full name</th>
                                            <th>Status</th>
                                            <th>Create date</th>
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
    var table;
    $(function() {
        table = $('#games').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url":'{{ url(route("games.search"))  }}',
                "type": "POST",
                "async": false,
            },
            columns: [
                { data: 'sr_no' ,name:'sr_no', orderable:false },
            	{ data: 'gameName', name: 'gameName'},
            	{ data: 'gameFullName', name: 'gameFullName'},
                { data: 'isActive', name: 'isActive' },
                { data: 'createDate', name: 'createDate' },
                { data: 'action', name: 'action', orderable: false },
            ],
            "aaSorting": [[4,'desc']],
         });
    });

</script>
@endsection
