@extends('layouts.app')

@section('body')
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <div class="top-container mb-4 d-flex align-items-left">
    <h2 class="font-weight-bold mb-0">
    <i class="fas fa-certificate"></i> My Certificate
</h2>

        </h2>
    </div>
    <div class="certificates-page">

        @if($user->certUser->isEmpty())
            <p>No certificates found.</p>
        @else
            <div class="certificates-container">
                @foreach($user->certUser as $certificate)
                    <div class="certificate-list">
                        <div class="certificate-header">{{ $certificate->certificate->event->title }}</div> <!-- Added header -->
                        <img src="{{ asset($certificate->cert_path)}}" class="certificate-image">

                        <div class="certificate-actions">
                            <!-- View Button to open modal -->
                            <button type="button" class="btn btn-primary view-certificate-btn" data-image-url="{{ asset($certificate->cert_path) }}" data-bs-toggle="modal" data-bs-target="#viewCertificateModal">
                                Preview
                            </button>

                            <!-- Download Button -->
                            <a href="{{ asset($certificate->cert_path) }}" download class="btn btn-primary">
                                Download
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewCertificateModal" tabindex="-1" aria-labelledby="viewCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCertificateModalLabel">Certificate Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="certificateImage" src="" style="width:100%;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle modal and image update -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('click', function(event) {
                if (event.target.matches('.view-certificate-btn')) {
                    const imageUrl = event.target.getAttribute('data-image-url');
                    const certificateImage = document.querySelector('#certificateImage');
                    if (certificateImage) {
                        certificateImage.src = imageUrl;
                    }
                }
            });
        });
    </script>
@endsection
