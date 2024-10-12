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
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 20px;
            width: 100%; /* Full width */
            max-width: 600px; /* Max width for container */
            padding: 30px; /* Padding inside the container */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center; /* Centered text for content */
            margin: 0 auto; /* Center the container */
        }
        h1 {
            color: #1a1a5e; /* Dark blue */
            font-size: 24px;
            margin-top: 30px; /* Space above the heading */
            margin-bottom: 25px; /* Space below the heading */
        }
        p {
            margin: 20px 0; /* Vertical space for paragraphs */
            font-size: 16px;
            color: #1a1a5e;
        }
        .logo {
            max-width: 240px;
            width: 100%;
            height: auto;
            margin: 40px 0 25px; /* Space around the logo */
        }
        .divider {
            margin: 20px 0; /* Divider space */
            border-bottom: 2px solid #1a1a5e; /* Dark blue line */
        }
        .username {
            font-weight: bold; /* Bold styling for user name */
        }
    </style>
</head>
<body style="background: linear-gradient(to bottom right, #F99C9C, #ACDFF6);">
    <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" style="min-height: 100vh; display: table;">
        <tr>
            <td align="center" valign="middle"> <!-- Center vertically -->
                <div class="container">
                    <img src="https://i.postimg.cc/vTVtk9nL/stellelogo.png" alt="Logo" class="logo">

                    <h1><i class="fas fa-user-slash"></i> Account Deleted</h1>

                    <p>Dear <span class="username">{{ $user->first_name }}</span>,</p>
                    
                    <div class="divider"></div> <!-- Divider below greeting -->

                    <p>Your account has been deleted from our system. If you have any questions or concerns, please contact us at <a href="mailto:stelle.psite@gmail.com">stelle.psite@gmail.com</a></p>

                    <div class="divider"></div> <!-- Divider below main message -->

                    <p>Best regards,</p>
                    <p>Stelle</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
