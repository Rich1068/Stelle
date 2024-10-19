@extends('layouts.app')

@section('body')
<div class="col-lg-12 d-flex justify-content-center" style="padding: 0;"> <!-- Added padding: 0 to the parent -->
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
</div>

    
    <div class="row justify-content-center  custom-bg-white mt-4 mb-4 pt-4"> <!-- Added custom class for border radius -->
    <!-- Calendar Column -->
    <div class="col-xl-6 col-md-12 mb-4"> <!-- Adjusted width for the calendar -->
        <div class="card shadow mb-4" style="height: 500px;"> <!-- Increased height for the calendar -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Event Calendar</h6>
            </div>
            <div class="dropdown mt-3">
                <select id="calendarFilter" class="form-control">
                    <option value="all">All Events</option>
                    <option value="own">Own Events</option>
                    <option value="join">Joined Events</option>
                </select>
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

    <div class="col-xl-6 col-md-12"> <!-- Adjusted to occupy remaining space for cards -->
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalJoinedEvents }}</div>
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
    <div class="col-xl-6 col-lg-6 mb-4"> 
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
    <div class="col-xl-6 col-lg-6 mb-4">
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

<!-- Updated Total Events Created Monthly Section with Text Centered and Navigation Below -->
<div class="row mb-5 col-md-12">
    <div class="col-md-12">
        <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
            <div class="card-body p-4 d-flex flex-column align-items-center"> <!-- Center content -->
                <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center">
                    Total Events Created Monthly
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
                    <canvas id="monthlyEventsChart" style="height: 100%; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Updated Total Users Registered Monthly Section with Text Centered and Navigation Below -->
<div class="row mb-5 col-md-12 mt-2">
    <div class="col-md-12 mt-4">
        <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
            <div class="card-body p-4 d-flex flex-column align-items-center"> <!-- Center content -->
                <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center">
                    Total Users Registered Monthly
                </div>
                <!-- Year navigation buttons below the text and centered -->
                <div class="year-navigation d-flex align-items-center mb-3 justify-content-center"> <!-- Center navigation -->
                    <!-- Back Button -->
                    <button id="prev-year-users" class="btn circular-btn mx-2">
                        <i class="fas fa-chevron-left"></i> <!-- Simplified arrow -->
                    </button>
                    <span id="current-year-users" class="mx-2">{{ $currentYear }}</span> <!-- Display the current year -->
                    <!-- Next Button -->
                    <button id="next-year-users" class="btn circular-btn mx-2">
                        <i class="fas fa-chevron-right"></i> <!-- Simplified arrow -->
                    </button>
                </div>
                <div class="chart-container flex-grow-1">
                    <canvas id="monthlyUsersChart" style="height: 100%; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- New Monthly Participants Chart -->
    <div class="row mb-5 col-md-12 mt-2"> 
    <div class="col-md-12">
    <div class="col-md-12">
    <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
        <div class="card-body p-4 d-flex flex-column align-items-center">
            <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center">
                Participants per Event
            </div>

            <!-- Pagination Controls with Circular Buttons Moved Here -->
            <div class="pagination-controls mb-4 d-flex justify-content-center align-items-center">
                <button id="prevPage" class="btn circular-btn mx-2" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <!-- Centering the text inside the span -->
                <span id="pageInfo" class="mx-2" style="font-size: 14px; display: flex; align-items: center; justify-content: center;">
                    Page 1
                </span>
                <button id="nextPage" class="btn circular-btn mx-2">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Chart Container -->
            <div class="chart-container flex-grow-1">
                <canvas id="participantsPerEventChart" style="height: 100%; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
</div>

</div>

<style>
    /* Mobile-specific styles (for screens 768px or less) */
@media (max-width: 768px) {
    /* Only target the specific calendar container for centering */
    .calendar-container {
        display: flex; /* Use flexbox */
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        flex-direction: column; /* Stack items vertically */
        width: 100%; /* Full width for mobile */
        padding: 0; /* Remove padding */
        margin: 0; /* Remove margin */
    }

    /* Ensure the card inside the calendar takes full width on mobile */
    .calendar-container .card {
        width: 100%; /* Full width for mobile */
        max-width: 100%; /* Prevent white space */
        height: auto; /* Auto height */
        padding: 0; /* Remove padding */
        margin: 0; /* Remove margin */
        box-sizing: border-box; /* Ensure padding doesn't cause overflow */
    }

    /* Ensure calendar content takes full width */
    #calendar {
        width: 100%; /* Full width */
        padding: 0.5rem; /* Minimal padding */
        box-sizing: border-box; /* Prevent overflow due to padding */
    }

    /* Ensure the dropdown inside the calendar is full width */
    #calendarFilter {
        width: 100%; /* Full width for dropdown */
        margin-bottom: 1rem; /* Space below the dropdown */
    }

    /* Adjust text size for mobile */
    .calendar-container .card-header h6 {
        font-size: 1.2rem; /* Slightly smaller text on mobile */
    }

    /* Adjust modal dialog width on mobile */
    .modal-dialog {
        width: 100%; /* Full width for modal on mobile */
        margin: 0 auto;
    }

    /* Other non-calendar cards (if any) */
    .col-xl-6.col-md-12 {
        flex: 0 0 100%; /* Full width on mobile */
        max-width: 100%; /* Prevent white space */
        padding: 0; /* Remove padding */
        margin: 0; /* Stack vertically with no extra space */
    }
}

