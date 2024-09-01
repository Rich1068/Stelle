@extends('layouts.app')

@section('body')
<div class="container">
    <h1>Participants for {{ $event->title }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('sendCertificates', $event->id) }}" method="POST" id="sendCert">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>User</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $participant)
                    <tr>
                        <td>
                            <input type="checkbox" name="participants[]" value="{{ $participant->user->id }}">
                        </td>
                        <td>
                            <a href="{{ route('profile.view', $participant->user->id) }}">
                                {{ $participant->user->first_name }} {{ $participant->user->last_name }}
                            </a>
                        </td>
                        <td>{{ $participant->status->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Send Certificates</button>
    </form>
</div>

<script>

function disableButton(button) {
    button.disabled = true; // Disable the button
    button.innerText = 'Submitting...'; // Change button text

    // Re-enable the button after 3 seconds
    setTimeout(function() {
        button.disabled = false;
        button.innerText = 'Create Event';
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
