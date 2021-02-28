    
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{ url('admin-assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
<!-- Bootstrap popper Core JavaScript -->
<script src="{{ url('admin-assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ url('admin-assets/js/perfect-scrollbar.jquery.min.js') }}"></script>
<!--Wave Effects -->
<script src="{{ url('admin-assets/js/waves.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ url('admin-assets/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
{{-- <script src="{{ url('admin-assets/js/custom.min.js') }}"></script> --}}
<script src="{{ url('admin-assets/js/jquery.validate.min.js') }}" ></script>

<script src="{{ url('admin-assets/js/custom.js') }}"></script>
{{-- <script src="{{ url('admin-assets/js/common.js') }}"></script> --}}

<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!--morris JavaScript -->
<script src="{{ url('admin-assets/node_modules/raphael/raphael-min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/morrisjs/morris.min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<!-- Popup message jquery -->
<script src="{{ url('admin-assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<script src="{{ url('admin-assets/js/polyfill.min.js')}}"></script>

<!-- This JS for multiple select box -->
<script src="{{ url('admin-assets/node_modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ url('admin-assets/node_modules/multiselect/js/jquery.multi-select.js') }}"></script>


<script>
$(".select2").select2();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

     //override required method
        $.validator.methods.required = function(value, element, param) {
            
            return (value == undefined) ? false : (value.trim() != '');
        }

    // for alert message disppper after 3 sec
    $(document).ready(function(){
        setTimeout(function(){$('.alertdisapper').fadeOut();}, 4000);

    });

    $( ".blank_link" ).click(function( event ) {
      event.preventDefault();
     
    });

  
    // Open Video Modal
    $(document).on("click",'.videoModal',function(event) {
        $('#videoModal').modal('show');

         var url = $(this).data('url');
         url = url.split('v=')[1];
         $("#teamVideoPlayer")[0].src =  "https://www.youtube.com/embed/" + url;
         $("#teamVideoPlayer").show();
         
    });
    
    $(function () {
        "use strict";
        $(function () {
            $(".preloader").fadeOut()
        }), jQuery(document).on("click", ".mega-dropdown", function (e) {
            e.stopPropagation()
        });
        var e = function () {
            (window.innerWidth > 0 ? window.innerWidth : this.screen.width) < 1170 ? ($("body").addClass("mini-sidebar"), $(".navbar-brand span").hide(), $(".sidebartoggler i").addClass("ti-menu")) : ($("body").removeClass("mini-sidebar"), $(".navbar-brand span").show());
                        var e = (window.innerHeight > 0 ? window.innerHeight : this.screen.height) - 1;
            (e -= 55) < 1 && (e = 1), e > 55 //&& $(".page-wrapper").css("min-height", e + "px") // removed when i face issue footer not showing in data not available into page

        };
        $(window).ready(e), $(window).on("resize", e), $(".sidebartoggler").on("click", function () {
            $("body").hasClass("mini-sidebar") ? ($("body").trigger("resize"), $("body").removeClass("mini-sidebar"), $(".navbar-brand span").show()) : ($("body").trigger("resize"), $("body").addClass("mini-sidebar"), $(".navbar-brand span").hide())
        }), $(".nav-toggler").click(function () {
            $("body").toggleClass("show-sidebar"), $(".nav-toggler i").toggleClass("ti-menu"), $(".nav-toggler i").addClass("ti-close")
        }), $(".search-box a, .search-box .app-search .srh-btn").on("click", function () {
            $(".app-search").toggle(200)
        }), $(".right-side-toggle").click(function () {
            $(".right-sidebar").slideDown(50), $(".right-sidebar").toggleClass("shw-rside")
        }), $(".floating-labels .form-control").on("focus blur", function (e) {
            $(this).parents(".form-group").toggleClass("focused", "focus" === e.type || this.value.length > 0)
        }).trigger("blur"), $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        }), $(function () {
            $('[data-toggle="popover"]').popover()
        }), $(".scroll-sidebar, .right-side-panel, .message-center, .right-sidebar").perfectScrollbar(), $('#chat, #msg, #comment, #todo').perfectScrollbar(), $("body").trigger("resize"), $(".list-task li label").click(function () {
            $(this).toggleClass("task-done")
        }), $('a[data-action="collapse"]').on("click", function (e) {
            e.preventDefault(), $(this).closest(".card").find('[data-action="collapse"] i').toggleClass("ti-minus ti-plus"), $(this).closest(".card").children(".card-body").collapse("toggle")
        }), $('a[data-action="expand"]').on("click", function (e) {
            e.preventDefault(), $(this).closest(".card").find('[data-action="expand"] i').toggleClass("mdi-arrow-expand mdi-arrow-compress"), $(this).closest(".card").toggleClass("card-fullscreen")
        }), $('a[data-action="close"]').on("click", function () {
            $(this).closest(".card").removeClass().slideUp("fast")
        });
        var a, i = ["skin-default", "skin-green", "skin-red", "skin-blue", "skin-purple", "skin-megna", "skin-default-dark", "skin-green-dark", "skin-red-dark", "skin-blue-dark", "skin-purple-dark", "skin-megna-dark"];

        function s(e) {
            var a, s;
            return $.each(i, function (e) {
                $("body").removeClass(i[e])
            }), $("body").addClass(e), a = "skin", s = e, "undefined" != typeof Storage ? localStorage.setItem(a, s) : window.alert("Please use a modern browser to properly view this template!"), !1
        }(a = function (e) {
            if ("undefined" != typeof Storage) return localStorage.getItem(e);
            window.alert("Please use a modern browser to properly view this template!")
        }("skin")) && $.inArray(a, i) && s(a), $("[data-skin]").on("click", function (e) {
            $(this).hasClass("knob") || (e.preventDefault(), s($(this).data("skin")))
        }), $("#themecolors").on("click", "a", function () {
            $("#themecolors li a").removeClass("working"), $(this).addClass("working")
        })
    });


     //For send notification 
    $(document).on("click",'.btnSendNotification',function(event) {

        event.preventDefault();
        var title = $(this).data('title');
        var url = $(this).data('url');
        var form = '';
        swal({
            title: "Are you sure?",
            text: "Are you sure want to send notification to all users",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Send it!",
        }).then(function (result) {

            if(result.value)
        {
            form = $('#sendNotificationForm');
            form.attr('action', url);
            form.submit();
        }
    });
    });

    //For send notification 
    $(document).on("click",'.clearAllNotification',function(event) {

        
        event.preventDefault();
        var title = $(this).data('title');
        var url = $(this).data('url');
        var form = '';
        swal({
            title: "Are you sure?",
            text: "Are you sure want to clear all notifications",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Clear it!",
        }).then(function (result) {

            if(result.value)
        {
            form = $('#clearAllNotifcationForm');
            form.attr('action', url);
            form.submit();
        }
    });
    });


    var url = $('meta[name="baseUrl"]').attr('content');

    //For delete
    $(document).on("click",'.btnDelete',function(event) {

        event.preventDefault();
        var title = $(this).data('title');
        var url = $(this).data('url');
        var form = '';
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this "+title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
      }).then(function (result) {

            if(result.value)
        {
            form = $('#deleteForm');
            form.attr('action', url);
            form.submit();
        }
    });
    });

 
    // For update status
    $(document).on("click",'.btnChangeStatus',function(event) {

        event.preventDefault();
        var url = $(this).data('url');
        
        swal({
        title: "Are you sure?",
        text: "",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Change it!",
            }).then(function (result) {
                if(result.value)
                {
                    form = $('#changeStatusForm');
                    form.attr('action', url);
                    form.submit();
                }
            });
    });

    $(document).on("keypress",'.numeric',function(evt) {

        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31  && (charCode < 48 || charCode > 57))
            return false;

        return true;
    });

    $(document).on("keypress",'.decimalOnly',function(evt) {

        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57))
            return false;

        return true;
    });

    // for alert message disppper after 3 sec
    $(document).ready(function(){
        setTimeout(function(){$('.alertdisapper').fadeOut();}, 4000);
    });

    var url = $('meta[name="baseUrl"]').attr('content');

