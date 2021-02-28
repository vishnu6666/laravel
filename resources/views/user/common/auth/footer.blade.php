
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{ url('/admin-assets/node_modules/jquery/jquery-3.2.1.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ url('/admin-assets/node_modules/popper/popper.min.js')}}"></script>
<script src="{{ url('/admin-assets/node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!--Custom JavaScript -->

@yield('js')

<script type="text/javascript">
    $(function() {
        $(".preloader").fadeOut();
    });
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });
    // ==============================================================
    // Login and Recover Password
    // ==============================================================
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    // for alert message disppper after 3 sec
    $(document).ready(function(){
        setTimeout(function(){$('.alertdisapper').fadeOut();}, 4000);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var url = $('meta[name="baseUrl"]').attr('content');
</script>