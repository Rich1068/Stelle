@php
@endphp
<!-- Main Sidebar Container -->
<!-- Sidebar -->
<ul class="navbar-nav admin-sidebar bg-primary sidebar sidebar-light accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand sidebar-head d-flex align-items-center justify-content-center" href="{{ route('user.dashboard') }}">
    <i class="fas fa-th sidebar-icon"></i> <!-- Change the icon class as needed -->
    <div class="sidebar-brand-text mx-3">Dashboard</div>
</a>

    <!-- Nav Item - Profile -->
    <li class="nav-item">
        <a href="{{ route('profile.profile') }}" class="nav-link">
            <span>My Profile</span>
            <i class="fas fa-user"></i>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Events -->
    <li class="nav-item">
        <a href="{{ route('event.list') }}" class="nav-link">
            <span>Events</span>
            <i class="fas fa-calendar-alt"></i>
        </a>
    </li>



    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - My Certificates -->
    <li class="nav-item">
        <a href="{{ route('profile.mycertificates') }}" class="nav-link">
            <span>My Certificates</span>
            <i class="fas fa-certificate"></i>
        </a>
    </li>

</ul>
<!-- End of Sidebar -->