@extends('layouts.app')

@section('body')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none; margin-bottom: 100px;">
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-calendar-alt"></i> Event List
        </h2>
    </div>
</div>

<div class="card mb-4" style="margin-top: 50px; border: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Events List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Organizer</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>
                            <a href="{{ route('event.view', $event->id) }}" class="event-title" style="color: #001e54; text-decoration: none;">
                                {{ \Illuminate\Support\Str::limit($event->title, 16) }}
                            </a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</td>
                        <td>{{ $event->userEvent->user->first_name }} {{ $event->userEvent->user->last_name }}</td>
                        <td>
                            @if($event->trashed())
                                <span style="color: red;">DELETED</span>
                            @elseif(
                                \Carbon\Carbon::now('Asia/Manila')->isSameDay(\Carbon\Carbon::parse($event->date)) &&
                                    \Carbon\Carbon::now('Asia/Manila')->format('H:i:s') > $event->end_time || \Carbon\Carbon::parse($event->date . ' ' . $event->end_time)->isPast()
                                )
                                <span style="color: gray;">CLOSED</span>
                            @else
                                <span style="color: green;">ACTIVE</span>
                            @endif
                        </td>
                        <td>
                            <div class="button-group" style="display: flex; justify-content: center; align-items: center;">

                                <!-- Conditionally display Delete or Recover button -->
                                @if($event->trashed())
                                    <!-- Recover button for soft deleted event -->
                                    <form action="{{ route('event.recover', $event->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-recover rounded-circle" 
                                            onclick="return confirm('Are you sure you want to recover this event?')" title="Recover Event">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Delete button for active event -->
                                    <form action="{{ route('event.deactivate', $event->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-delete rounded-circle" 
                                            onclick="return confirm('Are you sure you want to soft delete this event?')" title="Delete Event">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



<style>

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    color: #333; /* Darker text color for better readability */
    font-size: 0.9rem; /* Slightly smaller font for compact look */
    margin: 10px 0; /* Space between elements */
}

#evaluationFormsTable {
    width: 100%;
    border-collapse: collapse;
    color: #002060; /* Dark blue text color */
    font-size: 0.9rem; /* Smaller font for cleaner look */
}

#evaluationFormsTable th, #evaluationFormsTable td {
    text-align: center;
    vertical-align: middle;
    padding: 10px;
    border-bottom: 1px solid #e0e0e0; /* Light grey bottom border only */
}

/* Header Styling */
#evaluationFormsTable th {
    background-color: #f7f8fa; /* Softer light grey for the header */
    font-weight: 600; /* Slightly bolder for visibility */
    color: #333; /* Darker text for header */
}

/* Alternating Row Colors */
#evaluationFormsTable tbody tr:nth-child(odd) {
    background-color: #fafbfc; /* Very light grey for odd rows */
}

#evaluationFormsTable tbody tr:nth-child(even) {
    background-color: #ffffff; /* White for even rows */
}

/* Hover Effect */
#evaluationFormsTable tbody tr:hover {
    background-color: #f0f4ff; /* Light blue tint on hover */
}

/* Cleaner Pagination Styling */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: none;
    color: #002060; /* Dark blue text */
    border: none;
    font-size: 0.85rem;
    padding: 5px 10px;
    margin: 0 2px;
    cursor: pointer;
    transition: color 0.2s ease;
}

/* Pagination Hover and Active Style */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #004080; /* Slightly darker blue on hover */
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #ffffff; /* White text for active page */
    background-color: #002060; /* Dark blue background for active page */
    border-radius: 5px;
}

/* Remove Pagination Focus Outline */
.dataTables_wrapper .dataTables_paginate .paginate_button:focus {
    outline: none;
    box-shadow: none;
}

/* Styling for Dropdown (entries selection) */
.dataTables_wrapper .dataTables_length select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: transparent;
    border: none;
    font-size: inherit;
    color: #333;
    padding: 0;
    margin: 0;
}

/* Styling for Search Box */
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px 10px;
    font-size: 0.9rem;
    color: #333;
    background-color: #fafafa;
    outline: none;
    transition: border-color 0.2s ease;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #004080; /* Slightly darker border on focus */
}

    
    .dataTables_wrapper .dataTables_length select {
    appearance: none;          /* Remove default styling */
    -webkit-appearance: none;   /* Remove default styling in Safari */
    -moz-appearance: none;      /* Remove default styling in Firefox */
    background-image: none;     /* Ensure no background arrow image */

    margin: auto;
}

    /* Minimalist Pagination Styling */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: none; /* No background */
    color: #002060; /* Dark blue text */
    border: none;
    padding: 5px 10px;
    margin: 0 2px;
    cursor: pointer;
    font-weight: normal;
    transition: color 0.3s ease; /* Smooth color transition on hover */
    outline: none; /* Remove focus outline */
}