// notification status update 
$(".Notification,#Notification").click(function (e) {

    updateNotifications();
});

$('.check-all-notification').on('click', function () {

});

function updateNotifications()
{   
    
   var Ajx = $.ajax({
       url:url+'/admin/update-status/notifications',
       method: "POST",
       async:false,
       success: function (result) {
           
         return true;
       },
   });

 // console.log('update notification');
   //latestNotification();
}   

// is notification available using ajax 
var interval =5000;
function isNotificationAvailable()
{   

   var Ajx = $.ajax({

       url:url+'/admin/notifications/isavailable',
       method: "POST",
       success: function (result) {
           
           var count=  result.notificationCount;
            
           if( count > 0)
           {
               $("#heartbit").addClass("heartbit");
               $("#point").addClass("point");

           }else{
               $("#heartbit").removeClass("heartbit");
               $("#point").removeClass("point");
           }
           
           //setTimeout(isNotificationAvailable, interval);
       },
      
   });
}   

//setTimeout(isNotificationAvailable, interval);

$('.navbar-nav .ti-bell').click(function(){
   //console.log('latest notification');
   latestNotification();
});

// get latest notification available using ajax
function latestNotification()
{
    console.log('latest notification');
   var Ajx = $.ajax({

       url:url+'/admin/notifications/getLatestNotification',
       method: "POST",
       success: function (result) {

           $('.check-all-notification').show();
           if(result.notificationCount < 5){
               $('.check-all-notification').hide();
           }
           var html = '';
           
           if(result.status == 1)
           {
                result.notifications.forEach(function(entry){
                
                
                html+= '<a href="{{ url('admin/notifications/') }}/"'+entry.id+'>'+
                    '<div class="btn btn-success btn-circle"><i class="ti-calendar"></i>' +
                    '</div>'+
                    '<div class="mail-contnet">'+
                    '<h5>'+entry.title+' </h5>'+
                    '<span class="time">'
                    +entry.createdAt+
                    '</span>'+
                    '</div>'+
                    '</a>';
                });
           }
           else
           {
                 html+=  '<div class=""><div class="text-center">'+
                       '<h5>Not Availabel</h5>'+
                       '</div></div>';      
           }
           $('.message-center').html(html);
           
       },

   });
}

</script>
@yield('js')