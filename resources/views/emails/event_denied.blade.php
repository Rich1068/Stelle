<!DOCTYPE html>
<html>
<head>
    <title>Event Denial Notification</title>

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
    <h1>Hello, {{ $user->first_name }}</h1>
    <p>We regret to inform you that your request to join the event: <strong>{{ $event->title }}</strong> has been denied.</p>
    <p>If you have any questions, please feel free to contact the creator of the event</p>
    <p>Meanwhile, Check out other events at Stelle </p>
    <a href="{{ route('event.list') }}" class="button">View Events</a>
</body>
</html>
