<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <title>Stelle</title>
    <link rel="icon" href="{{ asset('images/stelle_icon.png') }}" type="image/x-icon">
    <script>
        document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === "F12") {
                event.preventDefault();
            }
            if (event.ctrlKey && (event.shiftKey && ['I', 'J'].includes(event.key)) || event.key === 'U') {
                event.preventDefault();
            }
        });
    </script>
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
            
        }
        .divider {
            border-top: 2px solid darkblue;
            margin: 10px 0;
            width: 100%
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
            <h2>YOUR ACCOUNT HAS BEEN DELETED</h2>
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
