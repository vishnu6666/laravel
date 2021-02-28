@extends('admin.layout.index')
@section('css')
    <link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endsection
@section('title') Promocodes @endsection

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
                    <h4 class="text-themecolor">Promocodes</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Promocodes</li>
                        </ol>
                        <a href="{{ route('promocode.create')}}" class="btn btn-info d-none d-lg-block m-l-15">
                            <i class="fa fa-plus-circle"></i> Create New</a>
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

                            <h4 class="card-title">Promocodes</h4>
                            <div class="table-responsive m-t-40">
                                <table id="promoCode" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <th>Sr.No.</th>
                                    <th>Promo code</th>
                                    <th>Min amount</th>
                                    <th>Discount</th>
                                    <th width="10%">Date range</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
        $(document).ready(function () {

            var table = "";
            table = $('#promoCode').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url":'{{ url(route("promocode.search"))  }}',
                    "type": "POST",
                    "async": false,
                },
                columns: [
                    { data: 'sr_no' ,name:'sr_no', orderable:false },
                    { data: 'promoCode', name: 'promoCode' },
                    { data: 'minTotalAmount', name: 'minTotalAmount' },
                    { data: 'discountAmount', name: 'discountAmount' },
                    { data: 'rangeDate', name: 'startDate' },
                    { data: 'isActive', name: 'isActive' },
                    { data: 'action', name: 'action', orderable: false },
                ],

                "aaSorting": [[4,'asc']],

            });
        });
    </script>
@endsection