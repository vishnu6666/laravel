@extends('admin.layout.index')
@section('title') {{ empty($faq) ? 'Create FAQ' : 'Edit FAQ' }} @endsection
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
            <h4 class="text-themecolor">{{ empty($faq) ? 'Create FAQ' : 'Edit FAQ' }} </h4>
         </div>
         <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                     <a href="{{ route('AdminDashboard')}}">Home</a>
                  </li>
                  <li class="breadcrumb-item">
                     <a href="{{ route('faqs.index')}}">FAQs</a>
                  </li>
                  <li class="breadcrumb-item active">{{ empty($faq) ? 'Create FAQ' : 'Edit FAQ' }}  </li>
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
                  
                  @if(empty($faq))
                  <form class="form" name="faqForm" id="faqForm" method="post" enctype="multipart/form-data" action="{{ route('faqs.store') }}">
                     @else
                  <form class="form" name="faqForm" id="faqForm" method="post" enctype="multipart/form-data" action="{{ route('faqs.update', ['faq' => $faq->id]) }}">
                     @method('PUT')
                     @endif
                     {{ csrf_field() }}
                     <input type="hidden" name="id" id="id" value="{{ $faq->id ?? '' }}" />
                     
                     {{-- <div class="form-group row">
                        <label for="topicId" class="col-2 col-form-label">FAQ Topic Type<span class="text-danger">*</span></label>
                        <div class="col-3">
                           <select class="form-control" name="topicId" id="topicId" tabindex="1">
                                <option value="">Select Topic Type</option>
                                @forelse($faqTypes as $faqType)
                                <option value="{{ $faqType->id ?? '' }}" {{ (!empty($faq) && $faqType->id == $faq->topicId) ? 'selected' : '' }}>{{ $faqType->value ?? '' }}</option>
                                @empty
                                @endforelse
                           </select>
                        </div>
                    </div> --}}
                     
                     <div class="form-group row">
                        <label for="faq" class="col-2 col-form-label">Question<span class="text-danger">*</span></label>
                        <div class="col-10">
                         
                           <textarea name="question" rows="2" placeholder="Please enter question" required id="question" class="form-control">{{ $faq->question ?? '' }}</textarea>
                           
                        </div>
                     </div>

                     <div class="form-group row">
                        <label for="title" class="col-2 col-form-label">Title<span class="text-danger">*</span></label>
                        <div class="col-10">
                         
                           <textarea name="title" rows="2" placeholder="Please enter title" required id="title" class="form-control">{{ $faq->title ?? '' }}</textarea>
                           
                        </div>
                     </div>

                     <div class="form-group row">
                        <label for="faq" class="col-2 col-form-label">Answer<span class="text-danger">*</span></label>
                        <div class="col-10">
                                <textarea name="answer" rows="3" placeholder="Please enter answer" required id="answer" class="summernote form-control">{{ $faq->answer ?? '' }}</textarea>
                                <label id="message-error-msg" class="text-danger" style="display:none;">Please enter answer</label>
                        </div>
                    </div>
                    
                     <div class="form-group m-t-40 row">
                        <label for="status" class="col-2 col-form-label">Status<span class="text-danger">*</span></label>
                        <div class="custom-control custom-radio">
                           <input type="radio" id="status1" name="status" value="1"
                           class="custom-control-input" {{ (!empty($faq) && $faq->isActive == 1) ? 'checked' : ''}} {{ (empty($faq)) ? 'checked' : '' }} >
                           <label class="custom-control-label" for="status1">Active</label>
                        </div>
                        &nbsp;&nbsp;&nbsp;
                        <div class="custom-control custom-radio">
                           <input type="radio" id="status2" name="status" value="0" 
                           class="custom-control-input" {{ (!empty($faq) && $faq->isActive == 0) ? 'checked' : ''}}>     
                           <label class="custom-control-label" for="status2">Inactive</label>
                        </div>
                     </div>

                     <div class="form-group row">
                        <div class="col-10 text-center">
                           <button type="submit" class="btn btn-success"> {{ empty($faq) ? 'Create' : 'Update' }}</button>

                           @if(!empty($faq)) 
                                            
                              <a  href="{{ route('faqs.edit',[ 'faq' => $faq->id] )}}" class="btn btn-danger"> Reset</a>
                           @else

                              <a  href="{{ route('faqs.create')}}" class="btn btn-danger"> Reset</a>
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
<script src="{{ url('admin-assets/node_modules/summernote/dist/summernote-bs4.min.js') }}"></script> 	
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>
<script type="text/javascript">

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
                rules: {
                    topicId:{
                        required:true,
                    },
                    question: {
                        required: true,
                        maxlength:100,
                    },
                    title: {
                        required: true,
                        maxlength:100,
                    },
                    answer: {
                        required: true,
                        maxlength:400,
                    },
                },
                messages: {
                    topicId:{
                        required:"Please select FAQ topic",
                    },
                    question: {
                        required:"Please enter question",
                         maxlength:"Faq question must not be greater than {0} characters.",
                    },
                    title: {
                        required:"Please enter title",
                         maxlength:"Faq title must not be greater than {0} characters.",
                    },
                    answer: {
                        required:"Please enter answer",
                          maxlength:"Faq answer must not be greater than {0} characters.",
                    },
                },
                submitHandler: function (form)
                {
                    if(showContent())
                     {
                        form.submit();
                     }
                },

                invalidHandler: function(form, validator) {
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
</script>
@endsection
