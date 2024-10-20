<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/stelle_icon.png') }}" type="image/x-icon">
    <title>Stelle</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->
    <style>
        .login-container input[type="email"], 
        .login-container input[type="password"] {
            background-color: #f0f0f0;
            border: 1px solid #D9D9D9;
            border-radius: 10px;
            font-size: 1rem;
            width: 85%;
            margin-bottom: 1rem;
        }

        /* Image section adjustments */
        .image-section {
            position: relative;
            text-align: center;
            margin-bottom: 20px; /* Add some space below the image section */
        }

        /* Position stellelogo at the bottom of the illustration */
        .stelle-logo {
            margin-bottom: 20px;
            max-width: 160px; /* Adjust the size of the logo */
            margin: auto;
        }
        /* Default hidden on desktop */
    .signup-textm {
        display: none; /* Hidden by default */
    }

    /* Show signup text only on mobile */
    @media (max-width: 768px) {
        .signup-textm {
            display: block; /* Show on mobile */
            margin-top: 15px; /* Add some margin above */
            text-align: center; /* Center text */
            font-size: 0.875rem;
    color: #001f60;
    font-weight: bold;
    margin-top: 10px;
    text-align: center;
    text-align: center; /* Center text on desktop */
    margin-top: 20px; /* Space above the signup text */

        }
    }

        /* Hide image section and all non-login elements on mobile devices */
        @media (max-width: 900px) {

            .stelle-logo {
                margin-top: 20px;
            margin-bottom: 20px;
            max-width: 160px; /* Adjust the size of the logo */
            margin: auto;
        }
            .image-section {
                display: none; /* Hides the image section on mobile */
            }

            .main-container {
                width: 95% !important; /* Set width to 95% */
                margin: 0 auto; /* Center the main container */
            }

            .login-container {
                padding: 15px; /* Adjust padding for mobile */
                width: 80%; /* Ensure full width for the login container */
                margin: 0 auto; /* Center the container */
                display: flex; /* Use flexbox for centering */
                flex-direction: column; /* Stack contents vertically */
                align-items: center; /* Center items horizontally */
            }

            .login-container input[type="email"], 
            .login-container input[type="password"] {
                background-color: #f0f0f0;
                border: 1px solid #D9D9D9;
                border-radius: 10px;
                font-size: 1rem;
                width: 95%;
                margin-bottom: 1rem;
                max-width: 600px; /* Set a max width to ensure forms are longer */
            }

            /* Adjust child elements' width */
            .login-title, 
            .mb-4, 
            .remember-me, 
            .actions-container, 
            .or-divider, 
            .google-button {
                width: 100%; /* Allow them to take full width */
                max-width: 600px; /* Set a max width to ensure forms are longer */
                text-align: center; /* Center text */
            }

            .login-title {
                font-size: 1.5rem; /* Adjust font size */
                margin-bottom: 20px; /* Add some space below */
            }

            .actions-container {
                display: flex;
                flex-direction: column; /* Stack actions vertically */
                align-items: center; /* Center items */
            }

            .or-divider {
                margin: 10px 0; /* Add margin */
            }

            .google-button {
                margin-top: 10px; /* Add margin above */
            }

            /* Show signup text below the Google button on mobile */
            .signup-text {
                display: block; /* Ensure signup text is visible on mobile */
                margin-top: 15px; /* Add margin above */
                text-align: center; /* Center text */
            }

            /* Make signup text hidden by default */
            .signup-text.hidden {
                display: none; /* Hide signup text on desktop */
            }
        }

        /* Default styles (Desktop) */
        .signup-text {
            text-align: center; /* Center text on desktop */
            margin-top: 20px; /* Space above the signup text */
        }

        .custom-alert-danger {
        background-color: #f8d7da; /* Light red background */
        border: 1px solid #f5c2c7; /* Red border */
        color: #721c24; /* Darker red text */
        border-radius: 10px; /* Rounded corners */
        padding: 15px; /* Padding inside the alert box */
        font-size: 16px; /* Slightly larger font size */
        margin: 20px 0; /* Space around the alert box */
        display: flex; /* Flexbox for aligning items */
        align-items: center; /* Vertically center items */
    }

    /* Icon inside the alert */
    .custom-alert-danger .alert-icon {
        margin-right: 10px; /* Space between icon and text */
        font-size: 24px; /* Larger icon size */
        color: #721c24; /* Darker red for icon */
    }

    /* Close button inside the alert */
    .custom-alert-danger .close {
        margin-left: auto; /* Push close button to the right */
        color: #721c24; /* Dark red for close icon */
        font-size: 20px; /* Larger close icon */
        cursor: pointer; /* Pointer cursor for close button */
    }

    /* On hover, change close button color */
    .custom-alert-danger .close:hover {
        color: #491217; /* Darker shade of red on hover */
    }
    </style>
</head>


<body>

    <!-- Error Alert at the Top -->
    @if ($errors->has('google_login_error'))
    <div class="custom-alert-danger" style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 1200px; z-index: 1000;">
        <i class="fas fa-exclamation-circle alert-icon"></i>
        {{ $errors->first('google_login_error') }}
        <span class="close" onclick="this.parentElement.style.display='none';">&times;</span>
    </div>
    @endif
    @if ($errors->has('login'))
    <div class="custom-alert-danger" style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 1200px; z-index: 1000;">
        <i class="fas fa-exclamation-circle alert-icon"></i>
        {{ $errors->first('login') }}
        <span class="close" onclick="this.parentElement.style.display='none';">&times;</span>
    </div>
    @endif
    <!-- Main Content -->
    <div class="main-container">
        <!-- Image Section -->
        <div class="image-section">
            <img src="/images/illustration1.png" alt="Illustration" class="illustration">
            <p class="signup-text">Don't have an account? <a href="/register" class="signup-link">Sign Up Here</a></p>
        </div>

        <!-- Login Form Section -->
        <div class="login-container">
            <img src="/images/stellelogo.png" alt="Stelle Logo" class="stelle-logo"> <!-- Stelle logo positioned at the bottom -->  
            <!-- Login Title -->
            <h2 class="login-title">Login</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4 remember-me">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Actions Container for alignment -->
                <div class="actions-container">
                    @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="login-button">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

                <div class="or-divider">
                    <span>or</span>
                </div>

                <div class="google-button">
                    <a href="{{ route('google.redirect') }}">
                        <img src="images/googleIcon.png" alt="Google Icon" class="google-icon">
                        Login with Google
                    </a>
                </div>
            </form>

            <!-- Signup Text Positioning -->
            <p class="signup-textm">Don't have an account? <a href="/register" class="signup-link">Sign Up Here</a></p>
        </div>
    </div>

</body>
</html>
