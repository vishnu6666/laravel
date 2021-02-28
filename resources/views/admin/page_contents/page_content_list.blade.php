@extends('admin.layout.index')

@section('title') Page content list @endsection
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
                    <h4 class="text-themecolor"> Page content list </h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item active"> Page content list </li>
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
                    <div class="card"  style="min-height:90vh;">
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="page_contents" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th>Name</th>
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
            table = $('#page_contents').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url":'{{ url(route("page_contents.search"))  }}',
                    "type": "POST",
                    "async": false,
                },
                columns: [
                    { data: 'sr_no' ,name:'sr_no', orderable:false },
                    { data: 'title', name: 'title' },
                    { data: 'action', name: 'action', orderable:false},
                   
                ],
                "aaSorting": [[1,'asc']],
            });
        });
    </script>
@endsection