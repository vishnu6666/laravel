@extends('admin.layout.index')
@section('title') Send Mail Detail @endsection
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
            <h4 class="text-themecolor"></h4>
         </div>
         <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item">
                     <a href="{{ route('AdminDashboard')}}">Home</a>
                  </li>
                  <li class="breadcrumb-item">
                     <a href="{{ route('send-mail.index')}}">Send Mail</a>
                  </li>
                  <li class="breadcrumb-item active">Send Mail Detail</li>
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
      <!-- Row -->
                <div class="row">
                    <div class="col-12 m-t-0">
                        
                        <div class="card">
                            <div class="card-body collapse show">
                                <h4 class="card-title">Send Mail Detail </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
      <!-- .row -->
      <div class="row">
         <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-xs-6 b-r"> <strong>Email</strong>
                            <br>
                            <p class="text-muted">{{ $sendMailInfo->email ?? ''  }}</p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>Subject</strong>
                            <br>
                            <p class="text-muted">{{ $sendMailInfo->subject ?? '' }}</p>
                        </div>
                        @if($sendMailInfo->destinationPath != null)
                            <div class="col-md-3 col-xs-6"> <strong>Attachment</strong><br>
                              @foreach($sendMailInfo->destinationPath as $key => $attachment)
                                  @if($sendMailInfo->attachmentExt[$key] == 'jpeg' || $sendMailInfo->attachmentExt[$key] == 'jpg' || $sendMailInfo->attachmentExt[$key] == 'png' || $sendMailInfo->attachmentExt[$key] == 'gif')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                         <img src="{{URL::to('/mailAttachment/'.$sendMailInfo->attachments[$key])}}" alt="attachment" style="height:30px; width: 25px;">
                                      </a>
                                  @elseif($sendMailInfo->attachmentExt[$key] == 'zip' || $sendMailInfo->attachmentExt[$key] == 'zipx' || $sendMailInfo->attachmentExt[$key] == 'rar')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-archive fa-2x"></i>
                                      </a>
                                  @elseif($sendMailInfo->attachmentExt[$key] == 'mp3')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-music fa-2x"></i>
                                      </a>
                                  @elseif($sendMailInfo->attachmentExt[$key] == 'html' || $sendMailInfo->attachmentExt[$key] == 'htm' || $sendMailInfo->attachmentExt[$key] == 'css' || $sendMailInfo->attachmentExt[$key] == 'js')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-code fa-2x"></i>
                                      </a>
                                  @elseif($sendMailInfo->attachmentExt[$key] == 'xls' || $sendMailInfo->attachmentExt[$key] == 'xlsx' || $sendMailInfo->attachmentExt[$key] == 'csv' || $sendMailInfo->attachmentExt[$key] == 'ods')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-excel fa-2x"></i>
                                      </a>
                                  @elseif($sendMailInfo->attachmentExt[$key] == 'pdf')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-pdf fa-2x"></i>
                                      </a>
                                  @elseif($sendMailInfo->attachmentExt[$key] == 'ppt' || $sendMailInfo->attachmentExt[$key] == 'pptx')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-powerpoint fa-2x"></i>
                                      </a>
                                  @elseif($sendMailInfo->attachmentExt[$key] == 'mp4' || $sendMailInfo->attachmentExt[$key] == 'mkv' || $sendMailInfo->attachmentExt[$key] == '3gp' || $sendMailInfo->attachmentExt[$key] == 'avi')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-video fa-2x"></i>
                                      </a>
                                  @elseif($sendMailInfo->attachmentExt[$key] == 'doc' || $sendMailInfo->attachmentExt[$key] == 'docx' || $sendMailInfo->attachmentExt[$key] == 'odt')
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-word fa-2x"></i>
                                      </a>
                                  @else
                                      <a href="{{ $attachment }}" target="_blank" class="btn btn-large pull-right">
                                        <i class="fas fa-file-alt fa-2x"></i>
                                      </a>
                                  @endif
                              @endforeach
                            </div>
                        @else
                            <div class="col-md-3 col-xs-6"> <strong>Attachment</strong>
                              <br>
                              <p class="text-muted">No attachments</p>
                            </div>
                        @endif
                        <div class="col-md-2 col-xs-8"> <strong>Mail Date</strong>
                            <br>
                            <p class="text-muted">{{$sendMailInfo->mailSendDate }}</p>
                        </div>
                    </div>
                    <hr>
                    <strong>Message</strong>
                    <p class="m-t-30">{!! $sendMailInfo->message ?? ''  !!}</p>
                </div>
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

@endsection