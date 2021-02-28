
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" style="box-shadow: 7px 2px 13px 4px #cab7b7f2;">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li> <a class=" waves-effect waves-dark" href="{{ route('AdminDashboard') }}" aria-expanded="false"><i class="fas fa-tachometer-alt"></i><span class="hide-menu">Dashboard </span></a> </li>   
                {{-- <li class="{{ request()->is('admin/admins*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/admins*') || request()->is('admin/admins*') ? 'active' : '' }}" href="{{ route('admins.index') }}" aria-expanded="false"><i class=" fas fa-users"></i><span class="hide-menu">Admins  </span></a> </li> --}}
                
                <li class="{{ request()->is('admin/users*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/users*') || request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('users.index') }}" aria-expanded="false"><i class=" fas fa-user"></i><span class="hide-menu">Users  </span></a> </li>
                
                <li class="{{ request()->is('admin/subscription-plans*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/subscription-plans*') || request()->is('admin/subscription-plans*') ? 'active' : '' }}" href="{{ route('subscription-plans.index') }}" aria-expanded="false"><i class="far fa-money-bill-alt"></i><span class="hide-menu"> Plans </span></a> </li>
                
                <li class="{{ request()->is('admin/games*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/games*') || request()->is('admin/games*') ? 'active' : '' }}" href="{{ route('games.index') }}" aria-expanded="false"><i class=" fas fa-gamepad"></i><span class="hide-menu">Games  </span></a> </li>

                <li class="{{ request()->is('admin/packages*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/packages*') || request()->is('admin/packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}" aria-expanded="false"><i class="fas fa-window-restore"></i><span class="hide-menu">Packages  </span></a> </li>
                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-life-ring"></i><span class="hide-menu">Codes</span></a>
                    <ul aria-expanded="false" class="collapse">
                                    <li class="{{ request()->is('admin/groups*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/groups*') || request()->is('admin/groups*') ? 'active' : '' }}" href="{{ route('groups.index') }}" aria-expanded="false"><span class="hide-menu"> Groups Discount </span></a> </li>
                                    {{-- <li class="{{ request()->is('admin/promocode*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/promocode*') || request()->is('admin/promocode*') ? 'active' : '' }}" href="{{ route('promocode.index') }}" aria-expanded="false"><span class="hide-menu"> Promocodes  </span></a> </li> --}}
                                    <li class="{{ request()->is('admin/referCode*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/referCode*') || request()->is('admin/referCode*') ? 'active' : '' }}" href="{{ route('referCode.index') }}" aria-expanded="false"><span class="hide-menu"> Refercodes  </span></a> </li>
                    </ul>
                </li>
                   <li class="{{ request()->is('admin/manage-transaction*') || request()->is('admin/manage-transaction*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/manage-transaction*') || request()->is('admin/manage-transaction*') ? 'active' : '' }}" href="{{ route('manage-transaction.index') }}" aria-expanded="false"><i class="fas fa-dollar-sign"></i><span class="hide-menu">Manage Payments</span></a> </li>
                <li class="{{ request()->is('admin/messages*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/messages*') || request()->is('admin/show-message*') ? 'active' : '' }}" href="{{ route('messages.index') }}" aria-expanded="false"><i class="fas fa-inbox"></i><span class="hide-menu">Messages  </span></a> </li>
                <li class="{{ request()->is('admin/manage-tips*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/notifications*') || request()->is('admin/manage-tips*') ? 'active' : '' }}" href="{{ route('manage-tips.index') }}" aria-expanded="false"><i class="fas fa-strikethrough"></i><span class="hide-menu">Tips  </span></a> </li>
                
                 <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-life-ring"></i><span class="hide-menu">Report</span></a>
                    <ul aria-expanded="false" class="collapse">
                         <li class="{{ request()->is('admin/user-report*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/user-report*') ? 'active' : '' }}" href="{{ route('admin.userReportDetail') }}" aria-expanded="false"><span class="hide-menu">User Report</span></a> </li>
                        <li class="{{ request()->is('admin/game-report*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/game-report*') ? 'active' : '' }}" href="{{ route('admin.gameReport') }}" aria-expanded="false"><span class="hide-menu">Game Report</span></a> </li>
                        <li class="{{ request()->is('admin/game-trip-report*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/game-trip-report*') ? 'active' : '' }}" href="{{ route('admin.gameTripReport') }}" aria-expanded="false"><span class="hide-menu">Game Tips Report</span></a> </li>
                        <li class="{{ request()->is('admin/game-result*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/game-result*') ? 'active' : '' }}" href="{{ route('admin.gameResult') }}" aria-expanded="false"><span class="hide-menu">Game Result Report</span></a> </li>
                        <li class="{{ request()->is('admin/payment-transaction-report*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/payment-transaction-report*') ? 'active' : '' }}" href="{{ route('admin.paymentTransactionReport') }}" aria-expanded="false"><span class="hide-menu">Payment Transaction Report</span></a> </li>
                         <li class="{{ request()->is('admin/feedback-report*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/feedback-report*') ? 'active' : '' }}" href="{{ route('admin.feedbackReport') }}" aria-expanded="false"><span class="hide-menu">Complaints and Feedbacks Report</span></a> </li>

                    </ul>
                </li> 
                
                
                
                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-life-ring"></i><span class="hide-menu">Extra</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li class="{{ request()->is('admin/notification-templates*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/notifications*') || request()->is('admin/notifications*') ? 'active' : '' }}" href="{{ route('notification-templates.index') }}" aria-expanded="false"><span class="hide-menu">Notifications  </span></a> </li>
                        {{-- <li class="{{ request()->is('admin/manage-transaction*') || request()->is('admin/manage-transaction*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/manage-transaction*') || request()->is('admin/manage-transaction*') ? 'active' : '' }}" href="{{ route('manage-transaction.index') }}" aria-expanded="false"><span class="hide-menu">Manage Transaction</span></a> </li> --}}
                        <li class="{{ request()->is('admin/inquiry*') || request()->is('admin/inquiry*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/inquiry*') || request()->is('admin/inquiry*') ? 'active' : '' }}" href="{{ route('inquiry.index') }}" aria-expanded="false"><span class="hide-menu">Inquiry</span></a> </li>
                        <li class="{{ request()->is('admin/faqs*') || request()->is('admin/faqs*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/faqs*') || request()->is('admin/faqs*') ? 'active' : '' }}" href="{{ route('faqs.index') }}" aria-expanded="false"><span class="hide-menu">FAQs</span></a> </li>
                        <li><a href="{{ route('page_contents.index') }}">CMS </a></li>
                    </ul>
                </li>
            </ul>   
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>