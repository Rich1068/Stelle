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
    </style>
</head>
<body>
    <h1>Hello {{ $user->first_name }},</h1>
    <p>You have been accepted into the event <strong>{{ $event->title }}</strong>!</p>
    <p>Click the button below to view the event details:</p>

    <!-- Button to redirect to event page -->
    <a href="{{ url('/event/' . $event->id) }}" class="button">Go to Event Page</a>

    <p>We look forward to seeing you there!</p>

</body>
</html>