<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            background: linear-gradient(to bottom right, #F99C9C, #ACDFF6) !important;
            min-height: 100vh !important;
            margin: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            font-family: Arial, sans-serif !important;
        }

        .container {
            background-color: #ffffff !important;
            padding: 30px !important;
            border-radius: 20px !important;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2) !important;
            text-align: center !important;
            max-width: 400px !important;
            width: 100% !important;
            margin: auto !important;
        }

        .button {
            display: inline-block !important;
            background-color: #1E3A8A !important;
            color: white !important;
            padding: 12px 24px !important;
            border-radius: 25px !important;
            font-weight: bold !important;
            text-align: center !important;
            text-decoration: none !important;
            font-size: 16px !important;
            margin-top: 20px !important;
            margin-bottom: 30px !important; /* Increased space below the button */
            transition: none !important; /* Removed hover effect */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="color: #0F1F3F !important; font-size: 24px !important; margin-bottom: 20px !important;">Password Reset Request</h1>
        
        <p style="color: #0F1F3F !important; font-weight: bold !important; font-size: 16px !important; margin: 10px 0 !important;">
            You are receiving this email because we received a password reset request for your account.
        </p>
        
        <a href="{{ $url }}" class="button">Reset Password</a>
        
        <p style="color: #0F1F3F !important; font-weight: bold !important; font-size: 16px !important; margin: 10px 0 !important;">
            If you did not request a password reset, no further action is required.
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Email template loaded');
        });
    </script>
</body>
</html>
