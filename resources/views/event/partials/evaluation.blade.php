@extends('layouts.app')

@section('body')
    <div class="container">
        <h1>Evaluation Results</h1>

        <!-- Display Comments Section -->
        @if(!empty($comments))
            <h2>Comments</h2>
            <ul>
                @foreach ($comments as $comment)
                    <li>
                        <strong>{{ $comment['question'] }}:</strong>
                        <ul>
                            @foreach ($comment['answers'] as $answer)
                                <li>{{ $answer }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endif

        <!-- Display Radio Questions Charts -->
        <h2>Radio Question Results</h2>

        @foreach($radioData as $index => $data)
            <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                <h3>{{ $data['question'] }}</h3>
                <!-- Create a unique canvas ID for each radio question -->
                <canvas id="radioChart_{{ $index }}"></canvas>
            </div>
        @endforeach
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Initialize Chart.js for each radio question -->
    <script>
        @foreach($radioData as $index => $data)
            var ctx_{{ $index }} = document.getElementById('radioChart_{{ $index }}').getContext('2d');
            
            var chartData_{{ $index }} = {
                labels: @json($staticRadioOptions), // The static radio options (1, 2, 3, 4, 5)
                datasets: [{
                    label: '{{ $data['question'] }}',
                    data: @json($data['values']),
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
        @endforeach
    </script>
@endsection
