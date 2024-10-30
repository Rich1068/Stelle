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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalEvalAnswered}}</div>
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
    <div class="row mb-5 col-md-12 mt-2">
        <div class="col-md-12 mt-4">
            <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
                <div class="card-body p-4 d-flex flex-column align-items-center"> <!-- Center content -->
                    <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center">
                        Events Joined Per Month
                    </div>
                    <!-- Year navigation buttons below the text and centered -->
                    <div class="year-navigation d-flex align-items-center mb-3 justify-content-center"> <!-- Center navigation -->
                        <!-- Back Button -->
                        <button id="prev-year" class="btn circular-btn mx-2">
                            <i class="fas fa-chevron-left"></i> <!-- Simplified arrow -->
                        </button>
                        <span id="current-year" class="mx-2">{{ $currentYear }}</span> <!-- Display the current year -->
                        <!-- Next Button -->
                        <button id="next-year" class="btn circular-btn mx-2">
                            <i class="fas fa-chevron-right"></i> <!-- Simplified arrow -->
                        </button>
                    </div>
                    <div class="chart-container flex-grow-1">
                        <canvas id="eventsJoinedChart" style="height: 100%; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CSS Styling -->
<style>

/* Mobile-specific styles (for screens 768px or less) */
@media (max-width: 768px) {
    /* Only target the specific calendar container for centering */
    .calendar-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
        padding: 0;
        margin: 0;
    }

    /* Ensure the card inside the calendar takes full width on mobile */
    .calendar-container .card {
        width: 100%;
        max-width: 100%;
        height: auto;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    /* Ensure calendar content takes full width */
    #calendar {
        width: 100%;
        padding: 0.5rem;
        box-sizing: border-box;
    }

    /* Ensure the dropdown inside the calendar is full width */
    #calendarFilter {
        width: 100%;
        margin-bottom: 1rem;
    }

    /* Adjust text size for mobile */
    .calendar-container .card-header h6 {
        font-size: 1.2rem;
    }

    /* Adjust modal dialog width on mobile */
    .modal-dialog {
        width: 100%;
        margin: 0 auto;
    }

    /* Other non-calendar cards */
    .col-xl-6.col-md-12 {
        flex: 0 0 100%;
        max-width: 100%;
        padding: 0;
        margin: 0;
    }

    /* Adjust the max-width as needed */
    .responsive-card {
        transform: scale(0.9);
        transform-origin: top center;
    }
}

/* Circular button styles */
.circular-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #003366; /* Dark blue background */
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    padding: 0;
    margin: 0;
    position: relative;
    cursor: pointer; /* Make it clickable */
}

.circular-btn i {
    font-size: 16px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.circular-btn:hover {
    background-color: #002244; /* Darker blue on hover */
}

/* Year navigation container */
.year-navigation {
    display: flex;
    align-items: center;
}

/* General button styles */
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

/* Custom background */
.custom-bg-white {
    border-radius: 15px;
    max-width: 95%;
    margin: auto;
    background-color: rgba(255, 255, 255, 0.4); /* Semi-transparent white */
}

/* Card styles */
.card {
    border: none;
}

.card-header {
    border-radius: 15px 15px 0 0;
}

/* Table styles */
.table thead th {
    background-color: #003366; /* Dark blue for table header */
    color: white;
}

.table tbody td {
    background-color: #f8f9fc; /* Light background for table cells */
}

.table tbody tr:nth-child(odd) td {
    background-color: #e9ecef; /* Alternate row color */
}

/* Icon styles */
.icon-dark-blue {
    color: #003366; /* Initial dark blue color */
    cursor: pointer;
    transition: color 0.3s; /* Smooth transition effect */
}

.icon-dark-blue:hover {
    color: #007bff; /* Lighter blue on hover */
}

/* Dark blue background */
.bg-dark-blue {
    background-color: #003366;
}

</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentYear = {{ $currentYear }};
        const ctx = document.getElementById('eventsJoinedChart').getContext('2d');
        let chartData = @json($monthlyEventsData);

        // Initialize the chart
        let eventsJoinedChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Events Joined',
                    data: chartData.values,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                scales: {
                    y: {  // Set as an object, not an array
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                        }
                    }
                }
            }
        });

        // Function to update the chart with new data
        function updateChart(data) {
            eventsJoinedChart.data.labels = data.labels;
            eventsJoinedChart.data.datasets[0].data = data.values;
            eventsJoinedChart.update();
        }

        // Event listeners for year navigation
        document.getElementById('prev-year').addEventListener('click', function () {
            currentYear--;
            fetchYearData(currentYear);
        });

        document.getElementById('next-year').addEventListener('click', function () {
            currentYear++;
            fetchYearData(currentYear);
        });

        // Fetch data for the selected year
        function fetchYearData(year) {
            fetch(`/user/dashboard/events-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('current-year').innerText = year;
                    updateChart(data);
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    });
</script>

@vite('resources/js/calendar.js')
@endsection
