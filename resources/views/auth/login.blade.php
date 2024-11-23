<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/stelle_icon.png') }}" type="image/x-icon">
    <title>Stelle</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- <script>
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
    </script> -->

   
    <style>


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

/* Hide image section and all non-login elements on mobile devices */
@media (max-width: 768px) {
    /* Mobile-specific styles */
    .stelle-logo {
        margin-top: 20px !important;
        margin-bottom: 30px !important;
        max-width: 160px !important; /* Adjust logo size */
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .image-section {
        display: none !important; /* Hide image section on mobile */
    }

    .main-container {
        max-width: 80% !important; /* Make the width more flexible */
        margin: 0 auto !important; /* Center the container */
        min-height: 80vh !important; /* Ensure the container takes up full height */
        padding: 15px !important;
        display: flex !important; /* Use flexbox for better centering */
        flex-direction: column !important; /* Stack elements vertically */
        justify-content: center !important; /* Ensure content is centered vertically */
    }

    .login-container {
        max-width: 100% !important; /* Full width for mobile */
        margin: 0 auto !important; /* Center the container */
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        min-height: 90vh !important;
        justify-content: center !important;
        text-align: center;
    }

    .login-title {
        font-size: 1.25rem !important;
        margin-bottom: 20px !important;
        text-align: center !important;
    }

    .actions-container {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        width: 100% !important;
    }

    .or-divider {
        margin: 10px 0 !important;
        text-align: center !important;
    }

    .google-button {
    margin-top: 10px;
    padding: 8px 12px; /* Adjust padding to make it smaller */
    font-size: 0.875rem; /* Slightly smaller font size */
    max-width: 300px; /* Set a maximum width */
    width: auto; /* Remove full width */
    text-align: center;
}


    .signup-text {
        display: block !important;
        margin-top: 15px !important;
        text-align: center !important;
    }

    .signup-textm {
        display: block !important;
        text-align: center !important;
        font-size: 0.875rem !important;
        color: #001f60 !important;
        font-weight: bold !important;
        margin-top: 10px !important;
    }

    /* Input Fields Styling */
    .block.mt-1.w-full {
        width: 400px !important; /* Make input fields take full width */
        padding: 10px !important; /* Add some padding to the inputs */
        font-size: 1rem !important; /* Adjust font size */
        margin-bottom: 15px !important; /* Add some space between inputs */
        align-items: center !important;

    }

    .btn {
        width: 399px !important;
        padding: 12px !important;
        margin-top: 20px !important;
        font-size: 1rem !important;
        margin-bottom: 10px;
    }
}      
                

/* Hide image section and all non-login elements on mobile devices */
@media (max-width: 600px) {
    /* Mobile-specific styles */
    .stelle-logo {
        margin-top: 20px !important;
        margin-bottom: 30px !important;
        max-width: 160px !important; /* Adjust logo size */
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .image-section {
        display: none !important; /* Hide image section on mobile */
    }

    .main-container {
        max-width: 80% !important; /* Make the width more flexible */
        margin: 0 auto !important; /* Center the container */
        min-height: 80vh !important; /* Ensure the container takes up full height */
        padding: 15px !important;
        display: flex !important; /* Use flexbox for better centering */
        flex-direction: column !important; /* Stack elements vertically */
        justify-content: center !important; /* Ensure content is centered vertically */
    }

    .login-container {
        max-width: 100% !important; /* Full width for mobile */
        margin: 0 auto !important; /* Center the container */
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        min-height: 90vh !important;
        justify-content: center !important;
        text-align: center;
    }

    .login-title {
        font-size: 1.25rem !important;
        margin-bottom: 20px !important;
        text-align: center !important;
    }

    .actions-container {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        width: 100% !important;
    }

    .or-divider {
        margin: 10px 0 !important;
        text-align: center !important;
    }

    .google-button {
    margin-top: 10px;
    padding: 8px 12px; /* Adjust padding to make it smaller */
    font-size: 0.875rem; /* Slightly smaller font size */
    max-width: 300px; /* Set a maximum width */
    width: auto; /* Remove full width */
    text-align: center;
}


    .signup-text {
        display: block !important;
        margin-top: 15px !important;
        text-align: center !important;
    }

    .signup-textm {
        display: block !important;
        text-align: center !important;
        font-size: 0.875rem !important;
        color: #001f60 !important;
        font-weight: bold !important;
        margin-top: 10px !important;
    }

    /* Input Fields Styling */
    .block.mt-1.w-full {
        width: 100% !important; /* Make input fields take full width */
        padding: 10px !important; /* Add some padding to the inputs */
        font-size: 1rem !important; /* Adjust font size */
        margin-bottom: 15px !important; /* Add some space between inputs */
        align-items: center !important;

    }

    .btn {
        width: 100% !important;
        padding: 12px !important;
        margin-top: 20px !important;
        font-size: 1rem !important;
        margin-bottom: 10px;
    }
    
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
            <p class="signup-text">Check Out The <a href="https://psite.org/r3/" class="signup-link">Official PSITE-CL Website</a></p>
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
            <p class="signup-textm">Check Out The <a href="https://psite.org/r3/" class="signup-link">Official PSITE-CL Website</a></p>
        </div>
    </div>

</body>
</html>