/* Hover Effect for Pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #ffffff; /* White text on hover */
    background: none; /* Ensure no background on hover */
}

/* Active Page Style */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #ffffff; /* White text for active page */
    font-weight: bold; /* Bold for the active page */
    background: none; /* Ensure no background */
    outline: none; /* Remove outline */
    box-shadow: none; /* Remove any default shadow */
}

/* Remove hover effect on active page */
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: none; /* No background on hover for active page */
    color: #ffffff; /* Keep white text for consistency */
    box-shadow: none; /* No shadow */
}

/* Remove focus outline on click */
.dataTables_wrapper .dataTables_paginate .paginate_button:focus {
    outline: none;
    background: none;
    box-shadow: none;
}

    /* Active Page Style */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #ffffff !important; /* White text for active page */
    font-weight: bold !important; /* Bold for the active page */
    background: none !important; /* Ensure no background */
    outline: none !important; /* Remove outline */
    box-shadow: none !important; /* Remove any default shadow */
}

/* Remove hover effect on active page */
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: none !important; /* No background on hover for active page */
    color: #ffffff !important; /* Keep white text for consistency */
    box-shadow: none !important; /* No shadow */
}

/* General Table Styling */
#dataTable {
    width: 100%;
    border-collapse: collapse;
    color: #002060;
    font-size: 0.9rem;
}


#dataTable th, #dataTable td {
    text-align: center;
    vertical-align: middle;
    padding: 10px;
    border-bottom: 1px solid #e0e0e0;
}

/* Header Styling */
#dataTable th {
    background-color: #f2f4f7;
    font-weight: bold;
    color: #002060;
}

/* Alternating Row Colors */
#dataTable tbody tr:nth-child(odd) {
    background-color: #ffffff;
}

#dataTable tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Hover Effect */
#dataTable tbody tr:hover {
    background-color: #e0e7ff;
}

/* Custom Button Styles */
.custom-btn-light, .custom-btn-primary {
    background-color: #001e54;
    color: white;
    border-radius: 15px;
    padding: 10px 15px;
    font-size: 15px;
    font-weight: bold;
    text-align: center;
    border: none;
    transition: background-color 0.3s, transform 0.3s;
    max-width: 200px;
}

.custom-btn-primary:hover {
    background-color: #004080;
    transform: translateY(-2px);
    color: #ffffff;
}

/* Minimalist Pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: none;
    color: #002060;
    border: none;
    font-size: 0.85rem;
    padding: 5px 10px;
    margin: 0 2px;
    cursor: pointer;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #004080;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #ffffff;
    background-color: #002060;
    border-radius: 5px;
    box-shadow: none;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:focus {
    outline: none;
    box-shadow: none;
}

/* Styling for Dropdown (entries selection) */
.dataTables_wrapper .dataTables_length select {
    appearance: none;
    background: transparent;
    border: none;
    font-size: inherit;
    color: #333;
    padding: 0;
}

/* Styling for Search Box */
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px 10px;
    font-size: 0.9rem;
    color: #333;
    background-color: #fafafa;
    outline: none;
    transition: border-color 0.2s ease;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #004080;
}

/* Pagination Active Page Style */
.page-item.active .page-link {
    background-color: #ffffff !important;
    border-color: #001e54 !important;
    color: #001e54 !important;
}

/* Event Title Styling */
.event-title {
    font-size: 0.9rem;
    white-space: nowrap; /* Default behavior on larger screens */
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}

@media (max-width: 768px) {
    .event-title {
        white-space: normal; /* Allow line breaks on smaller screens */
        max-width: 100%; /* Use full width of container on mobile */
    }
}

/* General Button Styles */
.button-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px; /* Space between buttons */
}

.button-group .btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 15px;
    color: white;
}

/* Button Colors */
.btn-view {
    background-color: #008b8b;
}
.btn-recover {
    background-color: #008b8b;
}
.btn-edit {
    background-color: #001e54;
}

.btn-delete {
    background-color: #c9302c;
}

</style>

<!-- DataTables JavaScript -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "pageLength": 10,
            "language": {
                "searchPlaceholder": "Search for events..."
            }
        });
    });
</script>

@endsection
