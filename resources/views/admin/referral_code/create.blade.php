@extends('admin.layout.index')

@section('title') {{ empty($referCode) ? 'Create  Refer Code' : 'Edit Refer Code' }} @endsection
@section('css')

 <link rel="stylesheet" type="text/css" href="{{url('admin-assets/node_modules/bootstrap-daterangepicker/daterangepicker.css')}}" />
 <link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">

@endsection
@section('content')

@php 
    $priceSymbol = session()->get('restCurrencyCode');
    
@endphp
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
                    <h4 class="text-themecolor">{{ empty($referCode) ? 'Create Refer Code' : 'Edit  Refer Code' }}</h4>
                </div>

                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('referCode.index') }}">ReferCode</a>
                            </li>
                            <li class="breadcrumb-item active">{{ empty($referCode) ? 'Create  ReferCode' : 'Edit ReferCode' }} </li>
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
                            <h4 class="card-title">{{ empty($referCode) ? 'Create Refer Code' : 'Edit Refer Code' }}  </h4>
                            @if(empty($referCode))
                                <form class="form" name="createCoupon" id="createCoupon" method="post" enctype="multipart/form-data" action="{{ route('referCode.store') }}">

                                    @else
                                        <form class="form" name="editCoupon" id="editCoupon" method="post" enctype="multipart/form-data" action="{{ route('referCode.update', ['referCode' => $referCode->id]) }}">
                                            @method('PUT')
                                            @endif

                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" id="id" value="{{$referCode->id ?? ''}}">

                                            <div class="form-group m-t-40 row">
                                                <label for="title" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Title<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control" type="text" name="title" tabindex="1" placeholder="Please enter title" id="title" value="{{ $referCode->title ?? '' }}" required >
                                                </div>
                                            </div>

                                             <div class="form-group m-t-40 row">
                                                <label for="title" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Description<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <textarea class="form-control" type="text" name="description" tabindex="1" placeholder="Please enter description" id="description" value="{{ $referCode->description ?? '' }}" required >{{ $referCode->description ?? '' }}</textarea>
                                                </div>
                                            </div>


                                            <div class="form-group m-t-40 row">
                                                <label for="referCode" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">ReferCode<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control" type="text" name="referCode" tabindex="1" placeholder="Please enter refercode" id="referCode" value="{{ $referCode->referCode ?? '' }}" required >
                                                </div>
                                            </div>

                                            

                                            <div class="form-group m-t-40 row">
                                                <label for="numberOfparson" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">No Of Person<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control decimalOnly" type="text" name="numberOfparson" tabindex="1" placeholder="Please enter number of person" id="numberOfparson" value="{{ $referCode->numberOfparson ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="percentage" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Percentage<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control decimalOnly" type="text" name="percentage" tabindex="1" placeholder="Please enter percentage" id="percentage" value="{{ $referCode->percentage ?? '' }}" required >
                                                </div>
                                            </div>

                                      

                                            <div class="form-group m-t-40 row">
                                                <label for="status" class="col-xs-12 col-sm-12 col-md-2 col-lg-2  col-form-label">Status<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="status1" name="status" value="1" class="custom-control-input" {{ (!empty($referCode) && $referCode->isActive == 1) ? 'checked' : ''}} {{ (empty($referCode)) ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="status1">Active</label>
                                                </div>&nbsp;&nbsp;&nbsp;
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="status2" name="status" value="0" class="custom-control-input" {{ (!empty($referCode) && $referCode->isActive == 0) ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="status2">Inactive</label>
                                                </div>
                                                </div>
                                            </div>

                                             <div class="form-group row">
                                                  <div class="col-6 text-center">
                                                      <button type="submit" class="btn btn-success"> {{ empty($referCode) ? 'Create' : 'Update' }}</button>

                                                      @if(!empty($referCode)) 
                                                      
                                                           <a  href="{{ route('promocode.edit', ['promocode' => $referCode->id]) }}" class="btn btn-danger"> Reset</a>
                                                      @else
                                                            <a  href="{{ route('promocode.create')}}" class="btn btn-danger"> Reset</a>
                                                      @endif
                                                  </div>
                                              </div>
                                        </form>
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
<script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
<script type="text/javascript" src="{{url('admin-assets/node_modules/date-paginator/moment.min.js')}}"></script>
<script type="text/javascript" src="{{url('admin-assets/node_modules/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function ()
        {   

            @if(!empty($referCode->discountType)  && $referCode->discountType == 'Percentage')
                $('.percantage').html('(%)');

            @endif

            $('input[name="promo_date_range"]').daterangepicker({
                'minDate':new Date(),
                locale: {
                    format: 'YYYY-MM-DD'
                },
                'drops':"down"
            });


            var minAmt = ($('#minAmount').val());
          
            $("#discountType").change(function() {

                var  discount_type =  $( "#discountType option:selected" ).val();
                    if(discount_type == "Percentage"){
                        $('.percantage').html('(%)');
                    }else{
                        $('.percantage').html('');

                    }


            });

            // validation for max value 
            $.validator.addMethod('le', function(value, element, param) {
                return this.optional(element) || parseInt(value) < parseInt($(param).val());
            }, 'Invalid value');
            $.validator.addMethod('ge', function(value, element, param) {
                return this.optional(element) || value >= $(param).val();
            }, 'Invalid value');


            //override required method
            $.validator.methods.required = function(value, element, param) {

                return (value == undefined) ? false : (value.trim() != '');
            }

            jQuery.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[a-zA-z0-9]+$/i.test(value);
                }, "please enter word and digit only"); 
            
            // image dropify 
            $('.dropify').dropify();

            $(".form").validate({
                rules: {
                referCode: {
                                required: true,
                                maxlength:9,
                                lettersonly: true,
                                remote: {
                                    url:"{{ url('/') }}"+'/admin/check/uniquebothtable/referral_codes/referCode/groups_promocodes/promoCode',
                                    type: "post",
                                    data: {
                                        value: function() {
                                            return $( "#referCode" ).val();
                                        },
                                        id: function() {
                                            return $( "#id" ).val();
                                        },
                                    }
                                },
                                // remote: {
                                //     url:"{{ url('/') }}"+'/admin/check/unique/promocodes/promoCode',
                                //     type: "post",
                                //     data: {
                                //         value: function() {
                                //             return $( "#referCode" ).val();
                                //         },
    
                                //     }
                                // }
                            },
                    title:{
                         required: true,
                         maxlength:50,
                    },
                    description:{
                         required: true,
                         maxlength:300,
                    },
                    numberOfparson:{
                        required: true,
                        digits: true,
                        max:9,
                    },
                    percentage:{
                        required:true,
                        digits: true,
                        max:100,
                    },
                },
                messages: {
                    referCode:{
                         required:'Please enter refer code',
                          remote: 'This refer code already exist',
                    },
                    title:{
                         required:'Please enter  title',
                         maxlength:"Title must not be greater than {0} characters.",
                    },
                    description:{
                         required:'Please enter  description',
                    },
                    numberOfparson: {
                        required: 'Please enter number of person',
                    },
                    percentage:{
                        required: 'Please enter percentage',
                    },
                },
                submitHandler: function (form)
                {
                    form.submit();
                },

                invalidHandler: function(form, validator) {

                }
            });

        }); 
    </script>
@endsection