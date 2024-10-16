<div class="analytics-info">
    <div class="col-12 mb-4"> <!-- Increased margin for space between cards -->
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
                        <span id="current-year" class="mx-2">{{ now()->year }}</span> <!-- Display the current year -->
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