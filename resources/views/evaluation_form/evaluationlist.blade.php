@extends('layouts.app')



@section('body')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-clipboard-list"></i> Manage Evaluation Forms
        
        @if($evaluationForms->isEmpty())
        <form action="{{ route('evaluation-forms.create') }}" method="get">
            <div style="margin-top: 10px;">
                <button type="submit" class="btn-primary" style="margin-left: 30px; border-radius: 15px;">
                    <i class="fas fa-plus"></i>  Add Evaluation Form
                </button>
            </div>
        </form>
        @endif
        </h2>
    </div>
</div>

<div class="container-fluid" style="padding: 0;">
    @if($evaluationForms->isEmpty())
    <div class="no-events-container">
        <i class="fas fa-file-alt"></i>
        <p>No evaluation forms found.</p>
    </div>
    @else
    <div class="card mb-4" style="margin-top: 50px; border: none;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Evaluation Form List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="evaluationFormsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Form No.</th>
                            <th>Form Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $counter = 1; @endphp 
                        @foreach($evaluationForms as $index => $form)
                        <tr style="background-color: {{ $index % 2 === 0 ? '#f9f9f9' : 'white' }};">
                            <td>{{ $counter++ }}</td>
                            <td>{{ $form->form_name }}</td>
                            <td>{{ $form->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="button-group" style="display: flex; justify-content: center; align-items: center;">
                                    <form action="{{ route('evaluation-forms.duplicate', $form->id) }}" method="POST" style="display:inline-block; margin-right: 5px;">
                                        @csrf
                                        <button type="submit" class="btn btn-duplicate rounded-circle" onclick="return confirm('Are you sure you want to duplicate this form?')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('evaluation-forms.edit', $form->id) }}" class="btn btn-edit rounded-circle" style="margin-right: 5px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('evaluation-forms.deactivate', $form->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-delete rounded-circle" onclick="return confirm('Are you sure you want to deactivate this form?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 10px;">
        <form action="{{ route('evaluation-forms.create') }}" method="get" style="display: inline;">
            <button type="submit" class="btn-primary" style="border-radius: 15px; margin-left:10px; margin-bottom:20px;">
                <i class="fas fa-plus"></i>  Add Evaluation Form
            </button>
        </form>
    </div>
    @endif
</div>

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#evaluationFormsTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "pageLength": 10
        });
    });
</script>

<!-- Styles -->
<style>
    /* General Table Styling */
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



/* Remove Pagination Focus Outline */


/* Styling for Dropdown (entries selection) */
.dataTables_wrapper .dataTables_length select {

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



    .button-group {
        display: flex;
        justify-content: center;
        align-items: center;
    }


    .btn-edit, .btn-delete, .btn-duplicate {
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        transition: none;
    }

    .btn-edit {
        background-color: #001e54;
    }

    .btn-delete {
        background-color: #c9302c;
    }

    .btn-duplicate {
        background-color: #008b8b;
    }

    .btn i {
        margin: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
    /* Styles for Buttons Stacking on Mobile */
    @media (max-width: 768px) {
    .button-group {
        flex-direction: column; /* Stack buttons vertically */
        align-items: center; /* Center-aligns the buttons */
        gap: 5px;
    }
    
    .btn-delete {
        margin-left: -5px;
    }

        
    }
/* Evaluation Forms Table Styling */
#evaluationFormsTable {
    width: 100%;
    border-collapse: collapse;
    color: #002060; /* Dark blue text color */
}

#evaluationFormsTable th, #evaluationFormsTable td {
    text-align: center;
    vertical-align: middle;
    padding: 12px;
    border: 1px solid #e0e0e0; /* Light grey borders */
}

/* Header Styling */
#evaluationFormsTable th {
    background-color: #f2f4f7; /* Light grey background for header */
    font-weight: bold;
    color: #002060;
}

/* Alternating Row Colors */
#evaluationFormsTable tbody tr:nth-child(odd) {
    background-color: #ffffff; /* White for odd rows */
}

#evaluationFormsTable tbody tr:nth-child(even) {
    background-color: #f9f9f9; /* Light grey for even rows */
}

/* Hover Effect */
#evaluationFormsTable tbody tr:hover {
    background-color: #e0e7ff; /* Light blue tint on hover */
}
.page-item.active .page-link {
    background-color: #ffffff !important;
    border-color: #001e54 !important;
    color: #001e54 !important;
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


@endsection
