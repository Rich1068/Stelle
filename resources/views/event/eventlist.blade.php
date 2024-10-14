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


<!-- Filter Form -->
<div class="event-filter-container p-3 mb-3">
    <div class="d-flex justify-content-center align-items-center">
        <!-- Search input with search icon inside the same container -->
        <div class="search-wrapper position-relative">
            <input type="text" id="eventSearch" class="form-control search-input" placeholder="Search for events...">
            <button class="search-btn end-0 me-2" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Sort and date input inside the same container -->
        <div class="d-flex align-items-center sort-date-wrapper ms-3">
            <input type="date" name="date" class="form-control date-input" id="date-input">
            <button class="btn btn-outline-secondary ms-2" id="clear-date-btn" type="button">Clear Date</button>
        </div>



    </div>
</div>
<div class="form-switch hide-finished-container">
    <input class="form-check-input" type="checkbox" id="hide-old-events">
    <label class="form-check-label" for="hide-old-events">
        Hide Finished Events
    </label>
</div>

<!-- Event List -->
<div id="event-list-container">
    @include('event.partials.eventlist', ['events' => $events]) <!-- Separate partial for events -->
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center" id="pagination-links">
    {{ $events->appends(request()->query())->links('vendor.pagination.custom1') }}
</div>

@endsection

@section('scripts')
<script>
    // Event listener for search input
    document.getElementById('eventSearch').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const events = document.querySelectorAll('.event-list-item');

        events.forEach(event => {
            const title = event.querySelector('.event-list-title').textContent.toLowerCase();
            const description = event.querySelector('.event-list-description').textContent.toLowerCase();
            const location = event.querySelector('.meta-item.location .meta-text').textContent.toLowerCase();

            // Check if search term matches title, description, or location
            const matches = title.includes(searchTerm) || description.includes(searchTerm) || location.includes(searchTerm);

            // Toggle visibility
            if (matches) {
                event.style.display = '';
            } else {
                event.style.display = 'none';
            }
        });
    });
    // Automatically submit form when the date is changed using AJAX
    document.getElementById('date-input').addEventListener('change', function() {
        const selectedDate = this.value;

        // Send AJAX request to filter events by date
        $.ajax({
            url: '{{ route('event.list') }}', // Ensure this is the correct route
            type: 'GET',
            data: { date: selectedDate },
            success: function(data) {
                // Update the event list and pagination with the new filtered data
                $('#event-list-container').html(data.eventsHtml);
                $('#pagination-links').html(data.paginationHtml);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Clear date filter and fetch all events
    document.getElementById('clear-date-btn').addEventListener('click', function() {
        // Clear the date input
        document.getElementById('date-input').value = '';

        // Send AJAX request with no date filter to reset the list
        $.ajax({
            url: '{{ route('event.list') }}',
            type: 'GET',
            success: function(data) {
                $('#event-list-container').html(data.eventsHtml);
                $('#pagination-links').html(data.paginationHtml);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

        // Automatically submit form when the date is changed or hide old events is toggled using AJAX
    document.getElementById('date-input').addEventListener('change', fetchFilteredEvents);
    document.getElementById('hide-old-events').addEventListener('change', fetchFilteredEvents);

    function fetchFilteredEvents() {
        const selectedDate = document.getElementById('date-input').value;
        const hideOldEvents = document.getElementById('hide-old-events').checked;

        // Send AJAX request to filter events by date and hide past events if selected
        $.ajax({
            url: '{{ route('event.list') }}', // Ensure this is the correct route
            type: 'GET',
            data: {
                date: selectedDate,
                hide_old: hideOldEvents ? 'true' : 'false'
            },
            success: function(data) {
                // Update the event list and pagination with the new filtered data
                $('#event-list-container').html(data.eventsHtml);
                $('#pagination-links').html(data.paginationHtml);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    // Clear date filter and fetch all events
    document.getElementById('clear-date-btn').addEventListener('click', function() {
        // Clear the date input
        document.getElementById('date-input').value = '';
        document.getElementById('hide-old-events').checked = false; // Reset the checkbox

        fetchFilteredEvents(); // Fetch all events without filters
    });
</script>

<style>
.hide-finished-container {
    background-color: #003366; 
    border-radius: 15px;
    padding: 10px 10px;
    display: inline-flex; /* Use inline-flex to limit the container width to its content */
    align-items: center; /* Vertically center the switch and label */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-left: 20px; /* Adjust as needed to move the container to the right */
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


</style>
@endsection
