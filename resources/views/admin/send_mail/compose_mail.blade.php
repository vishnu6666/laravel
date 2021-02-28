@extends('admin.layout.index')
@section('title') Compose Mail @endsection

@section('css')

<link rel="stylesheet" href="{{ url('admin-assets/node_modules/dropzone-master/dist/dropzone.css') }}">
<link rel="stylesheet" href="{{ url('admin-assets/node_modules/html5-editor/bootstrap-wysihtml5.css') }}" />
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
                        <h4 class="text-themecolor">Compose Mail</h4>
                    </div>
                    
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('AdminDashboard') }}">Home</a>
                                </li>
<!--                                 <li class="breadcrumb-item">
                                    <a href="{{ route('send-mail.index') }}">Send Mail</a>
                                </li> -->
                                <li class="breadcrumb-item active">Compose Mail</li>
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
                                <form class="form" name="composeMail" id="composeMail" method="post" enctype="multipart/form-data" action="{{ route('send-mail.store') }}">                                
                                  {{ csrf_field() }}

                                <div class="form-group m-t-40 row">
                                    <label for="emailId" class="col-2 col-form-label">Email Id<span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <input class="form-control" placeholder="Enter Email Id Here" type="email" name="email" id="email" tabindex="1">
                                    </div>
                                </div>
                                <div class="form-group m-t-40 row">
                                    <label for="subject" class="col-2 col-form-label">Subject<span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <input class="form-control" placeholder="Enter Subject Here" type="text" name="subject" id="subject" tabindex="2">
                                    </div>
                                </div>
                                <div class="form-group m-t-40 row">
                                    <label for="message" class="col-2 col-form-label">Message<span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <textarea name="message" id="message" class="summernote form-control" tabindex="3"></textarea>
                                        <label id="message-error-msg" class="text-danger" style="display:none;">Please enter message for send</label>
                                    </div>
                                </div>
                                <div class="form-group m-t-40 row">
                                    <label for="attachment" class="col-2 col-form-label">Attachment</label>
                                    <div class='dropzone col-10' id='dropzoneForm' name='dropzoneForm' action="#">
                                    </div>
                                </div>
                                <div class="m--hide form-group m-t-40 row">
                                    <input hidden="hidden" type="text" name="attachmentNames" id="attachmentNames" />
                                </div>
                                <div class="form-group row">
                                    <div class="col-10 text-center">
                                        <button type="submit" class="btn btn-success">Send Mail</button>
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
<script src="{{ url('admin-assets/node_modules/dropzone/dropzone.min.js') }}"></script>
<script src="{{ url('admin-assets/dist/js/jquery.validate.min.js') }}" ></script>
<script src="{{ url('admin-assets/node_modules/summernote/dist/summernote-bs4.min.js') }}"></script>

<script type="text/javascript">

//override required method
    	$.validator.methods.required = function(value, element, param) {

          return (value == undefined) ? false : (value.trim() != '');
    	}
        
$(".form").validate({
            //ignore: [],
            rules: {
                email: {
                    required: true,
                    maxlength:100,
                },
                subject: {
                    required: true,
                    minlength: 1,
                    maxlength: 150,
                },
            },
            messages: {
                email: {
                    required: 'Please enter a email address',
                    maxlength:'Email address must be less than {0} characters',
                },
                subject: {
                    required: 'Please enter subject for mail',
                    minlength: "Subject must be greater than {0} digits",
                    maxlength: "Subject must be less than {0} digits",
                },
            },
            submitHandler: function(form) {
                console.log(form);
                form.submit();
                if( showMailAttachmentValidation() == true)
                {
                    form.submit();
                }
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

    $(document).ready(function ()
    { 
        $('.summernote').summernote({
            height: 300, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false, // set focus to editable area after initializing summernote
            callbacks: {
                onChange: function(contents, $editable) {

                  myElement = $('#message');
                  myElement.val(myElement.summernote('isEmpty') ? "" : contents);
                  
                  showContent();
                }
              }
        });
    });

    Dropzone.autoDiscover = false;
    var attachmentArray = [];
    
    $("#dropzoneForm").dropzone({
        url: "{{ url(route('send-mail.uploadMedias')) }}",
        maxFiles: 5, // Maximum Number of Files
        maxFilesize: 25, // MB
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.doc,.docx,.html,.htm,.css,.js,.odt,.pdf,.xls,.xlsx,.ods,.ppt,.pptx,.txt,.csv,.zip,.zipx,.rar,.mp3,.mp4,.mkv,.3gp,.avi",
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        init: function() {
            this.on("maxfilesexceeded", function(file) {
                alert("You are only allow to upload 5 Attachments");
                var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            });
            
            this.on("thumbnail", function(file) {
                console.log(file);
                if (file.size > 25000000) {
                    alert("Your attachment size is greater than 25MB.");
                    this.removeFile(file);
                }
                file.acceptDimensions();
            });
        },
/*        accept: function(file, done) {
            file.acceptDimensions = done;
            console.log(file.acceptDimensions);
        },*/
        error: function(file, response) {
            this.removeFile(file);
            console.log(response);
        },
        success: function(file, response) {
            var attachmentNameList = $("#attachmentNames").val();

            if (attachmentNameList.trim() == '') {
                attachmentNameList = response.attachment;
            } else {
                attachmentNameList += ',' + response.attachment;
            }

            attachmentArray[file.name] = response.attachment;

            $("#attachmentNames").val(attachmentNameList);
            showMailAttachmentValidation();
        },

        removedfile: function(file) {
            var attachmentName = $("#attachmentNames").val();

            var mailAttachmentArray = attachmentName.split(',');

            attachmentName = attachmentName.replace(attachmentArray[file.name] + ',', "");
            attachmentName = attachmentName.replace(attachmentArray[file.name], "");
            $("#attachmentNames").val(attachmentName);
            showMailAttachmentValidation();
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        },
    });

    function showMailAttachmentValidation() {
        var attachmentNames = $('#attachmentNames').val().trim();

        if (attachmentNames == '') {

            $('#attachmentNames-error-msg').show();
            return false;
        }

        $('#attachmentNames-error-msg').hide();
        return true;
    }

    function showContent()
        {
            var content = $('.summernote').summernote('isEmpty');              
            if(content)
             {
                $('#message-error-msg').show();
                return false;
             }  
           
            $('#message-error-msg').hide();
               return true; 
        }    
</script>

@endsection