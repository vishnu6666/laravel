@extends('admin.layout.index')
@section('title') {{ empty($inquiry) ? 'Inquiry reply' : 'Inquiry reply' }} @endsection
@section('css')
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
            <h4 class="text-themecolor">{{ empty($inquiry) ? 'Inquiry reply' : 'Inquiry reply' }} </h4>
         </div>
         <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                     <a href="{{ route('AdminDashboard')}}">Home</a>
                  </li>
                  <li class="breadcrumb-item">
                     <a href="{{ route('inquiry.index')}}">Inquiry list</a>
                  </li>
                  <li class="breadcrumb-item active">{{ empty($inquiry) ? 'Inquiry reply' : 'Inquiry reply' }}  </li>
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
                  
                  @if(!empty($inquiry))

                  <form class="form" name="faqForm" id="faqForm" method="post" enctype="multipart/form-data" action="{{ route('inquiry.update', ['inquiry' => $inquiry->id]) }}">
                     @method('PUT')
                     @endif
                     {{ csrf_field() }}
                     <input type="hidden" name="id" id="id" value="{{ $inquiry->id ?? '' }}" />

                     <div class="form-group row">
                        <label for="faq" class="col-2 col-form-label">To<span class="text-danger">*</span></label>
                        <div class="col-10">
                         
                           <input name="email" placeholder="Please enter email" value="{{ $userdata->email ?? '' }}" required id="email" class="form-control" disabled>
                           
                        </div>
                     </div>
                     
                     <div class="form-group row">
                        <label for="faq" class="col-2 col-form-label">Subject<span class="text-danger">*</span></label>
                        <div class="col-10">
                         
                           <input name="subject" rows="2" value="{{ $inquiry->subject ?? '' }}" placeholder="Please enter subject" required id="subject" class="form-control" disabled>
                           
                        </div>
                     </div>

                     <div class="form-group row">
                        <label for="message" class="col-2 col-form-label">Message<span class="text-danger">*</span></label>
                        <div class="col-10">
                         
                           <textarea name="message" rows="10" placeholder="Please enter message" required id="message" class=" form-control" disabled>{{ $inquiry->message ?? '' }}</textarea>
                           
                        </div>
                     </div>

                     <div class="form-group row">
                        <label for="message" class="col-2 col-form-label">Reply <span class="text-danger">*</span></label>
                        <div class="col-10">
                         
                           <textarea name="message" rows="10" placeholder="Please enter message"  required id="message" class=" form-control" <?php if($inquiry->isReply == 1 ){ echo 'disabled' ;} ?>>{{ $inquiry->reply_message ?? '' }}</textarea>
                           
                        </div>
                     </div>

                     <div class="form-group row">
                     <label for="message" class="col-2 col-form-label"></label>
                        <div class="col-10 text-left">
                           <button type="submit" class="btn btn-success Reply" > {{ empty($inquiry) ? 'Reply' : 'Reply' }}</button>

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
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>

<script type="text/javascript">

$(document).ready(function() {
    var isReplied = {{ $inquiry->isReply }};
    if(isReplied === 1){
       $('.Reply').hide();
    }
});
      $(".form").validate({
                rules: {
                    subject: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    message: {
                        required: true,
                         maxlength:400,
                    },
                },
                messages: {
                    subject: {
                        required:"Please enter subject",
                    },
                    email: {
                        required:"Please enter email",
                    },
                    message: {
                        required:"Please enter message",
                         maxlength:"The Inquiry message may not be greater than 400 characters.",
                    },
                },
                submitHandler: function (form)
                {
                    form.submit();
                },

                invalidHandler: function(form, validator) {

                }
            });
</script>
@endsection
