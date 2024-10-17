<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F99C9C; /* Fallback background color */
        }
        .outer {
            background: linear-gradient(to bottom right, #F99C9C, #ACDFF6); /* Gradient background */
            padding: 20px; /* Space around the container */
        }
        .container {
            background-color: white; /* White background for the container */
            border-radius: 10px; /* Rounded corners */
            width: 100%;
            max-width: 600px; /* Maximum width */
            padding: 30px; /* Padding inside the container */
            margin: 20px auto; /* Center the container */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Box shadow */
            overflow: hidden; /* Ensure no content overflows */
            box-sizing: border-box; /* Include padding in width */
        }
        h1 {
            color: #1a1a5e; /* Dark blue color for heading */
            margin-bottom: 15px; /* Space below the heading */
            font-weight: bold; /* Bolded text */
        }
        p {
            margin: 10px 0; /* Space around paragraphs */
            color: #1a1a5e; /* Dark blue */
        }
        .button {
            background-color: #1a1a5e; /* Dark blue */
            border: none;
            color: #ffff !important; /* White text */
            padding: 12px 24px; /* Padding for button */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 20px auto; /* Center the button */
            cursor: pointer;
            border-radius: 15px; /* Rounded corners */
        }
        .divider {
            margin: 20px 0; /* Space around divider */
            border-bottom: 2px solid #1a1a5e; /* Dark blue line */
        }
        .logo {
            max-width: 240px; /* Maximum width for the logo */
            width: 100%; /* Full width */
            height: auto; /* Maintain aspect ratio */
            margin: 40px 0 25px; /* Space around the logo */
        }
        /* Media query for smaller screens */
        @media (max-width: 600px) {
            .container {
                padding: 15px; /* Reduce padding for smaller screens */
            }
            h1 {
                font-size: 20px; /* Smaller heading size on mobile */
            }
            p {
                font-size: 14px; /* Smaller font size for mobile */
            }
            .logo {
                max-width: 180px; /* Smaller logo for mobile */
            }
            .button {
                font-size: 14px; /* Smaller button text */
                padding: 10px 20px; /* Adjust padding for button */
            }
        }
    </style>
</head>
<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="min-height: 100vh;">
        <tr>
            <td align="center" valign="middle"> <!-- Center vertically -->
                <div class="outer">
                    <div class="container">
                        <img src="https://i.postimg.cc/vTVtk9nL/stellelogo.png" alt="Logo" class="logo">

                        <h1>Hello {{ $user->first_name }},</h1>

                        <div class="divider"></div> <!-- Divider below the heading -->

                        <p>You have been accepted into the event <strong>{{ $event->title }}</strong>!</p>

                        <p>Click the button below to view the event details:</p>

                        <a href="{{ url('/event/' . $event->id) }}" class="button">Go to Event Page</a>

                        <div class="divider"></div> <!-- Divider below the button -->

                        <p>We look forward to seeing you there!</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
