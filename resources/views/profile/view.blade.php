    @extends('layouts.app')

    @section('body')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-container">
    <ul class="nav nav-tabs mt-4" id="profileTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">
                Personal Information
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="certificates-tab" data-toggle="tab" href="#certificates" role="tab" aria-controls="certificates" aria-selected="false">
                Certificates
            </a>
        </li>
    </ul>
    <div id="profileTabContent">
    <!-- Personal Information Tab Content -->
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <!-- Profile Header Section -->
        <div class="profile-header">
            <div class="profile-picture">
                @if($user->profile_picture == null)
                    <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default profile picture"> 
                @else 
                    <img src="{{ asset($user->profile_picture) }}" alt="Profile picture of {{ $user->first_name }}"> 
                @endif
            </div>
            
            <div class="profile-info">
            <h2 class="name-bold">
                {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}
                @if($user->trashed())
                    <span style="color: red;">(DELETED)</span>
                @endif
            </h2>
                <p style="font-size: 1.3em; color: #003d80; display: inline;">
                    <strong class="bio-label" style="font-size: 1.2em; color: #003d80; margin-right: 5px;">Bio:</strong> 
                    <span style="font-size: 1.2em; color: grey;">
                        @if($user->description == null)
                            N/A
                        @else
                            {{ $user->description }}
                        @endif
                    </span>
                </p>
            </div>
            @if(Auth::user()->role_id == 1)
            <a href="{{ route('superadmin.editProfile', ['id' => $user->id]) }}" class="btn-edit">
                <i class="fas fa-pencil-alt"></i> <span>Edit</span>
            </a>
            @endif
        </div>

        <!-- Profile Body Grid Layout -->
        <div class="profile-body-grid">
            <!-- Information Section -->
            <div class="info-section">
                <h3 class="bold-blue">
                    <i class="fas fa-info-circle"></i> Information
                </h3>
                <p><i class="fas fa-envelope"></i> <strong class="label-blue">Email:</strong> {{ $user->email }}</p>
                <div class="info-divider"></div>
                <p><i class="fas fa-phone"></i> <strong class="label-blue">Contact Number:</strong> 
                    @if($user->contact_number == null) N/A @else {{ $user->contact_number }} @endif
                </p>
                <div class="info-divider"></div>
                <p><i class="fas fa-flag"></i> <strong class="label-blue">Country:</strong> 
                    @if($user->country_id == null) 
                        N/A 
                    @else 
                        {{ $countryTable->countryname }} 
                        <img src="{{ asset('storage/images/flags/' . strtolower($countryTable->code) . '.png') }}" alt="Flag of {{ $countryTable->countryname }}" class="flag-icon"> 
                    @endif
                </p>
                @if($user->country_id == 177)
                <div class="info-divider"></div>
                <p><i class="fas fa-city"></i> <strong class="label-blue">Region:</strong> 
                    @if($user->region_id == null) N/A @else {{ $user->region->regDesc }} @endif
                </p>
                <div class="info-divider"></div>
                <p><i class="fas fa-map-marker-alt"></i> <strong class="label-blue">Province:</strong> 
                    @if($user->province_id == null) N/A @else {{ $user->province->provDesc }} @endif
                </p>
                @endif
                <div class="info-divider"></div>
                <p><i class="fas fa-university"></i> <strong class="label-blue">College:</strong> 
                    @if($user->college == null) N/A @else {{ $user->college }} @endif
                </p>
                <div class="info-divider"></div>
                <p><i class="fas fa-venus-mars"></i> <strong class="label-blue">Gender:</strong> 
                    @if($user->gender == null) N/A @else {{ $user->gender }} @endif
                </p>
                <div class="info-divider"></div>
                <p><i class="fas fa-birthday-cake"></i> <strong class="label-blue">Birthdate:</strong> 
                    @if($user->birthdate == null) N/A @else {{ $user->birthdate }} @endif
                </p>
                <!-- Additional profile information here -->
            </div>

            <!-- Events Tabs for Personal Information -->
            <div class="profile-attended-events-container">
                <ul class="nav nav-tabs" id="eventTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="attended-events-tab" data-toggle="tab" href="#attended-events" role="tab" aria-controls="attended-events" aria-selected="true">
                            <i class="fas fa-calendar-alt"></i> Attended Events
                        </a>
                    </li>
                    @if ($user->role_id == 1 || $user->role_id == 2)
                    <li class="nav-item">
                        <a class="nav-link" id="created-events-tab" data-toggle="tab" href="#created-events" role="tab" aria-controls="created-events" aria-selected="false">
                            <i class="fas fa-calendar-plus"></i> Created Events
                        </a>
                    </li>
                    @endif
                </ul>

                <!-- Tab Content for Events -->
                <div class="tab-content">
                    <!-- Attended Events Tab -->
                    <div class="tab-pane fade show active" id="attended-events" role="tabpanel" aria-labelledby="attended-events-tab">
                        <div class="profile-attended-events-list">
                            @if($attendedEvents->isEmpty())
                                <p>N/A</p>
                            @else
                                <table class="profile-attended-events-table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($attendedEvents as $event)
                                        <tr>
                                            <td>
                                                <a href="{{ route('event.view', $event->id) }}" class="profile-created-events-link">
                                                    {{ Str::limit($event->title, 40, '...') }}
                                                    @if($event->trashed())
                                                    <span style="color: red;">(DELETED)</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>{{ $event->start_date }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>

                    <!-- Created Events Tab -->
                    <div class="tab-pane fade" id="created-events" role="tabpanel" aria-labelledby="created-events-tab">
                        <div class="profile-attended-events-list">
                            @if($createdEvents->isEmpty())
                                <p>N/A</p>
                            @else
                                <table class="profile-attended-events-table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($createdEvents as $userEvent)
                                        @if($userEvent)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('event.view', $userEvent->id) }}" class="profile-created-events-link">
                                                        {{ Str::limit($userEvent->title, 40, '...') }} <!-- Limit to 40 characters -->
                                                    </a>
                                                </td>
                                                <td>{{ $userEvent->date }}</td>
                                            </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
        <div class="info-container">
            <div class="info-section">
                <h3 class="bold-blue">
                    <i class="fas fa-info-circle"></i> Analytics
                </h3>
            </div>

            @if($user->role_id == 1 || $user->role_id == 2)
                @include('profile.partials.admin-analytics')
            @elseif ($user->role_id == 3)
                @include('profile.partials.user-analytics')
            @endif
        </div>  
        @endif

    </div>

    <!-- Certificates Tab Content -->
    <div class="tab-pane fade" id="certificates" role="tabpanel" aria-labelledby="certificates-tab">
        @include('profile.partials.viewUserCertificate')
    </div>
