@extends('admin.layout.index')
@section('title') {{ empty($game) ? 'Create game' : 'Edit game' }} @endsection
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
                    <h4 class="text-themecolor">{{ empty($game) ? 'Create game' : 'Edit game ' }}</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('games.index')}}">Games</a>
                            </li>
                            <li class="breadcrumb-item active">{{ empty($game) ? 'Create game' : 'Edit game' }}</li>
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
                            @if(empty($game))
                                <form class="form" name="Gametore" id="Gametore" method="post" enctype="multipart/form-data" action="{{ route('games.store') }}">
                            @else
                                <form class="form" name="Gameupdate" id="Gameupdate" method="post" enctype="multipart/form-data" action="{{ route('games.update', ['game' => $game->id]) }}">
                                    @method('PUT')
                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id" value="{{ $game->id ?? '' }}" />

                                    <div class="form-group m-t-40 row">
                                        <label for="gameName" class="col-2 col-form-label">Game name<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $game->gameName ?? '' }}"
                                                    name="gameName" id="gameName" placeholder="Please enter game name" tabindex="1" required >
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="gameFullName" class="col-2 col-form-label">Game full name<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $game->gameFullName ?? '' }}"
                                                    name="gameFullName" id="gameFullName" placeholder="Please enter game full name" tabindex="1" required >
                                        </div>
                                    </div>

                                        @php
                                        $disableDate = '';
                                        $todayDate = date('Y-m-d');
                                            if(isset($game->launchDate) && $todayDate >= $game->launchDate)
                                            {
                                                $disableDate = "readonly";
                                            }
                                        @endphp

                                    <div class="form-group m-t-40 row">
                                        <label for="launchDate" class="col-2 col-form-label">Game launch date<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="date" {{ $disableDate }}  value="{{ $game->launchDate ?? '' }}"
                                                    name="launchDate" id="launchDate" placeholder="Please select game launch date" tabindex="1" required >
                                        </div>
                                    </div>
                                
                                    <div class="form-group row">
                                        <label for="gameImage" class="col-2 col-form-label">Game image<span class="text-danger">*</span> </label>
                                        <div class="col-5">
                                            <div class="">
                                                <input type="file" id="gameImage" name="gameImage" class="dropify" data-min-width="399" data-min-height="399" data-max-width="401" data-max-height="401" tabindex="3" data-show-remove="false" accept="image/x-png,image/jpg,image/jpeg"  data-allowed-file-extensions="jpg png jpeg" data-default-file="{{ !empty($game->gameImage) ? url('images/gameimage/'.$game->gameImage) : ''}}"/>
                                            </div>
                                        </div>
                                    </div>
                            
                                    <div class="form-group m-t-40 row">
                                        <label for="status" class="col-2 col-form-label">Status<span class="text-danger">*</span></label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status1" name="status" value="1"
                                            class="custom-control-input" {{ (!empty($game) && $game->isActive == 1) ? 'checked' : ''}} {{ (empty($game)) ? 'checked' : '' }} >
                                            <label class="custom-control-label" for="status1">Active</label>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="status2" name="status" value="0" 
                                            class="custom-control-input" {{ (!empty($game) && $game->isActive == 0) ? 'checked' : ''}}>     
                                            <label class="custom-control-label" for="status2">Inactive</label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-5 text-center">
                                            <button type="submit" class="btn btn-success"> {{ empty($game) ? 'Create' : 'Update' }}</button>

                                            @if(!empty($game)) 
                                    
                                            <a  href="{{ route('games.edit',[ 'game' => $game->id] )}}" class="btn btn-danger"> Reset</a>

                                            @else

                                            <a  href="{{ route('games.create')}}" class="btn btn-danger"> Reset</a>

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

            $('.dropify').dropify({
                error: {
                    'minWidth': 'The game image width must be 400px.',
                    'minHeight': 'The game image height must be 400px.',
                    'maxWidth': 'The game image width must be 400px.',
                    'maxHeight': 'The game image height must be  400px.',
                    'imageFormat': 'The game image format is not allowed jpg png jpeg only.'
                }
            });
            
            var emailpattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            $('.dropify').dropify();
            $(".form").validate({
                ignore: [],
                rules: {
                    gameName: {
                        required: true,
                        maxlength:10,
                        minlength:3,
                    },
                    gameFullName: {
                        required: true,
                        maxlength:50,
                        minlength:3,
                    },
                    gameImage:{

                        required:{{ empty($game) ? 'true' : 'false' }},
                    },
                    launchDate: {
                        required: true
                    },
                },
                messages: {
                    gameName: {
                        required: "Please enter game name",
                        maxlength:"The game name may not be greater than 10 characters.",
                    },
                    gameFullName: {
                        required: "Please enter game full name",
                        maxlength:"The game full name may not be greater than 50 characters.",
                    },
                    gameImage: {
                        required: "Please upload game image.",
                    },
                    launchDate: {
                        required: "Please select game launch date.",
                    }
                },
                submitHandler: function (form)
                {
                    form.submit();
                }
            });
        });
    </script>
@endsection
