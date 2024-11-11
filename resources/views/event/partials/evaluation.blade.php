@extends('layouts.app')

@section('body')
<div class="top-container mb-4">
    <h2 class="font-weight-bold">
        <i class="fas fa-chart-bar"></i> Evaluation Form Analytics
    </h2>
</div>

<div class="custom-bg-white" style="border-radius: 15px; background-color: white; padding-top: 10px;">
    <div class="row mt-2 mb-4">

    <div class="top-container-2 mb-4">
        <h5 class="font-weight-bold">
        <i class="fas fa-poll"></i> Percentage of Participants Response
        </h5>
    </div>
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
    <div class="row mb-4 d-flex align-items-start">
        @foreach ($questionsData as $index => $questionData)
            @if ($questionData['type'] === 'comment')
                <div class="col-md-4 mb-2">
                    <div class="card border-left-info shadow py-2" style="min-height: 100px;">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ $questionData['question'] }}
                            </div>
                            <ul class="list-unstyled mt-2" style="font-size: 0.9rem; max-height: 200px; overflow-y: auto;">
                                @foreach ($questionData['answers'] as $answer)
                                    <li style="margin-bottom: 10px;">
                                        <i class="fas fa-comment"></i>
                                        <div style="display: inline-block; padding: 8px; border-radius: 5px; background-color: #f9f9f9; margin-left: 5px; color: #333;">
                                            {{ $answer }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @endif
        <div class="top-container-2 mb-4">
            <h5 class="font-weight-bold">
                <i class="fas fa-check-square"></i> Answers - Radio Questions
            </h5>
        </div>

   <!-- Grouped Radio Questions -->
@if(!empty($questionsData))
@php $radioCount = 0; @endphp
<div class="row mb-4">
    @foreach ($questionsData as $index => $questionData)
        @if ($questionData['type'] === 'radio')
            <div class="col-md-4 mb-2">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body" style="padding: 0; height: 100%;"> <!-- Remove padding and ensure height is 100% -->
                        <div class="row no-gutters align-items-center" style="height: 100%;"> <!-- Make the row fill the card height -->
                            <div class="col">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="font-size: 1.1rem; padding: 10px;"> <!-- Add padding to the question text -->
                                    {{ $questionData['question'] }}
                                </div>
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="font-size: 0.9rem; padding-left: 10px;">
                                    Average Score: {{ $questionData['average'] }}/5
                                </div>
                                <div class="chart-container mt-2" style="position: relative; height: calc(100% - 50px); width: 100%; padding: 10px;"> <!-- Adjust the height and add padding -->
                                    <canvas id="radioChart_{{ $index }}" style="height: 100%; width: 100%; margin: 0;"> <!-- Ensure canvas takes full width and height -->
                                    </canvas>
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
<style>
.custom-bg-white {
    border-radius: 15px; /* Add border radius */
    max-width: 80%;
    padding: 12px;
    align-items: center; /* This is not necessary unless you're using flexbox */
    margin: auto; /* Center the element */
    background-color: white; /* Set background color */
    background-color: rgba(255, 255, 255, 0.4) !important; /* Semi-transparent white */
    margin-bottom: 30px !important;
}

    </style>

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
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const value = tooltipItem.raw;
                            const total = tooltipItem.dataset.data.reduce((acc, curr) => acc + curr, 0);
                            const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                            return `${tooltipItem.label}: ${value} (${percentage}%)`; // Display count and percentage
                        }
                    }
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
