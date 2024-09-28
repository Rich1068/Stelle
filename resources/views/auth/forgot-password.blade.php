<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->

<div class="white-container"> 
    <!-- Forgot Password Form Section -->
    <div class="fpassword-container text-center w-100" style="max-width: 400px;"> <!-- Restrict width of form container -->
        <!-- Forgot Password Title -->

        <div class="logo-section">
        <!-- Logo -->
            <img src="/images/stellelogo.png" alt="Stelle Logo">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

            <div class="email-label-container mb-4">
                <div class="email-label">
                    <x-input-label for="email" :value="__('Enter Your Email For The Reset Password Link')" />
                </div>
            </div>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Insert Email Here" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <x-auth-session-status class="mb-4" style="text-align: center;" :status="session('status')" />
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <!-- Actions Container -->
            <x-primary-button class="login-button w-100"> <!-- Button spans full width -->
            {{ __('Reset') }}
            </x-primary-button>
        </form>
        <a class="back-to-login" href="{{ route('login') }}">Back To Login</a>
    </div>
</div>
