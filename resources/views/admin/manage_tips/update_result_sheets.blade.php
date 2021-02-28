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
            <h4 class="text-themecolor">Update Result</h4>
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
                  <li class="breadcrumb-item active">Update Result</li>
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

                  <form class="form" name="spredsheetsForm" id="spredsheetsForm" method="get" enctype="multipart/form-data" action="{{ route('excelImport') }}">
                   
                     {{ csrf_field() }}

                     <div class="form-group m-t-40 row">
                        <label for="package" class="col-1 col-form-label">Package <span class="text-danger">*</span></label>
                        <div class="col-2">
                           <select name="package"  id="package" class="form-control" required>
                              <option value="">Select package</option>
                              @foreach($packages as $package)

                              <option value="{{ $package->id }}">{{ $package->packageName }}</option>

                              @endforeach
                           </select>
                        </div>

                        <label for="spreadsheetId" class="col-1.5 col-form-label">Spreadsheet Url <span class="text-danger">*</span></label>
                        <div class="col-6">
                           <input name="spreadsheetId" id="spreadsheetId" class=" form-control">
                        </div>

                        <div class="col-1.5" align="right">
                            <button type="submit" class="btn btn-success"> Create</button>
                            &nbsp;
                            <a  href="" class="btn btn-danger"> Reset</a>
                        </div>

                     </div>
                  </form>
                  <hr>
               </div>

                  <div class="card">
                     <div class="card-body">
                           <div class="table-responsive m-t-40">
                              <table id="tips" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                       <tr>
                                          <th>#</th>
                                          <th>Package name</th>
                                          <th>Sheets url</th>
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
	
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>
<script src="{{ url('admin-assets/node_modules/datatables/datatables.min.js') }}"></script>

<script type="text/javascript">

   $(document).ready(function ()
   {         
        $(".form").validate({
        	//ignore: [],
            rules: {
                package : {
                    required : true,
                }
            },
            rules: {
                spreadsheetId : {
                    required: true,
                    url: true
                }
            },
            messages:{
                package : {
                    required : 'Package must be required',
                }
            },
            messages:{
                spreadsheetId : {
                    required : 'Spreadsheet Url must be required',
                }
            },
            submitHandler: function(form) {
                form.submit();
            },
        }); 

         $(document).ready(function ()
         {         
            dataTable = $('#tips').DataTable({
               processing: true,
                  serverSide: true,
                  "ajax" : {
                     "url" : '{{ url( route("manage-tips.searchspreadsheets") ) }}',
                     "type" : 'post',
                     'async' : false,
                  },
                  columns: [
                     { data: 'sr_no' ,name:'sr_no', orderable: false },
                     { data: 'packageName', name: 'packageName'},
                     { data: 'spreadsheetId', name: 'spreadsheetId'},
                     { data: 'createdAt', name: 'createdAt'},
                     { data: 'action', name: 'action', orderable: false },
                  ],
                  "aaSorting": [[3,'desc']],
               });  
         }); 
   });
</script>
@endsection
