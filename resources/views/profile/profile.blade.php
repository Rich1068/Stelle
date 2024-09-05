@extends('layouts.app')

@section('body')

<div class="profile-container">
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
        </div>
        <a href="{{ route('profile.edit') }}" class="btn-edit">
            <i class="fas fa-pencil-alt"></i> <span>Edit</span>
        </a>
    </div>

    <div class="profile-body">
        <div class="about-section">
            <h3 class="bold-blue">
                <i class="fas fa-user-circle"></i> About/Bio
            </h3>
            <p>@if($user->description == null) N/A @else {{ $user->description }} @endif</p>
        </div>

        <div class="info-section">
            <h3 class="bold-blue">
                <i class="fas fa-info-circle"></i> Information
            </h3>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Contact Number:</strong> @if($user->contact_number == null) N/A @else {{ $user->contact_number }} @endif</p>
            <p><strong>Country:</strong> 
                @if($user->country_id == null) 
                    N/A 
                @else 
                    {{ $countryTable->countryname }} 
                    <img src="{{ asset('storage/images/flags/' . $countryTable->code . '.png') }}" alt="Flag of {{ $countryTable->countryname }}" class="flag-icon"> 
                @endif
            </p>
            <p><strong>Gender:</strong> @if($user->gender == null) N/A @else {{ $user->gender }} @endif</p>
        </div>
    </div>
</div>

@endsection