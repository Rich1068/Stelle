@extends('layouts.app')

@section('body')
<div class="container-fluid px-4">
    
    <div class="col-lg-12 d-flex justify-content-center">
        <div class="card shadow-sm rounded" style="border-radius: 15px !important; width: 100% !important; background-color: white;">
            <div class="d-flex" style="width: 100%;">
                <!-- First Section -->
                <div class="flex-fill" style="padding: 2rem; border-radius: 15px 0 0 15px; background-color: white;">
                    <h2 class="text-primary font-weight-bold mb-0" style="color: darkcyan !important;">
                        Good Evening <span style="color: darkblue !important;">User!</span>
                    </h2>
                    <p class="text-muted mb-0 font-weight-bold" style="color: darkblue;">
                        How are you feeling?
                    </p>
                </div>

                <!-- Divider -->
                <div class="divider" style="width: 2px; background-color: #dee2e6;"></div>

                <!-- Second Section -->
                <div class="flex-fill" style="padding: 2rem; border-radius: 15px 15px 15px 0; background-color: white;">
                    <h2 class="text-primary font-weight-bold mb-0" style="color: darkcyan !important;">
                        Here are your scheduled events <span style="color: darkblue !important;">for this month</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="row justify-content-center custom-bg-white mt-4 mb-4 pt-4">
        <!-- Calendar Column -->
        <div class="col-xl-5 col-md-12 mb-4">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Event Calendar</h6>
                </div>
                <div class="dropdown mt-3">
                    <select id="calendarFilter" class="form-control">
                        <option value="all">All Events</option>
                        <option value="join">Joined Events</option>
                    </select>
                </div>
                <div id="calendar" class="p-3" style="height: 400px; overflow-y: auto;"></div>
            </div>
        </div>

        <!-- Request as Admin Column -->
        <div class="col-xl-4 col-md-2"> 
            <form id="registerAdminForm" action="{{ route('register.admin') }}" method="POST"> <!-- Original form from old code -->
                @csrf
                <div class="card border-left-info shadow h-100" style="height: 120px !important; cursor: pointer;" onclick="confirmSubmission()"> <!-- Added form and onclick -->
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Request as Admin
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-dark-blue btn-sm" style="pointer-events: none;"> <!-- Prevents separate click on button -->
                                <i class="fas fa-user-plus text-white"></i> <!-- Icon-only button -->
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>





        
        <!-- Modal for event details -->
        <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body" id="modalContent">
                        <!-- Modal content will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- CSS Styling -->
<style>
    .btn-dark-blue {
        background-color: #003366; /* Dark Blue Color */
        color: white;
        border-radius: 15px; /* Circular but consistent */
        padding: 10px 20px; /* Adjust padding as needed */
        border: none;
        font-weight: bold; /* Make button text bold */
    }

    .btn-dark-blue:hover {
        background-color: #002244; /* Darker shade on hover */
    }

    .card {
        border: none;
    }

    .custom-bg-white {
        border-radius: 15px;
        max-width: 80%;
        margin: auto;
        background-color: rgba(255, 255, 255, 0.4);
    }
</style>

<!-- JavaScript -->
<script>
    function confirmSubmission() {
        // Show confirmation dialog
        if (confirm('Are you sure you want to register as an event admin?')) {
            // If confirmed, submit the form
            document.getElementById('registerAdminForm').submit();
        }
        // If not confirmed, do nothing
    }
</script>

@vite('resources/js/calendar.js')
@endsection