</div>

<!-- Analytics Section (Always Visible Below the Tabs) -->

</div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        // Activate tab on click
        $('#profileTabs a[data-toggle="tab"]').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show'); // Show the clicked tab content
        });

        // Optionally maintain the active tab on page reload (using URL hash)
        var hash = window.location.hash;
        if (hash) {
            $('#profileTabs a[href="' + hash + '"]').tab('show');
        }

        // Optionally update the URL when a tab is switched
        $('#profileTabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash; // Update the hash in the URL
        });
    });

    $(document).ready(function () {
        function resizeTabsOnMobile() {
            // Check if the screen width is less than 768px (mobile)
            if ($(window).width() <= 768) {
                // Remove 'active' class from all tabs and make them smaller
                $('#profileTabs .nav-link').not('.active').css({
                    'font-size': '0.75em',
                    'padding': '5px 10px'
                });

                // Make the active tab larger
                $('#profileTabs .nav-link.active').css({
                    'font-size': '1.1em',
                    'padding': '10px 20px'
                });
            }
        }

        // Apply the resize when a tab is clicked
        $('#profileTabs a[data-toggle="tab"]').on('click', function () {
            // Mark the clicked tab as active and resize
            $('#profileTabs .nav-link').removeClass('active');
            $(this).addClass('active');
            resizeTabsOnMobile();
        });

        // Call the function on page load to ensure the initial state is correct
        resizeTabsOnMobile();

        // Optionally, you can listen for window resize to reapply the styles if needed
        $(window).resize(function () {
            resizeTabsOnMobile();
        });
    });
</script>


<style>

.profile-attended-events-container {
    position: relative; /* Ensure the parent element allows absolute positioning */
}

ul.nav.nav-tabs {
    position: absolute;
    top: 0;
    left: 0;
    margin: 0;
    padding: 0;
    z-index: 10;
}

.profile-attended-events-list {
    margin-top: 40px; /* Adjust to push content below the tabs */
}

.profile-attended-events-table {
    width: 100%;
    border-collapse: collapse;
}

.profile-created-events-list {
    margin-top: 40px; /* Adjust to push content below the tabs */
}

.profile-created-events-table {
    width: 100%;
    border-collapse: collapse;
}

.profile-attended-events-table th, .profile-created-events-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
ul.nav-tabs {
    border-bottom: 2px solid #002855; /* Blue bottom border */
    background-color: transparent; /* Remove any background color from the tab container */
    border: none; /* Remove the default border */
}

ul.nav-tabs .nav-link {
    border: none; /* Remove the border around the individual tabs */
    background-color: transparent; /* Set tab background to transparent */
    color: #002855; /* Set text color to blue */
    padding: 10px 20px; /* Adjust padding to create spacing */
}

ul.nav-tabs .nav-link.active {
    border-bottom: 4px solid #002855; /* Blue bottom border on the active tab */
    background-color: #002855; /* Blue background for active tab */
    color: #fff; /* White text for the active tab */
    border-radius: 0; /* Remove any border-radius to square the tab */
}

ul.nav-tabs .nav-link:hover {
    color: #002855; /* Blue text color on hover */
}

.tab-content {
    border: none; /* Remove borders around the tab content */

}

