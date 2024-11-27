@extends('layouts.app')

@section('body')


<!-- Include the global CSS in your main layout -->
<link href="{{ asset('assets/css/sb-admin-2.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

<div class="top-container">
    <h2 class="font-weight-bold mb-0">
        <i class="fas fa-calendar-alt me-2"></i> <!-- Calendar icon -->
        Event List
    </h2>
</div>

<div class="d-flex justify-content-between align-items-center filter-container mb-3">
    <div class="form-switch show-all-container">
        <input class="form-check-input" type="checkbox" id="show-all-events">
        <label class="form-check-label" for="show-all-events">
            Show All Events
        </label>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-end event-filters">
        <!-- Search input with search icon inside the same container -->
        <div class="search-wrapper position-relative mb-3 mb-md-0 me-md-3">
            <input type="text" id="eventSearch" class="form-control search-input" placeholder="Search for events...">
            <button class="search-btn end-0 me-2" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Sort and date input inside the same container -->
        <div class="d-flex align-items-center sort-date-wrapper" style="margin-right:1.5%;">
            <input type="date" name="date" class="form-control date-input" id="date-input">
            <button class="btn btn-outline-secondary ms-2" id="clear-date-btn" type="button">Clear Date</button>
        </div>

        <!-- Organization filter -->
        <div class="d-flex align-items-center">
            <select id="organizationFilter" class="form-select">
                <option value="">{{ __('All Organizations') }}</option>
                @foreach ($organizations as $organization)
                    <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<!-- Event List -->
<div id="event-list-container">
    @include('event.partials.eventlist', ['events' => $events]) <!-- Separate partial for events -->
</div>

<!-- No Events Message -->
@if ($events->count() == 0)
    <div class="no-events-container">
        <i class="fas fa-calendar-times"></i>
        <p>No events available.</p>
    </div>
@endif

<!-- Pagination -->
<div class="d-flex justify-content-center" id="pagination-links">
    {{ $events->appends(request()->query())->links('vendor.pagination.custom1') }}
</div>


@endsection

