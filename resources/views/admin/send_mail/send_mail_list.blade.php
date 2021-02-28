@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endsection
@section('title')
@if(!empty($emailId))
Player Messages History
@else
Send Mail
@endif

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
                    <h4 class="text-themecolor">@if(!empty($emailId)) Messages History @else Send Mail @endif</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            @if(!empty($emailId))
                            <li class="breadcrumb-item">
                                <a href="{{ route('users.index')}}">Players</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('users.show', ['user' => $userId]) }}"> Player Information</a>
                            </li>
                            @endif
                            
                            <li class="breadcrumb-item active">@if(!empty($emailId)) Messages History @else Send Mail @endif</li>
                        </ol>
<!--                         <a href="{{ route('send-mail.create')}}" type="button" class="btn btn-info d-none d-lg-block m-l-15">
                            <i class="fa fa-plus-circle"></i>Compose Mail</a> -->
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
                                <table id="sendMail" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="display:none">#</th>
                                            <th>#</th>
                                            <th>Email</th>
                                            <th>Subject</th>
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
        table = $('#sendMail').DataTable({
            
        	processing: true,
            serverSide: true,
            "ajax": {
                "url":'{{ url(route("send-mail.search"))  }}',
                "type": "POST",
                "async": false,
                "data":function(d){
                    d.emailId = '{{ !empty($emailId) ? $emailId : ''}}';
                },
            },
            columns: [
                 {data : 'id', name : 'id', visible:false},
                { data: 'sr_no' ,name:'sr_no', orderable: false },
             	{ data: 'email', name: 'email' },
                { data: 'subject', name: 'subject' },
                { data: 'action', name: 'action', orderable: false },
            ],
            "aaSorting": [[0,'asc']],
         });
    });
</script>
@endsection
