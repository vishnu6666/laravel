@extends('admin.layout.index')
@section('title') Update Result @endsection
@section('css')
<link href="{{ url('admin-assets/node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
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
            <h4 class="text-themecolor">Update Result Tips</h4>
         </div>
         <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                     <a href="{{ route('AdminDashboard')}}">Home</a>
                  </li>
                  <li class="breadcrumb-item">
                     <a href="{{ route('manage-tips.index')}}">Manage tips</a>
                  </li>
                  <li class="breadcrumb-item">
                     <a href="{{ route('updateresult')}}">Update Result</a>
                  </li>
                  <li class="breadcrumb-item active">Update Result Tips</li>
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
      <!-- .row -->
      <div class="row">
         <div class="col-sm-12">
            @include('admin.common.flash')
            <div class="card">
               <div class="card-body">
                     <div class="table-responsive m-t-40">
                        <table id="resultTips" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Game name</th>
                                 <th>Package name</th>
                                 <th>Date</th>
                                 <th>Tips</th>
                                 <th>Odds</th>
                                 <th>Units</th>
                                 <th>Win</th>
                                 <th>Pot</th>
                                 <th>Profit ($10 Unit )</th>
                                 <th>Profit ($25 Unit )</th>
                                 <th>Profit ($50 Unit )</th>
                                 <th>Create date</th>
                                 <th width="10px">Result Update</th>
                                 <th width="10px">Log</th>
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
      <!-- /.row -->
      <!-- ============================================================== -->
      <!-- End Page Content or Row-->
      <!-- ============================================================== -->
   </div>
   <!-- ============================================================== -->
   <!-- End Container fluid  -->
   <!-- ============================================================== -->
</div>
@endsection
@section('js')
	

<script src="{{ url('admin-assets/node_modules/datatables/datatables.min.js') }}"></script>

<script type="text/javascript">

   $(document).ready(function ()
   {         
         $(document).ready(function ()
         {         
            dataTable = $('#resultTips').DataTable({
            processing: true,
               serverSide: true,
               "ajax" : {
                  "url" : '{{ url( route("manage-tips.searchresultupdatetips") ) }}',
                  "type" : 'post',
                  'async' : false,
                  'data' : function(d)
                  {
                     d.spreadsheetsId = {{ $spreadsheetsId }}
                  }
               },
               columns: [
                  { data: 'sr_no' ,name:'sr_no', orderable: false },
                  { data: 'competition', name: 'competition'},
                  { data: 'packageName', name: 'packageName'},
                  { data: 'date', name: 'date'},
                  { data: 'tips', name: 'tips'},
                  { data: 'odds', name: 'odds'},
                  { data: 'units', name: 'units'},
                  { data: 'IsWin', name: 'IsWin' },
                  { data: 'pot', name: 'pot' },
                  { data: 'profitOneUnit', name: 'profitOneUnit' },
                  { data: 'profitTwoUnit', name: 'profitTwoUnit' },
                  { data: 'profitThreeUnit', name: 'profitThreeUnit' },
                  { data: 'createdAt', name: 'createdAt'},
                  { data: 'isResultUpdate', name: 'isResultUpdate' },
                  { data: 'resultUpdateStatus', name: 'resultUpdateStatus' , orderable: false}
               ],
               "aaSorting": [[7,'desc']],
            });  
         }); 
   });
</script>
@endsection
