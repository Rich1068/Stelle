<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->

<div class="main-container">
    <!-- Image Section -->
    <div class="image-section">
        <!-- Include your desired image here -->
        <img src="/images/illustration1.png" alt="Illustration">

        <!-- Sign Up Text -->
        <p class="signup-text">Don't have an account? <a href="{{route('register') }}" class="signup-link">Sign Up Here</a></p>
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
        </form>
    </div>
</div>
