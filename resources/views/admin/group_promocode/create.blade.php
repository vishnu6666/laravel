@extends('admin.layout.index')

@section('title') {{ empty($groups) ? 'Create group discount ' : 'Edit group discount' }} @endsection
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
                    <h4 class="text-themecolor">{{ empty($groups) ? 'Create group discount' : 'Edit  group discount' }}</h4>
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
                            <li class="breadcrumb-item active">{{ empty($groups) ? 'Create  Groups Discount' : 'Edit Groups Discount' }} </li>
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
                            <h4 class="card-title">{{ empty($groups) ? 'Create group discount code' : 'Edit group discount code' }}  </h4>
                            @if(empty($groups))
                                <form class="form" name="creategroups" id="creategroups" method="post" enctype="multipart/form-data" action="{{ route('groups.store') }}">

                                    @else
                                        <form class="form" name="editgroups" id="editgroups" method="post" enctype="multipart/form-data" action="{{ route('groups.update', ['group' => $groups->id]) }}">
                                            @method('PUT')
                                            @endif

                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" id="id" value="{{$groups->id ?? ''}}">

                                            <div class="form-group m-t-40 row">
                                                <label for="groupName" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Group Promo Name<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control" type="text" name="groupName" tabindex="1" placeholder="Please enter group promo name" id="groupName" value="{{ $groups->groupName ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="promoCode" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Discount Code<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control" type="text" name="promoCode" tabindex="2" placeholder="Please enter discount code" id="promoCode" value="{{ $groups->promoCode ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="description" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Discount Code Description<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control" type="text" name="description" tabindex="3" placeholder="Please enter discount code description" id="description" value="{{ $groups->description ?? '' }}" required >
                                                </div>
                                            </div>

                                            <div class="form-group m-t-40 row">
                                                <label for="planId" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Subscription Plan<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                   <select id="planId" tabindex="4"  name="planId" class="form-control">
                                                      <option value="any">All Plan</option>
                                                      @forelse( $subscriptionPlan as $key => $plan)
                                                      <option value="{{ $plan['id'] }}" {{ (!empty($groups) && $groups->planId == $plan['id']) ? 'selected' : '' }}>{{ $plan['planName']}}</option>
                                                      @empty
                                                      @endforelse
                                                   </select>
                                                </div>
                                             </div>

                                             <div class="form-group m-t-40 row">
                                                <label for="discountType" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Discount Type<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                   <select id="discountType" tabindex="5"  name="discountType" class="form-control">
                                                      <option value="">Select discount type</option>
                                                      @forelse( config('constant.discount_type') as $key => $type)
                                                      <option value="{{ $type ?? '' }}" {{ (!empty($groups) && ucfirst($groups->discountType) == $type) ? 'selected' : '' }}>{{ $type ?? '' }}</option>
                                                      @empty
                                                      @endforelse
                                                   </select>
                                                </div>
                                             </div>

                                             <div class="form-group m-t-40 row">
                                                <label for="discountAmount"  class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-form-label">Discount {{ $priceSymbol ?? '' }} <span class="percantage"></span><span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <input class="form-control decimalOnly" type="text" name="discountAmount" tabindex="6" placeholder="Please enter discount" id="discountAmount" value="{{ $groups->discountAmount ?? '' }}" required >
                                                </div>
                                            </div>

                                            @php
                                                $startDate = isset($groups->startDate)?$groups->startDate:'';
                                                $endDate = isset($groups->endDate)?$groups->endDate:'';

                                            @endphp

                                            <div class="form-group m-t-40 row">
                                                <label for="promo_date_range" class="col-2 col-form-label">Select Date Range <span class="text-danger">*</span></label>
                                                <div class="col-6">
                                                    <input type="text" name="promo_date_range" id="promo_date_range" class=" form-control" value="<?php echo ($startDate!='')?($startDate." - ".$endDate):''; ?>"
                                                           readonly style="text-align: center">
                                                </div>
                                            </div>

                                          
                                            <div class="form-group m-t-40 row">
                                                <label for="status" class="col-xs-12 col-sm-12 col-md-2 col-lg-2  col-form-label">Status<span class="text-danger">*</span></label>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="status1" name="status" value="1" class="custom-control-input" {{ (!empty($groups) && $groups->isActive == 1) ? 'checked' : ''}} {{ (empty($groups)) ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="status1">Active</label>
                                                </div>&nbsp;&nbsp;&nbsp;
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="status2" name="status" value="0" class="custom-control-input" {{ (!empty($groups) && $groups->isActive == 0) ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="status2">Inactive</label>
                                                </div>
                                                </div>
                                            </div>

                                             <div class="form-group row">
                                                  <div class="col-6 text-center">
                                                      <button type="submit" class="btn btn-success"> {{ empty($groups) ? 'Create' : 'Update' }}</button>

                                                      @if(!empty($groups)) 
                                                      
                                                           <a  href="{{ route('groups.edit', ['group' => $groups->id]) }}" class="btn btn-danger"> Reset</a>
                                                      @else
                                                            <a  href="{{ route('groups.create')}}" class="btn btn-danger"> Reset</a>
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

            @if(!empty($groups->discountType)  && $groups->discountType == 'Percentage')
                $('.percantage').html('(%)');
            @endif

            $('input[name="promo_date_range"]').daterangepicker({
                'minDate':new Date(),
                locale: {
                    format: 'YYYY-MM-DD'
                },
                'drops':"down"
            });

            $("#discountType").change(function() {

                var  discount_type =  $( "#discountType option:selected" ).val();

                    if(discount_type == "Percentage"){
                        $("#discountAmount").rules("remove",'le');
                        $("#discountAmount").rules("add",{
                            max:100
                        });
                        $('.percantage').html('(%)');

                    }else{
                        $("#discountAmount").rules("remove",'max');
                        $('.percantage').html('');

                    }
            });

            // validation for max value 
            $.validator.addMethod('le', function(value, element, param) {
                return this.optional(element) || parseInt(value) <= parseInt($(param).val());
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
                    groupName:{
                         required: true,
                         maxlength:250,
                         remote: {
                            url:"{{ url('/') }}"+'/admin/check/unique/groups_promocodes/groupName',
                            type: "post",
                            data: {
                                value: function() {
                                    return $( "#groupName" ).val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            }
                        }
                    },
                    description:{
                         required: true,
                         maxlength:300,
                    },
                    promoCode: {
                        required: true,
                        maxlength:90,
                        remote: {
                            url:"{{ url('/') }}"+'/admin/check/uniquebothtable/groups_promocodes/promoCode/referral_codes/referCode',
                            type: "post",
                            data: {
                                value: function() {
                                    return $( "#promoCode" ).val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            }
                        }
                    },
                    discountType:{
                        required:true,
                    },

                    discountAmount:{
                         required:true,
                         number: true,
                    },

                    maxDiscount:{
                         required: true,
                    }
                },
                messages: {
                    groupName:{
                         required:'Please enter group promo name',
                         maxlength: 'Group promo name must be less than {0} character',
                         remote: 'Group promo name already exist',
                    },
                    description:{
                         required:'Please enter discount code description',
                         maxlength: 'Discount code must be less than {0} character',
                    },
                    promoCode: {
                        required: 'Please enter discount code',
                        maxlength: 'Discount code must be less than {0} character',
                        remote: 'This discount code already exist',

                    },
                    discountType:{

                        required:'Please select discount type',
                    },
                    discountAmount:{
                         required:'Please enter discount',
                         le:'Must be less than minimum total amount',
                    },
                    maxDiscount:{
                         required:'Please enter max discount'
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