@media (max-width: 768px) { /* Adjust the max-width as needed */
    .responsive-card {
        transform: scale(0.9); /* Adjust the scale value to make it smaller */
        transform-origin: top center; /* Ensures it scales down from the top */
    }
}

    .circular-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #003366; /* Dark blue background */
        color: white; /* White arrow color */
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        padding: 0; /* Ensure no extra padding for centering */
        margin: 0; /* Remove any default margins */
        position: relative;
    }

    .circular-btn i {
        font-size: 16px; /* Arrow size */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); /* Precisely center the arrow inside the button */
    }

    .circular-btn:hover {
        background-color: #002244; /* Slightly darker blue on hover */
    }

    .year-navigation {
        display: flex;
        align-items: center;
    }
.custom-bg-white {
    border-radius: 15px; /* Add border radius */
    max-width: 95%;
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
        let currentYear = {{ $currentYear }};
    
    // Function to update the chart with new data
    function updateChart(chart, data) {
        chart.data.labels = data.labels;
        chart.data.datasets[0].data = data.values;
        chart.update();
    }

    // Event listener for 'Back' button
    document.getElementById('prev-year').addEventListener('click', function() {
        currentYear--;
        fetchYearData(currentYear);
    });

    // Event listener for 'Next' button
    document.getElementById('next-year').addEventListener('click', function() {
        currentYear++;
        fetchYearData(currentYear);
    });

    // Fetch data for the selected year
    function fetchYearData(year) {
        fetch(`/super-admin/events-data?year=${year}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('current-year').innerText = year; // Update the displayed year
                updateChart(monthlyEventsChart, data); // Update the chart with the new data
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    const ctx = document.getElementById('monthlyEventsChart').getContext('2d');
    const chartData = @json($monthlyEventsData);

    const monthlyEventsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels, // Month names: January to December
            datasets: [{
                label: 'Total Events Created',
                data: chartData.values.length ? chartData.values : Array(12).fill(0), // Ensure 12 data points even if no data
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 4
            }]
        },
        options: {
        scales: {
            yAxes: [{ 
                ticks: {
                    beginAtZero: true,
                    stepSize: 1,
                     
                }
            }]
        }
    }
        
});

    let currentYearUsers = {{ $currentYear }}; // Use the current year

        // Function to update the chart with new data
        function updateUserChart(chart, data) {
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.values;
            chart.update();
        }

        // Event listener for 'Back' button
        document.getElementById('prev-year-users').addEventListener('click', function() {
            currentYearUsers--;
            fetchYearUserData(currentYearUsers);
        });

        // Event listener for 'Next' button
        document.getElementById('next-year-users').addEventListener('click', function() {
            currentYearUsers++;
            fetchYearUserData(currentYearUsers);
        });

        // Fetch data for the selected year
        function fetchYearUserData(year) {
            fetch(`/super-admin/users-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('current-year-users').innerText = year; // Update displayed year
                    updateUserChart(monthlyUsersChart, data); // Update chart with new data
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        const usersCtx = document.getElementById('monthlyUsersChart').getContext('2d');
        const monthlyUsersData = @json($monthlyUsers);

        const monthlyUsersChart = new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Total Users Registered',
                    data: monthlyUsersData, // User data for each month
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
                    yAxes: [{ 
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1 ,
                            min: 0,
                            precision: 0, 
                        }
                    }]
                }
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('participantsPerEventChart').getContext('2d');
        let currentPage = 1;
        let lastPage = 1;

        // Initialize the chart
        let participantsPerEventChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // Event names
                datasets: [{
                    label: 'Participants',
                    data: [], // Participants count
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            }
        });
        function truncateLabel(label, maxLength = 20) {
            if (label.length > maxLength) {
                return label.substring(0, maxLength) + '...';
            }
            return label;
        }

        // Function to update the chart with new data
        function updateChart(data) {
            participantsPerEventChart.data.labels = data.labels.map(label => truncateLabel(label));
            participantsPerEventChart.data.datasets[0].data = data.values;
            participantsPerEventChart.update();

            currentPage = data.current_page;
            lastPage = data.last_page;
            document.getElementById('pageInfo').innerText = `Page ${currentPage}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === lastPage;
        }

        // Fetch and update the chart for the specified page
        function fetchPageData(page) {
            fetch(`/super-admin/dashboard/participants-per-event?page=${page}`)
                .then(response => response.json())
                .then(data => updateChart(data))
                .catch(error => console.error('Error fetching data:', error));
        }

        // Initial load for page 1
        fetchPageData(currentPage);

        // Event listener for 'Previous' button
        document.getElementById('prevPage').addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                fetchPageData(currentPage);
            }
        });

        // Event listener for 'Next' button
        document.getElementById('nextPage').addEventListener('click', function () {
            if (currentPage < lastPage) {
                currentPage++;
                fetchPageData(currentPage);
            }
        });
    });
</script>
@vite('resources/js/calendar.js')
@endsection
