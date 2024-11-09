@extends('layouts.app')
@section('body')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="container">
    <div class="top-container">
        <div class="answer-forms-event-title">
            Pending Users
        </div>
    </div>
    @foreach($users as $user)
        <div class="participant-list-item">
            <div class="participant-info">
            <div class="participant-profile">
                    @if($user->usertable->profile_picture == null)
                        <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" class="profile-picture">
                    @else
                        <img src="{{ asset($user->usertable->profile_picture) }}" alt="Profile picture of {{ $user->usertable->first_name }}" class="profile-picture">
                    @endif
                    <div class="participant-details">
                        <a href="{{ route('profile.view', $user->usertable->id) }}" class="participant-name">
                            {{ $user->usertable->first_name }} {{ $user->usertable->last_name }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="actions">
                <form action="{{ route('super_admin.adminRequest', ['id' => $user->id, 'action' => 'accept']) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success">Accept</button>
                </form>
                
                <form action="{{ route('super_admin.adminRequest', ['id' => $user->id, 'action' => 'decline']) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Decline</button>
                </form>
            </div>
        </div>
    @endforeach
</div>

@endsection
