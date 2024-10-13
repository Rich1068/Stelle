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
<div class="top-container mb-4" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <!-- Left: My Certificate Templates Title -->
    <div class="d-block">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-users"></i> Pending Participants For
        </h2>
        <h2 class="font-weight-bold mt-2" style="color: grey;">
            {{ $event->title }}
        </h2>
    </div>
</div>

<!-- Search Bar -->
<div class="container">
    <div class="search-bar-container d-flex justify-content-center" style="margin: 30px 0;">
        <div class="search-wrapper position-relative w-50">
            <input type="text" id="search-participants" class="form-control search-input" placeholder="Search participants..." style="border-radius: 10px; height: 50px; padding: 10px 45px 10px 20px;">
            <i class="fas fa-search search-icon position-absolute" style="top: 50%; right: 20px; transform: translateY(-50%); color: #999;"></i>
        </div>
    </div>

    <!-- Participant List -->
    <div class="participant-list-container">
    @foreach($participants as $participant)
    <div class="participant-list-item" data-user-id="{{ $participant->user_id }}" data-name="{{ strtolower($participant->user->first_name . ' ' . $participant->user->last_name) }}">
        <!-- User Information -->
        <div class="participant-info">
            <div class="participant-profile">
                @if($participant->user->profile_picture == null)
                    <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" class="profile-picture"> 
                @else 
                    <img src="{{ $participant->user->profile_picture }}" alt="{{ $participant->user->first_name }}" class="profile-picture">
                @endif
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
            <div class="button-container">
                <button type="button" class="btn btn-success accept-btn" data-user-id="{{ $participant->user_id }}" data-event-id="{{ $event->id }}">Accept</button>
                <button type="button" class="btn btn-danger decline-btn" data-user-id="{{ $participant->user_id }}" data-event-id="{{ $event->id }}">Decline</button>
            </div>
        </div>
    </div>
    @endforeach
</div>


    <!-- Pagination Links with custom template -->
    <div class="d-flex justify-content-center" id="pagination-links">
        {{ $participants->appends(request()->query())->links('vendor.pagination.custom1') }}
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Search filter
    $('#search-participants').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.participant-list-item').each(function() {
            var participantName = $(this).data('name');
            if (participantName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
    $(document).on('click', '.accept-btn', function(e) {
    e.preventDefault();
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
            var participantItem = $('.participant-list-item[data-user-id="' + userId + '"]');
            if (participantItem.length) {
                participantItem.remove();
            } 
            alert('Participant accepted successfully');
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseText);
        }
    });
});

    // Handle the Decline button click
    $(document).on('click', '.decline-btn', function(e) {
        e.preventDefault();
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
                var participantItem = $('.participant-list-item[data-user-id="' + userId + '"]');
                if (participantItem.length) {
                    participantItem.remove();
                }
                alert('Participant declined successfully');
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });

</script>

<style>
    .search-input {
        border-radius: 8px;
        height: 50px;
        padding-right: 45px; /* Enough space for the icon */
    }

    .search-icon {
        pointer-events: none;
    }
</style>
@endsection
