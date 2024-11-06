<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <title>Stelle - Forgot Password</title>
    <link rel="icon" href="{{ asset('images/stelle_icon.png') }}" type="image/x-icon">
    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
                // Reload the page
                window.location.reload();
            }
        });
    </script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .white-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 20px auto;
            width: 400px;
            max-width: 90%;
        }
        .logo-section img {
            max-width: 60%;
            height: auto;
        }
        .login-button {
            width: 200px !important; /* Fixed width */
            height: 50px; /* Increased height */
            background-color: #1E3A8A;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            padding: 10px; /* Increased padding for better appearance */
            font-size: 16px;
            transition: background-color 0.3s; /* Transition for hover effect */
            display: flex; /* Use flexbox for centering text */
            justify-content: center; /* Center text horizontally */
            align-items: center; /* Center text vertically */
            margin: 0 auto; /* Center the button */
        }
        .login-button:hover {
            background-color: #0F2A5B; /* Darker blue on hover */
        }
        .text-dark-blue {
            color: #00008B;
            font-weight: bold;
        }
        .back-to-login {
            display: inline-block;
            margin-top: 15px;
            font-size: 15px;
            color: #1E3A8A;
            font-weight: bold;
            text-decoration: none;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%; /* Full width for the button container */
        }
        @media (max-width: 600px) {
            .white-container {
                width: 80%;
                padding: 15px;
            }
            .logo-section img {
                max-width: 50%;
            }
            .login-button {
                height: 45px;
                padding: 8px;
                font-size: 15px;
            }
            .back-to-login {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="white-container">
        <div class="fpassword-container text-center">
            <!-- Logo Section -->
            <div class="logo-section mb-3">
                <img src="/images/stellelogo.png" alt="Stelle Logo">
            </div>

            <!-- Forgot Password Form -->
            <form id="resetForm" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="email-label-container mb-3">
                    <x-input-label for="email" :value="__('Enter Your Email For The Reset Password Link')" class="text-dark-blue" />
                </div>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Insert Email Here" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />

                <x-auth-session-status class="mb-4 text-dark-blue" :status="session('status')" />

                <!-- Centered Button Container -->
                <div class="button-container">
                    <button type="button" class="login-button" id="resetButton" onclick="changeButtonText()">
                        {{ __('Reset') }}
                    </button>
                    <a class="back-to-login mt-2" href="{{ route('login') }}">Back To Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function changeButtonText() {
            var resetButton = document.getElementById('resetButton');
            resetButton.innerHTML = 'Sending...';
            resetButton.disabled = true;

            // Centering the text for "Sending..." by adding a class
            resetButton.style.justifyContent = 'center'; // Ensure the text is centered
            
            document.getElementById('resetForm').submit();
        }
    </script>
</body>
</html>
