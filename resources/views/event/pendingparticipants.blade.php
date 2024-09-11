@extends('layouts.app')

@section('body')

@if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div id="notifications" class="position-fixed top-0 end-0 p-3">
        <!-- Notifications will be appended here -->
</div>

<div class="container">
    <div class="top-container">
        <div class="answer-forms-event-title">
            Pending Participants For
        </div>
        <div class="answer-forms-event-subtitle">
            {{ $event->title }}
        </div>
    </div>



    <div class="participant-list-container">
        @foreach($participants as $participant)
            <div class="participant-list-item" data-user-id="{{ $participant->user_id }}">
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
                    <button type="button" class="btn btn-success accept-btn" data-user-id="{{ $participant->user_id }}" data-event-id="{{ $event->id }}">Accept</button>
                    <button type="button" class="btn btn-danger decline-btn" data-user-id="{{ $participant->user_id }}" data-event-id="{{ $event->id }}">Decline</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
$(document).ready(function() {
    // Handle the Accept button click
    $('.accept-btn').on('click', function(e) {
        e.preventDefault();  // Prevent the form from submitting normally
        var userId = $(this).data('user-id');
        var eventId = $(this).data('event-id');
        var statusId = 1;  // Accepted

        $.ajax({
            url: '/event/' + eventId + '/participants/' + userId + '/update',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status_id: statusId
            },
            success: function(response) {
                console.log(response); // Log response data for debugging
                // Ensure the data-user-id attribute matches
                var participantItem = $('.participant-list-item[data-user-id="' + userId + '"]');
                if (participantItem.length) {
                    participantItem.remove();
                } 
            },
            error: function(xhr) {
                console.log(xhr.responseText); // Log error for debugging
                alert('Error: ' + xhr.responseText);
            }
        });
    });

    // Handle the Decline button click
    $('.decline-btn').on('click', function(e) {
        e.preventDefault();  // Prevent the form from submitting normally
        var userId = $(this).data('user-id');
        var eventId = $(this).data('event-id');
        var statusId = 2;  // Declined

        $.ajax({
            url: '/event/' + eventId + '/participants/' + userId + '/update',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status_id: statusId
            },
            success: function(response) {
                console.log(response); // Log response data for debugging
                var participantItem = $('.participant-list-item[data-user-id="' + userId + '"]');
                if (participantItem.length) {
                    participantItem.remove();
                } 
            },
            error: function(xhr) {
                console.log(xhr.responseText); // Log error for debugging
                alert('Error: ' + xhr.responseText);
            }
        });
    });
});
</script>
@endsection