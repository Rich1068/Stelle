@extends('layouts.app')

@section('body')
<div class="container">
    <div class="top-container">
        <div class="manage-participants">
            Pending Participants For
        </div>
        <div class="event-title">
            {{ $event->title }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="participant-list-container">
        @foreach($participants as $participant)
            <div class="participant-list-item">
                <!-- User Information -->
                <div class="participant-info">
                    <div class="participant-profile">
                        <img src="{{ $participant->user->profile_picture_url }}" alt="{{ $participant->user->first_name }}" class="profile-picture">
                        <div class="participant-details">
                            <a href="{{ route('profile.view', $participant->user->id) }}" class="participant-name">
                                {{ $participant->user->first_name }} {{ $participant->user->last_name }}
                            </a>
                            <div class="participant-status">{{ $participant->status->status }}</div>
                          
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="participant-actions">
                    <form action="{{ route('participants.updateStatus', [$event->id, $participant->user_id]) }}" method="POST" class="participant-action-form">
                        @csrf
                        <input type="hidden" name="status_id" value="1"> <!-- Accepted -->
                        <button type="submit" class="btn btn-success">Accept</button>
                    </form>
                    <form action="{{ route('participants.updateStatus', [$event->id, $participant->user_id]) }}" method="POST" class="participant-action-form">
                        @csrf
                        <input type="hidden" name="status_id" value="2"> <!-- Declined -->
                        <button type="submit" class="btn btn-danger">Decline</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
