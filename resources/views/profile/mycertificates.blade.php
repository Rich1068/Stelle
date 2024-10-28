@extends('layouts.app')

@section('body') 
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="top-container mb-4 d-flex align-items-left">
    <h2 class="font-weight-bold mb-0">
        <i class="fas fa-certificate"></i> My Certificate
    </h2>
</div>
<div class="certificates-page">
    @if($user->certUser->isEmpty())
    <!-- No templates available message -->
    <div class="no-events-container">
        <i class="fas fa-file-alt"></i> <!-- Icon representing no forms -->
        <p>No Certificates Found.</p>
    </div>
    @else
        <div class="certificates-container">
            @foreach($user->certUser as $certificate)
            <div class="certificate-list">
                <div class="certificate-header">{{ $certificate->certificate->event->title }}</div> <!-- Added header -->
                    <img src="{{ asset($certificate->cert_path)}}" class="certificate-image">
                    <div class="certificate-actions">
                        <!-- View Button to open modal -->
                    <button type="button" class="btn-primary view-certificate-btn" data-image-url="{{ asset($certificate->cert_path) }}" data-bs-toggle="modal" data-bs-target="#viewCertificateModal">
                        Preview
                    </button>
                    <!-- Download Button -->
                    <a href="{{ asset($certificate->cert_path) }}" download class="btn-primary">
                        Download
                    </a>
                </div>
            </div>
            @endforeach
        </div>
<!-- Modal -->
<div class="modal fade" id="viewCertificateModal" tabindex="-1" aria-labelledby="viewCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCertificateModalLabel">Certificate Preview</h5>
                
            </div>
            <div class="modal-body">
                <img id="certificateImage" src="" style="width:100%;">
            </div>
            <div class="modal-footer">
                <a href="{{ route('event.view', $certificate->certificate->event->id) }}" class="event-list-view-btn">View Event</a>
            </div>
        </div>
    </div>
</div>


@endif
</div>
    <!-- JavaScript to handle modal and image update -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle preview button click event
    document.body.addEventListener('click', function(event) {
        if (event.target.matches('.view-certificate-btn')) {
            const imageUrl = event.target.getAttribute('data-image-url');
            const certificateImage = document.querySelector('#certificateImage');
            if (certificateImage) {
                certificateImage.src = imageUrl;
                console.log('Image URL set:', imageUrl);
            }

            // Log if modal is shown
            console.log('Opening modal...');
            const modal = new bootstrap.Modal(document.getElementById('viewCertificateModal'));
            modal.show();
        }

        // Handle modal close button click event
        if (event.target.matches('.btn-close')) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('viewCertificateModal'));
            if (modal) {
                modal.hide();
                console.log('Modal closed');
            }
        }
    });
});
</script>
@endsection
