@extends('layouts.app')

@section('body')
<div class="top-container">
<h2 class="font-weight-bold mb-0">
        <i class="fas fa-list me-2"></i> <!-- List icon -->
        My Event List
    </h2>
</div> <!-- Close page-title-container-eventlist -->

<!-- Add a search input above the list -->
<div class="event-filter-container p-3 mb-3">
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center">
        <!-- Search input with search icon inside the same container -->
        <div class="search-wrapper position-relative mb-3 mb-md-0"> <!-- Added mb-3 for spacing -->
            <input type="text" id="eventSearch" class="form-control search-input" placeholder="Search for events...">
            <button class="search-btn end-0 me-2" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Sort and date input inside the same container -->
        <div class="d-flex align-items-center sort-date-wrapper ms-md-3">
            <input type="date" name="date" class="form-control date-input" id="date-input">
            <button class="btn btn-outline-secondary ms-2" id="clear-date-btn" type="button">Clear Date</button>
        </div>
    </div>
</div>

<!-- Event List -->
<div id="event-list-container">
    @include('event.partials.myeventlist', ['events' => $events]) <!-- Load the partial for events -->
</div>

<!-- Pagination -->
<div id="pagination-links">
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
            url: '{{ route('event.myeventlist') }}', // Ensure this is the correct route
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
</script>
@endsection
