@extends('admin.layout.index')
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
<style>
    table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable td:last-child, table.table-bordered.dataTable td:last-child {
    border-right-width: 0;
    text-align: center !important;
}
</style>
@endsection
@section('title')
Group Members : {{ $group->groupName }}
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
                    <h4 class="text-themecolor">Group Members : {{ $group->groupName }} </h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('groups.index') }}">Groups Discount</a>
                            </li>
                            <li class="breadcrumb-item active">Group Members</li>
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
            <div class="alert alert-success alertdisapper" id="success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    Member has been removed successfully
            </div>

            <div class="alert alert-danger alertdisapper" id="error">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    There is some problem to remove Member
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                         <button type="submit" style="float: right;" class="btn btn-danger" id="userRemove"  data-id="{{ $group->id}}"> Remove Members </button>
                            <div class="table-responsive m-t-40">
                            
                                <table id="users" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Join Date</th>
                                            <th scope="col" class="border center" width="10px">
                                              Select All <input type="checkbox" data-tablesaw-checkall id="selectAll">
                                                    <span class="sr-only" id="selectAll"> </span>
                                            </th>
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
    $('#success').hide();
    $('#error').hide();
    var table;
    $(function() {
        var groupId =  $('#userRemove').attr('data-id');
        table = $('#users').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [ [100, 200, 300, 500], [100, 200, 300, 500] ],
            pageLength: 500,
            "ajax": {
                "url":'{{ url(route("groups.searchGroupUsers"))  }}',
                "type": "POST",
                "async": false,
                'data' : function(d)
                {
                    d._token = "{{ csrf_token() }}",
                    d.page = 'asigned_users',
                    d.groupId = groupId 
                }
            },
            columns: [
                { data: 'sr_no' ,name:'sr_no', orderable:false },
            	{ data: 'name', name: 'name'},
            	{ data: 'email', name: 'email'},
                { data: 'joinDate', name: 'joinDate' },
                { data: 'action', name: 'action', orderable: false },
            ],
            "aaSorting": [[3,'desc']],
         });
    });

    // select all rows

    $('#selectAll').click(function () {
        $('.checkUserId').prop('checked',this.checked);
    })

    // get selected rows

    $(document).on('click', '#userRemove', function () {
        event.preventDefault();
        var groupId =  $('#userRemove').attr('data-id');
        var matches = [];
        var checkedcollection = table.$(".checkUserId:checked", { "page": "all" });
        
        checkedcollection.each(function (index, elem) {
            matches.push($(elem).val());
        });

        var userJsonString = JSON.stringify(matches);
        
        if (matches.length === 0) {
            alert('Please select atleast one member');
            event.preventDefault();
        }else{
            $.ajax({
                type: "post",
                url: "{{ url(route('groups.removeGroupUser'))  }}",
                dataType: "json",
                data: { userId : userJsonString ,groupId : groupId },

                success: function(data){
                        if(data == 1){
                        $('#success').show();
                        $('#error').hide();
                        setTimeout(function(){ location.reload(true); }, 1000);
                        }else{
                            $('#success').hide();
                            $('#error').show();
                        setTimeout(function(){ location.reload(true); }, 1000);
                        }
                    }
                });
        }
    });

</script>
@endsection
