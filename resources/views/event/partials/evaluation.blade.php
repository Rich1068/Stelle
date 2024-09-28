@extends('layouts.app')

@section('body')
<div class="top-container mb-4">
    <h2 class="font-weight-bold">
        <i class="fas fa-chart-bar"></i> Evaluation Form Analytics
    </h2>
</div>
<div class="container mt-4">
    <div class="row mt-2 mb-4">
        <!-- Total Users Who Answered Card -->
        <div class="col-md-6 mb-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Users Who Answered
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                            <div class="text-xs font-weight-bold text-muted">
                                Out of {{ $currentParticipants }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participation Rate Card -->
        <div class="col-md-6 mb-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Participation Rate
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $participationRate }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="top-container-2 mb-4">
        <h5 class="font-weight-bold">
            <i class="fas fa-comment-dots"></i> Answers - Comment Questions
        </h5>
    </div>

    @if(!empty($questionsData))
        <!-- Grouped Comment Questions -->
        @php $commentCount = 0; @endphp
        <div class="row mb-4">
            @foreach ($questionsData as $index => $questionData)
                @if ($questionData['type'] === 'comment')
                    <div class="col-md-4 mb-2">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            {{ $questionData['question'] }}
                                        </div>
                                        <ul class="list-unstyled mt-2" style="font-size: 0.9rem;">
                                            @foreach ($questionData['answers'] as $answer)
                                                <li><i class="fas fa-comment"></i> {{ $answer }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $commentCount++; @endphp
                    @if ($commentCount % 3 === 0)
                        </div><div class="row mb-4">
                    @endif
                @endif
            @endforeach
        </div>

        <div class="top-container-2 mb-4">
            <h5 class="font-weight-bold">
                <i class="fas fa-check-square"></i> Answers - Radio Questions
            </h5>
        </div>

        <!-- Grouped Radio Questions -->
        @php $radioCount = 0; @endphp
        <div class="row mb-4">
            @foreach ($questionsData as $index => $questionData)
                @if ($questionData['type'] === 'radio')
                    <div class="col-md-4 mb-2">
                        <div class="card border-left-info shadow h-100 py-1">
                            <div class="card-body" style="padding: 10px;">
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="font-size: 1.1rem;">
                                            {{ $questionData['question'] }}
                                        </div>
                                        <div class="chart-container mt-2" style="position: relative; height: 20vh; width: 100%;">
                                            <canvas id="radioChart_{{ $index }}"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $radioCount++; @endphp
                    @if ($radioCount % 3 === 0)
                        </div><div class="row mb-4">
                    @endif
                @endif
            @endforeach
        </div>

        <div class="top-container-2 mb-4">
            <h5 class="font-weight-bold">
                <i class="fas fa-users"></i> Event Participants - Ages
            </h5>
        </div>

   <!-- Container for Age Distribution -->
<div class="container mt-3">
    <div class="d-flex justify-content-center">
        <div class="card border-left-primary mb-4" style="width: 100%; max-width: 600px; margin-right: 20px;"> <!-- Add margin-right for spacing -->
            <div class="card-body d-flex flex-column align-items-center">
                <h6 class="font-weight-bold text-dark text-center">Individual Age Distribution</h6>
                <div class="chart-container" style="position: relative; height: 30vh; width: 100%; display: flex; justify-content: center; align-items: center;">
                    <canvas id="individualAgePieChart" style="max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="card border-left-primary mb-4" style="width: 100%; max-width: 600px;"> <!-- No margin-right here -->
            <div class="card-body d-flex flex-column align-items-center">
                <h6 class="font-weight-bold text-dark text-center">Age Range Distribution</h6>
                <div class="chart-container" style="position: relative; height: 30vh; width: 100%; display: flex; justify-content: center; align-items: center;">
                    <canvas id="ageRangePieChart" style="max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


        <div class="top-container-2 mb-4">
            <h5 class="font-weight-bold">
                <i class="fas fa-venus"></i>
                <i class="fas fa-mars"></i> Event Participants - Gender Distribution
            </h5>
        </div>

        <div class="container mt-3">
            <div class="d-flex justify-content-center">
                <div class="card mt-3 border-left-primary mb-4" style="width: 100%; max-width: 600px;">
                    <div class="card-body d-flex flex-column align-items-center">
                        <h6 class="font-weight-bold text-dark text-center">Gender Distribution</h6>
                        <div class="chart-container" style="position: relative; height: 30vh; width: 100%; display: flex; justify-content: center; align-items: center;">
                            <canvas id="genderPieChart" style="max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Initialize Chart.js for Individual Ages -->
<script>
    var ctx = document.getElementById('individualAgePieChart').getContext('2d');
    
    // Prepare individual age data for the chart
    var individualAgeLabels = @json($userAges->unique()->values()); // Unique ages
    var individualAgeCounts = @json($userAges->countBy()->values()); // Count occurrences of each age

    var individualAgeData = {
        labels: individualAgeLabels,
        datasets: [{
            data: individualAgeCounts,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FFCD56'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FFCD56']
        }]
    };

    var individualAgeChart = new Chart(ctx, {
        type: 'pie',
        data: individualAgeData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
</script>

<!-- Initialize Chart.js for Age Ranges -->
<script>
    var ctx = document.getElementById('ageRangePieChart').getContext('2d');
    
    // Prepare age range data for the chart
    var ageRangeLabels = @json(array_keys($ageRanges)); // Age range labels
    var ageRangeCounts = @json(array_values($ageRanges)); // Count of users in each range

    var ageRangeData = {
        labels: ageRangeLabels,
        datasets: [{
            data: ageRangeCounts,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FFCD56'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FFCD56']
        }]
    };

    var ageRangeChart = new Chart(ctx, {
        type: 'pie',
        data: ageRangeData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
</script>

<!-- Initialize Chart.js for Gender Distribution -->
<script>
    var ctx = document.getElementById('genderPieChart').getContext('2d');
    
    // Prepare gender data for the chart
    var genderLabels = @json(array_keys($genderDistribution)); // Gender labels
    var genderCounts = @json(array_values($genderDistribution)); // Gender counts

    var genderData = {
        labels: genderLabels,
        datasets: [{
            data: genderCounts,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
        }]
    };

    var genderChart = new Chart(ctx, {
        type: 'pie',
        data: genderData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
</script>

<!-- Initialize Chart.js for Radio Questions -->
<script>
    @foreach($questionsData as $index => $questionData)
        @if($questionData['type'] === 'radio')
            var ctx_{{ $index }} = document.getElementById('radioChart_{{ $index }}').getContext('2d');

            var chartData_{{ $index }} = {
                labels: @json($staticRadioOptions),
                datasets: [{
                    label: '{{ $questionData['question'] }}',
                    data: @json($questionData['values']),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            var chartOptions_{{ $index }} = {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            };

            var radioChart_{{ $index }} = new Chart(ctx_{{ $index }}, {
                type: 'bar',
                data: chartData_{{ $index }},
                options: chartOptions_{{ $index }}
            });
        @endif
    @endforeach
</script>

@endsection
