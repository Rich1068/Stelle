@extends('layouts.app')
@section('body')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="event-view-container">
    <!-- Tab Navigation -->
    <div class="tabs">
        <div class="tab-button active" data-tab="main">Event Details</div>
        <div class="tab-button" data-tab="participants">Participants</div>
        @if ($currentUser == $userevent->user->id)
            <div class="tab-button" data-tab="feedback">Evaluation Form/Analytics</div>
        @endif
    </div>

    <!-- Tab Contents -->
    <div class="tab-content">
        <!-- Main Page Content -->
        <div class="tab-pane active" id="main">
            <div class="event-view-content">
                <div class="event-view-header">
                    <h1 class="event-view-event-title">{{ $event->title }}@if($event->trashed()) <span style="color: red;">(DELETED)</span>@endif</h1>
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
                    <div><i class="fas fa-calendar-alt"></i><span data-label="Date: ">{{ $event->date }}</span></div>
                    <div><i class="fas fa-map-marker-alt"></i><span data-label="Address: ">{{ $event->address }}</span></div>
                    <div><i class="fas fa-clock"></i><span data-label="Duration: ">{{ $event->start_time }} to {{ $event->end_time }}</span></div>
                    <div><i class="fas fa-users"></i><span data-label="Capacity: ">{{ $currentParticipants }}/{{ $event->capacity }}</span></div>
                    <div><i class="fas fa-desktop"></i><span data-label="Mode: ">{{ $event->mode }}</span></div>
                    <div><i class="fas fa-user"></i><span data-label="By: "><a href="{{ route('profile.view', $userevent->user->id) }}" class="no-link-style">
                        {{ $userevent->user->first_name }} {{ $userevent->user->last_name }}</a></span></div>
                </div>
                @if($event->trashed())
                
                @else
                @if($userevent->user_id == Auth::user()->id)
                    <div class="event-view-buttons">
                        <a href="{{ route('event.edit', $event->id) }}" class="btn btn-primary">
                            <span>Edit</span>
                        </a>

                        <!-- Create and View Certificate Buttons Side by Side -->
                        <div class="certificate-buttons">
                            @if ($certificate == null)
                                <a href="{{ route('event_certificates.create', $event->id) }}" class="btn btn-primary">Create/Send Certificate</a>
                            @else
                                <a href="{{ route('event_certificates.create', $event->id) }}" class="btn btn-primary">Update/Send Certificate</a>
                            @endif

                            @if ($certificate)
                                <button id="viewCertificateButton" data-image-url="{{ asset($certificate->cert_path) }}" class="btn btn-primary">View Certificate</button>
                            @endif
                        </div>

                        <!-- View Participants Button -->
                 
                    </div>
                @endif
                @if($userevent->user_id != Auth::user()->id)
                    @if ($participant && $participant->status_id == 1)
                        <p>You have been accepted to this event.</p>
                    @elseif (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($event->date . ' ' . $event->end_time)))
                        <button type="button" class="btn btn-secondary" disabled>Closed</button>
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
                                <form action="{{ route('evaluation-form.take', ['id' => $event->id, 'form' => $evaluationForm->form_id]) }}" method="GET" class="full-width-button">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Take Evaluation</button>
                                </form>
                            @endif
                        @else
                            <button type="button" class="btn btn-secondary" disabled>Evaluation Not Yet Available</button>
                        @endif
                    @endif
                @endif
                @endif
            </div>
        </div>
        <!-- Participants Tab -->
        <div class="tab-pane" id="participants">
            @include('event.partials.participantlist', ['event' => $event, 'participants' => $participants, 'currentUser' => $currentUser, 'userevent' =>$userevent])
            @if ($currentUser == $userevent->user->id)
            @if($event->trashed())
                
            @else
            <a href="{{ route('events.pendingparticipants', $event->id) }}" class="position-relative">
                <button type="submit" class="btn btn-primary-2 position-relative">
                    View Pending Participants
                    @if($pendingParticipantsCount > 0)
                        <span class="badge bg-danger pending-badge">{{ $pendingParticipantsCount }}</span>
                    @endif
                </button>
            </a>
            @endif
            @endif
        </div>
    </div>
