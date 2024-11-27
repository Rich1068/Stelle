<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body style="background: #aac2db;">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header text-white text-center" style="background:#002a60;">
                <h3>Event Information</h3>
            </div>
            <div class="card-body">
                <!-- Event Details -->
                <h4 class="text-primary">Event Details</h4>
                <p><strong>Event Name:</strong> {{ $event->title }}</p>
                @if ($event->start_date != $event->end_date)
                <p><strong>Date:</strong> {{ $event->start_date }} - {{ $event->end_date }}</p>
                @else
                <p><strong>Date:</strong> {{ $event->start_date }}</p>
                @endif
                <p><strong>Time:</strong> {{ $event->start_time }} - {{ $event->end_time }}</p>
                <p><strong>Location:</strong> {{ $event->address }}</p>
                <hr>

                <!-- Participant Details -->
                <h4 class="text-primary">Participant Details</h4>
                <p><strong>Participant Name:</strong> {{ $participant->user->first_name }} {{ $participant->user->last_name }}</p>
                <p><strong>Email:</strong> {{ $participant->user->email }}</p>

                <!-- Status -->
                <h5 class="text-success mt-3">
                    <i class="fas fa-check-circle"></i> Valid QR Code
                </h5>
            </div>
            <div class="card-footer text-center">
                <a href="{{ url('/event/' . $event->id) }}" class="btn btn-primary" style="background:#002a60 !important;">Go to Event Page</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>