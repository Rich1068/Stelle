@extends('layouts.app')

@section('body')

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
        <i class="fas fa-user-edit"></i> Edit Profile Section
        </h2>
    </div>
</div>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                <div class="side-by-side-container">
                    <!-- Profile Edit Section (left) -->
                    <div class="profile-edit-section">
                        <h3 class="profile-edit-header">{{ __('Edit Profile') }}</h3>
                        <div class="profile-edit-description">
                            {{ __('Update your profile information.') }}
                        </div>
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- Container for vertical alignment of password update and delete account sections (right) -->
                    <div class="vertical-align-container">
                        <!-- Separate Container for Password Update Section -->
                        <div class="password-update-container">
                            <h3 class="password-update-header">{{ __('Update Password') }}</h3>
                            <div class="password-update-description">
                                {{ __('Change your password.') }}
                            </div>
                            @include('profile.partials.update-password-form')
                        </div>

                        <!-- Separate Container for Delete Account Section -->
                        <div class="delete-account-container">
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
    </div>

    <style>
        /* Global Styling */
        h3 {
            color: #003366; /* Dark blue */
            font-weight: bold; /* Bold text */
        }

        /* Container Layout */
        .container {
            width: 100%; /* Set the width of the container to 80% */
            margin: 0 auto; /* Center the container */
        }

        .side-by-side-container {
            display: flex;
            justify-content: space-between; /* Space between sections */
            gap: 20px; /* Space between columns */
            flex-wrap: wrap; /* Allow items to wrap on smaller screens */
        }


        /* Vertical Alignment Container */
        .vertical-align-container {
            display: flex;
            flex-direction: column; /* Stack vertically */
            gap: 20px; /* Space between password and delete sections */
            flex: 1; /* Allow to grow and fill space */
            min-width: 300px; /* Minimum width for smaller screens */
        }

        /* Individual Sections (Update Password, Delete Profile) */
        .password-update-container,
        .delete-account-container {
            background-color: #f9f9f9; /* Background color for sections */
            padding: 20px; /* Padding for sections */
            border-radius: 10px; /* Rounded corners */
            box-shadow: none; /* No box shadow */
        }

        /* Make sections behave well for smaller screens */
        @media (max-width: 768px) {
            .side-by-side-container {
                flex-direction: column; /* Stack vertically on smaller screens */
                width: 100%; /* Ensure full width on mobile */
            }

            .profile-edit-section,
            .vertical-align-container {
                width: 100%; /* Ensure full width for each section on mobile */
                margin: 0; /* Reset margin */
            }
        }
    </style>
@endsection
