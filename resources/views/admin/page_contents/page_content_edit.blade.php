@extends('admin.layout.index')
@section('title') Edit  {{ $pageContent->name ?? '' }} Content @endsection
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
                        <h4 class="text-themecolor">Edit  {{ $pageContent->title ?? '' }} Content</h4>
                    </div>
                    
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('AdminDashboard')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('page_contents.index')}}">Page content list</a>
                                </li>
                                <li class="breadcrumb-item active"> Edit  {{ $pageContent->title ?? '' }} Content </li>
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
                                <form class="form" name="editPageContent" id="editPageContent" method="post" enctype="multipart/form-data" action="{{ route('page_contents.update', ['pageContent' => $pageContent->id]) }}">
                                @method('PUT')
                                    {{ csrf_field() }}
                                    @if( ($pageContent->isTitleShow) == 1 )
                                    <div class="form-group m-t-40 row">
                                        <label for="example-text-input" class="col-2 col-form-label">Title <span class="text-danger">*</span></label>
                                        <div class="col-7">
                                            <input class="form-control" type="text" name="title" id="title" tabindex="1" value="{{ $pageContent->title ?? '' }}" required>
                                        </div>
                                    </div>
                                    @endif
                                    @if( ($pageContent->isContentShow) == 1 )
                                    <div class="form-group m-t-40 row">
                                        <label for="example-text-input" class="col-2 col-form-label">Content<span class="text-danger">*</span></label>
                                        <div class="col-7">
                                            <textarea name="content" id="content" rows="10" class="form-control summernote" required>{{ $pageContent->content ?? '' }}</textarea>
                                           <label id="message-error-msg" class=""  style="display:none;color:red;">Please enter the content</label>
                                        </div>
                                        
                                    </div>
                                    @endif
                                    <div class="form-group row">
                                        <div class="col-10 text-center">
                                            <button type="submit" class="btn btn-success"> {{ empty($pageContent) ? 'Create' : 'Update' }}</button>
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
        	
            rules: {
            	title: {
                    required: true,
                    minlength: 3
                },
                content :{
                    required:true,
                    minlength: 10
                }
            },
            messages: {
            	title: {
                    required: 'Please enter title',
                },
                content:{
                    required:'Please enter the content'
                }
            },
            submitHandler: function (form)
            {
                if(showContent())
                {
                form.submit();
                }
                
            },
            invalidHandler: function(form, validator) 
            {
                    showContent();    
            }
        });

        function requiredDescription()
            {
                var content = $('.summernote').summernote('isEmpty');
               
                if(content)
                 {
                    $('#taskDescription-error-msg').show();
                    return false;
                 }  
               
                $('#taskDescription-error-msg').hide();
                   return true; 
            }


            function showContent()
            {
                var content = $(".summernote").summernote("code").replace(/&nbsp;|<\/?[^>]+(>|$)/g, "").trim();           
                if(content.length == 0)
                {
                    $('#message-error-msg').show();
                    return false;
                }  
            
                $('#message-error-msg').hide();
                return true; 
            } 
        
        //  function showContent()
        // {
        //     var content = $('.summernote').summernote('isEmpty');              
        //     if(content)
        //      {
        //         $('#message-error-msg').show();
        //         return false;
        //      }  
           
        //     $('#message-error-msg').hide();
        //        return true; 
        // } 
});
</script>

@endsection