@extends('layouts.app')
@section('body')

<div class="container">
    <div class="top-container">
        <div class="answer-forms-event-title">
            User List
        </div>
    </div>
    @foreach($users as $user)
        <div class="participant-list-item">
            <div class="participant-info">
                <div class="participant-profile">
                @if($user->profile_picture == null)
                <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" style="max-width: 200px; max-height: 100px;">
                @else
                    <img src="{{ asset($user->profile_picture) }}" alt="Profile picture of {{ $user->first_name }}" style="max-width: 200px; max-height: 100px;">
                @endif
                    <div class="participant-details">
                        <a href="{{ route('profile.view', $user->id) }}" class="participant-name">
                        {{ $user->first_name }} {{ $user->last_name }}
                        </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        <a href="{{ route('super_admin.requestingAdmins') }}" class="nav-link">
            <span>View Requesting Admin</span>
        </a>
</div>
@endsection
