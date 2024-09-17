<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->

<div class="white-container">

    <!-- Password Reset Form Section -->
    <div class="fpassword-container text-center w-100 mx-auto" style="max-width: 400px;"> <!-- Restrict width of form container and center -->

        <!-- Logo Section -->
        <div class="logo-section mb-4">
            <!-- Logo -->
            <img src="/images/stellelogo.png" alt="Stelle Logo">
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="email-label-container mb-4 text-center"> <!-- Text Centered -->
                <div class="email-label">
                    <x-input-label for="email" :value="__('Email')" />
                </div>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" placeholder="Insert Email Here" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="forgotpassword-input mb-4 text-center"> <!-- Text Centered -->
                <div class="email-label">
                    <x-input-label for="password" :value="__('Password')" />
                </div>
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Enter Password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="forgotpassword-input mb-4 text-center"> <!-- Text Centered -->
                <div class="email-label">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                </div>
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Submit Button Centered -->
            <div class="flex items-center justify-center mt-4"> <!-- Button Centered -->
                <x-primary-button class="resetpass w-100 text-center"> <!-- Text Centered -->
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>

    </div>
</div>
