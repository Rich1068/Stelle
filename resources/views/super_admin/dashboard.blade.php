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


    
    <div class="row justify-content-center  custom-bg-white mt-4 mb-4 pt-4"> <!-- Added custom class for border radius -->
    <!-- Calendar Column -->
    <div class="col-xl-5 col-md-12 mb-4"> <!-- Adjusted width for the calendar -->
        <div class="card shadow mb-4" style="height: 500px;"> <!-- Increased height for the calendar -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Event Calendar</h6>
            </div>
        
            <div id="calendar" class="p-3" style="height: 400px; overflow-y: auto;"> <!-- Set height for the calendar and added overflow -->
                <!-- Calendar will be inserted here -->
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

    <div class="col-xl-5 col-md-12"> <!-- Adjusted to occupy remaining space for cards -->
        <div class="row mb-4">
            <!-- Stack all cards on top of each other -->
            <div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
                <div class="card border-left-info shadow h-100" style="height: 220px;"> <!-- Reduced height for the card -->
                    <div class="card-body"> <!-- Removed d-flex justify-content-center -->
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Users
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
                <div class="card border-left-info shadow h-100" style="height: 220px;"> <!-- Reduced height for the card -->
                    <div class="card-body"> <!-- Removed d-flex justify-content-center -->
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Events
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEvents }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
                <div class="card border-left-info shadow h-100" style="height: 220px;"> <!-- Reduced height for the card -->
                    <div class="card-body"> <!-- Removed d-flex justify-content-center -->
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Created Events
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCreatedEvents }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-plus-square fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
                <div class="card border-left-info shadow h-100" style="height: 220px;"> <!-- Reduced height for the card -->
                    <div class="card-body"> <!-- Removed d-flex justify-content-center -->
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Joined Events
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ isset($totalJoinedEvents) ? $totalJoinedEvents : 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- User Roles Card -->
    <div class="col-xl-5 col-lg-6 mb-4"> 
        <div class="card shadow h-100"> 
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">User Roles</h6>
            </div>
            <div class="card-body" style="height: 300px;">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="totalUserChart" style="height: 100%; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- User Genders Card -->
    <div class="col-xl-5 col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">User Genders</h6>
            </div>
            <div class="card-body" style="height: 300px;">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="genderChart" style="height: 100%; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div> 

    <div class="row mb-5 col-md-11">
    <div class="col-md-12">
        <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
            <div class="card-body p-4 d-flex flex-column"> <!-- Added padding and flexbox for layout -->
                <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-3"> <!-- Use dark blue text -->
                   Total Events Created Monthly
                </div>
                <div class="chart-container flex-grow-1"> <!-- Use flex-grow to fill space -->
                    <canvas id="monthlyEventsChart" style="height: 100%; width: 100%;"></canvas> <!-- Set canvas to 100% -->
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- New Monthly Participants Chart -->
    <div class="row mb-5 col-md-11 mt-2"> 
        <div class="col-md-12">
            <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-3">
                        Total Users Created Monthly
                    </div>
                    <div class="chart-container flex-grow-1">
                        <canvas id="monthlyParticipantsChart" style="height: 100%; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
</div>
    


<style>
.custom-bg-white {
    border-radius: 15px; /* Add border radius */
    max-width: 80%;
    align-items: center; /* This is not necessary unless you're using flexbox */
    margin: auto; /* Center the element */
    background-color: white; /* Set background color */
    background-color: rgba(255, 255, 255, 0.4); /* Semi-transparent white */
}


    .btn-dark-blue {
        background-color: #003366;
        /* Dark Blue Color */
        color: white;
        border-radius: 15px;
        /* Circular but consistent */
        padding: 10px 20px;
        /* Adjust padding as needed */
        border: none;
        font-weight: bold;
        /* Make button text bold */
    }

    .btn-dark-blue:hover {
        background-color: #002244;
        /* Darker shade on hover */
    }

    .card {
        border: none;
    }

    .card-header {
        border-radius: 15px 15px 0 0;
        /* Rounded top corners consistent with other elements */
    }

    .table thead th {
        background-color: #003366;
        /* Dark Blue for table header */
        color: white;
    }

    .table tbody td {
        background-color: #f8f9fc;
        /* Light background for table cells */
    }

    .table tbody tr:nth-child(odd) td {
        background-color: #e9ecef;
        /* Alternate row color */
    }

    .bg-dark-blue {
        background-color: #003366;
        /* Dark Blue Background */
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pass Laravel data to JavaScript
    var userCountData = @json($userCountData); // Ensure $chartData is passed from the controller

    // Pie Chart Example
    var ctx = document.getElementById("totalUserChart").getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: userCountData.labels,  // Use the labels from Laravel
            datasets: [{
                data: userCountData.values,  // Use the values from Laravel
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 10,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });

    var genderData = @json($genderData); // Ensure $chartData is passed from the controller

    // Pie Chart Example
    var ctx = document.getElementById("genderChart").getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: genderData.labels,  // Use the labels from Laravel
            datasets: [{
                data: genderData.values,  // Use the values from Laravel
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 10,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });

    document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('monthlyEventsChart').getContext('2d');
    const monthlyEventsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Events Joined',
                data: [12, 19, 3, 5, 2, 3, 10, 7, 8, 15, 4, 9],
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

    const ctxParticipants = document.getElementById('monthlyParticipantsChart').getContext('2d');
    const monthlyParticipantsChart = new Chart(ctxParticipants, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Participants Joined',
                data: [82, 121, 52, 72, 120, 83, 32, 15, 56, 69, 144, 90],
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
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
                        text: 'Number of Participants'
                    }
                }
            }
        }
    });
});

</script>
@vite('resources/js/calendar.js')
@endsection