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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $answeredUsers }}</div>
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
    @endif
</div>


<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                },
                scales: {
                    y: {
                        ticks: {
                            stepSize: 1, // Increment the y-axis by 1
                            beginAtZero: true // Ensure the y-axis starts from 0
                        }
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
