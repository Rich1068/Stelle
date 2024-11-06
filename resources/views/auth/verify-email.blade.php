<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="stylesheet" href="/css/sb-admin.css">
    <title>Stelle</title>
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
        .resetpass {
            width: 200px !important; /* Fixed width */
            height: 40px;
            background-color: #1E3A8A;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            padding: 10px;
            margin-bottom: 20px !important; /* Bottom margin */
            margin-top: 20px !important; /* Top margin */
            display: flex; /* Use flexbox for centering */
            justify-content: center; /* Center text horizontally */
            align-items: center; /* Center text vertically */
            margin: 0 auto; /* Center the button */
        }
        @media (max-width: 600px) {
            .white-container {
                width: 80%;
                margin: 20px auto;
                padding: 15px;
            }
            .logo-section img {
                max-width: 50%;
            }
            .resetpass {
                height: 35px;
                padding: 8px;
                margin-bottom: 20px !important; /* Ensure consistent spacing on mobile */
                margin-top: 20px !important; /* Top margin on mobile */
            }
            .fpassword-container p {
                margin: 5px 0;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <main class="white-container d-flex justify-content-center align-items-center">
        <section class="fpassword-container text-center">
            <!-- Logo Section -->
            <div class="logo-section mb-3">
                <img src="/images/stellelogo.png" alt="Stelle Logo">
            </div>

            <!-- Divider Below Logo -->
            <hr style="border-top: 2px solid darkblue; margin: 5px 0; width: 100%">

            <!-- Session Status & Verification Message -->
            <div style="color: #00008B; font-weight: bold;">
                <p>{{ __('Thank you for signing up!') }}</p>
                <p>{{ __('Please verify your email by clicking the link we sent. If you didn’t receive it, we can send another.') }}</p>
            </div>

            <!-- Divider Below Text -->
            <hr style="border-top: 2px solid darkblue; margin: 5px 0; width: 100%">

            <!-- Resend Verification Email -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="resetpass">
                    {{ __('Send Verification') }}
                </button>
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-2 font-medium text-sm" style="color: #00008B;">
                        {{ __('A verification link has been sent to your email.') }}
                    </div>
                @endif
            </form>

            <!-- Centered Log Out Text -->
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <span class="text-sm logout-span" style="cursor: pointer;" onclick="this.closest('form').submit();">
                    {{ __('Log Out') }}
                </span>
            </form>
        </section>
    </main>
</body>
</html>
