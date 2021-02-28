@php
 $admin = \Auth::guard('admin')->user();

 $adminImage = !empty($admin->profilePic) ? url('images/profiles/'.$admin->profilePic ) : url('images/default.jpg');
@endphp
<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar">
   <nav class="navbar top-navbar navbar-expand-md navbar-dark" style="padding-right:10px;">
         <!-- ============================================================== -->
         <!-- Logo -->
         <!-- ============================================================== -->
         <div class="navbar-header" style="width: 218px !important;">
            <a class="navbar-brand" href="{{ route('subAdminDashboard') }}">
               <!-- Logo icon -->
               <b>
                  <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                  <!-- Dark Logo icon -->
                  {{-- <img src="{{ url('admin-assets/images/logo/logo_icon.png')}}" alt="homepage" style="width:28px;" class="dark-logo" /> --}}
                  <!-- Light Logo icon -->
                  {{-- <img src="{{ url('admin-assets/images/logo/logo_icon.png')}}" alt="homepage" style="width:28px;" class="light-logo" /> --}}
               </b>
               <!--End Logo icon -->
               <!-- Logo text -->
               <span class="hidden-sm-down">
                  <!-- dark Logo text -->
                  <img src="{{ url('admin-assets/images/logo/full_logo_new.png')}}" alt="homepage" class="dark-logo"  style="height: 31px;"/>
                  <!-- Light Logo text -->    
                  <img src="{{ url('admin-assets/images/logo/full_logo_new.png')}}" class="light-logo" alt="homepage" style="height: 31px;" />
               </span> 
            </a>
         </div>
         <!-- ============================================================== -->
         <!-- End Logo -->
         <!-- ============================================================== -->
         <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto">
               <!-- This is  -->
               <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
               <li class="nav-item"> <a class="nav-link sidebartoggler d-none waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
              
            </ul>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
             <!-- Comment -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                 {{--  <a class="nav-link dropdown-toggle waves-effect waves-dark Notification" id="Notification"  href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="    color: #ffff;"> <i class="ti-bell "></i>
                        @if($notificationCount > 0 )
                        <div class="notify"> 
                              <span id="point"  class=""></span> <span id="heartbit" class=""></span> 
                        </div>
                        @else
                        <div class="notify"> 
                           <span id="point"  class=""></span> <span id="heartbit" class=""></span> 
                        </div>
                        @endif   
                  </a>
                  <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                     <ul>
                        <li>
                              <div class="drop-title">Notifications</div>
                        </li>
                        <li>
                              <div class="message-center">

                                 @forelse($notifications as $notification)

                                 <a href="{{route('notifications.index') }}">
                                    <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                       <div class="mail-contnet">
                                          <h5>{{ $notification->title ?? '' }} </h5>
                                           <span class="time">
                                             {{ date('jS F Y H:i A',strtotime($notification->createdAt))}}
                                          </span> 
                                       </div>
                                 </a>
                                 @empty
                                 @endforelse
                              </div>
                        </li>
                        <li>
                              <a class="nav-link text-center link" href="{{ route('notifications.index') }}">
                                <span class="check-all-notification">
                                  <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i>
                                </span>
                              </a>
                        </li>
                     </ul>
                  </div>--}}
            </li>
            <!-- ============================================================== -->
            <!-- End Comment -->
            <!-- ============================================================== -->
               
               <!-- ============================================================== -->
               <!-- User Profile -->
               <!-- ============================================================== -->
               <li class="nav-item dropdown u-pro">
                     <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ $adminImage  }}" alt="user" class=""> <span style="    color: #ffff;" class="hidden-md-down font-weight-bold">{{ $admin->name }} &nbsp;
                           <i class="fa fa-angle-down"></i></span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right animated flipInY">
                        <!-- text-->
                        <a href="{{ route('EditSubAdminProfile') }}" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                        <!-- text-->
                        <a href="{{ route('EditSubAdminChangePassword')}}" class="dropdown-item"><i class="ti-wallet"></i> Change Password</a>
                        <!-- text-->
                        <a href="{{ route('subAdminLogout') }}" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                        <!-- text-->
                     </div>
               </li>
               <!-- ============================================================== -->
               <!-- End User Profile -->
               <!-- ============================================================== -->
               
            </ul>
         </div>
   </nav>
</header>