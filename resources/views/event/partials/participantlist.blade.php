<div class="container">
    <div class="top-container">
        <div class="answer-forms-event-title">
            Participants List For
        </div>
        <div class="answer-forms-event-subtitle">
            {{ $event->title }}
        </div>
        <div><i class="fas fa-users"></i><span data-label="Capacity:">{{ $currentParticipants }}/{{ $event->capacity }}</span></div>
    </div>
    <div class="participant-list-container">
        @foreach($participants as $participant)
            <div class="participant-list-item">
                <!-- User Information -->
                <div class="participant-info">
                    <div class="participant-profile">
                        @if($participant->user)
                            @if($participant->user->profile_picture == null)
                                <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture" class="profile-picture"> 
                            @else 
                                <img src="{{ $participant->user->profile_picture }}" alt="{{ $participant->user->first_name }}" class="profile-picture">
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
            </div>
        @endforeach
    </div>
</div>