<!-- Event Analytics Tab -->
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
    <button type="button" class="btn btn-primary-2" data-toggle="modal" data-target="#evaluationFormModal">
        Setup Evaluation Form
    </button>
    @if($event->evaluationForm)
    <button type="button" class="btn btn-primary-2">
        <a href="{{ route('evaluation.results', ['id' => $event->id]) }}" style="color:white; text-decoration:none;">
            View Evaluation Results
        </a>
    </button>
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
    @endif
</div>

</div>

<!-- Modal for certificate viewing -->
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
                <form action="{{ route('event-evaluation-forms.edit', ['formId' => $event->evaluationForm->form_id, 'id' => $event->id]) }}" method="GET" class="full-width-button">
                    @csrf
                    <button type="submit" class="btn btn-primary-2 btn-block">Update Evaluation Form</button>
                </form>
                @else
                <form action="{{ route('event-evaluation-forms.create', ['id' => $event->id]) }}" method="GET" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block">Create Evaluation Form</button>
                </form>
                @endif
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
                                                <option value="{{ $form->id }}">{{ $form->form_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block">Use Selected Form</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<style>
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

    var userAgeData = @json($userAgeData); // Data passed from the controller

    var ctx = document.getElementById("userAgeChart").getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: userAgeData.labels,  // Age ranges from the controller
            datasets: [{
                data: userAgeData.values,  // Age counts from the controller
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#d3d3d3'], 
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f4b619', '#e63946','#5a32a3', '#b0b0b0'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 10,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80, // Adjusts the doughnut chart cutout size
        },
    });

    var genderLabels = @json($genderLabels);
    var genderCounts = @json($genderCounts);

    var ctx = document.getElementById("userGenderChart").getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: genderLabels,  // Gender labels (Male, Female, N/A)
            datasets: [{
                data: genderCounts,  // Gender counts
                backgroundColor: ['#4e73df', '#1cc88a', '#d3d3d3'],  // Colors for Male, Female, N/A
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#b0b0b0'], // Hover colors for Male, Female, N/A
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 10,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80, // Adjust the doughnut chart cutout size
        },
    });
    const regionLabels = @json($regionLabels);
    const regionCounts = @json($regionCounts);

    const provinceLabels = @json($provinceLabels);
    const provinceCounts = @json($provinceCounts);

    
    const collegeLabels = @json($collegeLabels);
    const collegeCounts = @json($collegeCounts);


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
    // Render Region Pie Chart
    var ctxRegion = document.getElementById("regionChart").getContext('2d');
    var regionChart = new Chart(ctxRegion, {
        type: 'doughnut',
        data: {
            labels: regionLabels,  // Region names (labels)
            datasets: [{
                data: regionCounts,  // Region participant counts
                backgroundColor: regionColors,
                hoverBackgroundColor: regionColors,
                hoverBorderColor: regionColors,
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 10,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,  // Adjust doughnut chart cutout size
        },
    });


    // Render Province Pie Chart
    var ctxProvince = document.getElementById("provinceChart").getContext('2d');
    var provinceChart = new Chart(ctxProvince, {
        type: 'doughnut',
        data: {
            labels: provinceLabels,  // Province names (labels)
            datasets: [{
                data: provinceCounts,  // Province participant counts
                backgroundColor: provinceColors,
                hoverBackgroundColor: provinceColors,
                hoverBorderColor: provinceColors,
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 10,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,  // Adjust doughnut chart cutout size
        },
    });


    // College Distribution Chart
    const ctxCollege = document.getElementById('collegeChart').getContext('2d');
    const collegeChart = new Chart(ctxCollege, {
        type: 'doughnut',
        data: {
            labels: collegeLabels,
            datasets: [{
                data: collegeCounts,
                backgroundColor: collegeColors,
                hoverBackgroundColor: collegeColors,
                hoverBorderColor: collegeColors,
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 10,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80, // Adjust the doughnut chart cutout size
        },
    });

    $('#existingFormModal').on('hidden.bs.modal', function () {
        $('#evaluationFormModal').modal('show');
    });

    // Handle the X button click specifically
    $('#existingFormModal .close').on('click', function () {
        // Manually trigger the hiding of the modal and reopening of the first modal
        $('#existingFormModal').modal('hide');
        $('#evaluationFormModal').modal('show');
    });

    // Region Data for Pie Chart
    
</script>
@endsection
