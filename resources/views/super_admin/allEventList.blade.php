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
            <i class="fas fa-solid fa-calendar-week"></i> All Event List
        </h2>
    </div>
</div>

<div class="card mb-4" style="margin-top: 50px; border: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Events List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered text-center" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Organizer</th>
                        <th class="d-none d-md-table-cell">Participants</th> <!-- Hidden on smaller screens -->
                        <th>Status</th>
                        <th>created_at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <td>
                            <a href="{{ route('event.view', $event->id) }}" class="event-title" style="color: #001e54; text-decoration: none;">
                                {{ \Illuminate\Support\Str::limit($event->title, 16) }}
                            </a>
                        </td>
                        <td>
                            @if ($event->start_date === $event->end_date)
                                {{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') }}
                            @else
                                {{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') }}
                            @endif
                        </td>
                        <td>
                            @if($event->userEvent && $event->userEvent->user)
                                @if($event->userEvent->user->trashed())
                                    <span style="color: red;">
                                        {{ $event->userEvent->user->first_name }} {{ $event->userEvent->user->last_name }}
                                    </span>
                                @else
                                    {{ $event->userEvent->user->first_name }} {{ $event->userEvent->user->last_name }}
                                @endif
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">{{ $event->current_participants }}/{{ $event->capacity }}</td>
                        <td>
                        @if($event->trashed())
                            <span style="color: red;">ARCHIVED</span>
                        @elseif(
                            (\Carbon\Carbon::now('Asia/Manila')->between(
                                \Carbon\Carbon::parse($event->start_date . ' ' . $event->start_time),
                                \Carbon\Carbon::parse($event->end_date . ' ' . $event->end_time)
                            )) || 
                            (\Carbon\Carbon::now('Asia/Manila')->lessThan(\Carbon\Carbon::parse($event->start_date . ' ' . $event->start_time)))
                        )
                            <span style="color: green;">ACTIVE</span>
                        @else
                            <span style="color: gray;">CLOSED</span>
                        @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($event->created_at)->format('Y-m-d') }}</td>
                        <td>
                            <div class="button-group" style="display: flex; justify-content: center; align-items: center;">
                                <form action="{{ route('event.view', $event->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="btn btn-recover rounded-circle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
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
/* Default Styles */
h2 {
    font-size: 1.5rem;
}
h6 {
    font-size: 1rem;
}
.event-title {
    font-size: 0.9rem;
}
.table th,
.table td {
    font-size: 0.9rem;
}
.alert {
    font-size: 0.9rem;
}
.button-group .btn i {
    font-size: 1rem;
}

/* Responsive Styles */
@media (max-width: 768px) {
    h2 {
        font-size: 1.25rem;
    }
    h6 {
        font-size: 0.9rem;
    }
    .event-title {
        font-size: 0.8rem;
    }
    .table th,
    .table td {
        font-size: 0.8rem;
    }
    .alert {
        font-size: 0.85rem;
    }
    .button-group .btn i {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    h2 {
        font-size: 1rem;
    }
    h6 {
        font-size: 0.8rem;
    }
    .event-title {
        font-size: 0.7rem;
    }
    .table th,
    .table td {
        font-size: 0.75rem;
    }
    .alert {
        font-size: 0.8rem;
    }
    .button-group .btn i {
        font-size: 0.8rem;
    }
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    color: #333; /* Darker text color for better readability */
    font-size: 0.9rem; /* Slightly smaller font for compact look */
    margin: 10px 0; /* Space between elements */
}

/* Remove hover background on pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: transparent !important; 
    color: #004080; 
    box-shadow: none; 
    border: none; 
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




/







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
    padding: 5px;
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


/* Event Title Styling */
.event-title {
    font-size: 0.9rem;
    white-space: nowrap; /* Keeps text on one line */
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}

/* Mobile-friendly adjustments */
@media (max-width: 768px) { /* Target screens smaller than 768px */
    .event-title {
        font-size: 0.8rem; /* Slightly smaller text */
        max-width: 120px; /* Reduced width for smaller screens */
    }
}

@media (max-width: 480px) { /* Target screens smaller than 480px */
    .event-title {
        font-size: 0.7rem; /* Even smaller text for very small screens */
        max-width: 100px; /* Further reduced width */
    }
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

.button-group .btn .fas, 
.button-group .btn .fa {
    margin: auto; /* Center icon within the button */
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
@media (max-width: 768px) {
    .button-group {
        flex-direction: column;
        align-items: center;
    }
    
    .button-group .btn {
        font-size: 0.8rem;
        padding: 8px;
        width: 35px;
        height: 35px;
    }
}

/* Media query for smaller screens */
@media (max-width: 768px) {
    .event-title {
        font-size: 0.8rem;
    }
    .table th,
    .table td {
        font-size: 0.8rem;
    }
    .alert {
        font-size: 0.85rem;
    }
    .button-group .btn i {
        font-size: 0.9rem;
    }
}
</style>

<style>
.page-item.active .page-link {
  background-color: transparent !important;
  border-color: transparent !important;
  color: inherit !important;
  font-weight: normal !important;
}

.pagination {
  margin-top: 20px !important;
  padding-bottom: 40px !important;
  display: flex !important;
  justify-content: center !important;
  list-style-type: none !important;
  margin-top: 10px !important;
  padding: 0 !important;
  align-items: center !important;
  margin-left: auto !important;
  margin-right: auto !important;
}

.pagination a, .pagination span {
  display: inline-block !important;
  color: grey !important;
  text-decoration: none !important;
  background-color: transparent !important;
  border: none !important;
  padding: 10px 15px !important;
  margin: 0 5px !important;
  font-weight: 600 !important;
  font-size: 1rem !important;
}

.pagination .active span {
  background-color: darkblue !important;
  color: white !important;
  font-weight: 800 !important;
  border-radius: 50% !important;
}

.pagination a:hover {
  background-color: lightgray !important;
  color: white !important;
  border-radius: 50% !important;
}

@media (max-width: 768px) {
  .pagination {
    justify-content: center !important;
    margin: auto !important;
  }

  .pagination a, .pagination span {
    padding: 6px 8px !important;
    font-size: 0.85rem !important;
    margin: 0 3px !important; /* Closer numbers */
  }

  .pagination .active span {
    font-weight: 700 !important;
  }
}
</style>
   
<script>
    $(document).ready(function() {
        // Check if DataTable is already initialized and destroy it if so
        if ($.fn.dataTable.isDataTable('#userDataTable')) {
            $('#userDataTable').DataTable().destroy();
        }

        const table = $('#userDataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "pageLength": 10,
            "pagingType": "full_numbers", // Show full pagination (numbers)
            "iDisplayLength": 10,  // Items per page
            "fnDrawCallback": function (settings) {
                // Check screen size and apply custom pagination for mobile and PC
                var api = this.api();
                var currentPage = api.page.info().page + 1;  // Get current page number
                var totalPages = api.page.info().pages;     // Get total pages

                 // On Mobile: Show only 3 pages (Previous, Current, Next)
            if ($(window).width() <= 768) {
                var pageNumbers = [];
                if (currentPage === 1) {
                    pageNumbers = [1, 2, 3];  // Show pages 1, 2, 3 when on the first page
                } else if (currentPage === totalPages) {
                    pageNumbers = [totalPages - 2, totalPages - 1, totalPages];  // Show last 3 pages
                } else {
                    pageNumbers = [currentPage - 1, currentPage, currentPage + 1];  // Show 3 pages around the current page
                }

                // Hide pages that are not part of the range for mobile
                $('.dataTables_paginate .paginate_button').each(function() {
                    var pageNumber = $(this).text();
                    if (!pageNumbers.includes(parseInt(pageNumber)) && pageNumber !== "«" && pageNumber !== "»") {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });

                // Show the "Previous" and "Next" buttons
                $('.dataTables_paginate .paginate_button.previous').show();
                $('.dataTables_paginate .paginate_button.next').show();

            } else {
                // On PC: Show all page numbers
                $('.dataTables_paginate .paginate_button').show();

                // Hide "Previous" and "Next" if we're on the first or last page
                $('.dataTables_paginate .paginate_button.previous').toggle(currentPage > 1); // Show 'Previous' if not on the first page
                $('.dataTables_paginate .paginate_button.next').toggle(currentPage < totalPages); // Show 'Next' if not on the last page
            }
        }
    });

        // Role filter event handler
        $('#roleFilter').on('change', function() {
            const selectedRole = $(this).val();
            if (selectedRole) {
                table.column(3).search(`^${selectedRole}$`, true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
        });
    });
</script>




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
