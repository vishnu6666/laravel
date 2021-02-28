@extends('admin.layout.index')
@section('title') Message List   @endsection
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
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
                    <h4 class="text-themecolor">{{ $package->packageName }} Message</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                 <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                            
                            <a href="{{ route('messages.index') }}">Message</a>
                            </li>

                            <li class="breadcrumb-item active">
                            
                            {{ $package->packageName }} Message
                            </li>
                        </ol>
                        <a href="{{ route('show.message.create',['package' => $package->id])}}" type="button" class="btn btn-info d-none d-lg-block m-l-15">
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                       
                        <div class="card-body">
                            <h4 class="card-title">{{ $package->packageName }} Message </h4>
                            <div class="table-responsive m-t-40">
                                <table id="message" class="display  table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <th width="5%">Sr.No.</th>
                                        {{-- <th>Username</th> --}}
                                        <th width="80%">Message</th>
                                        <th width="15%">Date &amp; Time</th>
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
        table = $('#message').DataTable({
        	processing: true,
            serverSide: true,
            "ajax": {
                "url":'{{ url(route("show.message.search"))  }}',
                "type": "POST",
                "async": false,
                'data' : function(d)
                {
                    d.packageId = {{ $package->id}}
                },
            },
            columns: [
                { data: 'sr_no' ,name:'sr_no', orderable:false, "width": "5%" },
                // { data: 'name', name: 'name'},
                { data: 'content', name: 'content',"width": "75%"},
                { data: 'createdAt', name: 'createdAt',"width": "20%"},
            ],
            "aaSorting": [[2,'desc']],
         });
    });
</script>
@endsection
