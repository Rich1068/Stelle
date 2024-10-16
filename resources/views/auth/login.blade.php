<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->
    <style>
        
        .login-container input[type="email"], .login-container input[type="password"] {
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
        .image-section img.stelle-logo {
            position: absolute;
            bottom: 0;
            left: 57%;
            transform: translateX(-50%);
            max-width: 260px; /* Adjust the size of the logo */
            padding-bottom: 35px;
        }

        /* Hide image section and all non-login elements on mobile devices */
        @media (max-width: 768px) {
            .image-section,
            .divider-line {
                display: none; /* Hides the image section and divider line */
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

            .login-container input[type="email"], .login-container input[type="password"] {
                background-color: #f0f0f0;
                border: 1px solid #D9D9D9;
                border-radius: 10px;
                font-size: 1rem;
                width: 95%;
                margin-bottom: 1rem;
            }

            /* Adjust child elements' width */
            .login-title, .mb-4, .remember-me, .actions-container, .or-divider, .google-button, .signup-text {
                width: 100%; /* Allow them to take full width */
                max-width: 400px; /* Set a max width to ensure forms are longer */
                text-align: center; /* Center text */
            }

            .login-title {
                font-size: 1.5rem; /* Adjust font size */
                margin-bottom: 20px; /* Add some space below */
            }

            .signup-text {
                display: block; /* Ensure signup text is visible on mobile */
                margin-top: 15px; /* Add margin above */
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
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Image Section -->
        <div class="image-section">
            <img src="/images/illustration1.png" alt="Illustration" class="illustration">
            <img src="/images/stellelogo.png" alt="Stelle Logo" class="stelle-logo"> <!-- Stelle logo positioned at the bottom -->
        </div>

        <!-- Divider Line -->
        <div class="divider-line"></div>

        <!-- Login Form Section -->
        <div class="login-container">
            <!-- Login Title -->
            <h2 class="login-title">Login</h2> <!-- Adjusted Login text size and position -->

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
                        <img src="/images/googleicon.png" alt="Google Icon" class="google-icon">
                        Login with Google
                    </a>
                </div>
            </form>

            <!-- Sign Up Text -->
            <p class="signup-text">Don't have an account? <a href="/register" class="signup-link">Sign Up Here</a></p>
        </div>
    </div>
</body>
</html>
