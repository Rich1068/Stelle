@php
@endphp
<!-- Main Sidebar Container -->
<!-- Sidebar -->
<ul class="navbar-nav admin-sidebar bg-primary sidebar sidebar-light accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand sidebar-head d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <i class="fas fa-th sidebar-icon"></i> <!-- Change the icon class as needed -->
        <div class="sidebar-brand-text mx-3">Dashboard</div>
    </a>

    <!-- Nav Item - Profile -->
    <li class="nav-item">
        <a href="{{ route('profile.profile') }}" class="nav-link {{ request()->routeIs('profile.profile') ? 'active' : '' }}">
            <span>My Profile</span>
            <i class="fas fa-user"></i>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Events -->
    <li class="nav-item">
        <a href="{{ route('event.list') }}" class="nav-link {{ request()->routeIs('event.list') ? 'active' : '' }}">
            <span>Events</span>
            <i class="fas fa-calendar-alt"></i>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - My Events -->
    <li class="nav-item">
        <a href="{{ route('event.myeventlist') }}" class="nav-link {{ request()->routeIs('event.myeventlist') ? 'active' : '' }}">
            <span>My Events</span>
            <i class="fas fa-calendar-check"></i>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Create Events -->
    <li class="nav-item">
        <a href="{{ route('event.create') }}" class="nav-link {{ request()->routeIs('event.create') ? 'active' : '' }}">
            <span>Create Events</span>
            <i class="fas fa-plus-circle"></i>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - My Certificates -->
    <li class="nav-item">
        <a href="{{ route('profile.mycertificates') }}" class="nav-link {{ request()->routeIs('profile.mycertificates') ? 'active' : '' }}">
            <span>My Certificates</span>
            <i class="fas fa-certificate"></i>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Nav Item - Manage Evaluation Form -->
    <li class="nav-item">
        <a href="{{ route('evaluation.evaluationlist') }}" class="nav-link {{ request()->routeIs('evaluation.evaluationlist') ? 'active' : '' }}">
            <span>Manage Evaluation Forms</span>
            <i class="fas fa-clipboard-list"></i>
        </a>
    </li>

    <hr class="sidebar-divider">
    <li class="nav-item">
        <a href="{{ route('certificate.list') }}" class="nav-link {{ request()->routeIs('certificate.list') ? 'active' : '' }}">
            <span>Manage Certificate Templates</span>
            <i class="fas fa-folder"></i>
        </a>
    </li>
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a href="{{ route('help.page') }}" class="nav-link {{ request()->routeIs('help.page') ? 'active' : '' }}">
            <span>FAQs</span>
            <i class="fas fa-question-circle"></i>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a href="#" class="nav-link" data-toggle="modal" data-target="#logoutModal">
            <span>Log Out</span>
            <i class="fas fa-sign-out-alt"></i> <!-- Changed the icon for clarity -->
        </a>
    </li>
</ul>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Log Out Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End of Sidebar -->