#profileTabs {
    position: relative;
    top: 0;
    left: 0;
    z-index: 5;
    margin-left: 15px;
    margin-right: 15px;
    padding: 0;
    border-bottom: 2px solid #ddd; /* Clean border under the profile tabs */
}

#profileTabContent .tab-pane {
    padding-top: 20px; /* Proper spacing for profile tab content */
}

/* Styling for the Personal Information & Certificates nav-tabs */
#profileTabs .nav-link {
    border: none;
    background-color: transparent;
    color: #002855;
    padding: 10px 20px;
    font-size: 1.1em;
    border-radius: 5px 5px 0 0; /* Round top corners */
}

#profileTabs .nav-link.active {
    background-color: #002855;
    color: white;
    border-radius: 5px 5px 0 0; /* Rounded top, squared bottom */
    border-bottom: none;
}

#profileTabs .nav-link:hover {
    color: #002855;
}


#eventTabs .nav-link:hover {
    color: #002855;
}

/* Container positioning */
.profile-container {
    position: relative; /* Ensure proper absolute positioning */
    padding-top: 30px;
}

/* Profile header positioning */
.profile-header {
    position: relative;
    margin-top: 30px;
}

/* Adjust margin for the events list content */
.profile-attended-events-list {
    margin-top: 40px; /* Create space between tabs and content */
}

/* Profile Page Specific Styling for Personal Information & Certificates */
/* Profile Page Specific Styling for Personal Information & Certificates */
#profileTabs {
    position: relative;
    top: 0;
    left: 0;
    z-index: 5;
    margin-left: 0;
    margin-right: 0;
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 0;
    border-bottom: none;
}

/* Proper spacing for profile tab content */
#profileTabContent .tab-pane {
    padding-top: 0px; /* Remove padding between tabs and profile content */
}

/* Styling for the Personal Information & Certificates nav-tabs */
#profileTabs .nav-link {
    border: none;
    background-color: transparent;
    color: #002855;
    padding: 8px 15px; /* Keep padding minimal */
    font-size: 1.1em;
    border-radius: 5px 5px 0 0; /* Round top corners */
    margin-right: 5px; /* Reduced space between the tabs */
    text-align: center; /* Keep text centered */
    white-space: nowrap; /* Prevent text from wrapping */
}

#profileTabs .nav-link.active {
    background-color: #002855;
    color: white;
    border-radius: 5px 5px 0 0; /* Rounded top, squared bottom */
    border-bottom: 2px solid #002855;
}

#profileTabs .nav-link:hover {
    color: #002855;
}

/* Profile header positioning */
.profile-header {
    position: relative;
    margin-top: 0; /* Remove any extra margin */
}

/* Ensure the profile section starts right after the tabs */
.profile-container {
    padding-top: 0px; /* Remove any padding between tabs and profile content */
}

.certificate-actions .btn {
    margin: auto;
}

.profile-body-grid {
    margin-bottom: 20px; /* Adjust the value as needed */
}
    /* Customize only for mobile screens */
/* Customize for mobile screens */

/* Mobile-specific styles */
/* Mobile-specific styles */
/* Mobile-specific styles */
@media (max-width: 768px) {
    ul.nav-tabs .nav-link {
        font-size: 0.75em; /* Smaller font size for inactive tabs */
        padding: 5px 10px; /* Smaller padding */
        text-align: center;
        white-space: nowrap;
        transition: font-size 0.3s, padding 0.3s, border-radius 0.3s; /* Smooth transition for size and shape */
        background-color: white; /* Set inactive tabs to white */
        color: #002855; /* Keep text color blue */
        border-radius: 50px; /* Pill-shaped border for unclicked tabs */
    }

    /* Style for active tab on mobile */
    ul.nav-tabs .nav-link.active {
        font-size: 1.1em; /* Larger font size for active tab */
        padding: 10px 20px; /* Larger padding for active tab */
        background-color: white; /* Set active tab to white */
        color: #002855; /* Keep text color blue */
        border-bottom: 2px solid #002855; /* Blue bottom border for active tab */
        border-radius: 0; /* No pill shape for active tab */
    }

    /* Make sure tabs are side by side */
    ul.nav-tabs {
        display: flex;
        justify-content: space-between; /* Ensure tabs remain side by side */
        flex-wrap: nowrap; /* Prevent wrapping */
        overflow-x: auto; /* Allow horizontal scroll if content exceeds width */
        width: 100%;
    }

    ul.nav-tabs .nav-item {
        flex: 1; /* Ensure each tab takes up equal space */
    }

    /* Profile header adjustments */
    .profile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 15px;
    }

    /* Ensure profile container and events list are centered and do not overflow */
    .profile-container, .profile-attended-events-container {
        padding-left: 10px;
        padding-right: 10px;
        text-align: center;
    }

    .profile-attended-events-list {
        margin-top: 20px;
    }

    .profile-attended-events-table, .profile-created-events-table {
        width: 100%;
        table-layout: auto;
        border-collapse: collapse;
    }

    .profile-attended-events-table th, .profile-created-events-table td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
}



</style>


@endsection
