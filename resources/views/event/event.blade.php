@extends('layouts.app')
@section('body')

<div class="event-view-container">
    <!-- Tab Navigation -->
    <div class="tabs">
        <div class="tab-button active" data-tab="main">Event Details</div>
        <div class="tab-button" data-tab="participants">Participants</div>
        <div class="tab-button" data-tab="feedback">Evaluation Form</div>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content">
        <!-- Main Page Content -->
        <div class="tab-pane active" id="main">
            <div class="event-view-content">
                <div class="event-view-header">
                    <h1 class="event-view-event-title">{{ $event->title }}</h1>
                    <img src="{{ asset($event->event_banner) }}" alt="Event banner" class="event-view-banner">
                </div>

                <h3 class="event-view-about">
                    <i class="fas fa-info-circle"></i>
                    <b>About:</b><br>
                    {{ $event->description }}
                </h3>

                <div class="event-view-divider">
                    <span>Information</span>
                </div>

                <div class="event-view-info">
                    <div><i class="fas fa-calendar-alt"></i><span data-label="Date:">{{ $event->date }}</span></div>
                    <div><i class="fas fa-map-marker-alt"></i><span data-label="Address:">{{ $event->address }}</span></div>
                    <div><i class="fas fa-clock"></i><span data-label="Duration:">{{ $event->start_time }} to {{ $event->end_time }}</span></div>
                    <div><i class="fas fa-users"></i><span data-label="Capacity:">{{ $currentParticipants }}/{{ $event->capacity }}</span></div>
                    <div><i class="fas fa-desktop"></i><span data-label="Mode:">{{ $event->mode }}</span></div>
                    <div><i class="fas fa-user"></i><span data-label="By:">{{ $userevent->user->first_name }} {{ $userevent->user->last_name }}</span></div>
                </div>

                @if($userevent->user_id == Auth::user()->id)
                    <div class="event-view-buttons">
                        <a href="{{ route('event.edit', $event->id) }}" class="btn btn-primary">
                            <span>Edit</span>
                        </a>

                        <!-- Create and View Certificate Buttons -->
                        <div class="create-certificate-buttons">
                            @if ($certificate == null)
                                <a href="{{ route('certificates.create', $event->id) }}" class="btn btn-primary">Create Certificate</a>
                            @else
                                <a href="{{ route('certificates.create', $event->id) }}" class="btn btn-primary">Update Certificate</a>
                            @endif

                            @if ($certificate)
                                <button id="viewCertificateButton" data-image-url="{{ asset($certificate->cert_path) }}" class="btn btn-primary">View Certificate</button>
                            @endif
                        </div>
                    </div>
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

        <!-- Participants Tab -->
        <div class="tab-pane" id="participants">
            @include('event.partials.participantlist', ['event' => $event, 'participants' => $participants])
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pendingParticipantsModal">
                <span>View Requesting Participants</span>
            </button>
        </div>
        @include('event.partials.pendingparticipants', ['event' => $event, 'pendingparticipants' => $pendingparticipants])
        <!-- Feedback Form Tab -->
        <div class="tab-pane" id="feedback">
            <!-- Create or Update Evaluation Form Button -->
            @if($event->evaluationForm)
                <form action="{{ route('evaluation-forms.update', ['id' => $event->id, 'form' => $event->evaluationForm->id]) }}" method="POST" class="full-width-button">
                    @method('PUT')
                    @csrf
                    <button type="submit" class="btn btn-primary">Update Evaluation Form</button>
                </form>
            @else
                <form action="{{ route('evaluation-forms.store', $event->id) }}" method="POST" class="full-width-button">
                    @csrf
                    <button type="submit" class="btn btn-primary">Create Evaluation Form</button>
                </form>
            @endif

            <!-- Activate Checkbox -->
            @if($event->evaluationForm)
                <form action="{{ route('evaluation-forms.toggle', ['id' => $event->id, 'form' => $event->evaluationForm->id]) }}" method="POST" class="full-width-button">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="is_active_toggle">Activate:</label>
                        <input type="checkbox" name="is_active" id="is_active_toggle" onchange="this.form.submit()" {{ $event->evaluationForm->status_id == 1 ? 'checked' : '' }}>
                    </div>
                </form>
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

<!-- JavaScript to handle tab switching and modal display -->
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    const activeTabKey = 'activeTab'; // Key to store the active tab in local storage

    // Retrieve the last selected tab from local storage, if it exists
    const savedTab = localStorage.getItem(activeTabKey);
    if (savedTab) {
        // Find the button and pane for the saved tab
        const savedButton = document.querySelector(`.tab-button[data-tab="${savedTab}"]`);
        const savedPane = document.getElementById(savedTab);

        if (savedButton && savedPane) {
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Activate the saved tab
            savedButton.classList.add('active');
            savedPane.classList.add('active');
        }
    }

    // Tab switching logic
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.dataset.tab;

            // Store the selected tab in local storage
            localStorage.setItem(activeTabKey, target);

            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Activate the clicked tab
            button.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });

    // Certificate modal logic
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
