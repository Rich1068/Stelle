@php
// Add any PHP logic here if needed.
@endphp

<!-- Main Sidebar Container -->
<!-- Sidebar -->
<ul class="navbar-nav admin-sidebar bg-primary sidebar sidebar-light accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand sidebar-head d-flex align-items-center justify-content-center" href="{{ route('super_admin.dashboard') }}">
        <i class="fas fa-th sidebar-icon"></i>
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

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Management of System (Dropdown for User and Event Management) -->
    <li class="nav-item d-none d-md-block"> <!-- Hidden on mobile -->
        <a class="nav-link {{ request()->routeIs('super_admin.userlist') || request()->routeIs('superadmin.eventlist') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseManagement" aria-expanded="{{ request()->routeIs('super_admin.userlist') || request()->routeIs('superadmin.eventlist') ? 'true' : 'false' }}" aria-controls="collapseManagement">
            <span>User and Event Management</span> <!-- Removed the .small class -->
            <i class="fas fa-chevron-down float-right arrow-icon"></i>
        </a>
        <div id="collapseManagement" class="collapse {{ request()->routeIs('super_admin.userlist') || request()->routeIs('superadmin.eventlist') ? 'show' : '' }}" aria-labelledby="headingManagement" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="{{ route('super_admin.userlist') }}" class="collapse-item {{ request()->routeIs('super_admin.userlist') ? 'active-item' : '' }}">Manage All Users</a>
                <a href="{{ route('superadmin.eventlist') }}" class="collapse-item {{ request()->routeIs('superadmin.eventlist') ? 'active-item' : '' }}">Manage All Events</a>
            </div>
        </div>
    </li>

    <!-- Display Manage All Users and Manage All Events links directly on mobile -->
    <li class="nav-item d-md-none"> <!-- Visible only on mobile -->
        <a href="{{ route('super_admin.userlist') }}" class="nav-link {{ request()->routeIs('super_admin.userlist') ? 'active' : '' }}">
            <span>Manage All Users</span>
            <i class="fas fa-users"></i>
        </a>
        <a href="{{ route('superadmin.eventlist') }}" class="nav-link {{ request()->routeIs('superadmin.eventlist') ? 'active' : '' }}">
            <span>Manage All Events</span>
            <i class="fas fa-calendar-alt"></i>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Manage Evaluation Form -->
    <li class="nav-item">
        <a href="{{ route('evaluation.evaluationlist') }}" class="nav-link {{ request()->routeIs('evaluation.evaluationlist') ? 'active' : '' }}">
            <span>Manage Evaluation Forms</span>
            <i class="fas fa-clipboard-list"></i>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Nav Item - Manage Certificate Templates -->
    <li class="nav-item">
        <a href="{{ route('certificate.list') }}" class="nav-link {{ request()->routeIs('certificate.list') ? 'active' : '' }}">
            <span>Manage Certificate Templates</span>
            <i class="fas fa-folder"></i>
        </a>
    </li>

    <hr class="sidebar-divider">
    
    <!-- Nav Item - FAQs -->
    <li class="nav-item">
        <a href="{{ route('help.page') }}" class="nav-link {{ request()->routeIs('help.page') ? 'active' : '' }}">
            <span>FAQs</span>
            <i class="fas fa-question-circle"></i>
        </a>
    </li>
    
    <hr class="sidebar-divider">

    <!-- Nav Item - Log Out -->
    <li class="nav-item">
        <a href="#" class="nav-link" data-toggle="modal" data-target="#logoutModal">
            <span>Log Out</span>
            <i class="fas fa-sign-out-alt"></i>
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

<style>
    @media (max-width: 768px) {
        /* Additional styles for mobile */
        .collapse-item {
            display: none; /* Hide collapse items on mobile */
        }
    }

    .admin-sidebar .nav-link.active, .collapse-item.active-item {
        color: white !important;
        background-color: #002a60 !important; /* Custom dark blue background */
        font-weight: bold;
    }

    /* Smaller and dynamic arrow */
    .arrow-icon {
        font-size: 0.75rem; /* Smaller size */
        color: #002a60; /* Dark blue color */
        transition: transform 0.3s; /* Smooth transition */
    }

    /* Hide default Bootstrap arrow on collapsed items */
    .nav-link.collapsed::after,
    .nav-link::after {
        display: none !important; /* Hide Bootstrap default arrow */
        content: none !important;
    }

    /* Rotate custom arrow when expanded */
    .nav-link[aria-expanded="true"] .arrow-icon {
        transform: rotate(180deg); /* Arrow points up when expanded */
    }
</style>
