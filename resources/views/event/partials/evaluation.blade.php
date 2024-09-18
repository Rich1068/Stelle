@extends('layouts.app')

@section('body')
    <div class="container">
        <h1>Evaluation Results</h1>

        <!-- Display the total number of users who answered the form and the participation rate -->
        <h3>Total Users Who Answered: {{ $totalUsers }} / {{ $currentParticipants }} ({{ $participationRate }}% Participation)</h3>

        <!-- Tabs for Individual Ages and Age Ranges -->
        <ul class="nav nav-tabs" id="ageTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="individual-tab" data-toggle="tab" href="#individual" role="tab" aria-controls="individual" aria-selected="true">Individual Ages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="range-tab" data-toggle="tab" href="#range" role="tab" aria-controls="range" aria-selected="false">Age Ranges</a>
            </li>
        </ul>

        <!-- Tab Content for Age Ranges and Individual Ages -->
        <div class="tab-content" id="ageTabsContent">
            <!-- Individual Ages Tab -->
            <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                <h3>Individual Age Distribution</h3>
                <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                    <canvas id="individualAgePieChart"></canvas>
                </div>
            </div>

            <!-- Age Ranges Tab -->
            <div class="tab-pane fade" id="range" role="tabpanel" aria-labelledby="range-tab">
                <h3>Age Range Distribution</h3>
                <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                    <canvas id="ageRangePieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- New Container for Gender Distribution -->
        <div class="container mt-5">
            <h3>Gender Distribution</h3>
            <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                <canvas id="genderPieChart"></canvas>
            </div>
        </div>

        <!-- Display the comments and radio question results -->
        @if(!empty($questionsData))
            @foreach ($questionsData as $index => $questionData)
                @if ($questionData['type'] === 'comment')
                    <!-- Display Comment Question and Answers -->
                    <div>
                        <h3>{{ $questionData['question'] }}</h3>
                        <ul>
                            @foreach ($questionData['answers'] as $answer)
                                <li>{{ $answer }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif ($questionData['type'] === 'radio')
                    <!-- Display Radio Question and Chart -->
                    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                        <h3>{{ $questionData['question'] }}</h3>
                        <canvas id="radioChart_{{ $index }}"></canvas>
                    </div>
                @endif
            @endforeach
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
            labels: individualAgeLabels, // Unique ages as labels
            datasets: [{
                data: individualAgeCounts, // Count of users in each age
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
            labels: ageRangeLabels, // Age range labels
            datasets: [{
                data: ageRangeCounts, // Count of users in each range
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
            labels: genderLabels, // Gender labels
            datasets: [{
                data: genderCounts, // Count of users for each gender
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
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Responses'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Rating (1-5)'
                            }
                        }
                    }
                };

                new Chart(ctx_{{ $index }}, {
                    type: 'bar',
                    data: chartData_{{ $index }},
                    options: chartOptions_{{ $index }}
                });
            @endif
        @endforeach
    </script>
@endsection
