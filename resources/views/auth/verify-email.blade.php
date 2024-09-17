<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->

<!-- Forgot Password Form Section -->
<div class="white-container">
    <div class="fpassword-container text-center w-100" style="max-width: 400px;">
        
        <!-- Logo Section -->
        <div class="logo-section mb-1"> <!-- Reduced margin below the logo -->
            <img src="/images/stellelogo.png" alt="Stelle Logo">
        </div>
        
        <!-- Divider Below Logo -->
        <hr style="border-top: 2px solid darkblue; margin: 5px 0;"> <!-- Reduced margin for the divider -->

        <!-- Session Status -->
        <x-auth-session-status class="mb-2" :status="session('status')" /> <!-- Reduced margin below session status -->
        <div class="mb-2 text-sm" style="color: #00008B; font-weight: bold; text-align: center;">
            {{ __('Thank you for signing up!') }}
        </div>

        <!-- Verification Message -->
        <div class="mb-2 text-sm" style="color: #00008B; font-weight: bold; text-align: center;">
            {{ __('Please verify your email by clicking the link we sent. If you didn’t receive it, we can send another.') }}
        </div>

        <!-- Divider Below Text -->
        <hr style="border-top: 2px solid darkblue; margin: 5px 0;"> <!-- Reduced margin for the divider -->

        @if (session('status') == 'verification-link-sent')
            <div class="mb-2 font-medium text-sm" style="color: #00008B; font-weight: bold; text-align: center;">
                {{ __('A new verification link has been sent to your email.') }}
            </div>
        @endif

        <!-- Resend Verification Email -->
        <div class="flex items-center justify-between"> <!-- Removed additional vertical spacing here -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-primary-button class="resetpass">
                        {{ __('Resend Verification') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Centered Log Out Text -->
        <div class="LogOuttext mt-2 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <span class="text-sm logout-span" onclick="this.closest('form').submit();">
    {{ __('Log Out') }}
</span>

            </form>
        </div>
    </div>
</div>
