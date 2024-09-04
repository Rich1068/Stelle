@extends('layouts.app')

@section('body')
<div class="super-admin-dashboard container-fluid p-4">


<!-- Header with Title and Navigation -->
<div class="page-title-container">
    <h2 class="font-weight-bold text-dark">Innovations in Mobile Game Development: Optimizing for Performance and User Engagement</h2>
</div>


    <!-- New Section with Stats -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="card text-center p-3 shadow-sm mb-3">
                <div class="text-primary font-weight-bold">Forms Answered</div>
                <h4 class="font-weight-bold">64/67</h4>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-center p-3 shadow-sm mb-3">
                <div class="text-primary font-weight-bold">Participants Registered</div>
                <h4 class="font-weight-bold">67/80</h4>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column with Questions and Charts -->
        <div class="col-lg-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary font-weight-bold">1. How Is the Venue Of The Event?</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item font-weight-bold text-dark rounded-3">The venue was well-organized and comfortable.</li>
                        <li class="list-group-item font-weight-bold text-dark rounded-3">Great location, but the seating could be improved.</li>
                        <li class="list-group-item font-weight-bold text-dark rounded-3">The venue was spacious and clean.</li>
                        <li class="list-group-item font-weight-bold text-dark rounded-3">Good venue, but the acoustics were not ideal.</li>
                        <li class="list-group-item font-weight-bold text-dark rounded-3">Excellent venue with all the necessary facilities.</li>
                    </ul>
                </div>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary font-weight-bold">2. What are your thoughts on the Speaker?</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item font-weight-bold text-dark rounded-3">It was Alright for the most part.</li>
                        <li class="list-group-item font-weight-bold text-dark rounded-3">Could Be Louder But It’s Alright.</li>
                        <li class="list-group-item font-weight-bold text-dark rounded-3">Seminar was good with a good speaker.</li>
                        <li class="list-group-item font-weight-bold text-dark rounded-3">Good.</li>
                        <li class="list-group-item font-weight-bold text-dark rounded-3">Excellent presentation with engaging content.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column with Charts -->
        <div class="col-lg-6">
            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Radio Questions Answers</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Participant Ages</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Direct
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Social
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Referral
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Initialize Pie Chart for Participant Ages
    const ctxPie = document.getElementById('myPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Ages 18-24', 'Ages 25-34', 'Ages 35+'],
            datasets: [{
                data: [55, 30, 15],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            cutout: '70%',
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Initialize Area Chart
    const ctxArea = document.getElementById('myAreaChart').getContext('2d');
    new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Earnings',
                data: [65, 59, 80, 81, 56, 55],
                backgroundColor: 'rgba(78, 115, 223, 0.2)',
                borderColor: '#4e73df',
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#4e73df',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
