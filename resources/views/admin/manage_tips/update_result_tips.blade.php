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
                        <table id="resultTips" class="display  table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Game <br/>name</th>
                                 <th>Package <br/>name</th>
                                 <th>Tips</th>
                                 <th>Odds</th>
                                 <th>Units</th>
                                 <th>Win/Los</th>
                                 <th>Profit/Loss<br/>for Tips </th>
                                 <th>Weekly<br/>Profit/Loss</th>
                                 <th>Weekly<br/>POT %</th>
                                 <th>Monthly<br/>Profit/Loss</th>
                                 <th>Monthly<br/>POT %</th>
                                 <th>All Time<br/>Profit/Loss</th>
                                 <th>All Time<br/> POT %</th>
                                 <th width="200px">Text</th>
                                 <th width="10px">Result <br/>Update</th>
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
                  { data: 'tips', name: 'tips'},
                  { data: 'odds', name: 'odds'},
                  { data: 'units', name: 'units'},
                  { data: 'IsWin', name: 'IsWin' },
                  { data: 'profitLosForTips', name: 'profitLosForTips' },
                  { data: 'weeklyProfitLos', name: 'weeklyProfitLos' },
                  { data: 'weeklyPot', name: 'weeklyPot' },
                  { data: 'monthlyProfitLos', name: 'monthlyProfitLos' },
                  { data: 'monthlyPot', name: 'monthlyPot' },
                  { data: 'allTimeProfitLos', name: 'allTimeProfitLos' },
                  { data: 'allTimePot', name: 'allTimePot' },
                  { data: 'text', name: 'text'},
                  { data: 'isResultUpdate', name: 'isResultUpdate' },
                  { data: 'resultUpdateStatus', name: 'resultUpdateStatus' , orderable: false}
               ],
               "aaSorting": [[6,'asc']],
            });  
         }); 
   });
</script>
@endsection
