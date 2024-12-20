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

    <!-- Nav Item - My Certificates -->
    <li class="nav-item">
        <a href="{{ route('profile.mycertificates') }}" class="nav-link {{ request()->routeIs('profile.mycertificates') ? 'active' : '' }}">
            <span>My Certificates</span>
            <i class="fas fa-certificate"></i>
        </a>
    </li>

    <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('organization.list') || request()->routeIs('organization.mylist') || request()->routeIs('organization.create') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseOrganization" aria-expanded="{{ request()->routeIs('organization.list') || request()->routeIs('organization.mylist') || request()->routeIs('organization.create') ? 'true' : 'false' }}" aria-controls="collapseOrganization">
                <span>Organization</span>
                <i class="fas fa-chevron-down float-right arrow-icon"></i>
            </a>
            <div id="collapseOrganization" class="collapse {{ request()->routeIs('organization.list') || request()->routeIs('organization.mylist') || request()->routeIs('organization.create') ? 'show' : '' }}" aria-labelledby="headingOrganization" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a href="{{ route('organization.list') }}" class="collapse-item {{ request()->routeIs('organization.list') ? 'active-item' : '' }}">Organization List</a>
                    <div class="thick-divider"></div>
                    <a href="{{ route('organization.mylist') }}" class="collapse-item {{ request()->routeIs('organization.mylist') ? 'active-item' : '' }}">My Organizations</a>
                </div>
            </div>
        </li>

    <hr class="sidebar-divider">

    <!-- Nav Item - Logout -->
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

<style>
    .thick-divider {
        height: 2px;
        background-color: #001e54;
        margin: 5px 0;
        border-radius: 2px;
    }

    .admin-sidebar .nav-link.active, .collapse-item.active-item {
        color: white !important;
        background-color: #002a60 !important;
        font-weight: bold;
    }

    .arrow-icon {
        font-size: 0.75rem;
        color: #002a60;
        transition: transform 0.3s;
    }

    .nav-link.collapsed::after,
    .nav-link::after {
        display: none !important;
        content: none !important;
    }

    .nav-link[aria-expanded="true"] .arrow-icon {
        transform: rotate(180deg);
    }

    #collapseOrganization {
        position: relative;
    }

    @media (max-width: 768px) {
        #collapseOrganization{
            position: static;
        }
        .collapse-inner {
            width: auto;
            max-width: 80%;
            margin-left: 10px;
        }
    }
</style>
<!-- End of Sidebar -->
