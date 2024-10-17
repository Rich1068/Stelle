<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Denial Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F99C9C;
        }
        .outer {
            background: linear-gradient(to bottom right, #F99C9C, #ACDFF6);
            padding: 20px;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            padding: 30px;
            margin: 20px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            box-sizing: border-box;
        }
        h1 {
            color: #1a1a5e;
            margin-bottom: 15px;
            font-weight: bold;
        }
        p {
            margin: 10px 0;
            color: #1a1a5e;
        }
        .button {
            background-color: #1a1a5e;
            border: none;
            color: white;
            padding: 12px 24px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 20px auto;
            cursor: pointer;
            border-radius: 15px;
        }
        .divider {
            margin: 20px 0;
            border-bottom: 2px solid #1a1a5e;
        }
        .logo {
            max-width: 240px;
            width: 100%;
            height: auto;
            margin: 40px 0 25px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
            h1 {
                font-size: 20px;
            }
            p {
                font-size: 14px;
            }
            .logo {
                max-width: 180px;
            }
            .button {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="min-height: 100vh;">
        <tr>
            <td align="center" valign="middle">
                <div class="outer">
                    <div class="container">
                        <img src="https://i.postimg.cc/vTVtk9nL/stellelogo.png" alt="Logo" class="logo">

                        <h1>Hello {{ $user->first_name }},</h1>

                        <div class="divider"></div>

                        <p>We regret to inform you that your request to join the event <strong>{{ $event->title }}</strong> has been denied.</p>

                        <p>If you have any questions, please feel free to contact the event organizer.</p>

                        <p>In the meantime, feel free to explore other events on Stelle:</p>

                        <a href="{{ route('event.list') }}" class="button">View Events</a>

                        <div class="divider"></div>

                        <p>We hope to see you at other events!</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
