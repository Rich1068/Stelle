@extends('layouts.app')

@section('body')
<div class="register-container-wrapper">
    <section class="register-section">
        <header class="register-header">
            <h2 class="register-title text-lg font-medium text-gray-900">
                <i class="fas fa-user-plus"></i> {{ __('Create New Account') }}
            </h2>
            <p class="register-description mt-1 text-sm text-gray-600">
                {{ __("Fill in the details to create a new account.") }}
            </p>
        </header>

        <!-- Form should submit to the storeuser route (POST) -->
        <form method="POST" action="{{ route('superadmin.storeuser') }}" class="register-form" enctype="multipart/form-data">
            @csrf

            <div class="register-container">
                <!-- First Name -->
                <div class="register-item">
                    <x-input-label for="first_name" class="register-label">
                        <i class="fas fa-user"></i> {{ __('First Name:') }}
                    </x-input-label>
                    <x-text-input id="first_name" name="first_name" type="text" class="register-input" :value="old('first_name')" required autofocus autocomplete="first_name" placeholder="First Name" />
                    <x-input-error class="register-error" :messages="$errors->get('first_name')" />
                </div>

                <!-- Last Name -->
                <div class="register-item">
                    <x-input-label for="last_name" class="register-label">
                        <i class="fas fa-user"></i> {{ __('Last Name:') }}
                    </x-input-label>
                    <x-text-input id="last_name" name="last_name" type="text" class="register-input" :value="old('last_name')" required autofocus autocomplete="last_name" placeholder="Last Name" />
                    <x-input-error class="register-error" :messages="$errors->get('last_name')" />
                </div>

                <!-- Email Address -->
                <div class="register-item">
                    <x-input-label for="email" class="register-label">
                        <i class="fas fa-envelope"></i> {{ __('Email:') }}
                    </x-input-label>
                    <x-text-input id="email" name="email" type="email" class="register-input" :value="old('email')" required autocomplete="username" placeholder="example@example.com" />
                    <x-input-error class="register-error" :messages="$errors->get('email')" />
                </div>

                <!-- Password -->
                <div class="register-item">
                    <x-input-label for="password" class="register-label">
                        <i class="fas fa-lock"></i> {{ __('Password:') }}
                    </x-input-label>
                    <x-text-input id="password" name="password" type="password" class="register-input" required autocomplete="new-password" placeholder="Create Password" />
                    <x-input-error class="register-error" :messages="$errors->get('password')" />
                </div>

                <!-- Confirm Password -->
                <div class="register-item">
                    <x-input-label for="password_confirmation" class="register-label">
                        <i class="fas fa-lock"></i> {{ __('Confirm Password:') }}
                    </x-input-label>
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="register-input" required autocomplete="new-password" placeholder="Retype Password" />
                    <x-input-error class="register-error" :messages="$errors->get('password_confirmation')" />
                </div>

                <!-- Role Dropdown -->
                <div class="register-item">
                    <x-input-label for="role_id" class="register-label">
                        <i class="fas fa-user-tag"></i> {{ __('Role:') }}
                    </x-input-label>
                    <select id="role_id" name="role_id" class="register-input" required>
                        <option value="" disabled selected>{{ __('Select Role') }}</option>
                        <option value="1">{{ __('Super Admin') }}</option>
                        <option value="2">{{ __('Admin') }}</option>
                        <option value="3">{{ __('User') }}</option>
                    </select>
                    <x-input-error class="register-error" :messages="$errors->get('role_id')" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="register-actions">
                <x-primary-button class="register-save-button">
                    <i class="fas fa-save"></i> {{ __('Sign Up') }}
                </x-primary-button>
            </div>

        </form>
    </section>
</div>
@endsection
