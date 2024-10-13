@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container-fluid px-4">
    
<div class="col-lg-12 d-flex justify-content-center" style="padding: 0; margin: 0;"> 
    <div class="card shadow-sm rounded responsive-card" style="border-radius: 15px !important; width: 90% !important; background-color: white;"> 
        <div class="d-flex flex-column flex-lg-row" style="width: 100%;">
            <!-- First Section -->
            <div class="flex-fill" style="padding: 2rem; border-radius: 15px 0 0 15px; background-color: white;"> 
                <h3 class="text-primary font-weight-bold mb-0" style="color: darkcyan !important;"> 
                    Welcome back, <span style="color: darkblue !important;">{{Auth::user()->first_name}}</span>
                </h3>
                <p class="text-muted mb-0 font-weight-bold" style="color: darkblue;"> 
                    How are you feeling?
                </p>
            </div>

            <!-- Divider -->
            <div class="d-none d-lg-block" style="width: 2px; background-color: #dee2e6;"></div> <!-- Hide on smaller screens -->

            <!-- Second Section -->
            <div class="flex-fill" style="padding: 2rem; border-radius: 0 15px 15px 0; background-color: white;"> 
                <h3 class="text-primary font-weight-bold mb-0" style="color: darkcyan !important;"> 
                    Here is your dashboard.  <span style="color: darkblue !important;">Let’s make today productive!</span>
                </h3>
            </div>
        </div>
    </div>
</div>

    <!-- Calendar Container -->
    <div class="row justify-content-center custom-bg-white mt-4 mb-4 pt-4">
        <!-- Calendar Column -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card shadow mb-4" style="height: 500px;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Event Calendar</h6>
                </div>
                <div class="dropdown mt-3">
                    <select id="calendarFilter" class="form-control">
                        <option value="join">Joined Events</option>
                        <option value="all">All Events</option>
                    </select>
                </div>
                <div id="calendar" class="p-3" style="height: 400px; overflow-y: auto;"></div>
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
        

    <div class="col-xl-6 col-md-12 mb-4"> 
   
        <div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
            <div class="card border-left-info shadow h-100" style="height: 220px !imortant;">
                <div class="card-body d-flex align-items-center"> <!-- Added d-flex and align-items-center -->
                    <div class="row no-gutters align-items-center w-100"> <!-- Ensure the row takes full width -->
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Certificates
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalCertificates}}</div> <!-- Placeholder number -->
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-certificate fa-2x text-info"></i> <!-- Icon for certificates -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
            <div class="card border-left-info shadow h-100" style="height: 220px !imortant;">
                <div class="card-body d-flex align-items-center"> <!-- Added d-flex and align-items-center -->
                    <div class="row no-gutters align-items-center w-100"> <!-- Ensure the row takes full width -->
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Evaluation Forms Answered
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div> <!-- Placeholder number -->
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-square fa-2x text-info"></i> <!-- Icon for evaluation forms -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
            <div class="card border-left-info shadow h-100" style="height: 220px !imortant;">
                <div class="card-body d-flex align-items-center"> <!-- Added d-flex and align-items-center -->
                    <div class="row no-gutters align-items-center w-100"> <!-- Ensure the row takes full width -->
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Events attended
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$eventsAttendedTotal}}</div> <!-- Placeholder number -->
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-info"></i> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4 col-md-12">
        <div class="col-md-12">
            <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
                <div class="card-body p-4 d-flex flex-column"> <!-- Added padding and flexbox for layout -->
                    <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-3"> <!-- Use dark blue text -->
                        Monthly Events Joined
                    </div>
                    <div class="chart-container flex-grow-1"> <!-- Use flex-grow to fill space -->
                        <canvas id="monthlyEventsChart" style="height: 100%; width: 100%;"></canvas> <!-- Set canvas to 100% -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CSS Styling -->
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


    .custom-bg-white {
        border-radius: 15px;
        max-width: 95%;
        margin: auto;
        background-color: rgba(255, 255, 255, 0.4);
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JavaScript -->
<script>

         // Mock data for the chart
const ctx = document.getElementById('monthlyEventsChart').getContext('2d');
const monthlyEventsChart = new Chart(ctx, {
    type: 'bar', // Change to 'bar' for a bar chart
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: 'Events Joined',
            data: [12, 19, 3, 5, 2, 3, 10, 7, 8, 15, 4, 9], // Mock data
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Events'
                }
            }
        }
    }
});
        
</script>

@vite('resources/js/calendar.js')
@endsection
