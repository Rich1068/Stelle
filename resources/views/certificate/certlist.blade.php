@extends('layouts.app')

@section('body')

<div class="container">
    <h1 class="my-4">My Certificate Templates</h1>

    <!-- Create New Certificate Button -->
    <div class="mb-3">
        <a href="{{ route('certificates.create') }}" class="btn btn-primary">Create New Template</a>
    </div>

    <!-- Search Bar -->
    <div class="search-container mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search templates..." onkeyup="filterTable()">
    </div>

    <!-- Certificates Table -->
    <div class="table-responsive">
        <table class="table table-striped" id="certificatesTable">
            <thead>
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
                        <div class="d-flex">
                            <!-- View button triggers modal -->
                            <button type="button" class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#viewCertificateModal" onclick="loadCertificate('{{ $certificate->path }}', '{{ $certificate->template_name }}')">View</button>
                            
                            <a href="{{ route('certificates.create', $certificate->id) }}" class="btn btn-warning btn-sm mr-2">Edit</a>
                            <form action="{{ route('certificates.deactivate', $certificate->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this certificate?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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

@endsection
