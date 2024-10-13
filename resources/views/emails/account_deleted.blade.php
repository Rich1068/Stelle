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
            width: 90%;
            max-width: 600px; /* Max width for container */
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center; /* Centered text for content */
            margin: 0 auto;
            box-sizing: border-box; /* Ensure padding is included in width calculation */
        }
        h1 {
            color: #1a1a5e;
            font-size: 24px;
            margin-top: 30px;
            margin-bottom: 25px;
        }
        p {
            margin: 20px 0;
            font-size: 16px;
            color: #1a1a5e;
        }
        .logo {
            max-width: 240px;
            width: 100%;
            height: auto;
            margin: 40px 0 25px;
        }
        .divider {
            margin: 20px 0;
            border-bottom: 2px solid #1a1a5e;
        }
        .username {
            font-weight: bold;
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
