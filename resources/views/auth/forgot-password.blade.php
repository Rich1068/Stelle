<link rel="stylesheet" href="/css/bootstrap.min.css">
<title>Stelle</title>
<link rel="icon" href="{{ asset('images/stelle_icon.png') }}" type="image/x-icon">

<div class="white-container"> 
    <!-- Forgot Password Form Section -->
    <div class="fpassword-container text-center w-100" style="max-width: 400px;"> <!-- Restrict width of form container -->
        <!-- Forgot Password Title -->

        <div class="logo-section">
            <!-- Logo -->
            <img src="/images/stellelogo.png" alt="Stelle Logo">
            <form id="resetForm" method="POST" action="{{ route('password.email') }}">
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

                <!-- Actions Container -->
            <x-primary-button class="login-button w-100" id="resetButton" onclick="changeButtonText()"> <!-- Button spans full width -->
                {{ __('Reset') }}
            </x-primary-button>
            </form>

            <a class="back-to-login" href="{{ route('login') }}">Back To Login</a>
    </div>
</div>

<script>
    function changeButtonText() {
        var resetButton = document.getElementById('resetButton');
        resetButton.innerHTML = 'Sending...';
        resetButton.disabled = true;
        
        document.getElementById('resetForm').submit();
    }
</script>
