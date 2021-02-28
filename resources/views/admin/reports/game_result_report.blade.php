@extends('admin.layout.index')
@section('title') Game Result Report @endsection
@section('css')
<link href="{{ url('admin-assets/css/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />

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
                    <h4 class="text-themecolor">Game Result Report</h4>
                </div>
                
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Game Result Report</li>
                            
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
            @include('admin.common.flash')
            <div class="row">
                <div class="col-sm-12">
                <form class="form" name="paymentReport" id="paymentReport" method="post" enctype="multipart/form-data" action="{{ route('admin.gameResultDetail') }}">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="daterange">Date Range <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="daterange" name="daterange" placeholder="Select Date Range">
                                </div>
                            </div>
                            <div class="form-group m-t-40 row">
                                <label for="export_type" class="col-2 col-form-label">Export Type<span class="text-danger">*</span></label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="export_type1" name="export_type" value="Pdf"
                                    class="custom-control-input"  >
                                    <label class="custom-control-label" for="export_type1">Pdf </label>
                                </div>
                                
                                &nbsp;&nbsp;&nbsp;
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="export_type3" checked  name="export_type" value="Csv" 
                                    class="custom-control-input" >     
                                    <label class="custom-control-label" for="export_type3">Csv</label>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <div class="col-10">
                                    <button type="submit" class="btn btn-success"> Export</button>
                                </div>
                            </div>   
                        </div>
                    </div>
                    </form>
                </div>
            </div>  
        </div>   
</div>              
@endsection

@section('js')
<script type="text/javascript" src="{{ url('admin-assets/js/moments.min.js')}}"></script>
<script type="text/javascript" src="{{ url('admin-assets/js/daterangepicker.min.js') }}"></script>
<script>
    
    var date = new Date();
    date.setMonth(date.getMonth() - 12);

    $('#daterange').daterangepicker({
        'minDate':date,
        'maxDate': new Date(),
        'drops':"down",
        'dateLimit': {
            days: 91
            },
    });

    $("#paymentReport").validate({
        rules: {
            daterange:{
                required:true,
            },
        },
        messages: {

            daterange:{
                required:"Please select date",
            },
        },
        submitHandler: function (form)
        {
            form.submit();
        }
    });

</script>    
@endsection