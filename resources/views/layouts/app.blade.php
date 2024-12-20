<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/stelle_icon.png') }}" type="image/x-icon">
    @viteReactRefresh 
    <title>STELLE</title>
    <!-- Custom fonts for this template-->
    <link href="{{asset('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="https://polotno.com/style.css">
    <link href="{{asset('assets/css/sb-admin-2.css')}}" rel="stylesheet">
    <style>
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar .image-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .topbar .image-container img {
            max-width: 150px;
            max-height: 75px; 
            /* Optional: Add padding or margin to adjust position if needed */
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.0.6/dist/cdn.min.js"></script>
    <!-- <script>
        document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === "F12") {
                event.preventDefault();
            }
            if (event.ctrlKey && (event.shiftKey && ['I', 'J'].includes(event.key)) || event.key === 'U') {
                event.preventDefault();
            }
        });
    </script> -->
    <!-- <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
                // Reload the page
                window.location.reload();
            }
        });
    </script> -->
</head>
 
<div id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
 
    @if(isset($sidebar))
        @include($sidebar)
    @endif
 

  <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="padding-top: 5rem; !important">

<!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light mb-3 bg-white topbar fixed-top shadow">
                
                <!-- Centered Image Container -->
                <div class="image-container">
                    <img src="/images/stellelogo.png" alt="Stelle Logo">
                </div>

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search -->


                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <!-- <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a> -->
                        <!-- Dropdown - Messages -->
                        <!-- <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                            aria-labelledby="searchDropdown">
                            <form class="form-inline mr-auto w-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-0 small"
                                        placeholder="Search for..." aria-label="Search"
                                        aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li> -->

                
<!-- Space for the bell and messages refer to bottom -->
                

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline small" style="color: #00008B; font-weight: 700;">{{auth()->user()->first_name}} {{auth()->user()->last_name}}</span>
                            @if(auth()->user()->profile_picture == null)
                            <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Profile pic" class="circular-image" style="max-width: 50px; max-height: 50px;">
                            @else 
                            <img src="{{ asset(auth()->user()->profile_picture) }}" alt="Profile pic" class="circular-image" style="max-width: 50px; max-height: 50px;">
                            @endif
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <!-- <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a> -->
                            <x-dropdown-link :href="route('profile.profile')" class="dropdown-item">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            @if(auth()->user()->role_id == 3)
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#adminRegistrationModal">
                                    <i class="fas fa-solid fa-code fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Become An Admin
                                </a>
                            @endif

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutUserModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- content-->

            @yield('body')
        </div>

 
             <!-- Footer -->
            
             <footer class="sticky-footer" style="background: linear-gradient(to right, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.4)); padding: 20px; border-radius: 10px;">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span style="font-weight: bolder; font-size: 1.4em; color: white; text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.7); letter-spacing: 1.5px;">
        Copyright © Stelle 2024
      </span>
    </div>
  </div>
</footer>


    </div>

        <!-- Admin Registration Modal -->
    <div class="modal fade" id="adminRegistrationModal" tabindex="-1" role="dialog" aria-labelledby="adminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminModalLabel">Want to be an Admin?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Become an admin and unlock several new features, including:</p>
                    <ul>
                        <li><strong>Create and Manage Events:</strong> Plan and manage events for users to join in.</li>
                        <li><strong>Create Evaluation Forms:</strong> Customize and distribute evaluation forms to gather feedback from event participants.</li>
                        <li><strong>Create and Issue Certificates:</strong> Generate and issue personalized certificates for event participants, recognizing their participation.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    @if(auth()->user()->role_id != 3)
                    @else
                    <!-- Admin Register Form with unique ID -->
                    @if($adminRequest && $adminRequest->status_id == 3)
                        <!-- Show the 'Pending' button if the admin request is pending -->
                        <button class="btn btn-primary" disabled>
                            {{ __('Pending') }}
                        </button>
                    @else
                        <!-- Show the 'Register' button if the user hasn't applied for admin or is approved/rejected -->
                        <form id="admin-register-form" method="POST" action="{{ route('register.admin') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="color: white;">
                                {{ __('Register') }}
                            </button>
                        </form>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutUserModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

                    <!-- Correct Logout Form with unique ID -->
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="color: white;">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Page Wrapper -->
 
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- jQuery (only include it once) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- FullCalendar and moment.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar/main.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>



    <!-- Bootstrap (ensure it's Bootstrap 5 or compatible version) -->
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- DataTables -->
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.js') }}"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Remove redundant Popper.js if bootstrap.bundle.min.js already includes Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script> -->

    <!-- jQuery Easing Plugin -->
    <script src="{{asset('assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts -->
    <script src="{{asset('assets/js/sb-admin-2.min.js')}}"></script>
    

    @yield('scripts')
</body>
</html>


<?php
/*
<!-- 
    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <!-- Counter - Alerts -->
            <span class="badge badge-danger badge-counter">3+</span>
        </a>
        <!-- Dropdown - Alerts -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">
                Alerts Center
            </h6>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-success">
                        <i class="fas fa-donate text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    $290.29 has been deposited into your account!
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">December 2, 2019</div>
                    Spending Alert: We've noticed unusually high spending for your account.
                </div>
            </a>
            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
        </div>
    </li>

    <!-- Nav Item - Messages -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-envelope fa-fw"></i>
            <!-- Counter - Messages -->
            <span class="badge badge-danger badge-counter">7</span>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="messagesDropdown">
            <h6 class="dropdown-header">
                Message Center
            </h6>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_1.svg"
                        alt="...">
                    <div class="status-indicator bg-success"></div>
                </div>
                <div class="font-weight-bold">
                    <div class="text-truncate">Hi there! I am wondering if you can help me with a
                        problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_2.svg"
                        alt="...">
                    <div class="status-indicator"></div>
                </div>
                <div>
                    <div class="text-truncate">I have the photos that you ordered last month, how
                        would you like them sent to you?</div>
                    <div class="small text-gray-500">Jae Chun · 1d</div>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_3.svg"
                        alt="...">
                    <div class="status-indicator bg-warning"></div>
                </div>
                <div>
                    <div class="text-truncate">Last month's report looks great, I am very happy with
                        the progress so far, keep up the good work!</div>
                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                        alt="...">
                    <div class="status-indicator bg-success"></div>
                </div>
                <div>
                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                        told me that people say this to all dogs, even if they aren't good...</div>
                    <div class="small text-gray-500">Chicken the Dog · 2w</div>
                </div>
            </a>
            <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
        </div>
    </li>
-->







*/
?>
