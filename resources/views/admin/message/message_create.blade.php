@extends('admin.layout.index')
@section('title') Send Message for {{ $package->packageName }}@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ url('admin-assets/node_modules/summernote/dist/summernote-bs4.css') }}">
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
            <h4 class="text-themecolor">Send Message for {{ $package->packageName }}</h4>
         </div>
         <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                     <a href="{{ route('AdminDashboard')}}">Home</a>
                  </li>
                  <li class="breadcrumb-item">
                     <a href="{{ route('show.message.detail',['package' => $package->id])}}">Message</a>
                  </li>
                  <li class="breadcrumb-item active">Send Message for {{ $package->packageName }}</li>
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

                  <form class="form" name="faqForm" id="faqForm" method="post" enctype="multipart/form-data" action="{{ route('messages.store') }}">
                   
                     {{ csrf_field() }}

                     <input type="hidden" name="id" id="id" value="{{ $package->id }}" />

                     {{-- {{ (!empty($package) && $gameTip->packageId == $package->id) ? 'selected' : ''}} --}}

                      <div class="form-group m-t-40 row">
                                        <label for="gameName" class="col-2 col-form-label">Select game<span class="text-danger">*</span></label>
                                        <div class="col-5">
                                              <select name="gameId" id="gameId" class="form-control" required>
                                                <option value="">Select game name</option>
                                                 @foreach($getGame as $val)
                                                <option value="{{ $val->gameId }}" >{{ $val->gameName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                    
                     <div class="form-group m-t-40 row">
                        <label for="example-text-input" class="col-2 col-form-label">Message<span class="text-danger">*</span></label>
                        <div class="col-5">
                           <textarea name="content" id="content" rows="5" class="form-control summernote" placeholder="Please enter message">{{ $package->content ?? '' }}</textarea>
                           <label id="content-error-msg" class="text-danger" style="display:none;">Please enter Message</label>
                        </div>
                     </div>
                    
                     <div class="form-group row">
                        <div class="col-10 text-center">
                           <button type="submit" class="btn btn-success"> Create</button>
                            <a  href="{{ route('show.message.create',['package' =>$package->id])}}" class="btn btn-danger"> Reset</a>
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
<script src="{{ url('admin-assets/node_modules/summernote/dist/summernote-bs4.min.js') }}"></script> 	
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>

<script type="text/javascript">
    $(document).ready(function ()
    { 
        //override required method
    	$.validator.methods.required = function(value, element, param) {

          return (value == undefined) ? false : (value.trim() != '');
    	}
        
    	$('.summernote').summernote({
            height: 300, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false, // set focus to editable area after initializing summernote
            toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['insert', ['link']],
                        
                    // ['fontname', ['fontname']],
                        //['fontsize', ['fontsize']],
                        //['color', ['color']],
                        //['para', ['ul', 'ol', 'paragraph']],
                        //['height', ['height']],
                        //['table', ['table']],
                        //['insert', ['link', 'picture', 'hr']],
                        //['view', ['fullscreen']],   
                        //['help', ['help']]
                    ],
            callbacks: {
                onChange: function(contents, $editable) {

                  myElement = $('#content');
                  myElement.val(myElement.summernote('isEmpty') ? "" : contents);
                    
                  showContent();
                  
                }
              }
        });

        $(".form").validate({
        	//ignore: [],
            rules: {
                 gameId : {
                    required : true,
                },
                content : {
                    required : true,
                    maxlength:300,
                }
            },
            messages:{
                gameId : {
                    required : 'Please select game name',
                    
                },
                content : {
                    required : 'Please enter message',
                     maxlength: 'Message Content  must be less than 300 character',
                }
            },
            submitHandler: function(form) {
                form.submit();
            },
        });   
 
       $('.btn-success').click(function(event){
         summernote = $('.summernote').val();
         if(summernote==''){
            event.preventDefault()
            $('#content-error-msg').show();
         }else{
            $('#content-error-msg').hide();
             form.submit();
         }
     
      });
});
</script>
@endsection
