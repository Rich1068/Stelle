@extends('layouts.app')

@section('body')

<div class="top-container mb-4 d-flex align-items-left justify-content-between" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: none;">
    <!-- Left: My Certificate Templates Title -->
    <div class="d-flex align-items-center">
        <h2 class="font-weight-bold mb-0" style="color: #002060;">
            <i class="fas fa-certificate"></i> My Certificate Templates
        </h2>
    </div>
</div>

<div class="container-fluid" style="padding: 0;">

@if($certificates->isEmpty())
    <!-- No templates available message -->
    <div class="no-events-container">
    <i class="fas fa-file-alt"></i> <!-- Icon representing no forms -->
    <p>No Certificate Template Found.</p>
</div>
    @else
    <!-- Search Bar -->
    <div class="search-container" style="margin: 40px auto; max-width: 60%;">
        <input type="text" id="searchInput" placeholder="Search for forms..." class="search-input" onkeyup="filterTable()">
        <button class="search-button"><i class="fas fa-search"></i></button>
    </div>

    

    <!-- Certificates Table -->
    <div class="table-responsive">
        <table class="table table-striped custom-table text-center" id="certificatesTable" style="width: 90%; table-layout: fixed; margin: auto;">
            <thead class="custom-thead">
                <tr>
                    <th>Template ID</th>
                    <th>Template Name</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($certificates as $certificate)
                <tr>
                    <td>{{ $certificate->id }}</td>
                    <td>{{ $certificate->template_name }}</td>
                    <td>{{ $certificate->created_at->format('Y-m-d') }}</td>
                    <td>
                        <!-- View/Edit/Delete buttons -->
                        <div class="button-group" style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                            <!-- View button (Dark Cyan) -->
                            <button type="button" class="btn rounded-circle" 
                                    data-toggle="modal" 
                                    data-target="#viewCertificateModal" 
                                    onclick="loadCertificate('{{ $certificate->path }}', '{{ $certificate->template_name }}')"
                                    style="width: 40px; height: 40px; background-color: #008b8b; color: white; display: flex; align-items: center; justify-content: center;" 
                                    title="View Certificate">
                                <i class="fas fa-eye"></i>
                            </button>

                            <!-- Edit button (Dark Blue) -->
                            <a href="{{ route('certificates.create', $certificate->id) }}" 
                               class="btn rounded-circle" 
                               style="width: 40px; height: 40px; background-color: #001e54; color: white; display: flex; align-items: center; justify-content: center;" 
                               title="Edit Certificate">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Delete button (Dark Red) -->
                            <form action="{{ route('certificates.deactivate', $certificate->id) }}" method="POST" style="display:inline; margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn rounded-circle" 
                                        style="width: 40px; height: 40px; background-color: #c9302c; color: white; display: flex; align-items: center; justify-content: center;" 
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
                <!-- Certificate image will be loaded dynamically -->
                <img id="certificateImage" src="" alt="Certificate" class="img-fluid" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<button onclick="location.href='{{ route('certificates.create') }}'" class="btn btn-primary" style="margin-left: 30px;">
    <i class="fas fa-plus"></i> <span style="margin-left: 5px;"></span>Create New Template
</button>

<script>
// Filter table by certificate name
function filterTable() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toUpperCase();
    let table = document.getElementById("certificatesTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// Load the certificate into the modal when "View" button is clicked
function loadCertificate(certPath, certName) {
    let certificateImage = document.getElementById("certificateImage");
    let modalTitle = document.getElementById("viewCertificateModalLabel");

    // Set the image source and modal title
    certificateImage.src = "/" + certPath;
    modalTitle.textContent = certName;
}
</script>

<!-- Styles -->
<style>
    /* Your existing styles... */

    .btn-primary {
        background-color: #001e54;
        color: white;
        border-radius: 15px;
        padding: 10px 15px;
        font-size: 15px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .button-group .btn i {
        margin: 0; /* Ensure no extra margin for icons */
    }

    .custom-thead {
        background-color: #001e54;
        color: white;
    }

    .custom-thead th {
        padding: 1rem;
        cursor: pointer;
    }

    .custom-table {
        border: none;
        width: 100%;
    }

    .custom-table td, .custom-table th {
        border: none; 
        text-align: center; 
        vertical-align: middle; 
        padding: 10px; /* Reduced padding to fit more content */
        overflow-wrap: break-word; /* Ensure long text wraps */
    }

    .custom-table tbody tr:hover {
        background-color: #f2f2f2; 
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
    }

    .search-input {
        padding: 10px;
        border-radius: 15px 0 0 15px;
        border: 1px solid #ccc;
        transition: border-color 0.3s;
        border-right: none; 
        font-size: 14px; 
        flex: 1; /* Allow the input to take up available space */
    }

    .search-input:focus {
        border-color: black;
        outline: none;
    }

    .search-button {
        padding: 10px;
        border-radius: 0 15px 15px 0;
        border: 1px solid #001e54;
        background-color: #001e54; 
        color: white; 
        cursor: pointer; 
        font-size: 13px; 
    }

    .search-button:hover {
        background-color: #0d3b76; 
    }

    .no-templates-container {
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

    .no-templates-container i {
        font-size: 40px; 
        color: #001e54;
        margin-bottom: 10px;
    }

    .no-templates-container p {
        font-size: 18px; 
        color: #555;
        margin: 0;
    }
</style>

@endsection
