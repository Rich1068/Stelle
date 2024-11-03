<div class="container">
    <div class="participant-list-container">
        @foreach($participants as $participant)
            <div class="participant-list-item" data-user-id="{{ $participant->user_id }}" data-name="{{ strtolower($participant->user->first_name . ' ' . $participant->user->last_name) }}">
                <!-- User Information -->
                <div class="participant-info">
                    <div class="participant-profile">
                        @if($participant->user)
                            @if(is_null($participant->user->profile_picture))
                                <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" class="profile-picture"> 
                            @else 
                                <img src="/{{ $participant->user->profile_picture }}" alt="{{ $participant->user->first_name }}" class="profile-picture">
                            @endif
                            <div class="participant-details">
                                <a href="{{ route('profile.view', $participant->user->id) }}" class="participant-name">
                                    {{ $participant->user->first_name }} {{ $participant->user->last_name }}
                                </a>
                            </div>
                        @else
                            <p>User information is missing.</p>
                        @endif
                    </div>
                </div>
                @if($userevent->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                @if($participant->user) <!-- Ensure that user exists before rendering the button -->
                    <div class="participant-actions">
                        <button type="button" class="btn btn-danger remove-btn" data-user-id="{{ $participant->user->id }}">
                            Remove
                        </button>
                    </div>
                @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
