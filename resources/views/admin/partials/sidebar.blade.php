@php
// Add any PHP logic here if needed.
@endphp

<!-- Main Sidebar Container -->
<!-- Sidebar -->
<ul class="navbar-nav admin-sidebar bg-primary sidebar sidebar-light accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand sidebar-head d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
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

    <hr class="sidebar-divider">

    <!-- Nav Item - Manage Evaluation Form and Certificates (Dropdown) -->
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('evaluation.evaluationlist') || request()->routeIs('certificate.list') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseEvaluationCertificates" aria-expanded="{{ request()->routeIs('evaluation.evaluationlist') || request()->routeIs('certificate.list') ? 'true' : 'false' }}" aria-controls="collapseEvaluationCertificates">
            <span>Event Templates Management</span>
            <i class="fas fa-chevron-down float-right arrow-icon"></i>
        </a>
        <div id="collapseEvaluationCertificates" class="collapse {{ request()->routeIs('evaluation.evaluationlist') || request()->routeIs('certificate.list') ? 'show' : '' }}" aria-labelledby="headingEvaluationCertificates" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="{{ route('evaluation.evaluationlist') }}" class="collapse-item {{ request()->routeIs('evaluation.evaluationlist') ? 'active-item' : '' }}">Evaluation Forms</a>
                
                <!-- Unique thick divider -->
                <div class="thick-divider"></div>
                
                <a href="{{ route('certificate.list') }}" class="collapse-item {{ request()->routeIs('certificate.list') ? 'active-item' : '' }}">Certificate Templates</a>
            </div>
        </div>
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

    #collapseManagement, #collapseEvaluationCertificates {
        position: relative;
    }

    @media (max-width: 768px) {
        #collapseManagement, #collapseEvaluationCertificates {
            position: static;
        }
        .collapse-inner {
            width: auto;
            max-width: 80%;
            margin-left: 10px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth <= 768) {
            $('#accordionSidebar').collapse('hide');
        }
    });
</script>
