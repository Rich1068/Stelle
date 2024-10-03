@extends('layouts.app')

@section('body')
<div class="col-lg-12 d-flex justify-content-center">
    <div class="card shadow-sm rounded" style="border-radius: 15px !important; width: 100% !important; background-color: white;"> <!-- Set background color to white -->
        <div class="d-flex" style="width: 100%;">
            <!-- First Section -->
            <div class="flex-fill" style="padding: 2rem; border-radius: 15px 0 0 15px; background-color: white;"> <!-- Set background color to white -->
                <h2 class="text-primary font-weight-bold mb-0" style="color: darkcyan !important;"> <!-- Set text color to dark grey -->
                    Good Evening <span style="color: darkblue !important;">User!</span>
                </h2>
                <p class="text-muted mb-0 font-weight-bold" style="color: darkblue;"> <!-- Set text color to dark blue -->
                    How are you feeling?
                </p>
            </div>

            <!-- Divider -->
            <div class="divider" style="width: 2px; background-color: #dee2e6;"></div>

            <!-- Second Section -->
            <div class="flex-fill" style="padding: 2rem; border-radius: 15px 15px 15px 0; background-color: white;"> <!-- Set background color to white -->
                <h2 class="text-primary font-weight-bold mb-0" style="color: darkcyan !important;"> <!-- Set text color to dark grey -->
                    Here are your scheduled events <span style="color: darkblue !important;">for this month</span>
                </h2>
            </div>
        </div>
    </div>
</div>



    <!-- Main Container -->
    <div class="row justify-content-center  custom-bg-white mt-4 mb-4 pt-4"> <!-- Added custom class for border radius -
        <!-- Calendar Column -->
        <div class="col-xl-6 col-md-12 mb-4"> <!-- Adjusted width for the calendar -->
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Event Calendar</h6>
                </div>
                <!-- Dropdown for Event Filtering -->
                <div class="dropdown mt-3">
                    <select id="calendarFilter" class="form-control">
                        <option value="all">All Events</option>
                        <option value="own">Own Events</option>
                        <option value="join">Joined Events</option>
                    </select>
                </div>
                <div id="calendar" class="p-3" style="height: 400px; overflow-y: auto;"></div>
            </div>
        </div>

        <!-- Cards Column -->
        <div class="col-xl-4 col-md-12 mb-4"> <!-- Adjusted to occupy remaining space for cards -->
            <div class="row mb-4">
                <!-- Certificates Card -->
                <div class="col-12 mb-4">
                    <div class="card border-left-info shadow h-100" style="height: 220px;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Certificates</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div> 
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-certificate fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Forms Answered Card -->
                <div class="col-12 mb-4">
                    <div class="card border-left-info shadow h-100" style="height: 220px;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Evaluation Forms Created</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-square fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events Completed Card -->
                <div class="col-12 mb-4">
                    <div class="card border-left-info shadow h-100" style="height: 220px;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Events Completed</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-check fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events Created Card -->
                <div class="col-12 mb-4">
                    <div class="card border-left-info shadow h-100" style="height: 220px;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Events Created</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-plus fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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


<style>
    .icon-dark-blue {
        color: #003366; /* Initial dark blue color */
        cursor: pointer; /* Cursor changes to pointer */
        transition: color 0.3s; /* Smooth transition effect */
    }

    .icon-dark-blue:hover {
        color: #007bff; /* Change to a lighter blue on hover */
    }

    .btn-dark-blue {
        background-color: #003366;
        color: white;
        border-radius: 15px;
        padding: 10px 20px;
        border: none;
        font-weight: bold;
    }

    .btn-dark-blue:hover {
        background-color: #002244;
    }

    .custom-bg-white {
        border-radius: 15px;
        max-width: 80%;
        margin: auto;
        background-color: rgba(255, 255, 255, 0.4);
    }

    .card {
        border: none;
    }
</style>

@vite('resources/js/calendar.js')
@endsection
