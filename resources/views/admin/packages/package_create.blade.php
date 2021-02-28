@extends('admin.layout.index')
@section('title') {{ empty($package) ? 'Create Package' : 'Edit Package' }} @endsection
@section('css')

<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropify/dist/css/dropify.min.css') }}">
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
                    <h4 class="text-themecolor">{{ empty($package) ? 'Create Package' : 'Edit Package' }}</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('packages.index')}}">Packages</a>
                            </li>
                            <li class="breadcrumb-item active">{{ empty($package) ? 'Create Package' : 'Edit Package' }}</li>
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
                            @if(empty($package))
                                <form class="form" name="Packagetore" id="Packagetore" method="post" enctype="multipart/form-data" action="{{ route('packages.store') }}">
                            @else
                                <form class="form" name="Packageupdate" id="Packageupdate" method="post" enctype="multipart/form-data" action="{{ route('packages.update', ['package' => $package->id]) }}">
                                    @method('PUT')
                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id" value="{{ $package->id ?? '' }}" />
                                    <div class="form-group m-t-40 row">
                                        <label for="packageName" class="col-2 col-form-label">Package name<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $package->packageName ?? '' }}"
                                                    name="packageName" id="packageName" placeholder="Please enter package name" tabindex="1" required >
                                        </div>
                                    </div>

                                    @if(empty($package))
                                        <div class="form-group m-t-40 row">
                                        <label for="selectGames" class="col-2 form-label quizimgblock">Select Games <span class="text-danger">*</span></label>
                                        <div class="col-9">
                                                <select class="select2 m-b-10 select2-multiple select2-hidden-accessible col-5"  id="selectGame" name="selectGame[]" style="width: 53%" multiple="">
                                                    @foreach($gameList as $key => $games)
                                                    <option value="{{$games->id}}">{{$games->gameName ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                                <br>
                                                <label id="selectGames-error-msg" class="custom-error"  style="display:none;color:red;padding-top: 8px !important;">Please select atleast one game </label>
                                        </div>
                                        </div>
                                    @else
                                        <div class="form-group m-t-40 row">
                                            
                                            <label for="selectGames" class="col-2 form-label quizimgblock">Select Games <span class="text-danger">*</span></label>
                                            <div class="col-9">
                                                <select class="select2 m-b-10 select2-multiple select2-hidden-accessible col-5"  id="selectGame" name="selectGame[]" style="width: 53%" multiple="">
                                                    @foreach($gameList as $key => $games)
                                                            
                                                            @if(in_array($games->id, $gameAsignedIds))
                                                                <option selected value="{{$games->id}}">{{$games->gameName ?? ''}}</option>
                                                            @else
                                                                <option value="{{$games->id}}">{{$games->gameName ?? ''}}</option>
                                                            @endif
                                                    @endforeach
                                                </select>
                                                <br>
                                                <label id="selectGames-error-msg" class="custom-error"  style="display:none;color:red;padding-top: 8px !important;">Please select atleast one Game </label>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="form-group m-t-40 row">
                                        <label for="status" class="col-2 col-form-label">Status<span class="text-danger">*</span></label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status1" name="status" value="1"
                                            class="custom-control-input" {{ (!empty($package) && $package->isActive == 1) ? 'checked' : ''}} {{ (empty($package)) ? 'checked' : '' }} >
                                            <label class="custom-control-label" for="status1">Active</label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status2" name="status" value="0" 
                                            class="custom-control-input" {{ (!empty($package) && $package->isActive == 0) ? 'checked' : ''}}>     
                                            <label class="custom-control-label" for="status2">Inactive</label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-5 text-center">
                                            <button type="submit" class="btn btn-success"> {{ empty($package) ? 'Create' : 'Update' }}</button>

                                            @if(!empty($package)) 
                                    
                                            <a  href="{{ route('packages.edit',[ 'package' => $package->id] )}}" class="btn btn-danger"> Reset</a>

                                            @else

                                            <a  href="{{ route('packages.create')}}" class="btn btn-danger"> Reset</a>

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
    <script src="{{url('admin-assets/js/jquery.validate.min.js')}}"></script> 
    <script src="{{ url('admin-assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
    <script type="text/javascript">
          
        $(document).ready(function ()
        {   
            $.validator.addMethod(
                "regex",
                function(value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                },
                "Please check your input."
            );
            
            //override required method
            $.validator.methods.required = function(value, element, param) {
                return (value == undefined) ? false : (value.trim() != '');
            }

            function showGameSelectError()
            {   
                var selectStudent = $("#selectGame").val();
                if(selectStudent.length == 0)
                {
                    $('#selectGames-error-msg').show();
                    return false;
                }
                else
                {
                    $('#selectGames-error-msg').hide();
                    return true;
                }
            }

            $(document.body).on("change","#selectGame",function(){
                showGameSelectError();
            });
            
            $('.dropify').dropify();
            $(".form").validate({
                ignore: [],
                rules: {
                    packageName: {
                        required: true,
                        maxlength:20,
                        minlength:3,
                    }
                },
                messages: {
                    packageName: {
                        required: "Please enter Package name",
                        maxlength:"The Package name may not be greater than 20 characters.",
                    }
                },
        
                submitHandler: function (form)
                {
                    if(showGameSelectError())
                    {
                        form.submit();
                    }
                },
                invalidHandler: function(form, validator) {
                        showGameSelectError();
                }
            });
        });
    </script>
@endsection
