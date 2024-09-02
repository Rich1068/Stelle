@extends('layouts.app')
@section('body')

<div class="event-view-container">
    <div class="event-view-content">
        <!-- Event Title and Banner -->
        <div class="event-view-header">
            <h1 class="event-view-event-title">Event</h1> <!-- Title at top left -->
            <h1 class="event-view-event-title">{{ $event->title }}</h1>
            <img src="{{ asset($event->event_banner) }}" alt="Event banner" class="event-view-banner">
        </div>
        
        <!-- About Section -->
        <h3 class="event-view-about">
            <i class="fas fa-info-circle"></i> <!-- Icon added -->
            <b>About:</b><br> 
            {{ $event->description }}  
        </h3>

        <!-- Divider -->
        <div class="event-view-divider">
            <span>Information</span> <!-- Divider text -->
        </div>

        <!-- Event Information -->
        <div class="event-view-info">
            <div><i class="fas fa-calendar-alt"></i><span data-label="Date:">{{ $event->date }}</span></div>
            <div><i class="fas fa-map-marker-alt"></i><span data-label="Address:">{{ $event->address }}</span></div>
            <div><i class="fas fa-clock"></i><span data-label="Duration:">{{ $event->start_time }} to {{ $event->end_time }}</span></div>
            <div><i class="fas fa-users"></i><span data-label="Capacity:">{{$currentParticipants}}/{{ $event->capacity }}</span></div>
            <div><i class="fas fa-desktop"></i><span data-label="Mode:">{{ $event->mode }}</span></div>
            <div><i class="fas fa-user"></i><span data-label="By:">{{ $userevent->user->first_name }} {{ $userevent->user->last_name }}</span></div>
        </div>

        <!-- Buttons Section -->
        <div class="event-view-buttons">
            @if($userevent->user_id == Auth::user()->id)
                <a href="{{ route('event.edit', $event->id) }}" class="btn btn-primary">
                    <span>Edit</span>
                </a>
                <a href="{{ route('events.participantslist', $event->id) }}" class="btn btn-primary">
                    <span>View Participant</span>
                </a>
                <a href="{{ route('events.participants', $event->id) }}" class="btn btn-primary">
                    <span>View Requesting Participant</span>
                </a>
                
                <!-- Evaluation Form and Toggle in the same space -->
                <div class="event-view-buttons">
                    @if($event->evaluationForm)
                        <form action="{{ route('evaluation-forms.update', ['id' => $event->id, 'form' => $event->evaluationForm->id]) }}" method="POST" class="full-width-button">
                            @method('PUT')
                            @csrf
                            <button type="submit" class="btn btn-primary">Update Evaluation Form</button>
                        </form>
                        <form action="{{ route('evaluation-forms.toggle', ['id' => $event->id, 'form' => $event->evaluationForm->id]) }}" method="POST" class="full-width-button">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="is_active_toggle">Activate:</label>
                                <input type="checkbox" name="is_active" id="is_active_toggle" onchange="this.form.submit()" {{ $event->evaluationForm->status_id == 1 ? 'checked' : '' }}>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('evaluation-forms.store', $event->id) }}" method="POST" class="full-width-button">
                            @csrf
                            <button type="submit" class="btn btn-primary">Create Evaluation Form</button>
                        </form>
                    @endif
                </div>

                <br>
                <div class="create-certificate-button">
                    @if ($certificate == null)
                        <a href="{{ route('certificates.create', $event->id) }}" class="btn btn-primary">Create Certificate</a>
                    @else
                        <a href="{{ route('certificates.create', $event->id) }}" class="btn btn-primary">Update Certificate</a>
                    @endif
                </div>

                @if ($certificate)
                    <button id="viewCertificateButton" data-image-url="{{ asset($certificate->cert_path) }}" class="btn btn-primary">View Certificate</button>
                @endif
            @endif

            @if($userevent->user_id != Auth::user()->id)
                @if ($participant && $participant->status_id == 1)
                    <p>You have been accepted to this event.</p>
                @elseif ($currentParticipants < $event->capacity && $participant == null)
                    <form action="{{ route('event.join', $event->id) }}" method="POST" class="full-width-button">
                        @csrf
                        <button type="submit" class="btn btn-success">Join Event</button>
                    </form>
                @elseif ($participant && $participant->status_id == 3)
                    <button type="button" class="btn btn-secondary" disabled>Pending</button>
                @else
                    <button type="button" class="btn btn-secondary" disabled>Closed</button>
                @endif

                @if ($participant && $participant->status_id == 1)
                    @if($event->evaluationForm && $event->evaluationForm->status_id == 1)
                        @if($hasAnswered)
                            <button type="button" class="btn btn-secondary" disabled>Evaluation Form Already Answered</button>
                        @else
                            <form action="{{ route('evaluation-form.take', ['id' => $event->id, 'form' => $evaluationForm->id]) }}" method="GET" class="full-width-button">
                                @csrf
                                <button type="submit" class="btn btn-primary">Take Evaluation</button>
                            </form>
                        @endif
                    @else
                        <button type="button" class="btn btn-secondary" disabled>Evaluation Not Yet Available</button>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Modal HTML -->
@if($certificate)
<div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificateModalLabel">Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="certificateImage" src="" alt="Certificate Image" class="event-view-certificate-image">
            </div>
        </div>
    </div>
</div>
@endif

<!-- JavaScript to handle the button click and display the modal -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const viewCertificateButton = document.querySelector('#viewCertificateButton');
        if (viewCertificateButton) {
            viewCertificateButton.addEventListener('click', function (event) {
                event.preventDefault();
                const imageUrl = event.target.dataset.imageUrl;
                const certificateImage = document.querySelector('#certificateImage');
                if (certificateImage) {
                    certificateImage.src = imageUrl;
                    $('#certificateModal').modal('show');
                }
            });
        }
    });
</script>
@endsection
