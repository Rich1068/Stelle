@php
  @endphp
  <!-- Main Sidebar Container -->
          <!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
 
<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">dashboard</div>
</a>
 
<!-- Divider -->
<hr class="sidebar-divider my-0">
 
<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a href="{{route('user.dashboard')}}" class="nav-link">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>
 
<!-- Divider -->
<hr class="sidebar-divider">
 
<!-- Heading -->
<div class="sidebar-heading">
User Management
</div>
 
<li class="nav-item">
  <a href="{{route('profile.profile')}}" class="nav-link">
        <i class="fas fa-fw fa-table"></i>
        <span>My Profile</span></a>
</li>

<hr class="sidebar-divider">
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEvents" aria-expanded="true" aria-controls="collapseEvents">
    <i class="fas fa-fw fa-table"></i>
    <span>Events & Conferences</span>
  </a>
  <div id="collapseEvents" class="collapse" aria-labelledby="headingEvents" data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
      <h6 class="collapse-header">Event Management:</h6>
      <a class="collapse-item" href="{{ route('event.list') }}">All Events</a>
      <!-- <a class="collapse-item" href="{{ route('event.create') }}">Create Event</a> -->
    </div>
  </div>
</li>
<hr class="sidebar-divider">
<li class="nav-item">
  <a href="{{route('profile.mycertificates')}}" class="nav-link">
        <i class="fas fa-fw fa-table"></i>
        <span>My Certificates</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item">
  <a href="{{route('profile.edit')}}" class="nav-link">
        <i class="fas fa-fw fa-table"></i>
        <span>My Events</span></a>
</li>
 
</ul>
<!-- End of Sidebar -->