@section('scripts')
<script>
    document.getElementById('eventSearch').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const events = document.querySelectorAll('.event-list-item');
        let hasResults = false;

        events.forEach(event => {
            const title = event.querySelector('.event-list-title').textContent.toLowerCase();
            const description = event.querySelector('.event-list-description').textContent.toLowerCase();
            const location = event.querySelector('.meta-item.location .meta-text').textContent.toLowerCase();

            const matches = title.includes(searchTerm) || description.includes(searchTerm) || location.includes(searchTerm);
            event.style.display = matches ? '' : 'none';

            if (matches) hasResults = true;
        });

        toggleNoEventsMessage(hasResults);
    });

    document.getElementById('date-input').addEventListener('change', fetchFilteredEvents);
    document.getElementById('clear-date-btn').addEventListener('click', function() {
        document.getElementById('date-input').value = '';

        fetchFilteredEvents();
    });
    document.getElementById('show-all-events').addEventListener('change', function() {
        document.getElementById('eventSearch').value = '';
        fetchFilteredEvents();
    });

    function fetchFilteredEvents() {
        const selectedDate = document.getElementById('date-input').value;
        const showAllEvents = document.getElementById('show-all-events').checked;
        const selectedOrganization = document.getElementById('organizationFilter').value;
        // Send AJAX request to filter events by date and toggle between ongoing/all events
        $.ajax({
            url: '{{ route('event.list') }}',
            type: 'GET',
            data: {
                date: selectedDate,
                show_all: showAllEvents ? 'true' : 'false',
                organization: selectedOrganization
            },
            success: function(data) {
                // Update the event list and pagination with the new filtered data
                $('#event-list-container').html(data.eventsHtml);
                $('#pagination-links').html(data.paginationHtml);

                // Determine if the event list is empty and show/hide the "No events available" message
                const hasEvents = $(data.eventsHtml).find('.event-list-item').length > 0;
                $('.no-events-container').toggle(!hasEvents);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
    document.getElementById('organizationFilter').addEventListener('change', fetchFilteredEvents);
    function toggleNoEventsMessage(hasResults) {
        const noEventsContainer = document.querySelector('.no-events-container');
        noEventsContainer.style.display = hasResults ? 'none' : 'block';
    }
</script>



<style>
.filter-container {
    padding: 0 5%;
}
.show-all-container {
    background-color: #003366; 
    border-radius: 15px;
    padding: 10px 10px;
    display: inline-flex; /* Use inline-flex to limit the container width to its content */
    align-items: center; /* Vertically center the switch and label */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: auto; /* Ensure the container fits the content */
    color: white; /* Set text color to white */
}

/* Input switch styling */
.form-switch .form-check-input {
    width: 40px;
    height: 20px;
    background-color: #003366;
    border-radius: 20px;
    border: none;
    appearance: none;
    cursor: pointer;
    outline: none;
    transition: background-color 0.3s ease-in-out;
    position: relative;
    margin-left: 1px;
    margin-right: 10px; /* Add space between switch and label */
    margin-top: 1px; /* Slightly adjust the vertical position of the switch */
}

/* Styling for the circle inside the switch */
.form-switch .form-check-input::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 16px;
    height: 16px;
    background-color: white;
    border-radius: 50%;
    transition: transform 0.3s ease-in-out;
}

/* Change background and move the circle when checked */
.form-switch .form-check-input:checked {
    background-color: LightSeaGreen; /* This will show the light sea green when checked */
}

.form-switch .form-check-input:checked::before {
    transform: translateX(20px);
}

/* Label styling */
.form-check-label {
    font-size: 1rem;
    font-weight: bold;
    color: white; /* Set label color to white */
    cursor: pointer;
    display: flex; /* Use flex to align items in the label */
    align-items: center; /* Center the icon and text */
    line-height: 1; /* Set line height to ensure consistent alignment */
    margin-top: 2px; /* Slightly adjust the vertical position of the text */
}

.event-date-container {
    display: flex;
    flex-direction: column; /* Stack vertically by default */
    align-items: center;
}

.event-date-container span {
    display: block;
}

@media (min-width: 768px) and (max-width: 1024px) {
    /* Adjusting container padding for tablets */
    .filter-container {
        flex-direction: column; /* Stack vertically */
        align-items: stretch; /* Align filters to full width */
        padding: 10px;
        gap: 15px; /* Add spacing between filters */
    }

    .show-all-container {
        margin-bottom: 10px; /* Add space below */
    }

    .event-filters {
        flex-direction: column; /* Stack filters vertically */
        width: 100%;
        gap: 10px; /* Space between filters */
    }

    .event-filters > * {
        margin: 0; /* Remove margin */
        width: 100%; /* Make filters full width */
    }

    .search-wrapper,
    .sort-date-wrapper,
    #organizationFilter {
        width: 100%; /* Full width for individual elements */
    }

    /* Align the toggle switch in the center for portrait view */
    .show-all-container {
        justify-content: center; /* Center the toggle switch */
    }
}

#organizationFilter {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 120%;
    background-color: #fff;
    border-radius: 10px;
    padding: 0;
    border-color: #fff;
    height: 48px;
}


#organizationFilter option {
    padding: 5px; /* Space inside the dropdown options */
}
.ms-3 {
    margin-left: 10px; /* Adjust space from surrounding elements */
    align-items: center; /* Vertically center the dropdown */
}

.form-select {
    min-width: 200px; /* Adjust width for better readability */
    max-width: 300px; /* Prevent overly wide dropdowns */
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column; /* Stack vertically */
        align-items: stretch; /* Make full-width */
        gap: 10px; /* Reduce gap between sections */
    }

    .event-filters {
        flex-direction: column; /* Stack filters vertically */
        align-items: stretch;
    }

    .event-filters > * {
        margin-left: 0; /* Reset left margin for vertical layout */
        margin-bottom: 10px; /* Add spacing below each filter */
    }
}
</style>
@endsection
