
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" style="box-shadow: 7px 2px 13px 4px #cab7b7f2;">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li> <a class=" waves-effect waves-dark" href="{{ route('subAdminDashboard') }}" aria-expanded="false"><i class="fas fa-tachometer-alt"></i><span class="hide-menu">Dashboard </span></a> </li>   
        
                {{-- <li class="{{ request()->is('admin/manage-tips*') ? 'active' : '' }}"> <a class=" waves-effect waves-dark {{ request()->is('admin/notifications*') || request()->is('admin/manage-tips*') ? 'active' : '' }}" href="{{ route('manage-tips.index') }}" aria-expanded="false"><i class=" fas fa-users"></i><span class="hide-menu">Tips  </span></a> </li> --}}
            
            </ul>   
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>