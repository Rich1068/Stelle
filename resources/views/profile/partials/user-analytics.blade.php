<div class="analytics-info">
    <div class="row">
        <!-- Left Column: Cards in one column -->
        <div class="col-md-6 col-sm-12 mb-4"> <!-- Responsive stacking for mobile -->
            <!-- Events Attended -->
            <div class="card border-left-info shadow h-100 mb-4 w-100" style="max-height: 120px; margin-top: 30px;">
                <div class="card-body d-flex align-items-center">
                    <div class="row no-gutters align-items-center w-100">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Events Attended
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAttendedEvents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-info"></i> <!-- Icon for Events Attended -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Certificates -->
            <div class="card border-left-info shadow h-100 mb-4 w-100" style="max-height: 120px; margin-top: 30px;">
                <div class="card-body d-flex align-items-center">
                    <div class="row no-gutters align-items-center w-100">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Certificates
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCertificates }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-certificate fa-2x text-info"></i> <!-- Icon for Certificates -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Chart -->
        <div class="col-md-6 col-sm-12 d-flex flex-column align-items-center mb-4">
            <!-- Events Joined Per Month Chart -->
            <div class="card border-left-info shadow h-100 mb-4 w-100" style="max-height: 240px; width: 90%; margin-top: 30px;">
                <div class="card-body p-2">
                    <div class="text-xs font-weight-bold text-dark-blue text-uppercase mb-1 text-center" style="font-size: 14px;">
                        Events Joined Per Month
                    </div>
                    <div class="year-navigation d-flex align-items-center mb-2 justify-content-center">
                        <button id="prev-year" class="btn circular-btn mx-2">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="current-year" class="mx-2" style="font-size: 14px;">{{ now()->year }}</span>
                        <button id="next-year" class="btn circular-btn mx-2">
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

<!-- Styles for Circular Buttons & Layout -->
<style>
    .chart-container {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .circular-btn {
        background-color: #17a2b8;
        color: #fff;
        border-radius: 50%;
        width: 40px !important;
        height: 40px !important;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 18px !important;
        padding: 0 !important;
        line-height: 1 !important;
        border: none !important;
    }

    .circular-btn i {
        color: #fff !important;
        font-size: 20px !important;
        margin: auto;
    }

    .circular-btn:hover {
        background-color: #138496 !important;
    }

    @media (max-width: 768px) {
        /* Ensure the layout stacks correctly on smaller screens */
        .card {
            margin-top: 20px !important;
            margin-bottom: 20px !important;
        }
        .card-body {
            margin-top: 20px !important;
            margin-bottom: 20px !important;
        }
    }   
</style>

<!-- Chart.js script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentYear = {{ now()->year }};
        const ctx = document.getElementById('eventsJoinedChart').getContext('2d');
        
        // Initialize the chart
        let eventsJoinedChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Events Joined',
                    data: [], // Initial empty data
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
                            stepSize: 1
                        }
                    }]
                }
            }
        });

        // Function to update the chart with new data
        function updateChart(data) {
            eventsJoinedChart.data.datasets[0].data = data.values;
            eventsJoinedChart.update();
        }

        // Fetch data for a specific year
        function fetchYearData(year) {
            fetch(`/profile/{{ $user->id }}/events-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('current-year').innerText = year; // Update the displayed year
                    updateChart(data); // Update chart with new data
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Initial chart load for the current year
        fetchYearData(currentYear);

        // Event listener for 'Previous Year' button
        document.getElementById('prev-year').addEventListener('click', function () {
            currentYear--;
            fetchYearData(currentYear);
        });

        // Event listener for 'Next Year' button
        document.getElementById('next-year').addEventListener('click', function () {
            currentYear++;
            fetchYearData(currentYear);
        });
    });
</script>
