<div class="analytics-info">
    <div class="row">
        <!-- Left Column: Cards in one column -->
        <div class="col-md-6 col-sm-12 mb-4"> <!-- Responsive stacking for mobile -->
            <div class="card border-left-info shadow h-100 mb-4 w-100" style="max-height: 120px; margin-top: 30px;"> <!-- Added w-100 for mobile -->
                <div class="card-body d-flex align-items-center">
                    <div class="row no-gutters align-items-center w-100">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Events Created
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalEventsCreated}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-plus fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-left-info shadow h-100 mb-4 w-100" style="max-height: 120px; margin-top: 30px;">
                <div class="card-body d-flex align-items-center">
                    <div class="row no-gutters align-items-center w-100">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Evaluation Forms Created
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalEvaluationFormsCreated}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-left-info shadow h-100 mb-4 w-100" style="max-height: 120px; margin-top: 30px;">
                <div class="card-body d-flex align-items-center">
                    <div class="row no-gutters align-items-center w-100">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Events Attended
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalAttendedEvents}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-left-info shadow h-100 mb-4 w-100" style="max-height: 120px; margin-top: 30px; margin-bottom: 40px;"> <!-- Added margin-bottom -->
                <div class="card-body d-flex align-items-center">
                    <div class="row no-gutters align-items-center w-100">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Certificates
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalCertificates}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-certificate fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Charts centered vertically -->
<div class="col-md-6 col-sm-12 d-flex flex-column align-items-center mb-4">
    <!-- Events Created Per Month -->
    <div class="card border-left-info shadow h-100 mb-4 w-100" 
         style="max-height: 240px; width: 90%; margin-top: 30px; margin-bottom: 40px; display: flex; justify-content: center;">
        <div class="card-body p-2">
            <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center" style="font-size: 14px;">
                Events Created Per Month
            </div>
            <div class="year-navigation d-flex align-items-center mb-2 justify-content-center">
                <button id="prev-year-created" class="btn circular-btn mx-2">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span id="current-year-created" class="mx-2" style="font-size: 14px;">{{ now()->year }}</span>
                <button id="next-year-created" class="btn circular-btn mx-2">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="chart-container flex-grow-1" style="height: 140px;">
                <canvas id="eventsCreatedChart" style="height: 100%; width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Events Joined Per Month -->
    <div class="card border-left-info shadow h-100 w-100" 
         style="max-height: 240px; width: 90%; margin-top: 30px; margin-bottom: 50px !important; display: flex; justify-content: center;">
        <div class="card-body p-2">
            <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center" style="font-size: 14px;">
                Events Joined Per Month
            </div>
            <div class="year-navigation d-flex align-items-center mb-2 justify-content-center">
                <button id="prev-year-joined" class="btn circular-btn mx-2">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span id="current-year-joined" class="mx-2" style="font-size: 14px;">{{ now()->year }}</span>
                <button id="next-year-joined" class="btn circular-btn mx-2">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="chart-container flex-grow-1" style="height: 140px;">
                <canvas id="eventsJoinedChart" style="height: 100%; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<style>
 


.chart-container {
    width: 100%;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    /* Adjust margins for smaller screens */
    .card {
        margin-top: 20px !important;
        margin-bottom: 20px !important;
    }
    .card-body {
        margin-top: 20px !important;
        margin-bottom: 20px !important;
    }
}

.circular-btn {
    background-color: #17a2b8;
    color: #fff;
    border-radius: 50%;
    width: 40px !important;
    height: 40px !important;
    display: flex !important;
    justify-content: center !important;  /* Horizontally center the content */
    align-items: center !important;      /* Vertically center the content */
    font-size: 18px !important;          /* Size of the arrow icon */
    padding: 0 !important;
    line-height: 1 !important;           /* Prevent extra spacing around the icon */
    border: none !important;             /* Remove any border */
    margin: auto;
}

.circular-btn i {
    color: #fff !important;              /* Ensure arrow icon stays white */
    font-size: 20px !important;          /* Size of the arrow icon */
    margin: auto;
}

.circular-btn:hover {
    background-color: #138496 !important; /* Darker background color on hover */
}


</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let adminId = {{ $user->id }}; // Use the viewed admin's ID, not Auth::id()
        
        let currentYearCreated = {{ now()->year }};
        let currentYearJoined = {{ now()->year }};
        
        // Initialize Created Events Chart
        const createdCtx = document.getElementById('eventsCreatedChart').getContext('2d');
        let eventsCreatedChart = new Chart(createdCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Events Created',
                    data: [], // Placeholder data
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
                        ticks: { beginAtZero: true, stepSize: 1 }
                    }]
                }
            }
        });

        // Initialize Joined Events Chart
        const joinedCtx = document.getElementById('eventsJoinedChart').getContext('2d');
        let eventsJoinedChart = new Chart(joinedCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Events Joined',
                    data: [], // Placeholder data
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
                        ticks: { beginAtZero: true, stepSize: 1 }
                    }]
                }
            }
        });

        // Fetch data for Events Created Per Month
        function fetchCreatedEvents(year) {
            fetch(`/profile/${adminId}/events-created-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('current-year-created').innerText = year;
                    eventsCreatedChart.data.datasets[0].data = data.values;
                    eventsCreatedChart.update();
                });
        }

        // Fetch data for Events Joined Per Month
        function fetchJoinedEvents(year) {
            fetch(`/profile/${adminId}/events-joined-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('current-year-joined').innerText = year;
                    eventsJoinedChart.data.datasets[0].data = data.values;
                    eventsJoinedChart.update();
                });
        }

        // Load initial data for the current year
        fetchCreatedEvents(currentYearCreated);
        fetchJoinedEvents(currentYearJoined);

        // Event listeners for year navigation (Created)
        document.getElementById('prev-year-created').addEventListener('click', function () {
            currentYearCreated--;
            fetchCreatedEvents(currentYearCreated);
        });
        document.getElementById('next-year-created').addEventListener('click', function () {
            currentYearCreated++;
            fetchCreatedEvents(currentYearCreated);
        });

        // Event listeners for year navigation (Joined)
        document.getElementById('prev-year-joined').addEventListener('click', function () {
            currentYearJoined--;
            fetchJoinedEvents(currentYearJoined);
        });
        document.getElementById('next-year-joined').addEventListener('click', function () {
            currentYearJoined++;
            fetchJoinedEvents(currentYearJoined);
        });
    });
</script>
