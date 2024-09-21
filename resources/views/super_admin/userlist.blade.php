@extends('layouts.app')
@section('body')

<div class="page-title-container-eventlist" style="display: flex; justify-content: space-between; align-items: center;">
    <div class="answer-forms-event-title">
        <i class="fas fa-users"></i> User List <!-- Appropriate icon -->
    </div>
    <div class="add-user-buttons" style="display: flex; gap: 10px;">
        <a href="{{ route('superadmin.usercreate') }}" class="btn btn-primary mt-2">
            <i class="fas fa-user-plus"></i> Add User
        </a>
        <form action="{{ route('super_admin.requestingAdmins') }}" method="get" style="display: inline;">
            <button type="submit" class="btn btn-primary mt-2">
                <i class="fas fa-user-shield"></i> View Requesting Admin
            </button>
        </form>
    </div>
</div>

@foreach($users as $user)
    <div class="user-list-item">
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
                <span class="meta-text">{{ $user->role->role_name }}</span>
            </div>
        </div>
    </div>
@endforeach

@endsection
