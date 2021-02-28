@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endsection
@section('title')
Manage subscriptions list
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
                    <h4 class="text-themecolor">Manage subscriptions list</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Manage subscriptions list</li>
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
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="faqs" class="display  table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User name</th>
                                             <th>Plan name</th>
                                            <th>Plan type</th>
                                            <th>Plan amount</th>
                                            <th>Paid amount</th>
                                            {{-- <th>Validity</th> --}}
                                            <th>Expiry date</th>
                                            <th>purchased date</th>
                                            <th>Status</th>
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
        table = $('#faqs').DataTable({
          
        	processing: true,
            serverSide: true,
            "ajax": {
                "url":'{{ url(route("manage-transaction.search"))  }}',
                "type": "POST",
                "async": false,
            },
            columns: [
                { data: 'sr_no' ,name:'sr_no', orderable: false },
             	
                { data: 'name', name: 'name'},
                { data: 'planName', name: 'planName'},
                { data: 'planType', name: 'planType'},
                { data: 'planAmount', name: 'planAmount'},
                { data: 'amount', name: 'amount'},
                // { data: 'subscriptionValidity', name: 'subscriptionValidity' },
                { data: 'subscriptionExpiryDate', name: 'subscriptionExpiryDate'},
                { data: 'createdAt', name: 'createdAt' },
                { data: 'isTrial', name: 'isTrial' },
                { data: 'action', name: 'action', orderable: false },
            ],
            "aaSorting": [[8,'desc']],
         });
    });
</script>
@endsection
