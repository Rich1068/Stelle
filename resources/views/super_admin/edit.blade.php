@extends('layouts.app')

@section('body')
@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
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
                            {{ __('Update profile information.') }}
                        </div>
                        @include('super_admin.partials.superadmin-update-profile-information-form')
                    </div>


                    <!-- Container for vertical alignment of password update and delete account sections (right) -->
                    <div class="vertical-align-container">

                        <!-- Separate Container for Password Update Section -->
                        <div class="password-update-container">
                            <h3 class="password-update-header">{{ __('Change Role') }}</h3>
                            @include('super_admin.partials.superadmin-update-role')
                        </div>
                        <!-- Separate Container for Delete Account Section -->
                        <div class="delete-account-container">
                            <h3 class="delete-profile-header">{{ __('Delete Account') }}</h3>
                            <div class="delete-profile-description">
                                {{ __('Permanently delete account.') }}
                            </div>
                            @include('super_admin.partials.superadmin-delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>

@media (max-width: 768px) {
    /* Ensure the parent container is also a flex container */
    .side-by-side-container {
        display: flex; /* Use flexbox to manage layout */
        justify-content: center; /* Center the child containers horizontally */
        align-items: center; /* Center the child containers vertically */
        flex-direction: column; /* Stack child elements vertically */
        width: 100%; /* Ensure it takes full width */
    }

    @media (max-width: 768px) {
    .password-update-container {
        padding: 0px; /* Reduced padding for smaller screens */
        display: flex; /* Use flexbox for centering */
        flex-direction: column; /* Stack items vertically */
        align-items: center; /* Center align the items horizontally */
        justify-content: center; /* Center align the items vertically */
        max-width: 90%; /* Set a max-width to make the container smaller */
        margin: 20px auto; /* Center the container itself with top and bottom margin */
        gap: 10px; /* Reduce space between items */
        width: 100%; /* Ensure the container is full width */
    }
    .form-control {
        max-width: 100% !important;
    }
    
    /* Ensure the dropdown is fully visible in the password update section */
    .password-update-dropdown {
        width: 100%; /* Make dropdown take full width */
        max-width: 100%; /* Prevent overflow */
        margin-bottom: 10px; /* Add some space below the dropdown */
        z-index: 10; /* Ensure dropdown is on top of other elements */
    }

    /* Additional styles for dropdown items if necessary */
    .password-update-dropdown-item {
        width: 100%; /* Full width for dropdown items */
        padding: 10px; /* Padding for dropdown items */
        text-align: center; /* Center text in dropdown items */
    }
}

.custom-file-upload {
    display: flex;
    flex-direction: column; /* Stack elements vertically */
    max-width: 80%;
    text-align: center;
}

.custom-file-button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #003d80; /* Dark blue */
    color: #fff;
    border-radius: 15px; /* Rounded corners */
    cursor: pointer;
    font-size: 14px;
    margin-bottom: 5px; /* Space between button and file name text */
}

.custom-file-button:hover {
    background-color: #002b5c; /* Slightly darker shade for hover */
}

.file-name {
    font-size: 14px;
    color: #555;
    text-align: center;
}


    .delete-account-container {
        padding: 15px; /* Reduced padding for smaller screens */
        display: flex; /* Use flexbox for centering */
        flex-direction: column; /* Stack items vertically */
        align-items: center; /* Center align the items horizontally */
        justify-content: center; /* Center align the items vertically */
        max-width: 90%; /* Set a max-width to make the container smaller */
        margin: 20px auto; /* Center the container itself with top and bottom margin */
        gap: 10px; /* Reduce space between items */
        width: 100%; /* Ensure the container is full width */
    }
    

    /* Ensure items inside the containers are full width */
    .password-update-item,
    .delete-account-item {
        width: 100%; /* Make each item take the full width of the container */
        margin-bottom: 10px; /* Space between items */
        display: flex; /* Enable flex on items for centering */
        justify-content: center; /* Center align item content */
        align-items: center; /* Center align item content vertically */
    }
}


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
            background-color: #ffffff; /* Background color for sections */
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
