@extends('admin.layout.index')
@section('title') {{ empty($gameTip) ? 'Create Game Tip' : 'Edit Game Tip' }} @endsection
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
                    <h4 class="text-themecolor">{{ empty($gameTip) ? 'Create Game Tip' : 'Edit Game Tip - ' }}</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('AdminDashboard')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('manage-tips.index')}}">Games Tip</a>
                            </li>
                            <li class="breadcrumb-item active">{{ empty($gameTip) ? 'Create Game Tip' : 'Edit Game Tip' }}</li>
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
                            @if(empty($gameTip))
                                <form class="form" name="Gametore" id="Gametore" method="post" enctype="multipart/form-data" action="{{ route('manage-tips.store') }}">
                            @else
                                <form class="form" name="Gameupdate" id="Gameupdate" method="post" enctype="multipart/form-data" action="{{ route('manage-tips.update', ['manage_tip' => $gameTip->id]) }}">
                                    @method('PUT')
                                    @endif
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id" id="id" value="{{ $gameTip->id ?? '' }}" />
                                     <input type="hidden" name="gameTipGameId" id="gameTipGameId" value="{{ $gameTip->gameId ?? '' }}" />
                                     

                                    <div class="form-group m-t-40 row">
                                        <label for="gameName" class="col-2 col-form-label">Select package<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                              <select name="packageId" id="packageId" class="form-control" required>
                                                <option value="">Select packages name</option>
                                                @foreach($packages as $package)
                                                <option value="{{ $package->id }}" {{ (!empty($gameTip) && $gameTip->packageId == $package->id) ? 'selected' : ''}}>{{ $package->packageName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-t-40 row">
                                        <label for="gameName" class="col-2 col-form-label">Select game<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                              <select name="gameId" id="gameId" class="form-control" required>
                                                <option value="">Select game name</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="launchDate" class="col-2 col-form-label">Date<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="date" value="{{ $gameTip->date ?? '' }}"
                                                    name="date" id="date" placeholder="Please select game launch date" tabindex="1"  >
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="tips" class="col-2 col-form-label">Game tip name<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->tips ?? '' }}"
                                                    name="tips" id="tips" placeholder="Please enter game tip name" tabindex="1"  >
                                        </div>
                                    </div>

                                      <div class="form-group row">
                                        <label for="tipsImage" class="col-2 col-form-label">Game tip image<span class="text-danger"></span> </label>
                                        <div class="col-5">
                                            <div class="">
                                                <input type="file" id="tipsImage" name="tipsImage" class="dropify" tabindex="3" data-show-remove="false" accept="image/x-png,image/jpg,image/gif,image/jpeg"  data-allowed-file-extensions="jpg png jpeg gif" data-default-file="{{ !empty($gameTip->tipsImage) ? $gameTip->tipsImage : ''}}"/>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="form-group m-t-40 row">
                                        <label for="gameFullName" class="col-2 col-form-label">Game tip odds</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->odds ?? '' }}"
                                                    name="odds" id="odds" placeholder="Please enter game tip odds" tabindex="1"  >
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="units" class="col-2 col-form-label">Game tip units</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->units ?? '' }}"
                                                    name="units" id="units" placeholder="Please enter game tip units" tabindex="1"  >
                                        </div>
                                    </div>

                                      <div class="form-group m-t-40 row">
                                        <label for="gameName" class="col-2 col-form-label">Select game tip win -loss status</label>
                                        <div class="col-5">
                                              <select name="IsWin" id="IsWin" class="form-control" >
                                                <option value="">Select game tip win -loss status</option>
                                                <option value="win" {{ (!empty($gameTip) && $gameTip->IsWin == 'win') ? 'selected' : ''}}>Win</option>
                                                <option value="loss" {{ (!empty($gameTip) && $gameTip->IsWin == 'loss') ? 'selected' : ''}}>Loss</option>
                                                <option value="pending" {{ (!empty($gameTip) && $gameTip->IsWin == 'pending') ? 'selected' : ''}}>Pending</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group m-t-40 row">
                                        <label for="profitLosForTips" class="col-2 col-form-label">Game tip profit loss for tip</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->profitLosForTips ?? '' }}"
                                                    name="profitLosForTips" id="profitLosForTips" placeholder="Please enter game tip profit loss for day" tabindex="1"  >
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="weeklyProfitLos" class="col-2 col-form-label">Game tip weekly profit loss</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->weeklyProfitLos ?? '' }}"
                                                    name="weeklyProfitLos" id="weeklyProfitLos" placeholder="Please enter game weekly profit loss" tabindex="1"  >
                                        </div>
                                    </div>

                                     <div class="form-group m-t-40 row">
                                        <label for="weeklyPot" class="col-2 col-form-label">Game tip weekly pot</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->weeklyPot ?? '' }}"
                                                    name="weeklyPot" id="weeklyPot" placeholder="Please enter game tip weekly Pot" tabindex="1"  >
                                        </div>
                                    </div>

                                      <div class="form-group m-t-40 row">
                                        <label for="monthlyProfitLos" class="col-2 col-form-label">Game tip monthly profit loss</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->monthlyProfitLos ?? '' }}"
                                                    name="monthlyProfitLos" id="monthlyProfitLos" placeholder="Please enter game tip monthly profit loss" tabindex="1"  >
                                        </div>
                                    </div>


                                    <div class="form-group m-t-40 row">
                                        <label for="monthlyPot" class="col-2 col-form-label">Game tip monthly pot</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->monthlyPot ?? '' }}"
                                                    name="monthlyPot" id="monthlyPot" placeholder="Please enter game tip daily monthly pot" tabindex="1"  >
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="pot" class="col-2 col-form-label">Game tip all time profit los</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->allTimeProfitLos ?? '' }}"
                                                    name="allTimeProfitLos" id="allTimeProfitLos" placeholder="Please enter game tip all time profit los" tabindex="1"  >
                                        </div>
                                    </div>


                                    <div class="form-group m-t-40 row">
                                        <label for="pot" class="col-2 col-form-label">Game tip all time pot</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text" value="{{ $gameTip->allTimePot ?? '' }}"
                                                    name="allTimePot" id="allTimePot" placeholder="Please enter game tip all time pot" tabindex="1"  >
                                        </div>
                                    </div>

                                    <div class="form-group m-t-40 row">
                                        <label for="text" class="col-2 col-form-label">Game tip text</label>
                                        <div class="col-5">
                                            <textarea class="form-control" type="text" value="{{ $gameTip->text ?? '' }}"
                                                    name="text" id="text" placeholder="Please enter game tip text" tabindex="1"  >{{ $gameTip->text ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-5 text-center">
                                            <button type="submit" class="btn btn-success"> {{ empty($gameTip) ? 'Create' : 'Update' }}</button>

                                            @if(!empty($gameTip)) 
                                    
                                            <a  href="{{ route('manage-tips.edit',['manage_tip' => $gameTip->id] )}}" class="btn btn-danger"> Reset</a>

                                            @else

                                            <a  href="{{ route('manage-tips.create')}}" class="btn btn-danger"> Reset</a>

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

               jQuery.validator.addMethod("dollarsscents", function(value, element) {
                        return this.optional(element) || /^\d{0,2}(\.\d{0,2})?$/i.test(value);
                    }, "Please enter no less than 4 digit.");

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
            
            var emailpattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            $('.dropify').dropify();
            $(".form").validate({
                ignore: [],
                rules: {
                    packageId: {
                        required: true,
                    },
                     gameId: {
                        required: true,
                    },
                    //  IsWin: {
                    //     required: true,
                    // },
                    tips: {
                        required: true,
                         maxlength:50,
                         remote: {
                                    url:"{{ url('/') }}"+'/admin/check/gameTipUnique/games_tips/tips',
                                    type: "post",
                                    data: {
                                        gameId: function() {
                                            return $( "#gameId" ).val();
                                        },
                                        value: function() {
                                            return $( "#tips" ).val();
                                        },
                                        id: function() {
                                            return $( "#id" ).val();
                                        },
                                    }
                                },
                    },
                     date: {
                         required: true
                     },
                   
                    odds: {
                         number: true,
                         maxlength:9,
                    },
                    // units: {
                    //     required: true
                    // },
                     profitLosForTips: {
                        // required: true
                         maxlength:9,
                    },
                    
                     text: {
                        maxlength:200,
                    },
                    // gameImage:{

                    //     required:{{ empty($game) ? 'true' : 'false' }},
                    // },
                    
                },
                messages: {
                    packageId: {
                        required: "Please select package name",
                    },
                    gameId: {
                        required: "Please select game name",
                    },
                    // ,
                    //  IsWin: {
                    //     required: "Please select game tip win or not win",
                    // },
                    date: {
                         required: "Please select game tip date.",
                    },
                    tips: {
                        required: "Please enter game tip name",
                        remote: 'This game trip already exist today',
                        maxlength: 'Game Trip Name must be less than {0} character',
                    },
                    //  odds: {
                    //     required: "Please enter game tip odds",
                    // },
                    //  units: {
                    //     required: "Please enter game tip units",
                    // },
                    //  profitLosForTips: {
                    //     required: "Please enter game tip profit loss for day",
                    // },
                    //  profitLosForDay: {
                    //     required: "Please enter game profit loss for day",
                    // },

                    //  tips: {
                    //     required: "Please enter game tip name",
                    // },
                    //  tips: {
                    //     required: "Please enter game tip name",
                    // },
                    //  tips: {
                    //     required: "Please enter game tip name",
                    // },
                    //  tips: {
                    //     required: "Please enter game tip name",
                    // },
                    // gameImage: {
                    //     required: "Please upload game image.",
                    // },
                   
                },
                submitHandler: function (form)
                {
                    form.submit();
                }
            });

            var gameId = $('#gameTipGameId').val();
            var packageId = $('#packageId').val();


            if(packageId && gameId){

            $.ajax({
                            url:  url+"/getPackageGame",
                            method: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {
                                'packageId': packageId,
                                'gameId': gameId,
                            },
                            success: function (result)
                            {
                               if(result.status == 1)
                               {
                                 $('#gameId').html(result.html);
                               }
                            }
                        });

    }              

      $('#packageId').on('change',function (e) {
           var packageId = $(this).val();
            if(packageId)
            {
                      $.ajax({
                            url:  url+"/getPackageGame",
                            method: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {
                                'packageId': packageId
                            },
                            success: function (result)
                            {
                               if(result.status == 1)
                               {
                                 $('#gameId').html(result.html);
                               }
                            }
                        });
            }
        });

        });
    </script>
@endsection
