@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="profile-container">
<ul class="nav nav-tabs mt-4" id="profileTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">
            Personal Information
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="certificates-tab" data-toggle="tab" href="#certificates" role="tab" aria-controls="certificates" aria-selected="false">
            Certificates
        </a>
    </li>
</ul>
    <div id="profileTabContent">
        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
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

            <div class="profile-attended-events-container">
                <ul class="nav nav-tabs" id="eventTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active profile-attended-events-title" id="attended-events-tab" data-toggle="tab" href="#attended-events" role="tab" aria-controls="attended-events" aria-selected="true">
                            <i class="fas fa-calendar-alt"></i> Attended Events
                        </a>
                    </li>
                    @if ($user->role_id == 1 || $user->role_id == 2)
                    <li class="nav-item">
                        <a class="nav-link profile-attended-events-title" id="created-events-tab" data-toggle="tab" href="#created-events" role="tab" aria-controls="created-events" aria-selected="false">
                            <i class="fas fa-calendar-plus"></i> Created Events
                        </a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <!-- Attended Events Tab -->
                    <div class="tab-pane fade show active" id="attended-events" role="tabpanel" aria-labelledby="attended-events-tab">
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
                                                    <a href="{{ route('event.view', $event->id) }}" class="profile-created-events-link">
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

                    <!-- Created Events Tab -->
                    <div class="tab-pane fade" id="created-events" role="tabpanel" aria-labelledby="created-events-tab">
                        <div class="profile-created-events-list">
                            @if($createdEvents->isEmpty())
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
                                        @foreach($createdEvents as $event)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('event.view', $event->id) }}" class="profile-created-events-link">
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
        </div>
            
    </div>
        <br>
        @if(Auth::user()->role_id == 1)
        

            <!-- Centered Information Section -->
            <div class="info-container">
                <div class="info-section">
                    <h3 class="bold-blue">
                        <i class="fas fa-info-circle"></i> Analytics
                    </h3>
                </div>
                @if($user->role_id == 2)
                    @include('profile.partials.admin-analytics')
                @elseif ($user->role_id == 3)
                    @include('profile.partials.user-analytics')
                @endif
            </div>

        @endif
        </div>
        <div class="tab-pane fade" id="certificates" role="tabpanel" aria-labelledby="certificates-tab">
                @include('profile.partials.viewUserCertificate')
        </div>
    </div>
</div>


@endsection
