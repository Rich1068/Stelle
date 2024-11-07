@foreach($participants as $participant)
    <div class="participant-list-item" data-user-id="{{ $participant->user_id }}" data-name="{{ strtolower($participant->user->first_name . ' ' . $participant->user->last_name) }}">
        <!-- User Information -->
        <div class="participant-info">
            <div class="participant-profile">
                @if(is_null($participant->user->profile_picture))
                    <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" class="profile-picture"> 
                @else 
                    <img src="{{ asset($participant->user->profile_picture) }}" alt="{{ $participant->user->first_name }}" class="profile-picture">
                @endif
                <div class="participant-details">
                    <a href="{{ route('profile.view', $participant->user->id) }}" class="participant-name">
                        {{ $participant->user->first_name }} {{ $participant->user->last_name }}
                    </a>
                    @if($participant->user->role_id == 1) 
                        <p class="participant-status">Super Admin</p>
                    @elseif($participant->user->role_id == 2)
                        <p class="participant-status">Admin</p>
                    @elseif($participant->user->role_id == 3)
                        <p class="participant-status">User</p>
                    @endif
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
