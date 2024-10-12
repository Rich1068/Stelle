<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css">
    <style>
        .white-container {
            background-color: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .alert-title {
            color: #8B0000; /* Dark red */
            font-weight: bold;
            text-align: center;
            text-decoration: underline; /* Underline the text */
        }
        .divider {
            border-top: 2px solid darkblue;
            margin: 10px 0;
        }
        .logo-section img {
            max-width: 240px;
            height: auto;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .alert-message {
            color: #1a1a5e;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="white-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="/images/stellelogo.png" alt="Stelle Logo">
        </div>

        <!-- Divider Below Logo -->
        <hr class="divider">

        <!-- Alert Title -->
        <div class="alert-title">
            <h2>Your account has been deleted</h2>
        </div>

        <!-- Divider Below Title -->
        <hr class="divider">

        <!-- Message Body -->
        <div class="alert-message">
            <p>Your account is no longer active. If this was a mistake, please reach out to us at <a href="mailto:stelle.psite@gmail.com">stelle.psite@gmail.com</a> for assistance.</p>
        </div>
    </div>
</body>
</html>
