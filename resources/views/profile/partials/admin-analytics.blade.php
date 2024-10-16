<div class="analytics-info">
<div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
        <div class="card border-left-info shadow h-100" style="height: 220px !imortant;">
            <div class="card-body d-flex align-items-center"> <!-- Added d-flex and align-items-center -->
                <div class="row no-gutters align-items-center w-100"> <!-- Ensure the row takes full width -->
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Events Created
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalEventsCreated}}</div> <!-- Placeholder number -->
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-certificate fa-2x text-info"></i> <!-- Icon for certificates -->
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-left-info shadow h-100" style="height: 220px !imortant;">
            <div class="card-body d-flex align-items-center"> <!-- Added d-flex and align-items-center -->
                <div class="row no-gutters align-items-center w-100"> <!-- Ensure the row takes full width -->
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Evaluation Forms Created
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalEvaluationFormsCreated}}</div> <!-- Placeholder number -->
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-certificate fa-2x text-info"></i> <!-- Icon for certificates -->
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-left-info shadow h-100" style="height: 220px !imortant;">
            <div class="card-body d-flex align-items-center"> <!-- Added d-flex and align-items-center -->
                <div class="row no-gutters align-items-center w-100"> <!-- Ensure the row takes full width -->
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Events Attended
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalAttendedEvents}}</div> <!-- Placeholder number -->
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
                            Total Certificates
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
    <!-- Events Created Per Month -->
    <div class="row mb-5 col-md-12 mt-2">
        <div class="col-md-12 mt-4">
            <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
                <div class="card-body p-4 d-flex flex-column align-items-center">
                    <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center">
                        Events Created Per Month
                    </div>
                    <div class="year-navigation d-flex align-items-center mb-3 justify-content-center">
                        <button id="prev-year-created" class="btn circular-btn mx-2">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="current-year-created" class="mx-2">{{ now()->year }}</span>
                        <button id="next-year-created" class="btn circular-btn mx-2">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="chart-container flex-grow-1">
                        <canvas id="eventsCreatedChart" style="height: 100%; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Joined Per Month -->
    <div class="row mb-5 col-md-12 mt-2">
        <div class="col-md-12 mt-4">
            <div class="card border-left-info shadow h-100" style="height: 500px; width: 90%;">
                <div class="card-body p-4 d-flex flex-column align-items-center">
                    <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center">
                        Events Joined Per Month
                    </div>
                    <div class="year-navigation d-flex align-items-center mb-3 justify-content-center">
                        <button id="prev-year-joined" class="btn circular-btn mx-2">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="current-year-joined" class="mx-2">{{ now()->year }}</span>
                        <button id="next-year-joined" class="btn circular-btn mx-2">
                            <i class="fas fa-chevron-right"></i>
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
