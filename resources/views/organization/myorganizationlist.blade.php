@extends('layouts.app')

@section('body')


<!-- Include the global CSS in your main layout -->
<link href="{{ asset('assets/css/sb-admin-2.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

<div class="top-container">
    <h2 class="font-weight-bold mb-0">
        <i class="fas fa-solid fa-users me-2"></i> <!-- Calendar icon -->
        My Organization List
    </h2>
</div>

<!-- Filter Form -->
<div class="event-filter-container p-3">
    <div class="d-flex flex-column justify-content-center align-items-center">
        <!-- Search input with search icon inside the same container -->
        <div class="search-wrapper position-relative mb-3 mb-md-0 me-md-3">
            <input type="text" id="eventSearch" class="form-control search-input" placeholder="Search for organizations...">
            <button class="search-btn end-0 me-2" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</div>

<!-- Event List -->
<div id="event-list-container">
    @include('organization.partials.myorganizationlist', ['organizations' => $organizations]) <!-- Separate partial for events -->
</div>

<!-- No Events Message -->
@if ($organizations->count() == 0)
    <div class="no-events-container">
        <i class="fas fa-solid fa-users"></i>
        <p>No Organizations available.</p>
    </div>
@endif
    <div class="no-events-container" style="display: none;">
        <i class="fas fa-solid fa-users"></i>
        <p>No Organizations available.</p>
    </div>
<!-- Pagination -->
<div class="d-flex justify-content-center" id="pagination-links">
    {{ $organizations->appends(request()->query())->links('vendor.pagination.custom1') }}
</div>


@endsection

@section('scripts')
<script>
    document.getElementById('eventSearch').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();

        // Send AJAX request for search
        $.ajax({
            url: '{{ route("organization.mylist") }}', // Route for My Organization List
            type: 'GET',
            data: { search: searchTerm }, // Pass the search term to the backend
            success: function (data) {
                // Update the organization list and pagination dynamically
                $('#event-list-container').html(data.organizationsHtml);
                $('#pagination-links').html(data.paginationHtml);

                // Handle the "No Organizations" message
                if (data.hasOrganizations) {
                    $('.no-events-container').hide(); // Hide if organizations are available
                } else {
                    $('.no-events-container').show(); // Show if no organizations are found
                }
            },
            error: function (xhr) {
                console.error('Error fetching organizations:', xhr.responseText);
            }
        });
    });

    // Handle pagination clicks with AJAX
    $(document).on('click', '#pagination-links a', function (e) {
        e.preventDefault(); // Prevent default link behavior
        const url = $(this).attr('href'); // Get the pagination URL

        // Send AJAX request for the selected page
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                $('#event-list-container').html(data.organizationsHtml);
                $('#pagination-links').html(data.paginationHtml);

                if (data.hasOrganizations) {
                    $('.no-events-container').hide(); // Hide if organizations are available
                } else {
                    $('.no-events-container').show(); // Show if no organizations are found
                }
            },
            error: function (xhr) {
                console.error('Error fetching organizations:', xhr.responseText);
            }
        });
    });
</script>



<style>
.show-all-container {
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

.event-date-container {
    display: flex;
    flex-direction: column; /* Stack vertically by default */
    align-items: center;
}

.event-date-container span {
    display: block;
}

@media (max-width: 768px) {
    .event-date-container {
        flex-direction: row; /* Change to horizontal on mobile */
        justify-content: center;
        gap: 5px; /* Add some spacing between elements */
    }

    .event-date-container span {
        display: inline; /* Align text horizontally */
    }
}

</style>
@endsection
