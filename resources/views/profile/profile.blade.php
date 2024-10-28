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
                <img src="{{ asset($user->profile_picture) }}" alt="Profile picture of {{ $user->first_name }}"> 
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
    
        
        <a href="{{ route('profile.edit') }}" class="btn-edit">
            <i class="fas fa-pencil-alt"></i> <span>Edit</span>
        </a>

        
    </div>

    <!-- Profile Body Grid Layout -->
    <div class="profile-body-grid">
        <!-- Information Section -->
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
                    <img src="{{ asset('storage/images/flags/' . strtolower($countryTable->code) . '.png') }}" alt="Flag of {{ $countryTable->countryname }}" class="flag-icon"> 
                @endif
            </p>
            
            @if($user->country_id == 177)
            <div class="info-divider"></div>
            <p><i class="fas fa-city"></i> <strong class="label-blue">Region:</strong> 
                @if($user->region_id == null) N/A @else {{ $user->region->regDesc }} @endif
            </p>
            <div class="info-divider"></div>
            <p><i class="fas fa-map-marker-alt"></i> <strong class="label-blue">Province:</strong> 
                @if($user->province_id == null) N/A @else {{ $user->province->provDesc }} @endif
            </p>
            @endif
            <div class="info-divider"></div>
            <p><i class="fas fa-university"></i> <strong class="label-blue">College:</strong> 
                @if($user->college == null) N/A @else {{ $user->college }} @endif
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

        <!-- Attended Events Section -->
        <div class="profile-attended-events-container">
            <div class="profile-attended-events-header">
                <h3 class="profile-attended-events-title">
                    <i class="fas fa-calendar-alt"></i> Attended Events
                </h3>
            </div>
            <div class="profile-attended-events-list">
                @if($attendedEvents->isEmpty())
                    <p>N/A</p>
                @else
                    <table class="profile-attended-events-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendedEvents as $event)
                                <tr>
                                    <td>
                                        <a href="{{ route('event.view', $event->id) }}" class="profile-attended-events-link">
                                            {{ $event->title }}
                                        </a>
                                    </td>
                                    <td>{{ $event->date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
