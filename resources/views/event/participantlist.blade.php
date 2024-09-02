@extends('layouts.app')

@section('body')
<div class="container">
    <div class="top-container">
        <div class="manage-participants">
            Participants List For
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

    <form action="{{ route('sendCertificates', $event->id) }}" method="POST" id="sendCert">
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
                                <div class="participant-country">{{ $participant->user->country }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="send-cert-btn">
            <button type="submit" onclick="disableButton(this)">Send Certificates</button>
        </div>
    </form>
</div>

<script>
function disableButton(button) {
    button.disabled = true; // Disable the button
    button.innerText = 'Submitting...'; // Change button text

    // Re-enable the button after 3 seconds
    setTimeout(function() {
        button.disabled = false;
        button.innerText = 'Send Certificates';
    }, 3000); // 3000 milliseconds = 3 seconds

    button.form.submit(); // Submit the form
}

document.getElementById('sendCert').addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && event.target.tagName === 'INPUT' && event.target.type !== 'submit') {
        event.preventDefault();
    }
});
</script>
@endsection
