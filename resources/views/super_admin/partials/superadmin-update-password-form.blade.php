<section class="password-update-section">
    <header class="password-update-header">
        <h2 class="password-update-title text-lg font-medium text-gray-900">
            <i class="fas fa-lock"></i> {{ __('Update Password') }}
        </h2>

        <p class="password-update-description mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="password-update-form mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div class="password-update-item">
            <x-input-label for="update_password_current_password" class="password-update-label">
                <i class="fas fa-key"></i> {{ __('Current Password:') }}
            </x-input-label>
            <div class="password-input-container">
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="password-update-input" autocomplete="current-password" placeholder="••••••••" />
                <i class="fas fa-eye password-toggle" onclick="togglePassword('update_password_current_password')"></i>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="password-update-error" />
        </div>

        <!-- New Password -->
        <div class="password-update-item">
            <x-input-label for="update_password_password" class="password-update-label">
                <i class="fas fa-key"></i> {{ __('New Password:') }}
            </x-input-label>
            <div class="password-input-container">
                <x-text-input id="update_password_password" name="password" type="password" class="password-update-input" autocomplete="new-password" placeholder="••••••••" />
                <i class="fas fa-eye password-toggle" onclick="togglePassword('update_password_password')"></i>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="password-update-error" />
        </div>

        <!-- Confirm Password -->
        <div class="password-update-item">
            <x-input-label for="update_password_password_confirmation" class="password-update-label">
                <i class="fas fa-key"></i> {{ __('Confirm Password:') }}
            </x-input-label>
            <div class="password-input-container">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="password-update-input" autocomplete="new-password" placeholder="••••••••" />
                <i class="fas fa-eye password-toggle" onclick="togglePassword('update_password_password_confirmation')"></i>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="password-update-error" />
        </div>

        <div class="password-update-actions">
            <x-primary-button class="password-update-save-button">
                <i class="fas fa-save"></i> {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600 password-update-save-message">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
<!-- Your Blade template HTML -->

<script>
    function togglePassword(id) {
        const passwordInput = document.getElementById(id);
        const toggleIcon = passwordInput.nextElementSibling;
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
</body>
</html>

