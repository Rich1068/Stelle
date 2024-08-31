<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->

<div class="register-account">
    <a class="back-to-login" href="{{ route('login') }}">Back To Login</a>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- First Name -->
        <div class="input-group">
            <x-input-label for="first_name" class="register-label">
                {{ __('First Name') }}
            </x-input-label>
            <x-text-input id="first_name" class="register-input" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first-name" placeholder="Input First Name Here" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="input-group">
            <x-input-label for="last_name" class="register-label">
                {{ __('Last Name') }}
            </x-input-label>
            <x-text-input id="last_name" class="register-input" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last-name" placeholder="Input Last Name Here" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="input-group">
            <x-input-label for="email" class="register-label">
                {{ __('Email') }}
            </x-input-label>
            <x-text-input id="email" class="register-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Input Email Here" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="input-group">
            <x-input-label for="password" class="register-label">
                {{ __('Create Password') }}
            </x-input-label>
            <x-text-input id="password" class="register-input" type="password" name="password" required autocomplete="new-password" placeholder="Create Password Here" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="input-group">
            <x-input-label for="password_confirmation" class="register-label">
                {{ __('Retype Password') }}
            </x-input-label>
            <x-text-input id="password_confirmation" class="register-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Retype Password Here" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="register-button">
                {{ __('Sign Up') }}
            </x-primary-button>
        </div>
    </form>
</div>
