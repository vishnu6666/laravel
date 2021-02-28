@extends('admin.layout.index')

@section('title') {{ empty($coupon) ? 'Create  Promo Code' : 'Edit Promo Code' }} @endsection
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
                    <h4 class="text-themecolor">{{ empty($coupon) ? 'Create promocode' : 'Edit  promocode' }}</h4>
                </div>

                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('promocode.index') }}">Promocodes</a>
                            </li>
                            <li class="breadcrumb-item active">{{ empty($coupon) ? 'Create  Promocode' : 'Edit Promocode' }} </li>
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
                            <h4 class="card-title">{{ empty($coupon) ? 'Create Promo Code' : 'Edit Promo Code' }}  </h4>
                            @if(empty($coupon))
                                <form class="form" name="createCoupon" id="createCoupon" method="post" enctype="multipart/form-data" action="{{ route('promocode.store') }}">

                                    @else
                                        <form class="form" name="editCoupon" id="editCoupon" method="post" enctype="multipart/form-data" action="{{ route('promocode.update', ['promocode' => $coupon->id]) }}">
                                            @method('PUT')
                                            @endif

                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" id="id" value="{{$coupon->id ?? ''}}">

                                            <div class="form-group m-t-40 row">
                                                <label for="title" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Promocode title<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control" type="text" name="title" tabindex="1" placeholder="Please enter promocode title" id="title" value="{{ $coupon->title ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="description" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Promocode description<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control" type="text" name="description" tabindex="1" placeholder="Please enter promocode description" id="description" value="{{ $coupon->description ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="couponCode" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Promocode<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control" type="text" name="couponCode" tabindex="1" placeholder="Please enter promocode" id="couponCode" value="{{ $coupon->promoCode ?? '' }}" required >
                                                </div>
                                            </div>

                                            
                                            <div class="form-group m-t-40 row">
                                                <label for="planId" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Subscription Plan<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                   <select id="planId" tabindex="3"  name="planId" class="form-control">
                                                      <option value="any">All Plan</option>
                                                      @forelse( $subscriptionPlan as $key => $plan)
                                                      <option value="{{ $plan['id'] }}" {{ (!empty($coupon) && $coupon->planId == $plan['id']) ? 'selected' : '' }}>{{ $plan['planName']}}</option>
                                                      @empty
                                                      @endforelse
                                                   </select>
                                                </div>
                                             </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="discountType" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Discount type<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                   <select id="discountType" tabindex="3"  name="discountType" class="form-control">
                                                      <option value="">Select discount type</option>
                                                      @forelse( config('constant.discount_type') as $key => $type)
                                                      <option value="{{ $type ?? '' }}" {{ (!empty($coupon) && ucfirst($coupon->discountType) == $type) ? 'selected' : '' }}>{{ $type ?? '' }}</option>
                                                      @empty
                                                      @endforelse
                                                   </select>
                                                </div>
                                             </div>
                                              

                                            <div class="form-group m-t-40 row">
                                                <label for="minAmount" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Min order amount({{ $priceSymbol  ?? '' }})<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control decimalOnly" type="text" name="minAmount" tabindex="4" placeholder="Please enter min amount" id="minAmount" value="{{ $coupon->minTotalAmount ?? '' }}" required >
                                                </div>
                                            </div>

                                          
                                            <div class="form-group m-t-40 row">
                                                <label for="discount"  class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Discount amount({{ $priceSymbol ?? '' }}) <span class="percantage"></span><span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control decimalOnly" type="text" name="discount" tabindex="6" placeholder="Please enter discount" id="discount" value="{{ $coupon->discountAmount ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="maxDiscount" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Max discount ({{ $priceSymbol ?? '' }})<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control decimalOnly" type="text" name="maxDiscount" tabindex="7" placeholder="Please enter max discount" id="maxDiscount" value="{{ $coupon->maxDiscountAmount ?? '' }}" required >
                                                </div>
                                            </div>

                                            @php
                                                $startDate = isset($coupon->startDate)?$coupon->startDate:'';
                                                $endDate = isset($coupon->endDate)?$coupon->endDate:'';

                                            @endphp

                                            <div class="form-group m-t-40 row">
                                                <label for="promo_date_range" class="col-2 col-form-label">Select date range <span class="text-danger">*</span></label>
                                                <div class="col-6">
                                                    <input type="text" name="promo_date_range" id="promo_date_range" class=" form-control" value="<?php echo ($startDate!='')?($startDate." - ".$endDate):''; ?>"
                                                           readonly style="text-align: center">
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="status" class="col-xs-12 col-sm-12 col-md-2 col-lg-2  col-form-label">Apply multiTime<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="isApplyMultiTime1" name="isApplyMultiTime" value="1" class="custom-control-input" {{ (!empty($coupon) && $coupon->isApplyMultiTime == 1) ? 'checked' : ''}} {{ (empty($coupon)) ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="isApplyMultiTime1">Yes</label>
                                                </div>&nbsp;&nbsp;&nbsp;
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="isApplyMultiTime2" name="isApplyMultiTime" value="0" class="custom-control-input" {{ (!empty($coupon) && $coupon->isApplyMultiTime == 0) ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="isApplyMultiTime2">No</label>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="status" class="col-xs-12 col-sm-12 col-md-2 col-lg-2  col-form-label">Status<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="status1" name="status" value="1" class="custom-control-input" {{ (!empty($coupon) && $coupon->isActive == 1) ? 'checked' : ''}} {{ (empty($coupon)) ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="status1">Active</label>
                                                </div>&nbsp;&nbsp;&nbsp;
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="status2" name="status" value="0" class="custom-control-input" {{ (!empty($coupon) && $coupon->isActive == 0) ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="status2">Inactive</label>
                                                </div>
                                                </div>
                                            </div>

                                             <div class="form-group row">
                                                  <div class="col-6 text-center">
                                                      <button type="submit" class="btn btn-success"> {{ empty($coupon) ? 'Create' : 'Update' }}</button>

                                                      @if(!empty($coupon)) 
                                                      
                                                           <a  href="{{ route('promocode.edit', ['promocode' => $coupon->id]) }}" class="btn btn-danger"> Reset</a>
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

            @if(!empty($coupon->discountType)  && $coupon->discountType == 'Percentage')
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
            
            // image dropify 
            $('.dropify').dropify();

            $(".form").validate({
                rules: {
                    title:{
                         required: true,
                         maxlength:250,
                    },
                    description:{
                         required: true,
                         maxlength:300,
                    },
                    couponCode: {
                        required: true,
                        maxlength:90,
                        remote: {
                             url:"{{ url('/') }}"+'/admin/check/uniquebothtable/promocodes/promoCode/referral_codes/referCode',
                            type: "post",
                            data: {
                                value: function() {
                                    return $( "#couponCode" ).val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            }
                        }
                    },
                    minAmount:{
                        required: true,
                    },

                    discountType:{

                        required:true,
                    },
                    discount:{

                         required:true,
                         number: true,
                         le:'#minAmount',
                         max: 95
                    },
                    maxDiscount:{

                         required: true,
                         le:'#minAmount',

                    }
                },
                messages: {
                    title:{
                         required:'Please enter title',
                         maxlength: 'title must be less than {0} character',
                    },
                    description:{
                         required:'Please enter description',
                         maxlength: 'description must be less than {0} character',
                    },
                    couponCode: {
                        required: 'Please enter promo code',
                        maxlength: 'Promo Code must be less than {0} character',
                        remote: 'This promo code already exist',

                    },
                    minAmount:{
                        required: 'Please enter min amount',
                    },

                    discountType:{

                        required:'Please select discount type',
                    },
                    discount:{
                         required:'Please enter discount',
                         le:'Must be less than minimum total amount',
                    },
                    maxDiscount:{
                         required:'Please enter max discount',
                         le:'Must be less than minimum total amount'
                    }
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