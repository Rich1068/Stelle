@extends('layouts.app')

@section('body')
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
                Information
            </h3>
            <p><strong class="label-blue">Email:</strong> {{ $user->email }}</p>
            <div class="info-divider"></div>
            <p><strong class="label-blue">Contact Number:</strong> @if($user->contact_number == null) N/A @else {{ $user->contact_number }} @endif</p>
            <div class="info-divider"></div>
            <p><strong class="label-blue">Country:</strong> 
                @if($user->country_id == null) 
                    N/A 
                @else 
                    {{ $countryTable->countryname }} 
                    <img src="{{ asset('storage/images/flags/' . $countryTable->code . '.png') }}" alt="Flag of {{ $countryTable->countryname }}" class="flag-icon"> 
                @endif
            </p>
            <div class="info-divider"></div>
            <p><strong class="label-blue">Gender:</strong> @if($user->gender == null) N/A @else {{ $user->gender }} @endif</p>
            <div class="info-divider"></div>
            <p><strong class="label-blue">Birthdate</strong> @if($user->birthdate == null) N/A @else {{ $user->birthdate }} @endif</p>
            <div class="info-divider"></div>
            <p><strong class="label-blue">About/Bio:</strong> @if($user->description == null) N/A @else {{ $user->description }} @endif</p>
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
