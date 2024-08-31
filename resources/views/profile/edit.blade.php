@extends('layouts.app')

@section('body')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="side-by-side-container">
                <!-- Profile Edit Section (left) -->
                <div class="profile-edit-section">
                    <h3 class="profile-edit-header">{{ __('Edit Profile') }}</h3>
                    <div class="profile-edit-description">
                        {{ __('Update your profile information.') }}
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Container for vertical alignment of the password update and delete account sections (right) -->
                <div class="vertical-align-container">
                    <!-- Update Password Section (top-right) -->
                    <div class="password-update-section">
                        <h3 class="password-update-header">{{ __('Update Password') }}</h3>
                        <div class="password-update-description">
                            {{ __('Change your password.') }}
                        </div>
                        @include('profile.partials.update-password-form')
                    </div>

                    <!-- Delete Account Section (bottom-right) -->
                    <div class="delete-profile-section">
                        <h3 class="delete-profile-header">{{ __('Delete Account') }}</h3>
                        <div class="delete-profile-description">
                            {{ __('Permanently delete your account.') }}
                        </div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
