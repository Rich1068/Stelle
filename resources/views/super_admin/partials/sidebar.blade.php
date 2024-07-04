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
    <a href="{{route('dashboard')}}" class="nav-link">
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
  <a href="{{route('dashboard')}}" class="nav-link">
        <i class="fas fa-fw fa-table"></i>
        <span>Users</span></a>
</li>
 
 
</ul>
<!-- End of Sidebar -->