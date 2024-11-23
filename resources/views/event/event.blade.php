@extends('layouts.app')
@section('body')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@php
    $currentTime = \Carbon\Carbon::now('Asia/Manila');
    $eventStartTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date . ' ' . $event->start_time, 'Asia/Manila');
@endphp
<div class="event-view-container">
    <!-- Tab Navigation -->
    <div class="tabs">
        <div class="tab-button active" data-tab="main">Event Details</div>
        <div class="tab-button" data-tab="participants">Participants</div>
        @if ($currentUser == $userevent->user->id || Auth::user()->role_id == 1)
            <div class="tab-button" data-tab="feedback">Evaluation Form/Analytics</div>
        @endif
    </div>

    <!-- Tab Contents -->
    <div class="tab-content">
        <!-- Main Page Content -->
        <div class="tab-pane active" id="main">
            <div class="event-view-content">
                <div class="event-view-header">
                    <h1 class="event-view-event-title">{{ $event->title }}@if($event->trashed()) <span style="color: red;">(ARCHIVED)</span>@endif</h1>
                    @if($event->event_banner == null)
                    @else
                    <img src="{{ asset($event->event_banner) }}" alt="Event banner" class="event-view-banner">
                    @endif
                </div>

                <h3 class="event-view-about">
                    <i class="fas fa-info-circle"></i>
                    <b>About: </b>{{ $event->description }}
                </h3>

                <div class="event-view-divider">
                    <span>Information</span>
                </div>

                <div class="event-view-info">
                <!-- Display Date or Date Range -->
                    <div>
                        <i class="fas fa-calendar-alt"></i>
                        <span data-label="Dates: ">
                            @if ($event->start_date === $event->end_date)
                                {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}
                            @else
                                {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('F j, Y') }}
                            @endif
                        </span>
                    </div>
                                        
                    <!-- Display Address -->
                    <div>
                        <i class="fas fa-map-marker-alt"></i>
                        <span data-label="Address: ">{{ $event->address }}</span>
                    </div>

                    <!-- Display Time or Duration -->
                    <div>
                        <i class="fas fa-clock"></i>
                        <span data-label="Duration: ">
                            @if ($event->start_date === $event->end_date)
                                {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} to {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                            @else
                                {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }} {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('F j, Y') }} {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                            @endif
                        </span>
                    </div>

                    <!-- Display Capacity -->
                    <div>
                        <i class="fas fa-users"></i>
                        <span data-label="Capacity: ">{{ $currentParticipants }}/{{ $event->capacity }}</span>
                    </div>

                    <!-- Display Mode -->
                    <div>
                        <i class="fas fa-desktop"></i>
                        <span data-label="Mode: ">{{ $event->mode }}</span>
                    </div>

                    <!-- Display Organizer -->
                    <div>
                        <i class="fas fa-user"></i>
                        <span data-label="By: ">
                            <a href="{{ route('profile.view', $userevent->user->id) }}" class="no-link-style">
                                {{ $userevent->user->first_name }} {{ $userevent->user->last_name }}
                            </a>
                            @if($userevent->user->trashed())
                                <span style="color: red;">(DELETED)</span>
                            @endif
                        </span>
                    </div>
                </div>
                @if($event->trashed())
                </div>
                @else
                @if($userevent->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                    <div class="event-view-buttons" style="display: flex; flex-direction: column; align-items: flex-start;">
           
                    <!-- Top Divider with Left-Aligned Text -->
                    <div style="width: 100%; text-align: left; position: relative; margin-top: 10px;">
                        <hr style="margin: 0; border: 1px solid #ccc; width: 100%;" />
                        <span style="position: absolute; top: -8px; background: white; padding: 0 10px; font-weight: bold; font-size: 12px;">Admin Control</span>
                    </div>

    <div class="button-section"> 
        <a href="{{ route('event.edit', $event->id) }}" class="btn btn-primary" 
        style="flex: 1; min-width: 120px; text-align: center; padding: 10px;">
            <span>Edit</span>
        </a>

        <div class="certificate-buttons">
            @if ($certificate == null)
                <a href="{{ route('event_certificates.create', $event->id) }}" class="btn btn-primary" 
                style="flex: 1; min-width: 120px; text-align: center; padding: 10px;">
                    Create/Send Certificate
                </a>
            @else
                <a href="{{ route('event_certificates.create', $event->id) }}" class="btn btn-primary" 
                style="flex: 1; min-width: 120px; text-align: center; padding: 10px;">
                    Update/Send Certificate
                </a>
            @endif
            </div>
            @if ($certificate && ($userevent->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                <button id="viewCertificateButton" class="btn btn-primary" 
                        data-image-url="{{ asset($certificate->cert_path) }}" 
                        style="flex: 1; min-width: 120px; text-align: center; padding: 10px;">
                    View Certificate
                </button>
            @endif
        </div>

        <div style="width: 100%; text-align: left; position: relative; margin: 2px 0 10px;">
        <hr style="margin: 0; border: 1px solid #ccc; width: 100%;" />
              
        </div>
            @endif
            @if($userevent->user_id != Auth::user()->id)
                @if ($participant && $participant->status_id == 1)
                    <p>You have been accepted to this event.</p>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#qrCodeModal-{{ $participant->user_id }}">
                        Show QR Code
                    </button>
                @elseif (
                    (\Carbon\Carbon::now('Asia/Manila')->between(
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date . ' ' . $event->start_time, 'Asia/Manila'),
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->end_date . ' ' . $event->end_time, 'Asia/Manila')
                    )) || // Ongoing
                    \Carbon\Carbon::now('Asia/Manila')->lessThan(
                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date . ' ' . $event->start_time, 'Asia/Manila')
                    ) // Before start
                )
                    @if ($participant && $participant->status_id == 3)
                        <button type="button" class="btn btn-secondary" disabled>Pending</button>
                    @elseif ($currentParticipants < $event->capacity)
                        <form action="{{ route('event.join', $event->id) }}" method="POST" class="full-width-button">
                            @csrf
                            <button type="submit" class="btn btn-primary">Join Event</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>Closed</button>
                    @endif
                @else
                    <button type="button" class="btn btn-secondary" disabled>Closed</button>
                @endif
            @endif
    @if ($participant && $participant->status_id == 1)
    @if($event->evaluationForm && $event->evaluationForm->status_id == 1)
        @if($hasAnswered)
            <!-- Button with text "Evaluation Form Already Answered" -->
            <button type="button" class="btn btn-secondary" id="evaluationAnsweredBtn" disabled>
                Evaluation Form Already Answered
            </button>
        @elseif ($currentTime->gte($eventStartTime))
            <form action="{{ route('evaluation-form.take', ['id' => $event->id, 'form' => $evaluationForm->form_id]) }}" method="GET" class="full-width-button">
                @csrf
                <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;">Take Evaluation</button>
            </form>
        @else
            <button type="button" class="btn btn-secondary" id="evaluationNotAvailableBtn" disabled>
                Evaluation Not Yet Available
                
            </button>     
        @endif
        
    @else
        <!-- Button with text "Evaluation Not Yet Available" -->
        <button type="button" class="btn btn-secondary" id="evaluationNotAvailableBtn" disabled>
            Evaluation Not Yet Available
        </button>
        @endif
    @endif
    </div>
<!-- View Certificate Button -->
    @endif
    </div>
        </div>
        <!-- Participants Tab -->
        <div class="tab-pane" id="participants">
            <div class="top-container">
                <div class="answer-forms-event-title">
                    Participants List For
                </div>
                <div class="answer-forms-event-subtitle">
                    {{ $event->title }}
                </div>
                <div><i class="fas fa-users"></i><span data-label="Capacity:">{{ $currentParticipants }}/{{ $event->capacity }}</span></div>
            </div>
            <div class="search-bar-container d-flex justify-content-center" style="margin: 20px 0;">
                <div class="search-wrapper position-relative w-50">
                    <input type="text" id="search-participants-list" class="form-control search-input" placeholder="Search participants..." style="border-radius: 10px; height: 50px; padding: 10px 45px 10px 20px;">
                    <i class="fas fa-search search-icon position-absolute" style="top: 50%; right: 20px; transform: translateY(-50%); color: #999;"></i>
                </div>
            </div>
            
            <div id="participant-list-container">
            @include('event.partials.participantlist', ['event' => $event, 'participants' => $participants, 'currentUser' => $currentUser, 'userevent' =>$userevent, 'attendanceLog' => $attendanceLog])
            </div>
            @if ($currentUser == $userevent->user->id || Auth::user()->role_id == 1)
            @if($event->trashed())
            </div>
            @else
            <a href="{{ route('events.pendingparticipants', $event->id) }}" class="position-relative">
                <button type="submit" class="btn btn-primary-2 position-relative">
                    View Pending Participants
                    @if($pendingParticipantsCount > 0)
                        <span class="badge bg-danger pending-badge">{{ $pendingParticipantsCount }}</span>
                    @endif
                </button>
            </a>

            <a href="{{ route('event.attendance-log', $event->id) }}" class="position-relative">
                <button type="submit" class="btn btn-primary-2 position-relative">
                    View Attendance Logs
                </button>
            </a>
            @endif
            @endif
        </div>
<!-- Event Analytics Tab -->
@if ($currentUser == $userevent->user->id || Auth::user()->role_id == 1)
<div class="tab-pane" id="feedback">
<div class="d-flex flex-wrap justify-content-center mt-4"> <!-- Added mt-4 for top margin -->
    <div class="col-12 col-xl-5 col-lg-6 mb-4"> 
        <div class="card shadow h-80"> 
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">User Age Distribution</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                @if($participants->count() > 0)  <!-- Check for participant data -->
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="userAgeChart" style="height: 100%; width: 100%;"></canvas>
                    </div>
                @else
                    <p class="text-center font-weight-bold" style="color: #001e54; font-size: 1.5rem;">No Data Available</p>  <!-- Display when no participants -->
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5 col-lg-6 mb-4"> 
        <div class="card shadow h-80"> 
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">User Gender Distribution</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                @if($participants->count() > 0)  <!-- Check for participant data -->
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="userGenderChart" style="height: 100%; width: 100%;"></canvas>
                    </div>
                @else
                    <p class="text-center font-weight-bold" style="color: #001e54; font-size: 1.5rem;">No Data Available</p>  <!-- Display when no participants -->
                @endif
            </div>
        </div>
    </div>
    <!-- Region Distribution -->
    <div class="col-12 col-xl-5 col-lg-6 mb-4"> 
            <div class="card shadow h-80"> 
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Participant Region Distribution</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                    @if($participants->count() > 0)
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="regionChart" style="height: 100%; width: 100%;"></canvas>
                        </div>
                    @else
                        <p class="text-center font-weight-bold" style="color: #001e54; font-size: 1.5rem;">No Data Available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Province Distribution -->
        <div class="col-12 col-xl-5 col-lg-6 mb-4"> 
            <div class="card shadow h-80"> 
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Participant Province Distribution</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                    @if($participants->count() > 0)
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="provinceChart" style="height: 100%; width: 100%;"></canvas>
                        </div>
                    @else
                        <p class="text-center font-weight-bold" style="color: #001e54; font-size: 1.5rem;">No Data Available</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-5 col-lg-6 mb-4"> 
            <div class="card shadow h-80"> 
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Participant College Distribution</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                    @if($participants->count() > 0)
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="collegeChart" style="height: 100%; width: 100%;"></canvas>
                        </div>
                    @else
                        <p class="text-center font-weight-bold" style="color: #001e54; font-size: 1.5rem;">No Data Available</p>
                    @endif
                </div>
            </div>
        </div>
</div>

    @if($event->trashed())
                
    @else
    <!-- Create or Update Evaluation Form Button -->
    <button type="button" class="btn btn-primary-2" id="setupEvaluationFormButton" data-toggle="modal" data-target="#evaluationFormModal">
        Setup Evaluation Form
    </button>
    @if($event->evaluationForm)
    <button type="button" class="btn btn-primary-2 view-evaluation-button" onclick="window.location.href='{{ route('evaluation.results', ['id' => $event->id]) }}'">
        View Evaluation Results
    </button>
    @endif
    <!-- Activate Checkbox -->
    @if($event->evaluationForm)
        <div class="form-group">
            <label for="is_active_toggle">Activate Evaluation Form:</label>
            <input type="checkbox" name="is_active" id="is_active_toggle" 
                {{ $event->evaluationForm->status_id == 1 ? 'checked' : '' }}>
            <input type="hidden" id="evaluationFormId" value="{{ $event->evaluationForm->id }}">
        </div>
    @endif
    @endif
</div>

</div>
@endif
<!-- Modals -->
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

<div class="modal fade" id="evaluationFormModal" tabindex="-1" role="dialog" aria-labelledby="evaluationFormModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evaluationFormModalLabel">Choose an Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Option 1: Create Evaluation Form -->
            
                @if($event->evaluationForm)
                <form action="{{ route('event-evaluation-forms.edit', ['formId' => $event->evaluationForm->form_id, 'id' => $event->id]) }}" method="GET" 
                    class="full-width-button" id="updateEvaluationForm">
                    @csrf
                    <button type="submit" class="btn btn-primary-2 btn-block">Update Evaluation Form</button>
                </form>
                @else
                <form action="{{ route('event-evaluation-forms.create', ['id' => $event->id]) }}" method="GET" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block">Create Evaluation Form</button>
                </form>
                @endif
                @if($currentUser == $userevent->user->id)
                <!-- Option 2: Use Existing Evaluation Form -->
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#existingFormModal">
                    Use an Existing Evaluation Form
                </button>

                <!-- Modal for Selecting Existing Evaluation Form -->
                <div class="modal fade" id="existingFormModal" tabindex="-1" role="dialog" aria-labelledby="existingFormModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="existingFormModalLabel">Select Existing Evaluation Form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Form to Select Existing Evaluation Form -->
                                <form action="{{ route('event-evaluation-forms.use-existing', ['id' => $event->id]) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="existing_form_id">Choose Evaluation Form</label>
                                        <select name="form_id" id="form_id" class="form-control" required>
                                            <option value="">Select an Evaluation Form</option>
                                            @foreach($existingForms as $form)
                                                <option value="{{ $form->id }}" 
                                                    @if($event->evaluationForm && $form->id == $event->evaluationForm->evalForm->id) selected @endif>
                                                    {{ $form->form_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block">Use Selected Form</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@if ($participant && $participant->status_id == 1)
<div class="modal fade" id="qrCodeModal-{{ $participant->user_id }}" tabindex="-1" aria-labelledby="qrCodeModalLabel-{{ $participant->user_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel-{{ $participant->user_id }}">QR Code</h5>
            </div>
            <div class="modal-body text-center">
                @if ($participant->qr_code)
                    <img src="{{ asset($participant->qr_code) }}" alt="QR Code" class="img-fluid" />
                    <a href="{{ asset($participant->qr_code) }}" download="QR_Code_{{ $participant->user_id }}.png" class="btn btn-primary">
                        Download QR Code
                    </a>
                @else
                    <p>No QR Code available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
</div>
<style>
    /* Main button container styles */
.button-section {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}

/* Certificate buttons container */
.certificate-buttons {
    display: flex;
    gap: 10px;
}

/* Mobile layout - stack buttons on top of each other */
@media (max-width: 768px) {
    .button-section {
        flex-direction: column;
        align-items: stretch;
        margin: auto;
        min-width: 100%;
    }
    .certificate-buttons {
        flex-direction: column;
        align-items: stretch;
      
    }
}
    .no-link-style {
        text-decoration: none;  /* Remove underline */
        color: inherit;         /* Use the same color as surrounding text */
    }
    
    .pending-badge {
        position: absolute;
        top: 0px;
        right: -10px;
        padding: 8px 10px;
        border-radius: 50%;
        background-color: red;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .position-relative {
        position: relative;
    }

</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- JavaScript to handle tab switching and modal display -->
<script>
    
    $(document).ready(function() {
        $('#is_active_toggle').change(function() {
            const isChecked = $(this).is(':checked');
            const eventId = "{{ $event->id }}";
            const formId = $('#evaluationFormId').val();

            if (!formId) {
                alert("No evaluation form exists to toggle.");
                return;  // Exit if no evaluation form ID is found
            }

            $.ajax({
                url: '/events/' + eventId + '/evaluation-form/' + formId + '/toggle',
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: isChecked ? 1 : 0
                },
                success: function(response) {
                    const message = isChecked ? 
                        'The evaluation form is now activated and is open to participants.' : 
                        'The evaluation form is now deactivated and closed to participants.';
                    alert(message);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                    // Reset checkbox to previous state if there’s an error
                    $('#is_active_toggle').prop('checked', !isChecked);
                }
            });
        });
    });

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
        button.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Add active class to the clicked button and the corresponding pane
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');

            // Save the current tab to local storage
            localStorage.setItem(activeTabKey, targetTab);
        });
    });

    // Show certificate in modal
    document.querySelectorAll('[id="viewCertificateButton"]').forEach(button => {
        button.addEventListener('click', function () {
            const imageUrl = this.getAttribute('data-image-url');
            const modal = document.getElementById('certificateModal');
            const modalImage = document.getElementById('certificateImage');

            if (modal && modalImage) {
                modalImage.src = imageUrl;
                $(modal).modal('show');
            }
            });
        });
    });
   
    // age pie chart
    var userAgeData = {
    labels: @json($userAgeData['labels'] ?? ['No Data']),
    values: @json($userAgeData['values'] ?? [1]) // Show "No Data" if values are empty
    }; // Data passed from the controller

    var ageChartElement = document.getElementById("userAgeChart");
    if (ageChartElement) {
        var ctx = ageChartElement.getContext('2d');
        var chartData = {
            labels: userAgeData ? userAgeData.labels : ['No Data'],  // Placeholder label if no data
            datasets: [{
                data: userAgeData ? userAgeData.values : [1],  // Placeholder data if no data
                backgroundColor: userAgeData ? 
                    ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#d3d3d3'] : 
                    ['#d3d3d3'],  // Placeholder color
                hoverBackgroundColor: userAgeData ? 
                    ['#2e59d9', '#17a673', '#2c9faf', '#f4b619', '#e63946','#5a32a3', '#b0b0b0'] : 
                    ['#b0b0b0'],  // Placeholder hover color
                hoverBorderColor: "rgba(234, 236, 244, 1)"
            }]
        };

        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                const total = tooltipItem.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                                return `${label}: ${value} (${percentage}%)`; // Display count and percentage
                            }
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80, // Adjusts the doughnut chart cutout size
            }
        });
    }
    //gender chart
    var genderLabels = @json($genderLabels ?? ['No Data']);
    var genderCounts = @json($genderCounts ?? [1]);

    var genderChartElement = document.getElementById("userGenderChart");
    if (genderChartElement) {
        var ctx = genderChartElement.getContext('2d');
        var chartData = {
            labels: genderLabels ? genderLabels : ['No Data'],  // Placeholder label if no data
            datasets: [{
                data: genderCounts ? genderCounts : [1],  // Placeholder data if no data
                backgroundColor: genderLabels ? 
                    ['#4e73df', '#1cc88a', '#d3d3d3'] : 
                    ['#d3d3d3'],  // Placeholder color
                hoverBackgroundColor: genderLabels ? 
                    ['#2e59d9', '#17a673', '#b0b0b0'] : 
                    ['#b0b0b0'],  // Placeholder hover color
                hoverBorderColor: "rgba(234, 236, 244, 1)"
            }]
        };

        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                const total = tooltipItem.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                                return `${label}: ${value} (${percentage}%)`; // Display count and percentage
                            }
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80, // Adjust the doughnut chart cutout size
            }
        });
    }
    var regionLabels = @json($regionLabels ?? ['No Data']);
    var regionCounts = @json($regionCounts ?? [1]);

    var provinceLabels = @json($provinceLabels ?? ['No Data']);
    var provinceCounts = @json($provinceCounts ?? [1]);

    var collegeLabels = @json($collegeLabels ?? ['No Data']);
    var collegeCounts = @json($collegeCounts ?? [1]);


    function getRandomDistinctColor() {
    const hue = Math.floor(Math.random() * 360); 
    const saturation = 70 + Math.random() * 10;  
    const lightness = 50 + Math.random() * 10;   

    return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
    }

    function generateColors(numColors) {
        let colors = new Set(); // Use a set to avoid duplicates
        while (colors.size < numColors) {
            colors.add(getRandomDistinctColor());
        }
        return Array.from(colors); // Convert set to array
    }
    const regionColors = generateColors(regionCounts.length);
    const provinceColors = generateColors(provinceCounts.length);
    const collegeColors =generateColors(collegeCounts.length);

    var regionChartElement = document.getElementById("regionChart");
    if (regionChartElement) {
        var ctxRegion = regionChartElement.getContext('2d');
        var regionChart = new Chart(ctxRegion, {
            type: 'doughnut',
            data: {
                labels: regionLabels ? regionLabels : ['No Data'],  // Placeholder label if no data
                datasets: [{
                    data: regionCounts ? regionCounts : [1],  // Placeholder data if no data
                    backgroundColor: regionColors ? regionColors : ['#d3d3d3'], // Placeholder color
                    hoverBackgroundColor: regionColors ? regionColors : ['#b0b0b0'],
                    hoverBorderColor: regionColors ? regionColors : ['#b0b0b0'],
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                const total = tooltipItem.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                                return `${label}: ${value} (${percentage}%)`; // Display count and percentage
                            }
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,  // Adjust doughnut chart cutout size
            }
        });
    }

    // Render Province Pie Chart
    var provinceChartElement = document.getElementById("provinceChart");
    if (provinceChartElement) {
        var ctxProvince = provinceChartElement.getContext('2d');
        var provinceChart = new Chart(ctxProvince, {
            type: 'doughnut',
            data: {
                labels: provinceLabels ? provinceLabels : ['No Data'],
                datasets: [{
                    data: provinceCounts ? provinceCounts : [1],
                    backgroundColor: provinceColors ? provinceColors : ['#d3d3d3'],
                    hoverBackgroundColor: provinceColors ? provinceColors : ['#b0b0b0'],
                    hoverBorderColor: provinceColors ? provinceColors : ['#b0b0b0'],
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                const total = tooltipItem.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                                return `${label}: ${value} (${percentage}%)`; // Display count and percentage
                            }
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            }
        });
    }

    // College Distribution Chart
    var collegeChartElement = document.getElementById("collegeChart");
    if (collegeChartElement) {
        const ctxCollege = collegeChartElement.getContext('2d');
        const collegeChart = new Chart(ctxCollege, {
            type: 'doughnut',
            data: {
                labels: collegeLabels ? collegeLabels : ['No Data'],
                datasets: [{
                    data: collegeCounts ? collegeCounts : [1],
                    backgroundColor: collegeColors ? collegeColors : ['#d3d3d3'],
                    hoverBackgroundColor: collegeColors ? collegeColors : ['#b0b0b0'],
                    hoverBorderColor: collegeColors ? collegeColors : ['#b0b0b0'],
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw;
                                const total = tooltipItem.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                const percentage = ((value / total) * 100).toFixed(1); // Calculate percentage
                                return `${label}: ${value} (${percentage}%)`; // Display count and percentage
                            }
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            }
        });
    }


    $(document).ready(function () {
        $('#existingFormModal').on('hidden.bs.modal', function () {
            $('#evaluationFormModal').modal('show');
        });

        // Handle the X button click specifically
        $('#existingFormModal .close').on('click', function () {
            $('#existingFormModal').modal('hide');
            $('#evaluationFormModal').modal('show');
        });

    });
    $(document).ready(function() {
        const eventId = "{{ $event->id }}";

        // Function to fetch participants with optional search query and URL
        function fetchParticipants(url = `/event/${eventId}/search-participants-list`, searchQuery = '') {
            $.ajax({
                url: url,
                type: 'GET',
                data: { search: searchQuery },
                success: function(response) {
                    $('#participant-list-container').html(response.html); // Update the list and pagination
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }

        // Trigger fetch on search input
        $('#search-participants-list').on('input', function() {
            const searchQuery = $(this).val();
            fetchParticipants(`/event/${eventId}/search-participants-list`, searchQuery);
        });

        // Handle pagination click
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const searchQuery = $('#search-participants-list').val() || ''; // Default to empty if no search input
            fetchParticipants(url, searchQuery);
        });

        // Initial load to display the participant list when the page loads
        fetchParticipants(); // Call with default URL and no search query
    });
    $(document).on('click', '.remove-btn', function() {
        const userId = $(this).data('user-id');
        const eventId = "{{ $event->id }}";
    
        if (confirm('Are you sure you want to remove this participant?')) {
            $.ajax({
                url: '/event/' + eventId + '/participants/' + userId + '/remove',
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('.participant-list-item[data-user-id="' + userId + '"]').remove();
                        alert('Participant removed successfully');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
    // for disabling editing of eval form when its public
    document.addEventListener('DOMContentLoaded', function () {
        @if($userevent->user_id == Auth::user()->id || Auth::user()->role_id == 1)
        const toggle = document.getElementById('is_active_toggle');
        const setupButton = document.getElementById('setupEvaluationFormButton');

        // Initial state check
        if (toggle.checked) {
            setupButton.disabled = true;
        }

        // Add event listener to toggle
        toggle.addEventListener('change', function () {
            if (this.checked) {
                setupButton.disabled = true; // Disable button when toggle is checked
            } else {
                setupButton.disabled = false; // Enable button when toggle is unchecked
            }
        });
        @endif
    });
    document.addEventListener('DOMContentLoaded', function () {
        @if($userevent->user_id == Auth::user()->id)
        const existingFormButton = document.querySelector('[data-target="#existingFormModal"]');
        const eventId = '{{ $event->id }}'; // Pass the event ID to JavaScript
        let shouldPoll = true; // Flag to control polling

        function checkHasAnswers() {
            if (!shouldPoll) return; // Stop polling if the flag is false

            fetch(`/events/${eventId}/has-answers`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.hasAnswers) {
                    // Update button state
                    existingFormButton.disabled = true;
                    existingFormButton.title = 'This action is not allowed because answers already exist.';

                    // Stop further polling
                    shouldPoll = false;
                } else {
                    existingFormButton.disabled = false;
                    existingFormButton.title = '';
                }

                // Schedule the next polling request if needed
                if (shouldPoll) {
                    setTimeout(checkHasAnswers, 5000); // Wait 5 seconds before the next request
                }
            })
            .catch(error => {
                console.error('Error checking answers:', error);

                // Retry after a delay if there's an error
                if (shouldPoll) {
                    setTimeout(checkHasAnswers, 5000); // Retry in 5 seconds
                }
            });
        }

        // Initial call to start polling
        checkHasAnswers();
        @endif
    });
    document.getElementById('updateEvaluationForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form submission for now
        const eventId = '{{ $event->id }}'; // Pass the event ID to JavaScript

        fetch(`/events/${eventId}/has-answers`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.hasAnswers) {
                alert("Editing the Evaluation Form might affect the results. Press OK to continue");
            }

            // Submit the form after showing the alert
            e.target.submit();
        })
        .catch(error => {
            console.error('Error checking answers:', error);
            alert("Unable to check answers at this time. Please try again later.");
        });
    });
</script>
@endsection
