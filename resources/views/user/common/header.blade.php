@php
 $user = \Auth::guard('web')->user();

 $userImage = !empty($user->profilePic) ? url('images/profiles/'.$user->profilePic ) : url('images/default.jpg');

@endphp

   <header class="flex-space-box">
      <div class="head-menu-parent-box">
         <div class="head-menu-item logo">
            <a href="{{ route('userDashboard') }}">
               <img src="{{ url('front/images/logo.svg') }}">
            </a>
         </div>
         <div class="head-menu-item {{ request()->is('user/dashboard') ? 'active' : '' }}">
            <a href="{{ route('userDashboard') }}">Subscription Plans</a>
         </div>
         <div class="head-menu-item {{ request()->is('user/payment-method') ? 'active' : '' }}">
            <a href="{{ route('paymentMethod') }}">Payment Method</a>
         </div>
         <div class="head-menu-item  {{ request()->is('user/discount-code') ? 'active' : '' }}">
         <a href="{{ route('discountCode') }}">Discounts</a>
         </div>
      </div>
      <div class="account-detail flex-space-box" onclick="toggleMenu()">
         <div class="users-box flex-space-box">
            <div class="user-name">{{ $user->name }}</div>
            <div class="user-thumb">
               <img src="{{ $userImage }}">
            </div>
            <!-- <div class="button-box no-display"> -->
            <div id="porfile-show" style="display: none" class="button-box light-button user-porf-list porfile-show {{ request()->is('user/payment-history') ? 'active' : '' }}">
               <a href="{{ route('paymentHistory') }}">Payment History</a>
               <hr>
               <div class="flex-space-box">
                  <!-- <button class="logout-btn">Logout </button> -->
                  <a  class="logout-btn" href="{{ route('userLogout') }}">Logout</a>
                  <img src="{{ url('front/images/icon-logout.svg') }}">
               </div>
            </div>
            
         </div>
      </div>
   </header>
<script>

function toggleMenu() {
  var menuBox = document.getElementById('porfile-show');  
    
  if(menuBox.style.display == "block") { 
    menuBox.style.display = "none";
  }
  else { 
    menuBox.style.display = "block";
  }
}
</script>