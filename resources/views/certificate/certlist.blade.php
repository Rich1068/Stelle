@extends('layouts.app')



@section('body')

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-certificate"></i> My Certificate Templates
            @if($certificates->isEmpty())
            <div style="margin-top: 10px; margin-left: 30px;">
                <button onclick="location.href='{{ route('certificates.create') }}'" class="btn-primary">
                    <i class="fas fa-plus"></i>  Create New Template
                </button>
            </div>
            @endif
        </h2>
    </div>
</div>

<div class="container-fluid" style="padding: 0;">

@if($certificates->isEmpty())
    <div class="no-events-container">
        <i class="fas fa-file-alt"></i>
        <p>No Certificate Template Found.</p>
    </div>
@else
    <div class="card mb-4" style="margin-top: 50px; border: none;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Certificate Template List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="certificateTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Template No.</th>
                            <th>Template Name</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach($certificates as $certificate)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $certificate->template_name }}</td>
                            <td>{{ $certificate->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="button-group" style="display: flex; justify-content: center; align-items: center;">
                                    <form action="#" method="get" style="display:inline-block; margin-right: 5px;">
                                        <button type="button" class="btn btn-view rounded-circle" 
                                                data-toggle="modal" 
                                                data-target="#viewCertificateModal" 
                                                onclick="loadCertificate('{{ $certificate->path }}', '{{ $certificate->template_name }}')"
                                                title="View Certificate">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('certificates.create', $certificate->id) }}" class="btn btn-edit rounded-circle me-2" title="Edit Certificate" style="margin-right: 5px;">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('certificates.deactivate', $certificate->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-delete rounded-circle" 
                                                onclick="return confirm('Are you sure you want to delete this certificate?')" title="Delete Certificate">
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
        <button onclick="location.href='{{ route('certificates.create') }}'" class="btn-primary" style="border-radius: 15px; margin-left:10px; margin-bottom:20px;">
        <i class="fas fa-plus"></i>  Add Certiticate Template
        </button>
    </div>
@endif
</div>

<!-- View Certificate Modal -->
<div class="modal fade" id="viewCertificateModal" tabindex="-1" role="dialog" aria-labelledby="viewCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCertificateModalLabel">Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="certificateImage" src="" alt="Certificate" class="img-fluid" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#certificateTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "pageLength": 10
        });
    });
    
    function loadCertificate(certPath, certName) {
        let certificateImage = document.getElementById("certificateImage");
        let modalTitle = document.getElementById("viewCertificateModalLabel");
        certificateImage.src = "/" + certPath;
        modalTitle.textContent = certName;
    }
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

.btn-edit {
    background-color: #001e54;
}

.btn-delete {
    background-color: #c9302c;
}

/* Mobile Styles */
@media (max-width: 768px) {
    .button-group {
        flex-direction: column; /* Stack buttons vertically */
        align-items: center; /* Center-aligns the buttons */
    }
    
    .btn-delete {
        margin-left: -5px;
    }

        
    }


    .btn-view {
        background-color: #008b8b;
    }

    .btn-edit {
        background-color: #001e54;
    }

    .btn-delete {
        background-color: #c9302c;
    }

    .btn i {
        margin: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .no-events-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: white;
        color: #001e54;
        border-radius: 15px;
        padding: 20px;
        margin: 20px auto;
        max-width: 80%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .no-events-container i {
        font-size: 40px; 
        color: #001e54;
        margin-bottom: 10px;
    }

    .no-events-container p {
        font-size: 18px; 
        color: #555;
        margin: 0;
    }
 

    #certificateTable {
    width: 100%;
    border-collapse: collapse;
    color: #002060; /* Dark blue text color */
}

#certificateTable th, #certificateTable td {
    text-align: center;
    vertical-align: middle;
    padding: 12px;
    border: 1px solid #e0e0e0; /* Light grey borders */
}

/* Header Styling */
#certificateTable th {
    background-color: #f2f4f7; /* Light grey background for header */
    font-weight: bold;
    color: #002060;
}

/* Alternating Row Colors */
#certificateTable tbody tr:nth-child(odd) {
    background-color: #ffffff; /* White for odd rows */
}

#certificateTable tbody tr:nth-child(even) {
    background-color: #f9f9f9; /* Light grey for even rows */
}

/* Hover Effect */
#certificateTable tbody tr:hover {
    background-color: #e0e7ff; /* Light blue tint on hover */
}

.page-item.active .page-link {
    background-color: #ffffff !important;
    border-color: #001e54 !important;
    color: #001e54 !important;
}
</style>

@endsection
