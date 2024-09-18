@extends('layouts.app')

@section('body')
    <div class="container">
        <h1>Evaluation Results</h1>

        <!-- Display the total number of users who answered the form and the participation rate -->
        <h3>Total Users Who Answered: {{ $totalUsers }} / {{ $currentParticipants }} ({{ $participationRate }}% Participation)</h3>

        @if(!empty($questionsData))
            <!-- Loop through the unified list of questions (comments and radio) -->
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

    <!-- Initialize Chart.js for each radio question -->
    <script>
        @foreach($questionsData as $index => $questionData)
            @if($questionData['type'] === 'radio')
                var ctx_{{ $index }} = document.getElementById('radioChart_{{ $index }}').getContext('2d');

                var chartData_{{ $index }} = {
                    labels: @json($staticRadioOptions), // The static radio options (1, 2, 3, 4, 5)
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

                // Initialize Chart for each radio question
                new Chart(ctx_{{ $index }}, {
                    type: 'bar',
                    data: chartData_{{ $index }},
                    options: chartOptions_{{ $index }}
                });
            @endif
        @endforeach
    </script>
@endsection
