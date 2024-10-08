@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="profile-container">
    <!-- Profile Header Section -->
    <div class="profile-header">
        <div class="profile-picture">
            @if($user->profile_picture == null)
                <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture"> 
            @else 
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile picture of {{ $user->first_name }}"> 
            @endif
        </div>
        
        <div class="profile-info">
            <h2 class="name-bold">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h2>
            <p style="font-size: 1.3em; color: #003d80; display: inline;">
                <strong class="bio-label" style="font-size: 1.2em; color: #003d80; margin-right: 5px;">Bio:</strong> 
                <span style="font-size: 1.2em; color: grey;">
                    @if($user->description == null) 
                        N/A 
                    @else 
                        {{ $user->description }} 
                    @endif
                </span>
            </p>
        </div>
        @if(Auth::user()->role_id == 1)
        <a href="{{ route('superadmin.editProfile', ['id' => $user->id]) }}" class="btn-edit">
            <i class="fas fa-pencil-alt"></i> <span>Edit</span>
        </a>
        @endif
    </div>

    <!-- Profile Body Grid Layout with Specific View -->
    <div class="profile-body-grid-view">
        <!-- Centered Information Section -->
        <div class="info-container">
            <div class="info-section">
                <h3 class="bold-blue">
                    <i class="fas fa-info-circle"></i> Information
                </h3>
                <p><i class="fas fa-envelope"></i> <strong class="label-blue">Email:</strong> {{ $user->email }}</p>
                <div class="info-divider"></div>
                <p><i class="fas fa-phone"></i> <strong class="label-blue">Contact Number:</strong> 
                    @if($user->contact_number == null) N/A @else {{ $user->contact_number }} @endif
                </p>
                <div class="info-divider"></div>
                <p><i class="fas fa-flag"></i> <strong class="label-blue">Country:</strong> 
                    @if($user->country_id == null) 
                        N/A 
                    @else 
                        {{ $countryTable->countryname }} 
                        <img src="{{ asset('storage/images/flags/' . $countryTable->code . '.png') }}" alt="Flag of {{ $countryTable->countryname }}" class="flag-icon"> 
                    @endif
                </p>
                <div class="info-divider"></div>
                <p><i class="fas fa-venus-mars"></i> <strong class="label-blue">Gender:</strong> 
                    @if($user->gender == null) N/A @else {{ $user->gender }} @endif
                </p>
                <div class="info-divider"></div>
                <p><i class="fas fa-birthday-cake"></i> <strong class="label-blue">Birthdate:</strong> 
                    @if($user->birthdate == null) N/A @else {{ $user->birthdate }} @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
