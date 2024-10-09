<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .event-details {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Reminder: Your event is starting soon, {{ $user->first_name }}!</h1>

    <p>You have joined the event <strong>{{ $event->title }}</strong>, and we wanted to remind you that it starts soon.</p>

    <p><strong>Event Details:</strong></p>
    <div class="event-details">
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }}</p>
        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</p>
        <p><strong>Address:</strong> {{ $event->address }}</p>
    </div>

    <p>Click the button below to view more details about the event:</p>

    <a href="{{ url('/event/' . $event->id) }}" class="button">View Event Details</a>


</body>
</html>
