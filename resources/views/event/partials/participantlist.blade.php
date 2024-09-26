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

    @if ($currentUser == $userevent->user->id)
    <form id="sendCert">
        @csrf
        <div class="participant-list-container">
            @foreach($participants as $participant)
                <div class="participant-list-item">
                    <!-- Selection Checkbox -->
                    <input type="checkbox" name="participants[]" value="{{ $participant->user->id }}">
                    
                    <!-- User Information -->
                    <div class="participant-info">
                        <div class="participant-profile">
                            <img src="{{ $participant->user->profile_picture_url }}" alt="{{ $participant->user->first_name }}" class="profile-picture">
                            <div class="participant-details">
                                <a href="{{ route('profile.view', $participant->user->id) }}" class="participant-name">
                                    {{ $participant->user->first_name }} {{ $participant->user->last_name }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary" onclick="disableButton(this)">
            Send Certificates
        </button>
    </form>
    @else
        <div class="participant-list-container">
            @foreach($participants as $participant)
                <div class="participant-list-item">
                    <div class="participant-info">
                        <div class="participant-profile">
                            <img src="{{ $participant->user->profile_picture_url }}" alt="{{ $participant->user->first_name }}" class="profile-picture">
                            <div class="participant-details">
                                <a href="{{ route('profile.view', $participant->user->id) }}" class="participant-name">
                                    {{ $participant->user->first_name }} {{ $participant->user->last_name }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function disableButton(button) {
    button.disabled = true;  // Disable the button
    button.innerText = 'Submitting...';  // Change button text

    // Re-enable the button after 3 seconds
    setTimeout(function() {
        button.disabled = false;
        button.innerText = 'Send Certificates';
    }, 3000);  // 3 seconds
}
</script>
