<!-- resources/views/emails/verify-email.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            background: linear-gradient(to bottom right, #F99C9C, #ACDFF6) !important; /* Background gradient */
            min-height: 100vh !important; /* Full height */
            margin: 0 !important; /* No margin */
            display: flex !important; /* Flexbox for centering */
            justify-content: center !important; /* Center horizontally */
            align-items: center !important; /* Center vertically */
            font-family: Arial, sans-serif !important; /* Font style */
        }

        .container {
            background-color: #ffffff !important; /* White background */
            padding: 30px !important; /* Padding inside the container */
            border-radius: 20px !important; /* Rounded corners */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2) !important; /* Shadow effect */
            text-align: center !important; /* Center text */
            max-width: 400px !important; /* Max width of the container */
            width: 100% !important; /* Full width */
            margin: auto !important; /* Center the container */
        }

        .button {
            display: inline-block !important; /* Block for button */
            background-color: #1E3A8A !important; /* Button color */
            color: white !important; /* Button text color */
            padding: 12px 24px !important; /* Button padding */
            border-radius: 25px !important; /* Rounded button */
            font-weight: bold !important; /* Bold text */
            text-align: center !important; /* Center text */
            text-decoration: none !important; /* No underline */
            font-size: 16px !important; /* Font size */
            margin-top: 20px !important; /* Space above the button */
            margin-bottom: 30px !important; /* Increased space below the button */
            transition: none !important; /* Removed hover effect */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="color: #0F1F3F !important; font-size: 24px !important; margin-bottom: 20px !important;">Email Verification</h1>
        
        <p style="color: #0F1F3F !important; font-weight: bold !important; font-size: 16px !important; margin: 10px 0 !important;">
            You are receiving this email because you need to verify your email address.
        </p>
        
        <a href="{{ $url }}" class="button">Verify Email Address</a>
        
        <p style="color: #0F1F3F !important; font-weight: bold !important; font-size: 16px !important; margin: 10px 0 !important;">
            If you did not create an account, no further action is required.
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Email template loaded');
        });
    </script>
</body>
</html>
