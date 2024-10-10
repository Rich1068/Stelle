<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1a1a5e; /* Dark blue */
            background: linear-gradient(to bottom right, #F99C9C, #ACDFF6); /* Gradient background */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 10px; /* Reduced border radius */
            width: 80%;
            max-width: 600px;
            padding: 20px; /* Adjusted padding inside the container */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center text for main content */
            min-height: 400px; /* Increased height */
        }
        h1 {
            margin-bottom: 25px; /* Increased space below the heading */
        }
        p {
            margin: 20px 0; /* Increased vertical space around paragraphs */
        }
        .button {
            background-color: #1a1a5e; /* Dark blue */
            border: none;
            color: white;
            padding: 12px 24px; /* Increased padding for button */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 20px auto; /* Adjusted margin */
            cursor: pointer;
            border-radius: 4px;
        }
        .event-details {
            margin-top: 25px; /* Increased margin above the event details */
            font-size: 14px;
            color: #1a1a5e; /* Dark blue */
            text-align: left; /* Align text to the left for event details */
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove padding */
        }
        .event-details li {
            margin: 20px 0; /* Increased space between list items */
        }
        .event-details-title {
            position: relative; /* Set position for the title */
            padding-bottom: 10px; /* Space between title and line */
        }
        .event-details-title::after {
            content: ""; /* Empty content for the line */
            display: block; /* Make it a block element */
            border-bottom: 2px solid #1a1a5e; /* Dark blue line */
            margin-top: 5px; /* Space above the line */
        }
        .divider {
            margin: 20px 0; /* Space above and below the divider */
            border-bottom: 2px solid #1a1a5e; /* Dark blue line */
        }
        .logo {
            max-width: 240px;
            width: 100%;
            height: auto;
            margin: 40px 0 25px; /* Increased margin-top for more space above the logo */
        }
        .icon {
            margin-right: 8px; /* Reduced space between icon and text */
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://i.postimg.cc/vTVtk9nL/stellelogo.png" alt="Logo" class="logo">

        <h1><i class="fas fa-bell icon"></i> Reminder: Your event is starting soon, {{ $user->first_name }}!</h1>

        <p>You have joined the event <strong>{{ $event->title }}</strong>, and we wanted to remind you that it starts soon.</p>

        <p class="event-details-title"><strong>Event Details:</strong></p>
        
        <ul class="event-details">
            <li><i class="fas fa-calendar-alt icon"></i><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }}</li>
            <li><i class="fas fa-clock icon"></i><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</li>
            <li><i class="fas fa-map-marker-alt icon"></i><strong>Address:</strong> {{ $event->address }}</li>
        </ul>

        <div class="divider"></div> <!-- Divider below Address -->

        <p>Click the button below to view more details about the event:</p>

        <a href="{{ url('/event/' . $event->id) }}" class="button">View Event Details</a>
    </div>
</body>
</html>
